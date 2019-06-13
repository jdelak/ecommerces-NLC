<?php


namespace Ntic\MasterId\Api\Data;

interface MasterCodeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get MasterCode list.
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface[]
     */
    public function getItems();

    /**
     * Set nav_id list.
     * @param \Ntic\MasterId\Api\Data\MasterCodeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
