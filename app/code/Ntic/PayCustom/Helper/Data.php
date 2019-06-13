<?php
namespace Ntic\PayCustom\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, // Add this
        \Magento\Sales\Model\Service\OrderService $orderService, // Add this
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface
    ) {
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

    /**
     * Create Order On Your Store
     * ############################ EXEMPLE RECEPTION ##########################
     * $tempData =[
        'currency_id'  => 'USD',
        'email'        => 'test@example.com',
        'shipping_address' =>[
        'firstname'    => 'Fake', //address Details
        'lastname'     => 'Name',
        'street' => 'xxxxx',
        'city' => 'xxxxx',
        'country_id' => 'IN',
        'region' => 'xxx',
        'postcode' => '43244',
        'telephone' => '52332',
        'fax' => '32423',
        'save_in_address_book' => 1
        ],
        'items'=> [
            ['product_id'=>1,'qty'=>1]
            ]
        ];
     * @param array $orderData
     * @return array
     *
     */
    public function createOrder($orderData) {
        //init the store id and website id @todo pass from array
        $store = $this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        //init the customer
        if(isset($orderData['email'])){
            $customer=$this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->loadByEmail($orderData['email']);// load customet by email address
            //check the customer
            if(!$customer->getEntityId()){

                //If not avilable then create this customer
                $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($orderData['shipping_address']['firstname'])
                    ->setLastname($orderData['shipping_address']['lastname'])
                    ->setEmail($orderData['email'])
                    ->setPassword($orderData['email']);

                $customer->save();
            }
        }
        //init the quote
        $quote=$this->quote->create();
        $quote->setStore($store);
        // if you have already buyer id then you can load customer directly
        if(isset($orderData['customer_id'])){
            $customer = $this->customerRepository->getById($orderData['customer_id']);
        }else{
            $customer = $this->customerRepository->getById($customer->getEntityId());
        }
        $quote->setCurrency();
        $this->cartRepositoryInterface->save($quote); // Add this
        $quote->assignCustomer($customer); //Assign quote to customer
        //add items in quote

        foreach($orderData['items'] as $item){
            $product=$this->_product->load($item['product_id']);
            $product->setPrice($item['price']);
            $quote->addProduct(
                $product,
                intval($item['qty'])
            );
        }

        //Set Address to quote @todo add section in order data for seperate billing and handle it
        $quote->getBillingAddress()->addData($orderData['shipping_address']);
        $quote->getShippingAddress()->addData($orderData['shipping_address']);
        // Collect Rates and Set Shipping & Payment Method
        $shippingAddress = $quote->getShippingAddress();
        //@todo set in order data
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('owsh1_free_method')
            ->setPaymentMethod('paymentcustom');// ->setShippingMethod('flatrate_flatrate'); //shipping method


        //$quote->setPaymentMethod('paymentcustom'); //payment method

        //@todo insert a variable to affect the invetory
        $quote->setInventoryProcessed(false);
        //$quote->save();
        // Set sales order payment
        $quote->getPayment()->importData(['method' => 'paymentcustom']);
        //$quote->setShippingMethod('owsh1_standard_method1');
        $quote->setShippingMethod('owsh1_free_method');

        $quote->save();
        $this->cartRepositoryInterface->save($quote);
        // Collect total and saeve
        $quote->collectTotals()->save();

        $quote->collectTotals();

        // Submit the quote and create the order
        //$order = $this->quoteManagement->submit($quote);
        $quote = $this->cartRepositoryInterface->get($quote->getId());

        $order = $this->cartManagementInterface->submit($quote);

        //do not send the email
       // $order->setEmailSent(1);
        //give a result back

        if($order->getEntityId()){
            $result['order_id'] = $order->getEntityId();
        }else{
            $result=['error'=>1,'msg'=>'Your custom message'];
        }
        return $result;
    }
}
?>