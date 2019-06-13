<?php


namespace Ntic\PayCustom\Model;

use Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface;

class ConfigPaymentFraction extends \Magento\Framework\Model\AbstractModel implements ConfigPaymentFractionInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ntic\PayCustom\Model\ResourceModel\ConfigPaymentFraction');
    }

    /**
     * Get config_payment_fraction_id
     * @return string
     */
    public function getConfigPaymentFractionId()
    {
        return $this->getData(self::CONFIG_PAYMENT_FRACTION_ID);
    }

    /**
     * Set config_payment_fraction_id
     * @param string $configPaymentFractionId
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    public function setConfigPaymentFractionId($configPaymentFractionId)
    {
        return $this->setData(self::CONFIG_PAYMENT_FRACTION_ID, $configPaymentFractionId);
    }

    /**
     * Get amount
     * @return string
     */
    public function getAmount()
    {
        return $this->getData(self::AMOUNT);
    }

    /**
     * Set amount
     * @param string $amount
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    public function setAmount($amount)
    {
        return $this->setData(self::AMOUNT, $amount);
    }

    /**
     * Get operateur
     * @return string
     */
    public function getOperateur()
    {
        return $this->getData(self::OPERATEUR);
    }

    /**
     * Set operateur
     * @param string $operateur
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    public function setOperateur($operateur)
    {
        return $this->setData(self::OPERATEUR, $operateur);
    }

    /**
     * Get fraction
     * @return string
     */
    public function getFraction()
    {
        return $this->getData(self::FRACTION);
    }

    /**
     * Set fraction
     * @param string $fraction
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    public function setFraction($fraction)
    {
        return $this->setData(self::FRACTION, $fraction);
    }

    /**
     * Get to_type
     * @return string
     */
    public function getToType()
    {
        return $this->getData(self::TO_TYPE);
    }

    /**
     * Set to_type
     * @param string $to_type
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    public function setToType($to_type)
    {
        return $this->setData(self::TO_TYPE, $to_type);
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
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    public function setProductId($product_id)
    {
        return $this->setData(self::PRODUCT_ID, $product_id);
    }

    /**
     * Get store_id
     * @return string
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set store_id
     * @param string $store_id
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    public function setStoreId($store_id)
    {
        return $this->setData(self::STORE_ID, $store_id);
    }
}
