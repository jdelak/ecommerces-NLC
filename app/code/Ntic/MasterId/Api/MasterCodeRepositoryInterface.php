<?php


namespace Ntic\MasterId\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface MasterCodeRepositoryInterface
{


    /**
     * Save MasterCode
     * @param \Ntic\MasterId\Api\Data\MasterCodeInterface $masterCode
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Ntic\MasterId\Api\Data\MasterCodeInterface $masterCode
    );

    /**
     * Retrieve MasterCode
     * @param string $mastercodeId
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($mastercodeId);

    /**
     * Retrieve MasterCode matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ntic\MasterId\Api\Data\MasterCodeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete MasterCode
     * @param \Ntic\MasterId\Api\Data\MasterCodeInterface $masterCode
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Ntic\MasterId\Api\Data\MasterCodeInterface $masterCode
    );

    /**
     * Delete MasterCode by ID
     * @param string $mastercodeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($mastercodeId);
}
