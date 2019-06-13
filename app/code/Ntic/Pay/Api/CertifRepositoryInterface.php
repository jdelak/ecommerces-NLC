<?php


namespace Ntic\Pay\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CertifRepositoryInterface
{


    /**
     * Save Certif
     * @param \Ntic\Pay\Api\Data\CertifInterface $certif
     * @return \Ntic\Pay\Api\Data\CertifInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \Ntic\Pay\Api\Data\CertifInterface $certif
    );

    /**
     * Retrieve Certif
     * @param string $certifId
     * @return \Ntic\Pay\Api\Data\CertifInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($certifId);

    /**
     * Retrieve Certif matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ntic\Pay\Api\Data\CertifSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Certif
     * @param \Ntic\Pay\Api\Data\CertifInterface $certif
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \Ntic\Pay\Api\Data\CertifInterface $certif
    );

    /**
     * Delete Certif by ID
     * @param string $certifId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($certifId);
}
