<?php


namespace Ntic\AccessPermission\Model\ResourceModel\AccessPermission;

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
            'Ntic\AccessPermission\Model\AccessPermission',
            'Ntic\AccessPermission\Model\ResourceModel\AccessPermission'
        );
    }
}
