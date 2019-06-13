<?php


namespace Ntic\PayCustom\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Ntic\PayCustom\Model\ResourceModel\ConfigPaymentFraction\CollectionFactory as ConfigPaymentFractionCollectionFactory;
use Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterfaceFactory;
use Ntic\PayCustom\Api\ConfigPaymentFractionRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Ntic\PayCustom\Api\Data\ConfigPaymentFractionSearchResultsInterfaceFactory;
use Ntic\PayCustom\Model\ResourceModel\ConfigPaymentFraction as ResourceConfigPaymentFraction;

class ConfigPaymentFractionRepository implements ConfigPaymentFractionRepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $dataConfigPaymentFractionFactory;

    private $storeManager;

    protected $config_payment_fractionFactory;

    protected $dataObjectHelper;

    protected $resource;

    protected $config_payment_fractionCollectionFactory;


    /**
     * @param ResourceConfigPaymentFraction $resource
     * @param ConfigPaymentFractionFactory $configPaymentFractionFactory
     * @param ConfigPaymentFractionInterfaceFactory $dataConfigPaymentFractionFactory
     * @param ConfigPaymentFractionCollectionFactory $configPaymentFractionCollectionFactory
     * @param ConfigPaymentFractionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceConfigPaymentFraction $resource,
        ConfigPaymentFractionFactory $configPaymentFractionFactory,
        ConfigPaymentFractionInterfaceFactory $dataConfigPaymentFractionFactory,
        ConfigPaymentFractionCollectionFactory $configPaymentFractionCollectionFactory,
        ConfigPaymentFractionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->configPaymentFractionFactory = $configPaymentFractionFactory;
        $this->configPaymentFractionCollectionFactory = $configPaymentFractionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataConfigPaymentFractionFactory = $dataConfigPaymentFractionFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface $configPaymentFraction
    ) {
        /* if (empty($configPaymentFraction->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $configPaymentFraction->setStoreId($storeId);
        } */
        try {
            $this->resource->save($configPaymentFraction);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the configPaymentFraction: %1',
                $exception->getMessage()
            ));
        }
        return $configPaymentFraction;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($configPaymentFractionId)
    {
        $configPaymentFraction = $this->configPaymentFractionFactory->create();
        $configPaymentFraction->load($configPaymentFractionId);
        if (!$configPaymentFraction->getId()) {
            throw new NoSuchEntityException(__('config_payment_fraction with id "%1" does not exist.', $configPaymentFractionId));
        }
        return $configPaymentFraction;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->configPaymentFractionCollectionFactory->create();
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
        
        foreach ($collection as $configPaymentFractionModel) {
            $configPaymentFractionData = $this->dataConfigPaymentFractionFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $configPaymentFractionData,
                $configPaymentFractionModel->getData(),
                'Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $configPaymentFractionData,
                'Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Ntic\PayCustom\Api\Data\ConfigPaymentFractionInterface $configPaymentFraction
    ) {
        try {
            $this->resource->delete($configPaymentFraction);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the config_payment_fraction: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($configPaymentFractionId)
    {
        return $this->delete($this->getById($configPaymentFractionId));
    }
}
