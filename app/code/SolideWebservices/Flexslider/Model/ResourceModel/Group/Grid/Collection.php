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

namespace SolideWebservices\Flexslider\Model\ResourceModel\Group\Grid;

use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Search\AggregationInterface;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\Collection as GroupCollection;

/**
 * Class Collection
 * Collection for displaying grid of sales documents
 */
class Collection extends GroupCollection implements SearchResultInterface
{
    /**
     * Variable.
     *
     * @var AggregationInterface
     */
    protected $aggregations;

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
     * @param ManagerInterface       $eventManager           ManagerInterface.
     * @param StoreManagerInterface  $storeManager           SMInterface.
     * @param CollectionFactory      $groupCollectionFactory Group.
     * @param DateTime               $date                   Date.
     * @param mixed|null             $mainTable              MainTable.
     * @param mixed                  $eventPrefix            EventPrefix.
     * @param mixed                  $eventObject            EventObject.
     * @param mixed                  $resourceModel          ResourceModel.
     * @param string                 $model                  Model.
     * @param null                   $connection             Connection.
     * @param AbstractDb|null        $resource               Resource.
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        CollectionFactory $groupCollectionFactory,
        DateTime $date,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
        $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $storeManager,
            $groupCollectionFactory,
            $date,
            $connection,
            $resource
        );
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }

    /**
     * Get Aggregations.
     *
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * Set Aggregations.
     *
     * @param AggregationInterface $aggregations Aggregations.
     *
     * @return void
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * Retrieve all ids collection (backward compatibility with EAV collection).
     *
     * @param int $limit  Limit.
     * @param int $offset Offset.
     *
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol(
            $this->_getAllIdsSelect($limit, $offset),
            $this->_bindParams
        );
    }

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria SearchCriteriaInterface.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount TotalCount.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param array $items Items.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

}
