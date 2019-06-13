<?php


namespace Ntic\AccessPermission\Api\Data;

interface AccessPermissionInterface
{

    const STORE_ID = 'store_id';
    const GROUP_ID = 'group_id';
    const ACCESSPERMISSION_ID = 'accesspermission_id';
    const RULES = 'rules';


    /**
     * Get accesspermission_id
     * @return string|null
     */
    
    public function getAccesspermissionId();

    /**
     * Set accesspermission_id
     * @param string $accesspermission_id
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     */
    
    public function setAccesspermissionId($accesspermissionId);

    /**
     * Get group_id
     * @return string|null
     */
    
    public function getGroupId();

    /**
     * Set group_id
     * @param string $group_id
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     */
    
    public function setGroupId($group_id);

    /**
     * Get store_id
     * @return string|null
     */
    
    public function getStoreId();

    /**
     * Set store_id
     * @param string $store_id
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     */
    
    public function setStoreId($store_id);

    /**
     * Get rules
     * @return string|null
     */
    
    public function getRules();

    /**
     * Set rules
     * @param string $rules
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     */
    
    public function setRules($rules);
}
