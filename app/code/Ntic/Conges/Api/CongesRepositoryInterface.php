<?php


namespace Ntic\Conges\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CongesRepositoryInterface
{


    /**
     * Save Conges
     * @param \Ntic\Conges\Api\Data\CongesInterface $conges
     * @return \Ntic\Conges\Api\Data\CongesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Ntic\Conges\Api\Data\CongesInterface $conges
    );

    /**
     * Retrieve Conges
     * @param string $congesId
     * @return \Ntic\Conges\Api\Data\CongesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($congesId);

    /**
     * Retrieve Conges matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ntic\Conges\Api\Data\CongesSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Conges
     * @param \Ntic\Conges\Api\Data\CongesInterface $conges
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Ntic\Conges\Api\Data\CongesInterface $conges
    );

    /**
     * Delete Conges by ID
     * @param string $congesId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($congesId);
}
