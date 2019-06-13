<?php


namespace Ntic\PayCustom\Api\Data;

interface ConfigPaymentFractionInterface
{

    const AMOUNT = 'amount';
    const FRACTION = 'fraction';
    const OPERATEUR = 'operateur';
    const TO_TYPE = 'to_type';
    const PRODUCT_ID = 'product_id';
    const CONFIG_PAYMENT_FRACTION_ID = 'config_payment_fraction_id';


    /**
     * Get config_payment_fraction_id
     * @return string|null
     */
    
    public function getConfigPaymentFractionId();

    /**
     * Set config_payment_fraction_id
     * @param string $config_payment_fraction_id
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    
    public function setConfigPaymentFractionId($configPaymentFractionId);

    /**
     * Get amount
     * @return string|null
     */
    
    public function getAmount();

    /**
     * Set amount
     * @param string $amount
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    
    public function setAmount($amount);

    /**
     * Get operateur
     * @return string|null
     */
    
    public function getOperateur();

    /**
     * Set operateur
     * @param string $operateur
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    
    public function setOperateur($operateur);

    /**
     * Get fraction
     * @return string|null
     */
    
    public function getFraction();

    /**
     * Set fraction
     * @param string $fraction
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    
    public function setFraction($fraction);

    /**
     * Get to_type
     * @return string|null
     */
    
    public function getToType();

    /**
     * Set to_type
     * @param string $to_type
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    
    public function setToType($to_type);

    /**
     * Get product_id
     * @return string|null
     */
    
    public function getProductId();

    /**
     * Set product_id
     * @param string $product_id
     * @return Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */
    
    public function setProductId($product_id);

    /**
     * Get store_id
     * @return string|null
     */

    public function getStoreId();

    /**
     * Set store_id
     * @param string $store_id
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     */

    public function setStoreId($store_id);
}
