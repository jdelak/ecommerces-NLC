<?php


namespace Ntic\PortfolioCustomer\Api\Data;

interface PortfolioCustomerInterface
{

    const PORTFOLIO_CUSTOMER_ID = 'portfolio_customer_id';
    const SELLER_ID = 'seller_id';
    const CUSTOMER_ID = 'customer_id';


    /**
     * Get portfolio_customer_id
     * @return string|null
     */
    
    public function getPortfolioCustomerId();

    /**
     * Set portfolio_customer_id
     * @param string $portfolio_customer_id
     * @return Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface
     */
    
    public function setPortfolioCustomerId($portfolioCustomerId);

    /**
     * Get customer_id
     * @return string|null
     */
    
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customer_id
     * @return Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface
     */
    
    public function setCustomerId($customer_id);

    /**
     * Get seller_id
     * @return string|null
     */
    
    public function getSellerId();

    /**
     * Set seller_id
     * @param string $seller_id
     * @return Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface
     */
    
    public function setSellerId($seller_id);
}
