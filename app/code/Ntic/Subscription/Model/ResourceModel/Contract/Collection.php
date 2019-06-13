<?php


namespace Ntic\Subscription\Model\ResourceModel\Contract;

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
            'Ntic\Subscription\Model\Contract',
            'Ntic\Subscription\Model\ResourceModel\Contract'
        );
    }
}
