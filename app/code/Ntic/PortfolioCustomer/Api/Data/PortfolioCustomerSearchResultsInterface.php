<?php


namespace Ntic\PortfolioCustomer\Api\Data;

interface PortfolioCustomerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Portfolio_Customer list.
     * @return \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface[]
     */
    
    public function getItems();

    /**
     * Set customer_id list.
     * @param \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
