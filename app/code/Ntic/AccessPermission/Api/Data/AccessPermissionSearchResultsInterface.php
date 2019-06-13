<?php


namespace Ntic\AccessPermission\Api\Data;

interface AccessPermissionSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get AccessPermission list.
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface[]
     */
    
    public function getItems();

    /**
     * Set group_id list.
     * @param \Ntic\AccessPermission\Api\Data\AccessPermissionInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
