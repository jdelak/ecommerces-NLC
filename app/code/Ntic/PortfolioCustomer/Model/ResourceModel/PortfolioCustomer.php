<?php


namespace Ntic\PortfolioCustomer\Model\ResourceModel;

class PortfolioCustomer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ntic_portfolio_customer', 'portfolio_customer_id');
    }
}
