<?php


namespace Ntic\PayCustom\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Ntic\PayCustom\Api\Data\ConfigPaymentAboInterfaceFactory;
use Ntic\PayCustom\Api\ConfigPaymentAboRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;
use Ntic\PayCustom\Model\ResourceModel\ConfigPaymentAbo\CollectionFactory as ConfigPaymentAboCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Ntic\PayCustom\Api\Data\ConfigPaymentAboSearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Ntic\PayCustom\Model\ResourceModel\ConfigPaymentAbo as ResourceConfigPaymentAbo;

class ConfigPaymentAboRepository implements ConfigPaymentAboRepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $config_payment_aboFactory;

    private $storeManager;

    protected $dataConfigPaymentAboFactory;

    protected $config_payment_aboCollectionFactory;

    protected $dataObjectHelper;

    protected $resource;


    /**
     * @param ResourceConfigPaymentAbo $resource
     * @param ConfigPaymentAboFactory $configPaymentAboFactory
     * @param ConfigPaymentAboInterfaceFactory $dataConfigPaymentAboFactory
     * @param ConfigPaymentAboCollectionFactory $configPaymentAboCollectionFactory
     * @param ConfigPaymentAboSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceConfigPaymentAbo $resource,
        ConfigPaymentAboFactory $configPaymentAboFactory,
        ConfigPaymentAboInterfaceFactory $dataConfigPaymentAboFactory,
        ConfigPaymentAboCollectionFactory $configPaymentAboCollectionFactory,
        ConfigPaymentAboSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->configPaymentAboFactory = $configPaymentAboFactory;
        $this->configPaymentAboCollectionFactory = $configPaymentAboCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataConfigPaymentAboFactory = $dataConfigPaymentAboFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface $configPaymentAbo
    ) {
        /* if (empty($configPaymentAbo->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $configPaymentAbo->setStoreId($storeId);
        } */
        try {
            $this->resource->save($configPaymentAbo);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the configPaymentAbo: %1',
                $exception->getMessage()
            ));
        }
        return $configPaymentAbo;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($configPaymentAboId)
    {
        $configPaymentAbo = $this->configPaymentAboFactory->create();
        $configPaymentAbo->load($configPaymentAboId);
        if (!$configPaymentAbo->getId()) {
            throw new NoSuchEntityException(__('config_payment_abo with id "%1" does not exist.', $configPaymentAboId));
        }
        return $configPaymentAbo;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->configPaymentAboCollectionFactory->create();
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
        
        foreach ($collection as $configPaymentAboModel) {
            $configPaymentAboData = $this->dataConfigPaymentAboFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $configPaymentAboData,
                $configPaymentAboModel->getData(),
                'Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $configPaymentAboData,
                'Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Ntic\PayCustom\Api\Data\ConfigPaymentAboInterface $configPaymentAbo
    ) {
        try {
            $this->resource->delete($configPaymentAbo);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the config_payment_abo: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($configPaymentAboId)
    {
        return $this->delete($this->getById($configPaymentAboId));
    }
}
