<?php


namespace Ntic\PayCustom\Model\Payment;

class Paymentcustom extends \Magento\Payment\Model\Method\AbstractMethod
{

    protected $_code = "paymentcustom";
    protected $_isOffline = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}
