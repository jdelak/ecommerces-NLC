<?php


namespace Ntic\PayCustom\Model\ResourceModel;

class ConfigPaymentAbo extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ntic_config_payment_abo', 'config_payment_abo_id');
    }
}
