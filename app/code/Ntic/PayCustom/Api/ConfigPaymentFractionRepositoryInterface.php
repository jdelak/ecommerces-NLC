<?php


namespace Ntic\PayCustom\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ConfigPaymentFractionRepositoryInterface
{


    /**
     * Save config_payment_fraction
     * @param \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface $configPaymentFraction
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface $configPaymentFraction
    );

    /**
     * Retrieve config_payment_fraction
     * @param string $configPaymentFractionId
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($configPaymentFractionId);

    /**
     * Retrieve config_payment_fraction matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentFractionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete config_payment_fraction
     * @param \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface $configPaymentFraction
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface $configPaymentFraction
    );

    /**
     * Delete config_payment_fraction by ID
     * @param string $configPaymentFractionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($configPaymentFractionId);
}
