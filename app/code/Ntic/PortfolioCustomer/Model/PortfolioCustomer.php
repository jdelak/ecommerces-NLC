<?php


namespace Ntic\PortfolioCustomer\Model;

use Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface;

class PortfolioCustomer extends \Magento\Framework\Model\AbstractModel implements PortfolioCustomerInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ntic\PortfolioCustomer\Model\ResourceModel\PortfolioCustomer');
    }

    /**
     * Get portfolio_customer_id
     * @return string
     */
    public function getPortfolioCustomerId()
    {
        return $this->getData(self::PORTFOLIO_CUSTOMER_ID);
    }

    /**
     * Set portfolio_customer_id
     * @param string $portfolioCustomerId
     * @return Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface
     */
    public function setPortfolioCustomerId($portfolioCustomerId)
    {
        return $this->setData(self::PORTFOLIO_CUSTOMER_ID, $portfolioCustomerId);
    }

    /**
     * Get customer_id
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customer_id
     * @return Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface
     */
    public function setCustomerId($customer_id)
    {
        return $this->setData(self::CUSTOMER_ID, $customer_id);
    }

    /**
     * Get seller_id
     * @return string
     */
    public function getSellerId()
    {
        return $this->getData(self::SELLER_ID);
    }

    /**
     * Set seller_id
     * @param string $seller_id
     * @return Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface
     */
    public function setSellerId($seller_id)
    {
        return $this->setData(self::SELLER_ID, $seller_id);
    }

    /* AJOUT YVANE */
    public function getEnableStatus() {
        return 1;
    }

    /* AJOUT YVANE */
    public function getDisableStatus() {
        return 0;
    }
}
