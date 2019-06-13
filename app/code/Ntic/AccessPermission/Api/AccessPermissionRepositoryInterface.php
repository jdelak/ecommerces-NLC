<?php


namespace Ntic\AccessPermission\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface AccessPermissionRepositoryInterface
{


    /**
     * Save AccessPermission
     * @param \Ntic\AccessPermission\Api\Data\AccessPermissionInterface $accessPermission
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \Ntic\AccessPermission\Api\Data\AccessPermissionInterface $accessPermission
    );

    /**
     * Retrieve AccessPermission
     * @param string $accesspermissionId
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($accesspermissionId);

    /**
     * Retrieve AccessPermission matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete AccessPermission
     * @param \Ntic\AccessPermission\Api\Data\AccessPermissionInterface $accessPermission
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \Ntic\AccessPermission\Api\Data\AccessPermissionInterface $accessPermission
    );

    /**
     * Delete AccessPermission by ID
     * @param string $accesspermissionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($accesspermissionId);
}
