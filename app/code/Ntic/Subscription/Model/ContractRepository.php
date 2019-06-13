<?php


namespace Ntic\Subscription\Model;

use Magento\Framework\Api\SortOrder;
use Ntic\Subscription\Api\ContractRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Ntic\Subscription\Api\Data\ContractSearchResultsInterfaceFactory;
use Ntic\Subscription\Model\ResourceModel\Contract as ResourceContract;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotSaveException;
use Ntic\Subscription\Model\ResourceModel\Contract\CollectionFactory as ContractCollectionFactory;
use Ntic\Subscription\Api\Data\ContractInterfaceFactory;

class ContractRepository implements contractRepositoryInterface
{

    protected $contractFactory;

    protected $dataObjectProcessor;

    protected $searchResultsFactory;

    protected $contractCollectionFactory;

    private $storeManager;

    protected $dataObjectHelper;

    protected $resource;

    protected $dataContractFactory;


    /**
     * @param ResourceContract $resource
     * @param ContractFactory $contractFactory
     * @param ContractInterfaceFactory $dataContractFactory
     * @param ContractCollectionFactory $contractCollectionFactory
     * @param ContractSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceContract $resource,
        ContractFactory $contractFactory,
        ContractInterfaceFactory $dataContractFactory,
        ContractCollectionFactory $contractCollectionFactory,
        ContractSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->contractFactory = $contractFactory;
        $this->contractCollectionFactory = $contractCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataContractFactory = $dataContractFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ntic\Subscription\Api\Data\ContractInterface $contract
    ) {
        /* if (empty($contract->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $contract->setStoreId($storeId);
        } */
        try {
            $contract->getResource()->save($contract);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the contract: %1',
                $exception->getMessage()
            ));
        }
        return $contract;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($contractId)
    {
        $contract = $this->contractFactory->create();
        $contract->getResource()->load($contract, $contractId);
        if (!$contract->getId()) {
            throw new NoSuchEntityException(__('Contract with id "%1" does not exist.', $contractId));
        }
        return $contract;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->contractCollectionFactory->create();
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
        \Ntic\Subscription\Api\Data\ContractInterface $contract
    ) {
        try {
            $contract->getResource()->delete($contract);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Contract: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($contractId)
    {
        return $this->delete($this->getById($contractId));
    }
}
