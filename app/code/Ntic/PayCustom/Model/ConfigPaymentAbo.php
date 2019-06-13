<?php


namespace Ntic\PayCustom\Model;

use Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface;

class ConfigPaymentAbo extends \Magento\Framework\Model\AbstractModel implements ConfigPaymentAboInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ntic\PayCustom\Model\ResourceModel\ConfigPaymentAbo');
    }

    /**
     * Get config_payment_abo_id
     * @return string
     */
    public function getConfigPaymentAboId()
    {
        return $this->getData(self::CONFIG_PAYMENT_ABO_ID);
    }

    /**
     * Set config_payment_abo_id
     * @param string $configPaymentAboId
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    public function setConfigPaymentAboId($configPaymentAboId)
    {
        return $this->setData(self::CONFIG_PAYMENT_ABO_ID, $configPaymentAboId);
    }

    /**
     * Get product_id
     * @return string
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $product_id
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    public function setProductId($product_id)
    {
        return $this->setData(self::PRODUCT_ID, $product_id);
    }

    /**
     * Get type_mode_payment
     * @return string
     */
    public function getTypeModePayment()
    {
        return $this->getData(self::TYPE_MODE_PAYMENT);
    }

    /**
     * Set type_mode_payment
     * @param string $type_mode_payment
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    public function setTypeModePayment($type_mode_payment)
    {
        return $this->setData(self::TYPE_MODE_PAYMENT, $type_mode_payment);
    }

    /**
     * Get type_fraction
     * @return string
     */
    public function getTypeFraction()
    {
        return $this->getData(self::TYPE_FRACTION);
    }

    /**
     * Set type_fraction
     * @param string $type_fraction
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    public function setTypeFraction($type_fraction)
    {
        return $this->setData(self::TYPE_FRACTION, $type_fraction);
    }

    /**
     * Get first_echance_secure
     * @return string
     */
    public function getFirstEchanceSecure()
    {
        return $this->getData(self::FIRST_ECHANCE_SECURE);
    }

    /**
     * Set first_echance_secure
     * @param string $first_echance_secure
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    public function setFirstEchanceSecure($first_echance_secure)
    {
        return $this->setData(self::FIRST_ECHANCE_SECURE, $first_echance_secure);
    }
}
