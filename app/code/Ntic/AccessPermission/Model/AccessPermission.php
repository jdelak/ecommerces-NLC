<?php


namespace Ntic\AccessPermission\Model;

use Ntic\AccessPermission\Api\Data\AccessPermissionInterface;

class AccessPermission extends \Magento\Framework\Model\AbstractModel implements AccessPermissionInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ntic\AccessPermission\Model\ResourceModel\AccessPermission');
    }

    /**
     * Get accesspermission_id
     * @return string
     */
    public function getAccesspermissionId()
    {
        return $this->getData(self::ACCESSPERMISSION_ID);
    }

    /**
     * Set accesspermission_id
     * @param string $accesspermissionId
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     */
    public function setAccesspermissionId($accesspermissionId)
    {
        return $this->setData(self::ACCESSPERMISSION_ID, $accesspermissionId);
    }

    /**
     * Get group_id
     * @return string
     */
    public function getGroupId()
    {
        return $this->getData(self::GROUP_ID);
    }

    /**
     * Set group_id
     * @param string $group_id
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     */
    public function setGroupId($group_id)
    {
        return $this->setData(self::GROUP_ID, $group_id);
    }

    /**
     * Get store_id
     * @return string
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set store_id
     * @param string $store_id
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     */
    public function setStoreId($store_id)
    {
        return $this->setData(self::STORE_ID, $store_id);
    }

    /**
     * Get rules
     * @return string
     */
    public function getRules()
    {
        return $this->getData(self::RULES);
    }

    /**
     * Set rules
     * @param string $rules
     * @return \Ntic\AccessPermission\Api\Data\AccessPermissionInterface
     */
    public function setRules($rules)
    {
        return $this->setData(self::RULES, $rules);
    }
}
