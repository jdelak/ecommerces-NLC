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

namespace SolideWebservices\Flexslider\Block;

use SolideWebservices\Flexslider\Model\Slide;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory as GroupCollectionFactory;
use SolideWebservices\Flexslider\Model\ResourceModel\Slide\CollectionFactory as SlideCollectionFactory;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Cms\Model\BlockFactory;
use SolideWebservices\Flexslider\Helper\Data;
use SolideWebservices\Flexslider\Helper\Image;

/**
 * Flexslider group content block
 */
class Flexslider extends \Magento\Framework\View\Element\Template
{
    const DEFAULT_MEDIA_PATH = Slide::DEFAULT_MEDIA_PATH;
    const THUMB_MEDIA_PATH   = Slide::THUMB_MEDIA_PATH;
    const SMALL_MEDIA_PATH   = Slide::SMALL_MEDIA_PATH;
    const MEDIUM_MEDIA_PATH  = Slide::MEDIUM_MEDIA_PATH;
    const LARGE_MEDIA_PATH   = Slide::LARGE_MEDIA_PATH;

    /**
     * Variable.
     *
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Variable.
     *
     * @var Page
     */
    protected $_page;

    /**
     * Variable.
     *
     * @var GroupCollectionFactory
     */
    protected $_groupCollectionFactory;

    /**
     * Variable.
     *
     * @var SlideCollectionFactory
     */
    protected $_slideCollectionFactory;

    /**
     * Variable.
     *
     * @var inheret
     */
    protected $_storeManager;

    /**
     * Variable.
     *
     * @var FilterProvider
     */
    protected $_filterProvider;

    /**
     * Variable.
     *
     * @var BlockFactory
     */
    protected $_blockFactory;

    /**
     * Variable.
     *
     * @var Data
     */
    public $flexsliderHelper;

    /**
     * Variable.
     *
     * @var Image
     */
    public $flexsliderImageHelper;

    /**
     * Construct.
     *
     * @param Context                $context                Context.
     * @param Registry               $coreRegistry           CoreRegistry.
     * @param GroupCollectionFactory $groupCollectionFactory Group Collection.
     * @param SlideCollectionFactory $slideCollectionFactory Slide Collection.
     * @param Page                   $page                   Page.
     * @param FilterProvider         $filterProvider         FilterProvider.
     * @param BlockFactory           $blockFactory           BlockFactory.
     * @param Data                   $flexsliderHelper       FlexsliderHelper.
     * @param Image                  $flexsliderImageHelper  FlexImageHelper.
     * @param array                  $data                   Data.
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        GroupCollectionFactory $groupCollectionFactory,
        SlideCollectionFactory $slideCollectionFactory,
        Page $page,
        FilterProvider $filterProvider,
        BlockFactory $blockFactory,
        Data $flexsliderHelper,
        Image $flexsliderImageHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $coreRegistry;
        $this->_groupCollectionFactory = $groupCollectionFactory;
        $this->_slideCollectionFactory = $slideCollectionFactory;
        $this->_page = $page;
        $this->_storeManager = $context->getStoreManager();
        $this->_filterProvider = $filterProvider;
        $this->_blockFactory = $blockFactory;
        $this->flexsliderHelper = $flexsliderHelper;
        $this->flexsliderImageHelper = $flexsliderImageHelper;
    }

    /**
     * Check if there is valid group.
     *
     * @return bool || collection
     */
    public function hasValidGroup()
    {
        if ($this->flexsliderHelper->isEnabled()) {
            return is_object($this->_getCollection());
        }
        return false;
    }

    /**
     * Get collection.
     *
     * @return collection
     */
    public function _getCollection()
    {
        $code = $this->getCode();
        $groupPosition = $this->getPosition();
        $isGlobal = $this->getGlobal();
        $scope = $this->getScope();
        $shouldLoad = $this->flexsliderHelper->getEnabledPositions($scope);

        if ($shouldLoad) {
            $groupCollection = $this->_groupCollectionFactory->create()
                ->addEnabledFilter()
                ->addGroupDateFilter();

            if (!$this->_storeManager->isSingleStoreMode()) {
                $storeId = $this->_storeManager->getStore()->getId();
                $groupCollection->addStoreFilter($storeId);
            }

            if ($code) {
                $groupCollection->addCodeFilter($code);
            } else {

                if ($scope=='selected') {
                    if ($this->_coreRegistry->registry('current_product')) {
                        $groupCollection->addProductFilter(
                            $this->_coreRegistry
                                ->registry('current_product')
                                ->getId()
                        );
                    } elseif ($this->_coreRegistry
                                ->registry('current_category')
                            ) {
                        $groupCollection->addCategoryFilter(
                            $this->_coreRegistry
                                ->registry('current_category')->getId()
                        );
                    } elseif ($this->_page->getId()) {
                        $groupCollection->addPageFilter($this->_page->getId());
                    }
                }

                if ($groupPosition) {
                    $groupCollection->addGroupPositionFilter($groupPosition);
                }
            }

            $groupCollection->addGroupSortFilter();

            return $groupCollection;
        }

    }

    /**
     * Get slide collection.
     *
     * @param int $groupId Group ID.
     *
     * @return slide collection
     */
    public function getSlides($groupId)
    {
        $slideCollection = $this->_slideCollectionFactory->create()
            ->addGroupFilter($groupId)
            ->addEnabledFilter('1')
            ->addSlideDateFilter()
            ->addSlideSortFilter('ASC');

        return $slideCollection;
    }

    /**
     * Filter the content.
     *
     * @param string $content Content.
     *
     * @return string
     */
    public function filterContent($content)
    {
        return $this->_filterProvider->getBlockFilter()->filter($content);
    }

}
