<?php
namespace Ntic\UpdateCustomer\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, // Add this
        \Magento\Sales\Model\Service\OrderService $orderService, // Add this
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface
    )
    {
        $this->_storeManager = $storeManager;
        $this->_product = $product;
        $this->quote = $quote;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository; // Add this
        $this->orderService = $orderService; // Add this
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
    }


    public function createMageOrder($orderData)
    {


        //init the store id and website id @todo pass from array
        $store = $this->_storeManager->getStore(3);
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        //init the customer
        if (isset($orderData['email'])) {
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->loadByEmail($orderData['email']);// load customet by email address
            //check the customer
            /*if (!$customer->getEntityId()) {

                //If not avilable then create this customer
                $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($orderData['shipping_address']['firstname'])
                    ->setLastname($orderData['shipping_address']['lastname'])
                    ->setEmail($orderData['email'])
                    ->setPassword($orderData['email']);

                $customer->save();
            }*/
        }
        //init the quote
        $cart_id = $this->cartManagementInterface->createEmptyCart();
        $cart = $this->cartRepositoryInterface->get($cart_id);
        $cart->setStore($store);
        // if you have already buyer id then you can load customer directly
        if (isset($orderData['customer_id'])) {
            $customer = $this->customerRepository->getById($orderData['customer_id']);
        } else {
            $customer = $this->customerRepository->getById($customer->getEntityId());
        }

        $cart->setCurrency();
        $cart->assignCustomer($customer); //Assign quote to customer
        //add items in quote
        foreach ($orderData['items'] as $item) {
            $product = $this->_product->create()->load($item['product_id']);
            $product->setPrice($item['price']);
            $cart->addProduct($product, intval($item['qty']));
        }
        //Set Address to quote @todo add section in order data for seperate billing and handle it
        $cart->getBillingAddress()->addData($orderData['shipping_address']);
        $cart->getShippingAddress()->addData($orderData['shipping_address']);
        // Collect Rates and Set Shipping & Payment Method
        $shippingAddress = $cart->getShippingAddress();
        //@todo set in order data
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('freeshipping_freeshipping'); //shipping method
        $cart->setPaymentMethod('paymentcustom'); //payment method
        //@todo insert a variable to affect the invetory
        $cart->setInventoryProcessed(false);

        //$quote->save();
        // Set sales order payment
        $cart->getPayment()->importData(['method' => 'paymentcustom']);
        $cart->save();
        //$this->cartRepositoryInterface->save($quote);
        // Collect total and saeve
        $cart->collectTotals()->save();
        //$quote->collectTotals();
        // Submit the quote and create the order
        //$order = $this->quoteManagement->submit($quote);
        //$quote = $this->cartRepositoryInterface->get($quote->getId());
        $cart = $this->cartRepositoryInterface->get($cart->getId());
        $order_id = $this->cartManagementInterface->placeOrder($cart->getId());
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('\Magento\Sales\Model\Order')->load($order_id);
        $order->setEmailSent(0);
        $order->setState("complete")->setStatus("archive");
        $order->setData('created_at', $orderData['created_at'].' 00:00:00');
        $order->setData('Gesco_Order_Origin', $orderData['gesco_no']);
        //var_dump($order->getData());
        $order->save();

        return $order_id;
        /*//do not send the email
        $order->setEmailSent(0);
        $order->setState("complete")->setStatus("complete");
        $order->setState("canceled")->setStatus($order::STATE_CANCELED);
        $order->save();
        //give a result back
        if ($order->getEntityId()) {
            $result['order_id'] = $order->getEntityId();
        } else {
            $result = ['error' => 1, 'msg' => 'Your custom message'];
        }
        return $result;*/
    }
}