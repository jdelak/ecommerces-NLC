<?php


namespace Ntic\Pay\Model\ResourceModel;

class Certif extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ntic_certif', 'certif_id');
    }
}
