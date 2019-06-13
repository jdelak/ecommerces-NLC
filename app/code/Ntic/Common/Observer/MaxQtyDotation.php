<?php


namespace Ntic\Common\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class MaxQtyDotation implements \Magento\Framework\Event\ObserverInterface
{
    protected $objectManager;
    protected $resource;
    protected $_messageManager;
    protected $cart;
    protected $_responseFactory;
    protected $_url;

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */

    public function __construct(

        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->_objectManager = $objectmanager;
        $this->_resource = $resource;
        $this->_messageManager = $messageManager;
        $this->_cart = $cart;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
    }

    public function execute(
        \Magento\Framework\Event\Observer $observer
    )
    {

        $product = $observer->getProduct();
        $sku=$product->getSku();
        $return = FALSE;
        if($sku == 'DOTA'){

            // INIT
            $customerSession    = $this->_objectManager->create('Magento\Customer\Model\Session');
            $userid             = $customerSession->getCustomer()->getId();
            $connection         = $this->_resource->getConnection();
            // VERIF DOTA DANS LE MOIS
            $sql = "SELECT sales_order_item.order_id
                    FROM `sales_order`
                    INNER JOIN sales_order_item
                    ON sales_order_item.order_id = sales_order.entity_id
                    WHERE `customer_id` = " .$userid."
                    AND sales_order.created_at >= '".date('Y-m')."-01 00:00:00'
                    AND sku = 'DOTA'";
            $check_dotation = $connection->fetchOne($sql);
            // VERIF SI DOTA DEJA DANS PANIER
            $sql2= "SELECT quote_item.item_id, quote_item.qty, quote_item.sku 
                    FROM `quote` 
                    INNER JOIN quote_item ON quote_item.quote_id = quote.entity_id
                    WHERE `entity_id` = " .$this->_cart->getQuote()->getId(). " 
                    AND quote_item.sku = 'DOTA'";
            $check_cart = $connection->fetchOne($sql2);

            if($check_cart){
                $allItems = $observer->getQuoteItem()->getQuote()->getAllItems();
                foreach ($allItems as $item) {
                    $item->setData('qty',0);
                }
                $resultRedirect = $this->_responseFactory->create();
                $resultRedirect->setRedirect($this->_url->getUrl('checkout'))->sendResponse('200');
                return FALSE;
            }else{
                $return=TRUE;
            }

            if($check_dotation){
                throw new LocalizedException(__('Vous avez commandé une dotation ce mois !'));
            }else{
                $return=TRUE;
            }

        }else{
            $return=TRUE;
        }
        return $return;


    }

}
