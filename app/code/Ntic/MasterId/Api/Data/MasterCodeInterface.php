<?php


namespace Ntic\MasterId\Api\Data;

interface MasterCodeInterface
{

    const CUSTOMER_ID = 'customer_id';
    const WELL_MASTER = 'well_master';
    const INT_MASTER = 'int_master';
    const NAV_ID = 'nav_id';
    const MASTERCODE_ID = 'mastercode_id';


    /**
     * Get mastercode_id
     * @return string|null
     */
    public function getMastercodeId();

    /**
     * Set mastercode_id
     * @param string $mastercode_id
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setMastercodeId($mastercodeId);

    /**
     * Get nav_id
     * @return string|null
     */
    public function getNavId();

    /**
     * Set nav_id
     * @param string $nav_id
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setNavId($nav_id);

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customer_id
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setCustomerId($customer_id);

    /**
     * Get well_master
     * @return string|null
     */
    public function getWellMaster();

    /**
     * Set well_master
     * @param string $well_master
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setWellMaster($well_master);

    /**
     * Get int_master
     * @return string|null
     */
    public function getIntMaster();

    /**
     * Set int_master
     * @param string $int_master
     * @return \Ntic\MasterId\Api\Data\MasterCodeInterface
     */
    public function setIntMaster($int_master);
}
