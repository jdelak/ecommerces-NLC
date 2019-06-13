<?php


namespace Ntic\AccessPermission\Model\ResourceModel;

class AccessPermission extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ntic_accesspermission', 'accesspermission_id');
    }
}
