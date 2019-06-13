<?php


namespace Ntic\Subscription\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ContractRepositoryInterface
{


    /**
     * Save Contract
     * @param \Ntic\Subscription\Api\Data\ContractInterface $contract
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Ntic\Subscription\Api\Data\ContractInterface $contract
    );

    /**
     * Retrieve Contract
     * @param string $contractId
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($contractId);

    /**
     * Retrieve Contract matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ntic\Subscription\Api\Data\ContractSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Contract
     * @param \Ntic\Subscription\Api\Data\ContractInterface $contract
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Ntic\Subscription\Api\Data\ContractInterface $contract
    );

    /**
     * Delete Contract by ID
     * @param string $contractId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($contractId);
}
