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

use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Abstract collection of Flexslider groups and slides
 */
abstract class AbstractCollection extends
\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Group collection.
     *
     * @var CollectionFactory
     */
    protected $_groupCollectionFactory;

    /**
     * Date.
     *
     * @var DateTime
     */
    protected $_date;

    /**
     * Construct.
     *
     * @param EntityFactoryInterface $entityFactory          EntityFactory.
     * @param LoggerInterface        $logger                 Logger.
     * @param FetchStrategyInterface $fetchStrategy          FetchStrategy.
     * @param ManagerInterface       $eventManager           EventManager.
     * @param StoreManagerInterface  $storeManager           Store Manager.
     * @param CollectionFactory      $groupCollectionFactory Group.
     * @param DateTime               $date                   Date.
     * @param AdapterInterface|null  $connection             Connection.
     * @param AbstractDb|null        $resource               Resource.
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        CollectionFactory $groupCollectionFactory,
        DateTime $date,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->storeManager = $storeManager;
        $this->_groupCollectionFactory = $groupCollectionFactory;
        $this->_date = $date;
    }

    /**
     * Perform operations after collection load group.
     *
     * @param string $tableName  TableName.
     * @param string $columnName ColumnName.
     *
     * @return void
     */
    protected function performAfterLoadGroup($tableName, $columnName)
    {
        $items = $this->getColumnValues($columnName);
        if (count($items)) {
            $connection = $this->getConnection();
            $select = $connection->select()
                ->from(['group_entity_store' => $this->getTable($tableName)])
                ->where(
                    'group_entity_store.' . $columnName . ' IN (?)', $items
                );
            $result = $connection->fetchPairs($select);
            if ($result) {
                foreach ($this as $item) {
                    $entityId = $item->getData($columnName);
                    if (!isset($result[$entityId])) {
                        continue;
                    }
                    if ($result[$entityId] == 0) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = $result[$item->getData($columnName)];
                        $storeCode = $this->storeManager
                            ->getStore($storeId)->getCode();
                    }
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', [$result[$entityId]]);
                }
            }
        }
    }

    /**
     * Perform operations after collection load slide.
     *
     * @param string $tableName  TableName.
     * @param string $columnName ColumnName.
     *
     * @return void
     */
    protected function performAfterLoadSlide($tableName, $columnName)
    {
        $items = $this->getColumnValues($columnName);
        if (count($items)) {
            $connection = $this->getConnection();
            $select = $connection->select()
                ->from(['solidewebservices_flexslider_slide' => $this->getTable($tableName)])
                ->where('solidewebservices_flexslider_slide.' . $columnName .' IN (?)', $items);
            $result = $connection->fetchPairs($select);
            if ($result) {
                foreach ($this as $item) {
                    $entityId = $item->getData($columnName);
                    if (!isset($result[$entityId])) {
                        continue;
                    }
                    if ($result[$entityId] == 0) {
                        $groups = $this->_groupCollectionFactory->create();
                        $groupId = current($groups)->getId();
                    } else {
                        $groupId = $result[$item->getData($columnName)];
                    }
                    $item->setData('group_id', [$result[$entityId]]);
                }
            }
        }
    }

    /**
     * Add field filter to collection.
     *
     * @param array|string          $field     Field.
     * @param string|int|array|null $condition Condition.
     *
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add filter by store.
     *
     * @param int|array|\Magento\Store\Model\Store $store     Store.
     * @param bool                                 $withAdmin WithAdmin.
     *
     * @return $this
     */
    abstract public function addStoreFilter($store, $withAdmin = true);

    /**
     * Perform adding filter by store.
     *
     * @param int|array|\Magento\Store\Model\Store $store     Store.
     * @param bool                                 $withAdmin WithAdmin.
     *
     * @return void
     */
    protected function performAddStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof \Magento\Store\Model\Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');
    }

    /**
     * Perform adding filter by group.
     *
     * @param int $groupId Group ID.
     *
     * @return void
     */
    protected function performAddGroupFilter($groupId)
    {
        $this->addFilter('group_id', ['in' => $groupId], 'public');
    }

    /**
     * Perform adding filter by status.
     *
     * @param bool $status Status.
     *
     * @return $this
     */
    public function addEnabledFilter($status = 1)
    {
        $this->getSelect()->where('main_table.is_active = ?', $status);
        return $this;
    }

    /**
     * Perform adding filter by date for group.
     *
     * @return $this
     */
    public function addGroupDateFilter()
    {
        return $this->addFieldToFilter(
            'group_startdate',
            [
                ['to' => $this->_date->gmtDate('Y-m-d H:i:s')],
                ['group_startdate', 'null'=>'']

            ]
        )->addFieldToFilter(
            'group_enddate',
            [
                ['gteq' => $this->_date->gmtDate('Y-m-d H:i:s')],
                ['group_enddate', 'null'=>'']
            ]
        );
    }

    /**
     * Perform adding filter by date for slide.
     *
     * @return $this
     */
    public function addSlideDateFilter()
    {
        return $this->addFieldToFilter(
            'slide_startdate',
            [
                ['to' => $this->_date->gmtDate('Y-m-d H:i:s')],
                ['slide_startdate', 'null'=>'']

            ]
        )->addFieldToFilter(
            'slide_enddate',
            [
                ['gteq' => $this->_date->gmtDate('Y-m-d H:i:s')],
                ['slide_enddate', 'null'=>'']
            ]
        );
    }

    /**
     * Perform sort order for groups.
     *
     * @param string $groupSortOrder Group Sort Order.
     *
     * @return $this
     */
    public function addGroupSortFilter($groupSortOrder = 'ASC')
    {
        $this->getSelect()
            ->order('main_table.group_sort_order ' . $groupSortOrder);
        return $this;
    }

    /**
     * Perform sort order for slides.
     *
     * @param string $slideSortOrder Slide Sort Order.
     *
     * @return $this
     */
    public function addSlideSortFilter($slideSortOrder = 'ASC')
    {
        $this->getSelect()
            ->order('main_table.slide_sort_order ' . $slideSortOrder);
        return $this;
    }

    /**
     * Perform random sort order for slides.
     *
     * @param string $dir Direction.
     *
     * @return $this
     */
    public function addOrderByRandom($dir = 'ASC')
    {
        $this->getSelect()->order('RAND() ' . $dir);
        return $this;
    }

    /**
     * Perform filter by code.
     *
     * @param string $code Code.
     *
     * @return $this
     */
    public function addCodeFilter($code)
    {
        $this->getSelect()->where('main_table.identifier = ?', $code);
        return $this;
    }

    /**
     * Perform filter by group position.
     *
     * @param string $groupPosition Group Position.
     *
     * @return $this
     */
    public function addGroupPositionFilter($groupPosition)
    {
        $this->getSelect()
            ->where('main_table.group_position = ?', $groupPosition);
        return $this;
    }

    /**
     * Perform page filter.
     *
     * @param int $groupId Group ID.
     *
     * @return void
     */
    protected function performAddPageFilter($groupId)
    {
        $this->addFilter('page_id', ['in' => $groupId], 'public');
    }

    /**
     * Perform category filter.
     *
     * @param int $groupId Group ID.
     *
     * @return void
     */
    protected function performAddCategoryFilter($groupId)
    {
        $this->addFilter('category_id', ['in' => $groupId], 'public');
    }

    /**
     * Perform product filter.
     *
     * @param int $groupId Group ID.
     *
     * @return void
     */
    protected function performAddProductFilter($groupId)
    {
        $this->addFilter('product_id', ['in' => $groupId], 'public');
    }

    /**
     * Perform slide filter.
     *
     * @param int $slideId Slide ID.
     *
     * @return $this
     */
    protected function performAddSlideFilter($slideId)
    {
        $connection = $this->getConnection();

        $subquery = $connection->select()->from(
            $this->getTable('solidewebservices_flexslider_slide_group'),
            'group_id'
        )->where(
            'slide_id = ?',
            (int)$slideId
        );

        $this->getSelect()->where('main_table.group_id IN ?', $subquery);
        return $this;
    }

    /**
     * Join store relation table if there is store filter.
     *
     * @param string $tableName  TableName.
     * @param string $columnName ColumnName.
     *
     * @return void
     */
    protected function joinStoreRelationTable($tableName, $columnName)
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable($tableName)],
                'main_table.' . $columnName . ' = store_table.' . $columnName,
                []
            )->group(
                'main_table.' . $columnName
            );
        }
        parent::_renderFiltersBefore();
    }

    /**
     * Join group relation table if there is group filter.
     *
     * @param string $tableName  TableName.
     * @param string $columnName ColumnName.
     *
     * @return void
     */
    protected function joinGroupRelationTable($tableName, $columnName)
    {
        $this->getSelect()->join(
            ['solidewebservices_flexslider_slide_group' => $this->getTable($tableName)],
            'main_table.'. $columnName .' = solidewebservices_flexslider_slide_group.'. $columnName,
            []
            )->group(
                'main_table.' . $columnName
        );
        parent::_renderFiltersBefore();
    }

    /**
     * Join relation table if there is a filter.
     *
     * @param string $tableName  TableName.
     * @param string $columnName ColumnName.
     *
     * @return void
     */
    protected function joinRelationTable($tableName, $columnName)
    {
        if (($this->getFilter('store') &&
            $tableName == 'solidewebservices_flexslider_store') ||
            ($this->getFilter('page_id') &&
            $tableName == 'solidewebservices_flexslider_page') ||
            ($this->getFilter('category_id') &&
            $tableName == 'solidewebservices_flexslider_category') ||
            ($this->getFilter('product_id') &&
            $tableName == 'solidewebservices_flexslider_product') ||
            ($this->getFilter('slide_id') &&
            $tableName == 'solidewebservices_flexslider_slide_group')) {
            $this->getSelect()->join(
                ['connection_table' => $this->getTable($tableName)],
                'main_table.'. $columnName .' = connection_table.'. $columnName,
                []
            )->group(
                'main_table.' . $columnName
            );
        }
        parent::_renderFiltersBefore();
    }

    /**
     * Get SQL for get record count.
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(\Magento\Framework\DB\Select::GROUP);

        return $countSelect;
    }
}
