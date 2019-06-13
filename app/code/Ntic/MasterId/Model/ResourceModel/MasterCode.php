<?php


namespace Ntic\MasterId\Model\ResourceModel;

class MasterCode extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ntic_masterid_mastercode', 'mastercode_id');
    }
}
