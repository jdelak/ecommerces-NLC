<?php


namespace Ntic\PayCustom\Api\Data;

interface ConfigPaymentAboInterface
{

    const FIRST_ECHANCE_SECURE = 'first_echance_secure';
    const TYPE_MODE_PAYMENT = 'type_mode_payment';
    const PRODUCT_ID = 'product_id';
    const CONFIG_PAYMENT_ABO_ID = 'config_payment_abo_id';
    const TYPE_FRACTION = 'type_fraction';


    /**
     * Get config_payment_abo_id
     * @return string|null
     */
    
    public function getConfigPaymentAboId();

    /**
     * Set config_payment_abo_id
     * @param string $config_payment_abo_id
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    
    public function setConfigPaymentAboId($configPaymentAboId);

    /**
     * Get product_id
     * @return string|null
     */
    
    public function getProductId();

    /**
     * Set product_id
     * @param string $product_id
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    
    public function setProductId($product_id);

    /**
     * Get type_mode_payment
     * @return string|null
     */
    
    public function getTypeModePayment();

    /**
     * Set type_mode_payment
     * @param string $type_mode_payment
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    
    public function setTypeModePayment($type_mode_payment);

    /**
     * Get type_fraction
     * @return string|null
     */
    
    public function getTypeFraction();

    /**
     * Set type_fraction
     * @param string $type_fraction
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    
    public function setTypeFraction($type_fraction);

    /**
     * Get first_echance_secure
     * @return string|null
     */
    
    public function getFirstEchanceSecure();

    /**
     * Set first_echance_secure
     * @param string $first_echance_secure
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     */
    
    public function setFirstEchanceSecure($first_echance_secure);
}
