<?php


namespace Ntic\AccessPermission\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Ntic\AccessPermission\Model\ResourceModel\AccessPermission\CollectionFactory as AccessPermissionCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Ntic\AccessPermission\Api\Data\AccessPermissionInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\NoSuchEntityException;
use Ntic\AccessPermission\Api\Data\AccessPermissionSearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Ntic\AccessPermission\Model\ResourceModel\AccessPermission as ResourceAccessPermission;
use Ntic\AccessPermission\Api\AccessPermissionRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

class AccessPermissionRepository implements AccessPermissionRepositoryInterface
{

    private $storeManager;

    protected $dataAccessPermissionFactory;

    protected $dataObjectProcessor;

    protected $accessPermissionFactory;

    protected $accessPermissionCollectionFactory;

    protected $searchResultsFactory;

    protected $resource;

    protected $dataObjectHelper;


    /**
     * @param ResourceAccessPermission $resource
     * @param AccessPermissionFactory $accessPermissionFactory
     * @param AccessPermissionInterfaceFactory $dataAccessPermissionFactory
     * @param AccessPermissionCollectionFactory $accessPermissionCollectionFactory
     * @param AccessPermissionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceAccessPermission $resource,
        AccessPermissionFactory $accessPermissionFactory,
        AccessPermissionInterfaceFactory $dataAccessPermissionFactory,
        AccessPermissionCollectionFactory $accessPermissionCollectionFactory,
        AccessPermissionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->accessPermissionFactory = $accessPermissionFactory;
        $this->accessPermissionCollectionFactory = $accessPermissionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAccessPermissionFactory = $dataAccessPermissionFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ntic\AccessPermission\Api\Data\AccessPermissionInterface $accessPermission
    ) {
        /* if (empty($accessPermission->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $accessPermission->setStoreId($storeId);
        } */
        try {
            $accessPermission->getResource()->save($accessPermission);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the accessPermission: %1',
                $exception->getMessage()
            ));
        }
        return $accessPermission;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($accessPermissionId)
    {
        $accessPermission = $this->accessPermissionFactory->create();
        $accessPermission->getResource()->load($accessPermission, $accessPermissionId);
        if (!$accessPermission->getId()) {
            throw new NoSuchEntityException(__('AccessPermission with id "%1" does not exist.', $accessPermissionId));
        }
        return $accessPermission;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->accessPermissionCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Ntic\AccessPermission\Api\Data\AccessPermissionInterface $accessPermission
    ) {
        try {
            $accessPermission->getResource()->delete($accessPermission);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the AccessPermission: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($accessPermissionId)
    {
        return $this->delete($this->getById($accessPermissionId));
    }
}
