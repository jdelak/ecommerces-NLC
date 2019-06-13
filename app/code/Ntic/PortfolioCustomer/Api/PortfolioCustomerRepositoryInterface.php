<?php


namespace Ntic\PortfolioCustomer\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PortfolioCustomerRepositoryInterface
{


    /**
     * Save Portfolio_Customer
     * @param \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface $portfolioCustomer
     * @return \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface $portfolioCustomer
    );

    /**
     * Retrieve Portfolio_Customer
     * @param string $portfolioCustomerId
     * @return \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($portfolioCustomerId);

    /**
     * Retrieve Portfolio_Customer matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Portfolio_Customer
     * @param \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface $portfolioCustomer
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface $portfolioCustomer
    );

    /**
     * Delete Portfolio_Customer by ID
     * @param string $portfolioCustomerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($portfolioCustomerId);
}
