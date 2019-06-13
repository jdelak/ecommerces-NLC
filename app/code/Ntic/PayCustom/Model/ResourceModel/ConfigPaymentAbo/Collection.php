<?php


namespace Ntic\PayCustom\Model\ResourceModel\ConfigPaymentAbo;

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
            'Ntic\PayCustom\Model\ConfigPaymentAbo',
            'Ntic\PayCustom\Model\ResourceModel\ConfigPaymentAbo'
        );
    }
}
