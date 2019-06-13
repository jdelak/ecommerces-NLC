<?php


namespace Ntic\PortfolioCustomer\Model;

use Ntic\PortfolioCustomer\Model\ResourceModel\PortfolioCustomer as ResourcePortfolioCustomer;
use Magento\Framework\Reflection\DataObjectProcessor;
use Ntic\PortfolioCustomer\Api\PortfolioCustomerRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Ntic\PortfolioCustomer\Model\ResourceModel\PortfolioCustomer\CollectionFactory as PortfolioCustomerCollectionFactory;
use Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerSearchResultsInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;

class PortfolioCustomerRepository implements PortfolioCustomerRepositoryInterface
{

    protected $dataPortfolioCustomerFactory;

    protected $dataObjectHelper;

    protected $Portfolio_CustomerCollectionFactory;

    protected $Portfolio_CustomerFactory;

    protected $searchResultsFactory;

    private $storeManager;

    protected $resource;

    protected $dataObjectProcessor;


    /**
     * @param ResourcePortfolioCustomer $resource
     * @param PortfolioCustomerFactory $portfolioCustomerFactory
     * @param PortfolioCustomerInterfaceFactory $dataPortfolioCustomerFactory
     * @param PortfolioCustomerCollectionFactory $portfolioCustomerCollectionFactory
     * @param PortfolioCustomerSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourcePortfolioCustomer $resource,
        PortfolioCustomerFactory $portfolioCustomerFactory,
        PortfolioCustomerInterfaceFactory $dataPortfolioCustomerFactory,
        PortfolioCustomerCollectionFactory $portfolioCustomerCollectionFactory,
        PortfolioCustomerSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->portfolioCustomerFactory = $portfolioCustomerFactory;
        $this->portfolioCustomerCollectionFactory = $portfolioCustomerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPortfolioCustomerFactory = $dataPortfolioCustomerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface $portfolioCustomer
    ) {
        /* if (empty($portfolioCustomer->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $portfolioCustomer->setStoreId($storeId);
        } */
        try {
            $this->resource->save($portfolioCustomer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the portfolioCustomer: %1',
                $exception->getMessage()
            ));
        }
        return $portfolioCustomer;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($portfolioCustomerId)
    {
        $portfolioCustomer = $this->portfolioCustomerFactory->create();
        $portfolioCustomer->load($portfolioCustomerId);
        if (!$portfolioCustomer->getId()) {
            throw new NoSuchEntityException(__('Portfolio_Customer with id "%1" does not exist.', $portfolioCustomerId));
        }
        return $portfolioCustomer;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->portfolioCustomerCollectionFactory->create();
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
        $searchResults->setTotalCount($collection->getSize());
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
        $items = [];
        
        foreach ($collection as $portfolioCustomerModel) {
            $portfolioCustomerData = $this->dataPortfolioCustomerFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $portfolioCustomerData,
                $portfolioCustomerModel->getData(),
                'Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $portfolioCustomerData,
                'Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Ntic\PortfolioCustomer\Api\Data\PortfolioCustomerInterface $portfolioCustomer
    ) {
        try {
            $this->resource->delete($portfolioCustomer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Portfolio_Customer: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($portfolioCustomerId)
    {
        return $this->delete($this->getById($portfolioCustomerId));
    }
}
