<?php


namespace Ntic\MasterId\Model;

use Ntic\MasterId\Api\Data\MasterCodeInterface;

class MasterCode extends \Magento\Framework\Model\AbstractModel implements MasterCodeInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ntic\MasterId\Model\ResourceModel\MasterCode');
    }

    /**
     * Get mastercode_id
     * @return string
     */
    public function getMastercodeId()
    {
        return $this->getData(self::MASTERCODE_ID);
    }

    /**
     * Set mastercode_id
     * @param string $mastercodeId
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setMastercodeId($mastercodeId)
    {
        return $this->setData(self::MASTERCODE_ID, $mastercodeId);
    }

    /**
     * Get nav_id
     * @return string
     */
    public function getNavId()
    {
        return $this->getData(self::NAV_ID);
    }

    /**
     * Set nav_id
     * @param string $nav_id
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setNavId($nav_id)
    {
        return $this->setData(self::NAV_ID, $nav_id);
    }

    /**
     * Get customer_id
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customer_id
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setCustomerId($customer_id)
    {
        return $this->setData(self::CUSTOMER_ID, $customer_id);
    }

    /**
     * Get well_master
     * @return string
     */
    public function getWellMaster()
    {
        return $this->getData(self::WELL_MASTER);
    }

    /**
     * Set well_master
     * @param string $well_master
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setWellMaster($well_master)
    {
        return $this->setData(self::WELL_MASTER, $well_master);
    }

    /**
     * Get int_master
     * @return string
     */
    public function getIntMaster()
    {
        return $this->getData(self::INT_MASTER);
    }

    /**
     * Set int_master
     * @param string $int_master
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setIntMaster($int_master)
    {
        return $this->setData(self::INT_MASTER, $int_master);
    }
}
