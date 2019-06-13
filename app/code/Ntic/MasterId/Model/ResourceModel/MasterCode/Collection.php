<?php


namespace Ntic\MasterId\Model\ResourceModel\MasterCode;

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
            'Ntic\MasterId\Model\MasterCode',
            'Ntic\MasterId\Model\ResourceModel\MasterCode'
        );
    }
}
