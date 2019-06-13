<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.4
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Flexslider group mysql resource
 */
class Group extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Store model.
     *
     * @var null|\Magento\Store\Model\Store
     */
    protected $_store = null;

    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Construct.
     *
     * @param Context               $context        Context.
     * @param StoreManagerInterface $storeManager   StoreManager.
     * @param string                $connectionName ConnectionName.
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_storeManager = $storeManager;
    }

    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('solidewebservices_flexslider_group', 'group_id');
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
        $condition = ['group_id = ?' => (int)$object->getId()];

        $this->getConnection()->delete(
            $this->getTable('solidewebservices_flexslider_store'), $condition
        );
        $this->getConnection()->delete(
            $this->getTable('solidewebservices_flexslider_page'), $condition
        );
        $this->getConnection()->delete(
            $this->getTable('solidewebservices_flexslider_category'), $condition
        );
        $this->getConnection()->delete(
            $this->getTable('solidewebservices_flexslider_product'), $condition
        );

        return parent::_beforeDelete($object);
    }

    /**
     * Perform after save actions.
     *
     * @param \Magento\Framework\Model\AbstractModel $object AbstractModel.
     *
     * @return void
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->afterSaveStores($object);
        $this->afterSavePages($object);
        $this->afterSaveCategories($object);
        $this->afterSaveProducts($object);
    }

    /**
     * Assign group to store views.
     *
     * @param \Magento\Framework\Model\AbstractModel $object AbstractModel.
     *
     * @return $this
     */
    private function afterSaveStores(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table = $this->getTable('solidewebservices_flexslider_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = [
                'group_id = ?' => (int)$object->getId(),
                'store_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'group_id' => (int)$object->getId(),
                    'store_id' => (int)$storeId
                ];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Assign group to page views.
     *
     * @param \Magento\Framework\Model\AbstractModel $object AbstractModel.
     *
     * @return $this
     */
    private function afterSavePages(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldPages = $this->lookupPageIds($object->getId());
        $newPages = (array)$object->getPages();
        if (empty($newPages)) {
            $newPages = (array)$object->getPageId();
        }
        $table = $this->getTable('solidewebservices_flexslider_page');
        $insert = array_diff($newPages, $oldPages);
        $delete = array_diff($oldPages, $newPages);

        if ($delete) {
            $where = [
                'group_id = ?' => (int)$object->getId(),
                'page_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $pageId) {
                $data[] = [
                    'group_id' => (int)$object->getId(),
                    'page_id' => (int)$pageId
                ];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Assign group to categories views.
     *
     * @param \Magento\Framework\Model\AbstractModel $object AbstractModel.
     *
     * @return $this
     */
    private function afterSaveCategories(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldCategories = $this->lookupCategoryIds($object->getId());
        $newCategories = (array)$object->getCategories();
        if (empty($newCategories)) {
            $newCategories = (array)$object->getCategoriesIds();
        }
        $table = $this->getTable('solidewebservices_flexslider_category');
        $insert = array_diff($newCategories, $oldCategories);
        $delete = array_diff($oldCategories, $newCategories);

        if ($delete) {
            $where = [
                'group_id = ?' => (int)$object->getId(),
                'category_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $categoryId) {
                $data[] = [
                    'group_id' => (int)$object->getId(),
                    'category_id' => (int)$categoryId
                ];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Assign group to products views.
     *
     * @param \Magento\Framework\Model\AbstractModel $object AbstractModel.
     *
     * @return $this
     */
    private function afterSaveProducts(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldProducts = $this->lookupProductIds($object->getId());
        $newProducts = (array)$object->getProductsData();

        if (!$newProducts) {
            $newProducts = $oldProducts;
        }

        $table = $this->getTable('solidewebservices_flexslider_product');
        $insert = array_diff($newProducts, $oldProducts);
        $delete = array_diff($oldProducts, $newProducts);

        if ($delete) {
            $where = [
                'group_id = ?' => (int)$object->getId(),
                'product_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $productId) {
                $data[] = [
                    'group_id' => (int)$object->getId(),
                    'product_id' => (int)$productId
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
            $stores = $this->lookupStoreIds($object->getId());
            $pages = $this->lookupPageIds($object->getId());
            $categories = $this->lookupCategoryIds($object->getId());
            $products = $this->lookupProductIds($object->getId());

            $object->setData('store_id', $stores);
            $object->setData('page_id', $pages);
            $object->setData('category_ids', $categories);
            $object->setData('in_products', $products);
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

        if ($object->getStoreId()) {
            $storeIds = [
                \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                (int)$object->getStoreId()
            ];
            $select->join(
                ['solidewebservices_flexslider_store' => $this->getTable('solidewebservices_flexslider_store')],
                $this->getMainTable() . '.group_id = solidewebservices_flexslider_store.group_id',
                []
            )->where(
                'is_active = ?',
                1
            )->where(
                'solidewebservices_flexslider_store.store_id IN (?)',
                $storeIds
            )->order(
                'solidewebservices_flexslider_store.store_id DESC'
            )->limit(
                1
            );
        }
        if ($data = $object->getPageId()) {
            $select->join(
                ['solidewebservices_flexslider_page' => $this->getTable('solidewebservices_flexslider_page')],
                $this->getMainTable() . '.group_id = solidewebservices_flexslider_page.group_id',
                []
                )->where(
                    'solidewebservices_flexslider_page.page_id in (?) ',
                    $data
                );
        }
        if ($data = $object->getCategoriesIds()) {
            $select->join(
                ['solidewebservices_flexslider_category' => $this->getTable('solidewebservices_flexslider_category')],
                $this->getMainTable() . '.group_id = solidewebservices_flexslider_category.group_id',
                []
                )->where(
                    'solidewebservices_flexslider_category.category_id in (?) ',
                    $data
                );
        }
        if ($data = $object->getInProducts()) {
            $select->join(
                ['solidewebservices_flexslider_product' => $this->getTable('solidewebservices_flexslider_product')],
                $this->getMainTable() . '.group_id = solidewebservices_flexslider_product.group_id',
                []
                )->where(
                    'solidewebservices_flexslider_product.product_id in (?) ',
                    $data
                );
        }

        return $select;
    }

    /**
     * Retrieve load select with filter by identifier, store and activity.
     *
     * @param string    $identifier Identifier.
     * @param int|array $store      Store.
     * @param int       $isActive   IsActive.
     *
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByIdentifierSelect(
        $identifier,
        $store,
        $isActive = null
    ) {
        $select = $this->getConnection()->select()->from(
            ['solidewebservices_flexslider_group' => $this->getMainTable()]
        )->join(
            ['solidewebservices_flexslider_store' => $this->getTable('solidewebservices_flexslider_store')],
            'solidewebservices_flexslider_group.group_id = solidewebservices_flexslider_store.group_id',
            []
        )->where(
            'solidewebservices_flexslider_group.identifier = ?',
            $identifier
        )->where(
            'solidewebservices_flexslider_store.store_id IN (?)',
            $store
        );

        if ($isActive) {
            $select->where(
                'solidewebservices_flexslider_group.is_active = ?',
                $isActive
            );
        }

        return $select;
    }

    /**
     * Check if group identifier exist for specific store.
     *
     * @param string $identifier Identifier.
     * @param int    $storeId    StoreID.
     *
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        $stores = [\Magento\Store\Model\Store::DEFAULT_STORE_ID, $storeId];
        $select = $this->_getLoadByIdentifierSelect($identifier, $stores, 1);
        $select->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->columns('solidewebservices_flexslider_group.group_id')
            ->order('solidewebservices_flexslider_store.store_id DESC')
            ->limit(1);

        return $this->getConnection()->fetchOne($select);
    }

    /**
     * Retrieves group title from DB by passed id.
     *
     * @param string $id Group ID.
     *
     * @return string|false
     */
    public function getGroupTitleById($id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable(), 'title')
            ->where('group_id = :group_id');
        $binds = ['group_id' => (int)$id];
        return $connection->fetchOne($select, $binds);
    }

    /**
     * Get store ids to which specified item is assigned.
     *
     * @param int $groupId Group ID.
     *
     * @return $select[]
     */
    public function lookupStoreIds($groupId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('solidewebservices_flexslider_store'),
            'store_id'
        )->where(
            'group_id = ?',
            (int)$groupId
        );
        return $connection->fetchCol($select);
    }

    /**
     * Get page ids to which specified item is assigned.
     *
     * @param int $groupId Group ID.
     *
     * @return select[]
     */
    public function lookupPageIds($groupId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('solidewebservices_flexslider_page'),
            'page_id'
        )->where(
            'group_id = ?',
            (int)$groupId
        );
        return $connection->fetchCol($select);
    }

    /**
     * Get categories ids to which specified item is assigned.
     *
     * @param int $groupId Group ID.
     *
     * @return select[]
     */
    public function lookupCategoryIds($groupId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('solidewebservices_flexslider_category'),
            'category_id'
        )->where(
            'group_id = ?',
            (int)$groupId
        );
        return $connection->fetchCol($select);
    }

    /**
     * Get product ids to which specified item is assigned.
     *
     * @param int $groupId Group ID.
     *
     * @return select[]
     */
    public function lookupProductIds($groupId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('solidewebservices_flexslider_product'),
            'product_id'
        )->where(
            'group_id = ?',
            (int)$groupId
        );
        return $connection->fetchCol($select);
    }

    /**
     * Get slide ids to which specified item is assigned.
     *
     * @param int $groupId Group ID.
     *
     * @return select[]
     */
    public function lookupSlideIds($groupId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('solidewebservices_flexslider_slide_group'),
            'slide_id'
        )->where(
            'group_id = ?',
            (int)$groupId
        );
        return $connection->fetchCol($select);
    }

    /**
     * Count slide ids to which specified item is assigned.
     *
     * @param int $groupId Group ID.
     *
     * @return select[]
     */
    public function countSlideIds($groupId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('solidewebservices_flexslider_slide_group'),
            'COUNT("slide_id")'
        )->where(
            'group_id = ?',
            (int)$groupId
        );
        return (int)$connection->fetchOne($select);
    }

    /**
     * Set store model.
     *
     * @param \Magento\Store\Model\Store $store Store.
     *
     * @return $this
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Retrieve store model.
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->_storeManager->getStore($this->_store);
    }

}
