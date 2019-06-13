<?php


namespace Ntic\Pay\Api\Data;

interface CertifSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Certif list.
     * @return \Ntic\Pay\Api\Data\CertifInterface[]
     */
    
    public function getItems();

    /**
     * Set shopId list.
     * @param \Ntic\Pay\Api\Data\CertifInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
