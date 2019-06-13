<?php


namespace Ntic\PayCustom\Api\Data;

interface ConfigPaymentFractionSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get config_payment_fraction list.
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface[]
     */
    
    public function getItems();

    /**
     * Set amount list.
     * @param \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
