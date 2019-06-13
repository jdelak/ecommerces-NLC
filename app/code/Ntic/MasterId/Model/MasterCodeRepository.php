<?php


namespace Ntic\MasterId\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Ntic\MasterId\Api\MasterCodeRepositoryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Ntic\MasterId\Api\Data\MasterCodeSearchResultsInterfaceFactory;
use Ntic\MasterId\Api\Data\MasterCodeInterfaceFactory;
use Ntic\MasterId\Model\ResourceModel\MasterCode as ResourceMasterCode;
use Ntic\MasterId\Model\ResourceModel\MasterCode\CollectionFactory as MasterCodeCollectionFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotSaveException;

class MasterCodeRepository implements masterCodeRepositoryInterface
{

    protected $masterCodeCollectionFactory;

    protected $dataObjectProcessor;

    private $storeManager;

    protected $dataObjectHelper;

    protected $dataMasterCodeFactory;

    protected $resource;

    protected $masterCodeFactory;

    protected $searchResultsFactory;


    /**
     * @param ResourceMasterCode $resource
     * @param MasterCodeFactory $masterCodeFactory
     * @param MasterCodeInterfaceFactory $dataMasterCodeFactory
     * @param MasterCodeCollectionFactory $masterCodeCollectionFactory
     * @param MasterCodeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceMasterCode $resource,
        MasterCodeFactory $masterCodeFactory,
        MasterCodeInterfaceFactory $dataMasterCodeFactory,
        MasterCodeCollectionFactory $masterCodeCollectionFactory,
        MasterCodeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->masterCodeFactory = $masterCodeFactory;
        $this->masterCodeCollectionFactory = $masterCodeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataMasterCodeFactory = $dataMasterCodeFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ntic\MasterId\Api\Data\MasterCodeInterface $masterCode
    ) {
        /* if (empty($masterCode->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $masterCode->setStoreId($storeId);
        } */
        try {
            $masterCode->getResource()->save($masterCode);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the masterCode: %1',
                $exception->getMessage()
            ));
        }
        return $masterCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($masterCodeId)
    {
        $masterCode = $this->masterCodeFactory->create();
        $masterCode->getResource()->load($masterCode, $masterCodeId);
        if (!$masterCode->getId()) {
            throw new NoSuchEntityException(__('MasterCode with id "%1" does not exist.', $masterCodeId));
        }
        return $masterCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->masterCodeCollectionFactory->create();
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
        \Ntic\MasterId\Api\Data\MasterCodeInterface $masterCode
    ) {
        try {
            $masterCode->getResource()->delete($masterCode);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the MasterCode: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($masterCodeId)
    {
        return $this->delete($this->getById($masterCodeId));
    }
}
