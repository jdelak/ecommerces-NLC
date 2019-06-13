<?php


namespace Ntic\PortfolioCustomer\Model\ResourceModel\PortfolioCustomer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Ntic\PortfolioCustomer\Model\PortfolioCustomer',
            'Ntic\PortfolioCustomer\Model\ResourceModel\PortfolioCustomer'
        );
    }
}
