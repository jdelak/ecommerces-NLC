<?php


namespace Ntic\PayCustom\Model\ResourceModel;

class ConfigPaymentFraction extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ntic_config_payment_fraction', 'config_payment_fraction_id');
    }
}
