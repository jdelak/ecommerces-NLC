<?php
namespace Ntic\Pay\Block\Frontend;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;
use Magento\Payment\Model\CcConfig;

class SecurePayment extends \Magento\Framework\View\Element\Template
{
    protected $_cart;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Checkout\Model\Cart $cart,
        array $data = []
    ){
        $this->_cart = $cart;
        parent::__construct($context, $data);
    }

    public function getQuote(){
        return $this->_cart->getQuote();
    }
    /*public function getQuote(){
        $factory = ObjectManager::getInstance()->get('Magento\Quote\Model\QuoteFactory');
        $quote = $factory->create()->load($_SESSION['checkout']['last_quote_id'])->getData();
        return $quote;
    }*/
    public function getGrandTotal(){
        return $this->getQuote()->getGrandTotal();
        //return $this->_currentTotal + $this->getShippingPrice();
    }
}