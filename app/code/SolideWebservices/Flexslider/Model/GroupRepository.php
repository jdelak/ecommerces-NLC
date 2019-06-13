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

namespace SolideWebservices\Flexslider\Model;

use SolideWebservices\Flexslider\Api\Data;
use SolideWebservices\Flexslider\Api\GroupRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use SolideWebservices\Flexslider\Model\ResourceModel\Group as ResourceGroup;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory as GroupCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class GroupRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GroupRepository implements GroupRepositoryInterface
{

    /**
     * Variable.
     *
     * @var ResourcePage
     */
    protected $resource;

    /**
     * Variable.
     *
     * @var GroupFactory
     */
    protected $groupFactory;

    /**
     * Variable.
     *
     * @var GroupCollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * Variable.
     *
     * @var Data\GroupSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * Variable.
     *
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * Variable.
     *
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * Variable.
     *
     * @var \SolideWebservices\Flexslider\Api\Data\GroupInterfaceFactory
     */
    protected $dataGroupFactory;

    /**
     * Variable.
     *
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Construct.
     *
     * @param ResourceGroup                           $resource               Resource.
     * @param GroupFactory                            $groupFactory           GroupFactory.
     * @param Data\GroupInterfaceFactory              $dataGroupFactory       DataGroupFactory.
     * @param GroupCollectionFactory                  $groupCollectionFactory GroupColFactory.
     * @param Data\GroupSearchResultsInterfaceFactory $searchResultsFactory   SearchResultsFactory.
     * @param DataObjectHelper                        $dataObjectHelper       DataObjectHelper.
     * @param DataObjectProcessor                     $dataObjectProcessor    DataObjectProcessor.
     * @param StoreManagerInterface                   $storeManager           StoreManager.
     */
    public function __construct(
        ResourceGroup $resource,
        GroupFactory $groupFactory,
        Data\GroupInterfaceFactory $dataGroupFactory,
        GroupCollectionFactory $groupCollectionFactory,
        Data\GroupSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->groupFactory = $groupFactory;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataGroupFactory = $dataGroupFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Group data.
     *
     * @param Data\GroupInterface $group Group.
     *
     * @return Group
     * @throws CouldNotSaveException Not Save Exception.
     */
    public function save(Data\GroupInterface $group)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $group->setStoreId($storeId);
        try {
            $this->resource->save($group);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $group;
    }

    /**
     * Load Group data by given Group Identity.
     *
     * @param string $groupId Group ID.
     *
     * @return Group
     * @throws \Magento\Framework\Exception\NoSuchEntityException Not Exist Ex.
     */
    public function getById($groupId)
    {
        $group = $this->groupFactory->create();
        $group->load($groupId);
        if (!$group->getId()) {
            throw new NoSuchEntityException(
                __('Group with id "%1" does not exist.',
                $groupId)
            );
        }
        return $group;
    }

    /**
     * Load Group data collection by given search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria Criteria.
     *
     * @return \SolideWebservices\Flexslider\Model\ResourceModel\Group\Collection
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->groupCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter(
                    $filter->getField(),
                    [$condition => $filter->getValue()]
                );
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurGroup($criteria->getCurrentGroup());
        $collection->setGroupSize($criteria->getGroupSize());
        $groups = [];
        foreach ($collection as $groupModel) {
            $groupData = $this->dataGroupFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $groupData,
                $groupModel->getData(),
                'SolideWebservices\Flexslider\Api\Data\GroupInterface'
            );
            $groups[] = $this->dataObjectProcessor->buildOutputDataArray(
                $groupData,
                'SolideWebservices\Flexslider\Api\Data\GroupInterface'
            );
        }
        $searchResults->setItems($groups);
        return $searchResults;
    }

    /**
     * Delete Group.
     *
     * @param Data\GroupInterface $group Group.
     *
     * @return bool
     * @throws CouldNotDeleteException Not Delete Exception.
     */
    public function delete(Data\GroupInterface $group)
    {
        try {
            $this->resource->delete($group);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Group by given Group Identity.
     *
     * @param string $groupId Group ID.
     *
     * @return bool
     * @throws CouldNotDeleteException Not Delete Exception.
     * @throws NoSuchEntityException Not Exist Exception.
     */
    public function deleteById($groupId)
    {
        return $this->delete($this->getById($groupId));
    }
}
