<?php


namespace Ntic\Conges\Model\ResourceModel\Conges;

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
            'Ntic\Conges\Model\Conges',
            'Ntic\Conges\Model\ResourceModel\Conges'
        );
    }
}
