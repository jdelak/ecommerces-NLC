<?php


namespace Ntic\Conges\Model\ResourceModel;

class Conges extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ntic_conges_conges', 'conges_id');
    }
}
