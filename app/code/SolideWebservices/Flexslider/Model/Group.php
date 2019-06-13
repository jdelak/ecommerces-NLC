<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.4
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Cms\Model\PageFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use SolideWebservices\Flexslider\Api\Data\GroupInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Flexslider Group Model
 *
 * @method \SolideWebservices\Flexslider\Model\ResourceModel\Group _getResource()
 * @method \SolideWebservices\Flexslider\Model\ResourceModel\Group getResource()
 */
class Group extends \Magento\Framework\Model\AbstractModel implements
GroupInterface, IdentityInterface
{

    /**#@+
     * Groups Statuses.
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**#@+
     * Flexslider group cache tag.
     */
    const CACHE_TAG = 'flexslider_group';
    /**#@-*/

    /**
     * Variable.
     *
     * @var string
     */
    protected $_cacheTag = 'flexslider_group';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'flexslider_group';

    /**
     * Variable.
     *
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $_pageFactory;

    /**
     * Variable.
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Variable.
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollection;

    /**
     * Construct.
     *
     * @param Context           $context                  Context.
     * @param Registry          $registry                 Registry.
     * @param PageFactory       $pageFactory              PageFactory.
     * @param CollectionFactory $productCollectionFactory ProductCollection.
     * @param AbstractResource  $resource                 Resource.
     * @param AbstractDb        $resourceCollection       ResCollection.
     * @param array             $data                     Data.
     */
    public function __construct(
        Context $context,
        Registry $registry,
        PageFactory $pageFactory,
        CollectionFactory $productCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_pageFactory = $pageFactory;
        $this->productCollectionFactory  = $productCollectionFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SolideWebservices\Flexslider\Model\ResourceModel\Group');
    }

    /**
     * Prepare groups statuses.
     *
     * @return options[]
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_ENABLED => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }

    /**
     * Receive group store ids.
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Check if group identifier exist for specific store.
     *
     * @param string $identifier Identifier.
     * @param int    $storeId    Store.
     *
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    /**
     * Get available pages.
     *
     * @return $options[]
     */
    public function getAvailablePages()
    {
        $pageCollection = $this->_pageFactory->create();
        foreach ($pageCollection->getCollection() as $page) {
            $option[] = [
                'value' => $page->getId(),
                'label' => $page->getTitle(),
            ];
        }

        return $option;
    }

    /**
     * Get product ID's.
     *
     * @param int $groupId Group ID.
     *
     * @return products[]
     */
    public function lookupProductIds($groupId)
    {
        if (!$this->getId()) {
            return [];
        }
        return $this->getResource()->lookupProductIds($groupId);
    }

    /**
     * Count slide ids to which specified item is assigned.
     *
     * @param int $groupId Group ID.
     *
     * @return select[]
     */
    public function countSlideIds($groupId)
    {
        if (!$this->getId()) {
            return [];
        }
        return $this->getResource()->countSlideIds($groupId);
    }

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::GROUP_ID);
    }

    /**
     * Get Identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * Get Title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Is Active.
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * Get group sort order.
     *
     * @return int
     */
    public function getGroupSortOrder()
    {
        return $this->getData(self::GROUP_SORT_ORDER);
    }

    /**
     * Get group random slides.
     *
     * @return bool
     */
    public function getGroupRandomSlides()
    {
        return $this->getData(self::GROUP_RANDOM_SLIDES);
    }

    /**
     * Get group logged in.
     *
     * @return bool
     */
    public function getGroupLoggedin()
    {
        return $this->getData(self::GROUP_LOGGEDIN);
    }

    /**
     * Get group start date.
     *
     * @return datetime
     */
    public function getGroupStartdate()
    {
        return $this->getData(self::GROUP_STARTDATE);
    }

    /**
     * Get group end date.
     *
     * @return datetime
     */
    public function getGroupEnddate()
    {
        return $this->getData(self::GROUP_ENDDATE);
    }

    /**
     * Get group position.
     *
     * @return int
     */
    public function getGroupPosition()
    {
        return $this->getData(self::GROUP_POSITION);
    }

    /**
     * Get group type.
     *
     * @return string
     */
    public function getGroupType()
    {
        return $this->getData(self::GROUP_TYPE);
    }

    /**
     * Get overlay position.
     *
     * @return string
     */
    public function getOverlayPosition()
    {
        return $this->getData(self::OVERLAY_POSITION);
    }

    /**
     * Get overlay text color.
     *
     * @return string
     */
    public function getOverlayTextcolor()
    {
        return $this->getData(self::OVERLAY_TEXTCOLOR);
    }

    /**
     * Get overlay background color.
     *
     * @return string
     */
    public function getOverlayBgcolor()
    {
        return $this->getData(self::OVERLAY_BGCOLOR);
    }

    /**
     * Get overlay hover color.
     *
     * @return string
     */
    public function getOverlayHovercolor()
    {
        return $this->getData(self::OVERLAY_HOVERCOLOR);
    }

    /**
     * Get group theme.
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->getData(self::THEME);
    }

    /**
     * Get group custom theme.
     *
     * @return string
     */
    public function getCustomTheme()
    {
        return $this->getData(self::CUSTOM_THEME);
    }

    /**
     * Get group width.
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->getData(self::WIDTH);
    }

    /**
     * Get thumbnail size.
     *
     * @return int
     */
    public function getThumbnailSize()
    {
        return $this->getData(self::THUMBNAIL_SIZE);
    }

    /**
     * Get nav show.
     *
     * @return bool
     */
    public function getNavShow()
    {
        return $this->getData(self::NAV_SHOW);
    }

    /**
     * Get nav style.
     *
     * @return string
     */
    public function getNavStyle()
    {
        return $this->getData(self::NAV_STYLE);
    }

    /**
     * Get nav position.
     *
     * @return string
     */
    public function getNavPosition()
    {
        return $this->getData(self::NAV_POSITION);
    }

    /**
     * Get nav color.
     *
     * @return string
     */
    public function getNavColor()
    {
        return $this->getData(self::NAV_COLOR);
    }

    /**
     * Get pagination show.
     *
     * @return bool
     */
    public function getPaginationShow()
    {
        return $this->getData(self::PAGINATION_SHOW);
    }

    /**
     * Get pagination style.
     *
     * @return string
     */
    public function getPaginationStyle()
    {
        return $this->getData(self::PAGINATION_STYLE);
    }

    /**
     * Get pagination position.
     *
     * @return string
     */
    public function getPaginationPosition()
    {
        return $this->getData(self::PAGINATION_POSITION);
    }

    /**
     * Get pagination color.
     *
     * @return string
     */
    public function getPaginationColor()
    {
        return $this->getData(self::PAGINATION_COLOR);
    }

    /**
     * Get pagination hover color.
     *
     * @return string
     */
    public function getPaginationHoverColor()
    {
        return $this->getData(self::PAGINATION_HOVER_COLOR);
    }

    /**
     * Get pagination active color.
     *
     * @return string
     */
    public function getPaginationActiveColor()
    {
        return $this->getData(self::PAGINATION_ACTIVE_COLOR);
    }

    /**
     * Get loader show.
     *
     * @return bool
     */
    public function getLoaderShow()
    {
        return $this->getData(self::LOADER_SHOW);
    }

    /**
     * Get loader position.
     *
     * @return string
     */
    public function getLoaderPosition()
    {
        return $this->getData(self::LOADER_POSITION);
    }

    /**
     * Get loader color.
     *
     * @return string
     */
    public function getLoaderColor()
    {
        return $this->getData(self::LOADER_COLOR);
    }

    /**
     * Get loader background color.
     *
     * @return string
     */
    public function getLoaderBgcolor()
    {
        return $this->getData(self::LOADER_BGCOLOR);
    }

    /**
     * Get caption text color.
     *
     * @return string
     */
    public function getCaptionTextcolor()
    {
        return $this->getData(self::CAPTION_TEXTCOLOR);
    }

    /**
     * Get caption background color.
     *
     * @return string
     */
    public function getCaptionBgcolor()
    {
        return $this->getData(self::CAPTION_BGCOLOR);
    }

    /**
     * Get group animation.
     *
     * @return string
     */
    public function getGroupAnimation()
    {
        return $this->getData(self::GROUP_ANIMATION);
    }

    /**
     * Get group animation direction.
     *
     * @return string
     */
    public function getGroupAnimationDirection()
    {
        return $this->getData(self::GROUP_ANIMATION_DIRECTION);
    }

    /**
     * Get group animation duration.
     *
     * @return int
     */
    public function getGroupAnimationDuration()
    {
        return $this->getData(self::GROUP_ANIMATION_DURATION);
    }

    /**
     * Get group animation easing.
     *
     * @return string
     */
    public function getGroupAnimationEasing()
    {
        return $this->getData(self::GROUP_ANIMATION_EASING);
    }

    /**
     * Get group autoslide.
     *
     * @return bool
     */
    public function getGroupAutoslide()
    {
        return $this->getData(self::GROUP_AUTOSLIDE);
    }

    /**
     * Get group auto loop.
     *
     * @return bool
     */
    public function getGroupAutoloop()
    {
        return $this->getData(self::GROUP_AUTOLOOP);
    }

    /**
     * Get group pause on action.
     *
     * @return bool
     */
    public function getGroupPauseonaction()
    {
        return $this->getData(self::GROUP_PAUSEONACTION);
    }

    /**
     * Get group pause on hover.
     *
     * @return string
     */
    public function getGroupPauseonhover()
    {
        return $this->getData(self::GROUP_PAUSEONHOVER);
    }

    /**
     * Get group slide duration.
     *
     * @return int
     */
    public function getGroupSlideDuration()
    {
        return $this->getData(self::GROUP_SLIDE_DURATION);
    }

    /**
     * Get carousel minimum items.
     *
     * @return int
     */
    public function getCarouselMinitems()
    {
        return $this->getData(self::CAROUSEL_MINITEMS);
    }

    /**
     * Get carousel maximum items.
     *
     * @return int
     */
    public function getCarouselMaxitems()
    {
        return $this->getData(self::CAROUSEL_MAXITEMS);
    }

    /**
     * Get carousel move items.
     *
     * @return int
     */
    public function getCarouselMove()
    {
        return $this->getData(self::CAROUSEL_MOVE);
    }

    /**
     * Get group animation reverse.
     *
     * @return bool
     */
    public function getGroupAnimationReverse()
    {
        return $this->getData(self::GROUP_ANIMATION_REVERSE);
    }

    /**
     * Set ID.
     *
     * @param int $id Group ID.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setId($id)
    {
        return $this->setData(self::GROUP_ID, $id);
    }

    /**
     * Set Identifier.
     *
     * @param string $identifier Identifier.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set title.
     *
     * @param string $title Title.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set Is Active.
     *
     * @param bool $isActive Is Active.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Set group sort order.
     *
     * @param int $groupSortOrder Group Sort Order.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupSortOrder($groupSortOrder)
    {
        return $this->setData(self::GROUP_SORT_ORDER, $groupSortOrder);
    }

    /**
     * Set group random sort order.
     *
     * @param bool $groupRandomSlides Group Random Slides.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupRandomSlides($groupRandomSlides)
    {
        return $this->setData(self::GROUP_RANDOM_SLIDES, $groupRandomSlides);
    }

    /**
     * Set group logged in.
     *
     * @param bool $groupLoggedin Group Logged In.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupLoggedin($groupLoggedin)
    {
        return $this->setData(self::GROUP_LOGGEDIN, $groupLoggedin);
    }

    /**
     * Set group start date.
     *
     * @param datetime $groupStartdate Group Start Date.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupStartdate($groupStartdate)
    {
        return $this->setData(self::GROUP_STARTDATE, $groupStartdate);
    }

    /**
     * Set group end date.
     *
     * @param datetime $groupEnddate Group End Date.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupEnddate($groupEnddate)
    {
        return $this->setData(self::GROUP_ENDDATE, $groupEnddate);
    }

    /**
     * Set group position.
     *
     * @param int $groupPosition Group Position.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupPosition($groupPosition)
    {
        return $this->setData(self::GROUP_POSITION, $groupPosition);
    }

    /**
     * Set group type.
     *
     * @param string $groupType Group Type.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupType($groupType)
    {
        return $this->setData(self::GROUP_TYPE, $groupType);
    }

    /**
     * Set overlay position.
     *
     * @param string $overlayPosition Overlay Position.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setOverlayPosition($overlayPosition)
    {
        return $this->setData(self::OVERLAY_POSITION, $overlayPosition);
    }

    /**
     * Set overlay text color.
     *
     * @param string $overlayTextcolor Overlay Text Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setOverlayTextcolor($overlayTextcolor)
    {
        return $this->setData(self::OVERLAY_TEXTCOLOR, $overlayTextcolor);
    }

    /**
     * Set overlay background color.
     *
     * @param string $overlayBgcolor Overlay Background Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setOverlayBgcolor($overlayBgcolor)
    {
        return $this->setData(self::OVERLAY_BGCOLOR, $overlayBgcolor);
    }

    /**
     * Set overlay hover color.
     *
     * @param string $overlayHovercolor Overlay Hover Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setOverlayHovercolor($overlayHovercolor)
    {
        return $this->setData(self::OVERLAY_HOVERCOLOR, $overlayHovercolor);
    }

    /**
     * Set theme.
     *
     * @param string $theme Theme.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setTheme($theme)
    {
        return $this->setData(self::THEME, $theme);
    }

    /**
     * Set custom theme.
     *
     * @param string $customTheme Custom Theme.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setCustomTheme($customTheme)
    {
        return $this->setData(self::CUSTOM_THEME, $customTheme);
    }

    /**
     * Set width.
     *
     * @param int $width Width.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setWidth($width)
    {
        return $this->setData(self::WIDTH, $width);
    }

    /**
     * Set thumbnail size.
     *
     * @param int $thumbnailSize Thumbnail Size.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setThumbnailSize($thumbnailSize)
    {
        return $this->setData(self::THUMBNAIL_SIZE, $thumbnailSize);
    }

    /**
     * Set nav show.
     *
     * @param bool $navShow Nav Show.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setNavShow($navShow)
    {
        return $this->setData(self::NAV_SHOW, $navShow);
    }

    /**
     * Set nav style.
     *
     * @param string $navStyle Nav Style.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setNavStyle($navStyle)
    {
        return $this->setData(self::NAV_STYLE, $navStyle);
    }

    /**
     * Set nav position.
     *
     * @param string $navPosition Nav Position.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setNavPosition($navPosition)
    {
        return $this->setData(self::NAV_POSITION, $navPosition);
    }

    /**
     * Set nav color.
     *
     * @param string $navColor Nav Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setNavColor($navColor)
    {
        return $this->setData(self::NAV_COLOR, $navColor);
    }

    /**
     * Set pagination show.
     *
     * @param bool $paginationShow Pagination Show.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setPaginationShow($paginationShow)
    {
        return $this->setData(self::PAGINATION_SHOW, $paginationShow);
    }

    /**
     * Set pagination style.
     *
     * @param string $paginationStyle Pagination Style.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setPaginationStyle($paginationStyle)
    {
        return $this->setData(self::PAGINATION_STYLE, $paginationStyle);
    }

    /**
     * Set pagination position.
     *
     * @param string $paginationPosition Pagination Position.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setPaginationPosition($paginationPosition)
    {
        return $this->setData(self::PAGINATION_POSITION, $paginationPosition);
    }

    /**
     * Set pagination color.
     *
     * @param string $paginationColor Pagination Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setPaginationColor($paginationColor)
    {
        return $this->setData(self::PAGINATION_COLOR, $paginationColor);
    }

    /**
     * Set pagination hover color.
     *
     * @param string $paginationHoverColor Pagination hover Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setPaginationHoverColor($paginationHoverColor)
    {
        return $this->setData(self::PAGINATION_HOVER_COLOR, $paginationHoverColor);
    }

    /**
     * Set pagination active color.
     *
     * @param string $paginationHoverColor Pagination active Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setPaginationActiveColor($paginationActiveColor)
    {
        return $this->setData(self::PAGINATION_ACTIVE_COLOR, $paginationActiveColor);
    }

    /**
     * Set loader show.
     *
     * @param bool $loaderShow Loader Show.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setLoaderShow($loaderShow)
    {
        return $this->setData(self::LOADER_SHOW, $loaderShow);
    }

    /**
     * Set loader position.
     *
     * @param string $loaderPosition Loader Position.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setLoaderPosition($loaderPosition)
    {
        return $this->setData(self::LOADER_POSITION, $loaderPosition);
    }

    /**
     * Set loader color.
     *
     * @param string $loaderColor Loader Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setLoaderColor($loaderColor)
    {
        return $this->setData(self::LOADER_COLOR, $loaderColor);
    }

    /**
     * Set loader background color.
     *
     * @param string $loaderBgcolor Loader Background Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setLoaderBgcolor($loaderBgcolor)
    {
        return $this->setData(self::LOADER_BGCOLOR, $loaderBgcolor);
    }

    /**
     * Set caption text color.
     *
     * @param string $captionTextcolor Caption Text Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setCaptionTextcolor($captionTextcolor)
    {
        return $this->setData(self::CAPTION_TEXTCOLOR, $captionTextcolor);
    }

    /**
     * Set caption background color.
     *
     * @param string $captionBgcolor Caption Background Color.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setCaptionBgcolor($captionBgcolor)
    {
        return $this->setData(self::CAPTION_BGCOLOR, $captionBgcolor);
    }

    /**
     * Set group animation.
     *
     * @param string $groupAnimation Group Animation.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupAnimation($groupAnimation)
    {
        return $this->setData(self::GROUP_ANIMATION, $groupAnimation);
    }

    /**
     * Set group animation direction.
     *
     * @param string $groupAnimationDirection Group Animation Direction.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupAnimationDirection($groupAnimationDirection)
    {
        return $this->setData(
            self::GROUP_ANIMATION_DIRECTION,
            $groupAnimationDirection
        );
    }

    /**
     * Set group animation duration.
     *
     * @param int $groupAnimationDuration Group Animation Duration.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupAnimationDuration($groupAnimationDuration)
    {
        return $this->setData(
            self::GROUP_ANIMATION_DURATION,
            $groupAnimationDuration
        );
    }

    /**
     * Set group animation easing.
     *
     * @param string $groupAnimationEasing Group Animation Easing.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupAnimationEasing($groupAnimationEasing)
    {
        return $this->setData(
            self::GROUP_ANIMATION_EASING,
            $groupAnimationEasing
        );
    }

    /**
     * Set group autoslide.
     *
     * @param bool $groupAutoslide Group Auto Slide.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupAutoslide($groupAutoslide)
    {
        return $this->setData(self::GROUP_AUTOSLIDE, $groupAutoslide);
    }

    /**
     * Set group autoloop.
     *
     * @param bool $groupAutoloop Group Auto Loop.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupAutoloop($groupAutoloop)
    {
        return $this->setData(self::GROUP_AUTOLOOP, $groupAutoloop);
    }

    /**
     * Set group pause on action.
     *
     * @param bool $groupPauseonaction Group Pause On Action.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupPauseonaction($groupPauseonaction)
    {
        return $this->setData(self::GROUP_PAUSEONACTION, $groupPauseonaction);
    }

    /**
     * Set group pause on hover.
     *
     * @param bool $groupPauseonhover Group Pause On Hover.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupPauseonhover($groupPauseonhover)
    {
        return $this->setData(self::GROUP_PAUSEONHOVER, $groupPauseonhover);
    }

    /**
     * Set group slide duration.
     *
     * @param int $groupSlideDuration Group Slide Duration.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupSlideDuration($groupSlideDuration)
    {
        return $this->setData(self::GROUP_SLIDE_DURATION, $groupSlideDuration);
    }

    /**
     * Set carousel minimum items.
     *
     * @param int $carouselMinitems Carousel Minimum Items.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setCarouselMinitems($carouselMinitems)
    {
        return $this->setData(self::CAROUSEL_MINITEMS, $carouselMinitems);
    }

    /**
     * Set carousel maximum items.
     *
     * @param int $carouselMaxitems Carousel Maximum Items.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setCarouselMaxitems($carouselMaxitems)
    {
        return $this->setData(self::CAROUSEL_MAXITEMS, $carouselMaxitems);
    }

    /**
     * Set carousel move items.
     *
     * @param int $carouselMove Carousel Move Items.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setCarouselMove($carouselMove)
    {
        return $this->setData(self::CAROUSEL_MOVE, $carouselMove);
    }

    /**
     * Set group animation reverse.
     *
     * @param bool $groupAnimationReverse Group Animation Reverse.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     */
    public function setGroupAnimationReverse($groupAnimationReverse)
    {
        return $this->setData(
            self::GROUP_ANIMATION_REVERSE,
            $groupAnimationReverse
        );
    }

}
