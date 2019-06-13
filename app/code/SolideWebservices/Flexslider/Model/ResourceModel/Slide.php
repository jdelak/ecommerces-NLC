<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.2
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;

/**
 * Flexslider slide mysql resource
 */
class Slide extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Group model.
     *
     * @var null|\SolideWebservices\Flexslider\Model\Group
     */
    protected $_group = null;

    /**
     * Store model.
     *
     * @var null|\Magento\Store\Model\Store
     */
    protected $_store = null;

    /**
     * Group collection.
     *
     * @var \SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory
     */
    protected $_groupCollectionFactory;

    /**
     * Construct.
     *
     * @param Context           $context                Context.
     * @param CollectionFactory $groupCollectionFactory GroupCollectionFactory.
     * @param string            $connectionName         ConnectionName.
     */
    public function __construct(
        Context $context,
        CollectionFactory $groupCollectionFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_groupCollectionFactory = $groupCollectionFactory;
    }

    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('solidewebservices_flexslider_slide', 'slide_id');
    }

    /**
     * Process page data before deleting.
     *
     * @param \Magento\Framework\Model\AbstractModel $object AbstractModel.
     *
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['slide_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete(
            $this->getTable('solidewebservices_flexslider_slide_group'), $condition
        );
        return parent::_beforeDelete($object);
    }

    /**
     * Perform after save actions.
     *
     * @param \Magento\Framework\Model\AbstractModel $object AbstractModel.
     *
     * @return $object
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldGroups = $this->lookupGroupIds($object->getId());
        $newGroups = (array)$object->getGroups();
        if (empty($newGroups)) {
            $newGroups = (array)$object->getGroupId();
        }
        $table = $this->getTable('solidewebservices_flexslider_slide_group');
        $insert = array_diff($newGroups, $oldGroups);
        $delete = array_diff($oldGroups, $newGroups);

        if ($delete) {
            $where = [
                'slide_id = ?' => (int)$object->getId(),
                'group_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $groupId) {
                $data[] = [
                    'slide_id' => (int)$object->getId(),
                    'group_id' => (int)$groupId
                ];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Perform operations after object load.
     *
     * @param \Magento\Framework\Model\AbstractModel $object AbstractModel.
     *
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $groups = $this->lookupGroupIds($object->getId());

            $object->setData('group_id', $groups);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data.
     *
     * @param string                   $field  Field.
     * @param mixed                    $value  Value.
     * @param \Magento\Cms\Model\Group $object Group.
     *
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($data = $object->getGroupId()) {
            $select->join(
            ['solidewebservices_flexslider_slide_group' => $this->getTable('solidewebservices_flexslider_slide_group')],
            $this->getMainTable() . '.slide_id = solidewebservices_flexslider_slide_group.slide_id',
            []
            )->where(
                'is_active = ?',
                1
            )->where(
                'solidewebservices_flexslider_slide_group.group_id IN (?)',
                $data
            )->order(
                'solidewebservices_flexslider_slide_group.slide_id DESC'
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * Retrieve load select with filter by identifier, group and status.
     *
     * @param string    $identifier Identifier.
     * @param int|array $group      Group.
     * @param int       $isActive   IsActive.
     *
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $group, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['solidewebservices_flexslider_slide' => $this->getMainTable()]
        )->join(
            ['solidewebservices_flexslider_slide_group' => $this->getTable('solidewebservices_flexslider_slide_group')],
            'solidewebservices_flexslider_slide.slide_id = solidewebservices_flexslider_slide_group.slide_id',
            []
        )->where(
            'solidewebservices_flexslider_slide.identifier = ?',
            $identifier
        )->where(
            'solidewebservices_flexslider_slide_group.group_id IN (?)',
            $group
        );

        if ($isActive) {
            $select->where(
                'solidewebservices_flexslider_slide.is_active = ?',
                $isActive
            );
        }

        return $select;
    }

    /**
     * Retrieves group title from DB by passed id.
     *
     * @param string $id Slide ID.
     *
     * @return string|false
     */
    public function getSlideTitleById($id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable(), 'title')
            ->where('slide_id = :slide_id');
        $binds = ['slide_id' => (int)$id];
        return $connection->fetchOne($select, $binds);
    }

    /**
     * Get group ids to which specified item is assigned.
     *
     * @param int $slideId Slide ID.
     *
     * @return $select[]
     */
    public function lookupGroupIds($slideId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('solidewebservices_flexslider_slide_group'),
            'group_id'
        )->where(
            'slide_id = ?',
            (int)$slideId
        );
        return $connection->fetchCol($select);
    }

    /**
     * Set group model.
     *
     * @param \Magento\Store\Model\Store $group Group.
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->_group = $group;
        return $this;
    }

    /**
     * Retrieve group model.
     *
     * @return \SolideWebservices\Flexslider\Model\Group
     */
    public function getGroup()
    {
        return $this->_groupCollectionFactory->getGroup($this->_group);
    }

}
