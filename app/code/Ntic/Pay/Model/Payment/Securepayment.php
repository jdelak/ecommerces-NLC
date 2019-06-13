<?php


namespace Ntic\Pay\Model\Payment;

class Securepayment extends \Magento\Payment\Model\Method\AbstractMethod
{

    protected $_code = "securepayment";
    protected $_isOffline = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}
