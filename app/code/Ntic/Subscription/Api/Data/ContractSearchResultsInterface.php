<?php


namespace Ntic\Subscription\Api\Data;

interface ContractSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Contract list.
     * @return \Ntic\Subscription\Api\Data\ContractInterface[]
     */
    public function getItems();

    /**
     * Set store_id list.
     * @param \Ntic\Subscription\Api\Data\ContractInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
