<?php


namespace Ntic\PayCustom\Api\Data;

interface ConfigPaymentAboSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get config_payment_abo list.
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface[]
     */
    
    public function getItems();

    /**
     * Set product_id list.
     * @param \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
