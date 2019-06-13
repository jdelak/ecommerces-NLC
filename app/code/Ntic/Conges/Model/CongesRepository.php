<?php


namespace Ntic\Conges\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Ntic\Conges\Model\ResourceModel\Conges\CollectionFactory as CongesCollectionFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Ntic\Conges\Api\Data\CongesSearchResultsInterfaceFactory;
use Ntic\Conges\Model\ResourceModel\Conges as ResourceConges;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Ntic\Conges\Api\CongesRepositoryInterface;
use Ntic\Conges\Api\Data\CongesInterfaceFactory;

class CongesRepository implements congesRepositoryInterface
{

    protected $resource;

    protected $dataObjectProcessor;

    protected $congesCollectionFactory;

    protected $dataCongesFactory;

    private $storeManager;

    protected $congesFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;


    /**
     * @param ResourceConges $resource
     * @param CongesFactory $congesFactory
     * @param CongesInterfaceFactory $dataCongesFactory
     * @param CongesCollectionFactory $congesCollectionFactory
     * @param CongesSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceConges $resource,
        CongesFactory $congesFactory,
        CongesInterfaceFactory $dataCongesFactory,
        CongesCollectionFactory $congesCollectionFactory,
        CongesSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->congesFactory = $congesFactory;
        $this->congesCollectionFactory = $congesCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCongesFactory = $dataCongesFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ntic\Conges\Api\Data\CongesInterface $conges
    ) {
        /* if (empty($conges->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $conges->setStoreId($storeId);
        } */
        try {
            $conges->getResource()->save($conges);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the conges: %1',
                $exception->getMessage()
            ));
        }
        return $conges;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($congesId)
    {
        $conges = $this->congesFactory->create();
        $conges->getResource()->load($conges, $congesId);
        if (!$conges->getId()) {
            throw new NoSuchEntityException(__('Conges with id "%1" does not exist.', $congesId));
        }
        return $conges;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->congesCollectionFactory->create();
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
        \Ntic\Conges\Api\Data\CongesInterface $conges
    ) {
        try {
            $conges->getResource()->delete($conges);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Conges: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($congesId)
    {
        return $this->delete($this->getById($congesId));
    }
}
