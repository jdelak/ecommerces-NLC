<?php


namespace Ntic\PayCustom\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ConfigPaymentAboRepositoryInterface
{


    /**
     * Save config_payment_abo
     * @param \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface $configPaymentAbo
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface $configPaymentAbo
    );

    /**
     * Retrieve config_payment_abo
     * @param string $configPaymentAboId
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($configPaymentAboId);

    /**
     * Retrieve config_payment_abo matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ntic\PayCustom\Api\Data\ConfigPaymentAboSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete config_payment_abo
     * @param \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface $configPaymentAbo
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface $configPaymentAbo
    );

    /**
     * Delete config_payment_abo by ID
     * @param string $configPaymentAboId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($configPaymentAboId);
}
