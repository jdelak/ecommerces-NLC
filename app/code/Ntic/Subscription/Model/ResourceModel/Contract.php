<?php


namespace Ntic\Subscription\Model\ResourceModel;

class Contract extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ntic_subscription_contract', 'contract_id');
    }
}
