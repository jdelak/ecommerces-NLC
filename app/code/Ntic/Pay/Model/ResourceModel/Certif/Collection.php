<?php


namespace Ntic\Pay\Model\ResourceModel\Certif;

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
            'Ntic\Pay\Model\Certif',
            'Ntic\Pay\Model\ResourceModel\Certif'
        );
    }
}
