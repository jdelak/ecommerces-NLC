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
use SolideWebservices\Flexslider\Api\SlideRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use SolideWebservices\Flexslider\Model\ResourceModel\Slide as ResourceSlide;
use SolideWebservices\Flexslider\Model\ResourceModel\Slide\CollectionFactory as SlideCollectionFactory;

/**
 * Class SlideRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SlideRepository implements SlideRepositoryInterface
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
     * @var SlideFactory
     */
    protected $slideFactory;

    /**
     * Variable.
     *
     * @var SlideCollectionFactory
     */
    protected $slideCollectionFactory;

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
     * @var \SolideWebservices\Flexslider\Api\Data\SlideInterfaceFactory
     */
    protected $dataSlideFactory;

    /**
     * Construct.
     *
     * @param ResourceSlide                           $resource               Resource.
     * @param SlideFactory                            $slideFactory           SlideFactory.
     * @param Data\SlideInterfaceFactory              $dataSlideFactory       DataSlideFactory.
     * @param SlideCollectionFactory                  $slideCollectionFactory SlideCollectionFactory.
     * @param Data\SlideSearchResultsInterfaceFactory $searchResultsFactory   SearchResultsFactory.
     * @param DataObjectHelper                        $dataObjectHelper       DataObjectHelper.
     * @param DataObjectProcessor                     $dataObjectProcessor    DataObjectProcessor.
     */
    public function __construct(
        ResourceSlide $resource,
        SlideFactory $slideFactory,
        Data\SlideInterfaceFactory $dataSlideFactory,
        SlideCollectionFactory $slideCollectionFactory,
        Data\SlideSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->resource = $resource;
        $this->slideFactory = $slideFactory;
        $this->slideCollectionFactory = $slideCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSlideFactory = $dataSlideFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Save Slide data.
     *
     * @param Data\SlideInterface $slide Slide.
     *
     * @return Slide
     * @throws CouldNotSaveException Not Save Exception.
     */
    public function save(Data\SlideInterface $slide)
    {
        try {
            $this->resource->save($slide);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $slide;

    }

    /**
     * Load Slide data by given Slide Identity.
     *
     * @param string $slideId Slide ID.
     *
     * @return Slide
     * @throws \Magento\Framework\Exception\NoSuchEntityException Not Exist Ex.
     */
    public function getById($slideId)
    {
        $slide = $this->slideFactory->create();
        $slide->load($slideId);
        if (!$slide->getId()) {
            throw new NoSuchEntityException(
                __('Slide with id "%1" does not exist.',
                $slideId)
            );
        }
        return $slide;
    }

    /**
     * Load Slide data collection by given search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria Criteria.
     *
     * @return \SolideWebservices\Flexslider\Model\ResourceModel\Slide\Collection
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->slideCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'group_id') {
                    $collection->addGroupFilter($filter->getValue(), false);
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
        $collection->setCurSlide($criteria->getCurrentSlide());
        $collection->setSlideSize($criteria->getSlideSize());
        $slides = [];
        foreach ($collection as $slideModel) {
            $slideData = $this->dataSlideFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $slideData,
                $slideModel->getData(),
                'SolideWebservices\Flexslider\Api\Data\SlideInterface'
            );
            $slides[] = $this->dataObjectProcessor->buildOutputDataArray(
                $slideData,
                'SolideWebservices\Flexslider\Api\Data\SlideInterface'
            );
        }
        $searchResults->setItems($slides);
        return $searchResults;
    }

    /**
     * Delete Slide.
     *
     * @param Data\SlideInterface $slide Slide.
     *
     * @return bool
     * @throws CouldNotDeleteException Not Delete Exception.
     */
    public function delete(Data\SlideInterface $slide)
    {
        try {
            $this->resource->delete($slide);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Slide by given Slide Identity.
     *
     * @param string $slideId Slide ID.
     *
     * @return bool
     * @throws CouldNotDeleteException Not Delete Exception.
     * @throws NoSuchEntityException Not Exist Exception.
     */
    public function deleteById($slideId)
    {
        return $this->delete($this->getById($slideId));
    }
}
