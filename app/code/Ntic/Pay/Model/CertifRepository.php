<?php


namespace Ntic\Pay\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Ntic\Pay\Model\ResourceModel\Certif as ResourceCertif;
use Ntic\Pay\Api\Data\CertifSearchResultsInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Ntic\Pay\Model\ResourceModel\Certif\CollectionFactory as CertifCollectionFactory;
use Ntic\Pay\Api\CertifRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SortOrder;
use Ntic\Pay\Api\Data\CertifInterfaceFactory;

class CertifRepository implements CertifRepositoryInterface
{

    protected $resource;

    protected $dataObjectHelper;

    protected $searchResultsFactory;

    protected $certifCollectionFactory;

    private $storeManager;

    protected $dataObjectProcessor;

    protected $certifFactory;

    protected $dataCertifFactory;


    /**
     * @param ResourceCertif $resource
     * @param CertifFactory $certifFactory
     * @param CertifInterfaceFactory $dataCertifFactory
     * @param CertifCollectionFactory $certifCollectionFactory
     * @param CertifSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceCertif $resource,
        CertifFactory $certifFactory,
        CertifInterfaceFactory $dataCertifFactory,
        CertifCollectionFactory $certifCollectionFactory,
        CertifSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->certifFactory = $certifFactory;
        $this->certifCollectionFactory = $certifCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCertifFactory = $dataCertifFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Ntic\Pay\Api\Data\CertifInterface $certif
    ) {
        /* if (empty($certif->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $certif->setStoreId($storeId);
        } */
        try {
            $certif->getResource()->save($certif);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the certif: %1',
                $exception->getMessage()
            ));
        }
        return $certif;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($certifId)
    {
        $certif = $this->certifFactory->create();
        $certif->getResource()->load($certif, $certifId);
        if (!$certif->getId()) {
            throw new NoSuchEntityException(__('Certif with id "%1" does not exist.', $certifId));
        }
        return $certif;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->certifCollectionFactory->create();
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
        \Ntic\Pay\Api\Data\CertifInterface $certif
    ) {
        try {
            $certif->getResource()->delete($certif);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Certif: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($certifId)
    {
        return $this->delete($this->getById($certifId));
    }
}
