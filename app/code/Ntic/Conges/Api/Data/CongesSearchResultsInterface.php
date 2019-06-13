<?php


namespace Ntic\Conges\Api\Data;

interface CongesSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Conges list.
     * @return \Ntic\Conges\Api\Data\CongesInterface[]
     */
    public function getItems();

    /**
     * Set start_date list.
     * @param \Ntic\Conges\Api\Data\CongesInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
