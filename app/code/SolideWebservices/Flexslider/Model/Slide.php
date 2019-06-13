<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.6
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use SolideWebservices\Flexslider\Api\Data\SlideInterface;

/**
 * Flexslider Slide Model
 *
 * @method \SolideWebservices\Flexslider\Model\ResourceModel\Group _getResource()
 * @method \SolideWebservices\Flexslider\Model\ResourceModel\Group getResource()
 */
class Slide extends \Magento\Framework\Model\AbstractModel implements
SlideInterface, IdentityInterface
{

    /**#@+
     * Slides Statuses.
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    const DEFAULT_MEDIA_PATH = 'flexslider/';
    const THUMB_MEDIA_PATH   = 'flexslider/thumbnails/';
    const SMALL_MEDIA_PATH   = 'flexslider/small/';
    const MEDIUM_MEDIA_PATH  = 'flexslider/medium/';
    const LARGE_MEDIA_PATH   = 'flexslider/large/';

    const CACHE_TAG = 'flexslider_slide';

    /**
     * Variable.
     *
     * @var string
     */
    protected $_cacheTag = 'flexslider_slide';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'flexslider_slide';

    /**
     * Variable.
     *
     * @var CollectionFactory
     */
    protected $_groupCollectionFactory;

    /**
     * Construct.
     *
     * @param Context           $context                Context.
     * @param Registry          $registry               Registry.
     * @param CollectionFactory $groupCollectionFactory GroupCollectionFactory.
     * @param AbstractResource  $resource               Resource.
     * @param AbstractDb        $resourceCollection     ResCollection.
     * @param array             $data                   Data.
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CollectionFactory $groupCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_groupCollectionFactory = $groupCollectionFactory;
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
        $this->_init('SolideWebservices\Flexslider\Model\ResourceModel\Slide');
    }

    /**
     * Prepare slides statuses.
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
     * Get available groups.
     *
     * @return options[]
     */
    public function getAvailableGroups()
    {
        $groupCollection = $this->_groupCollectionFactory->create();
        $option = [];
        foreach ($groupCollection as $group) {
            $option[] = [
                'value' => $group->getId(),
                'label' => $group->getTitle(),
            ];
        }

        return $option;
    }

    /**
     * Get identities.
     *
     * @return int[]
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
        return $this->getData(self::SLIDE_ID);
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
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Get Is Active.
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * Get slide sort order.
     *
     * @return int
     */
    public function getSlideSortOrder()
    {
        return $this->getData(self::SLIDE_SORT_ORDER);
    }

    /**
     * Get slide logged in.
     *
     * @return bool
     */
    public function getSlideLoggedin()
    {
        return $this->getData(self::SLIDE_LOGGEDIN);
    }

    /**
     * Get slide start date.
     *
     * @return datetime
     */
    public function getSlideStartdate()
    {
        return $this->getData(self::SLIDE_STARTDATE);
    }

    /**
     * Get slide end date.
     *
     * @return datetime
     */
    public function getSlideEnddate()
    {
        return $this->getData(self::SLIDE_ENDDATE);
    }

    /**
     * Get slide type.
     *
     * @return string
     */
    public function getSlideType()
    {
        return $this->getData(self::SLIDE_TYPE);
    }

    /**
     * Get hosted image.
     *
     * @return bool
     */
    public function getHostedImage()
    {
        return $this->getData(self::HOSTED_IMAGE);
    }

    /**
     * Get hosted image url.
     *
     * @return string
     */
    public function getHostedImageUrl()
    {
        return $this->getData(self::HOSTED_IMAGE_URL);
    }

    /**
     * Get hosted thumbail url.
     *
     * @return string
     */
    public function getHostedImageThumburl()
    {
        return $this->getData(self::HOSTED_IMAGE_THUMBURL);
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * Get alt text.
     *
     * @return string
     */
    public function getAltText()
    {
        return $this->getData(self::ALT_TEXT);
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * Get url target.
     *
     * @return string
     */
    public function getUrlTarget()
    {
        return $this->getData(self::URL_TARGET);
    }

    /**
     * Get video ID.
     *
     * @return string
     */
    public function getVideoId()
    {
        return $this->getData(self::VIDEO_ID);
    }

    /**
     * Get video autoplay.
     *
     * @return bool
     */
    public function getVideoAutoplay()
    {
        return $this->getData(self::VIDEO_AUTOPLAY);
    }

    /**
     * Get captionHTML.
     *
     * @return string
     */
    public function getCaptionHtml()
    {
        return $this->getData(self::CAPTION_HTML);
    }

    /**
     * Get caption position.
     *
     * @return string
     */
    public function getCaptionPosition()
    {
        return $this->getData(self::CAPTION_POSITION);
    }

    /**
     * Get caption animation.
     *
     * @return string
     */
    public function getCaptionAnimation()
    {
        return $this->getData(self::CAPTION_ANIMATION);
    }

    /**
     * Set ID.
     *
     * @param int $id Slide ID.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setId($id)
    {
        return $this->setData(self::SLIDE_ID, $id);
    }

    /**
     * Set identifier.
     *
     * @param string $identifier Identifier.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
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
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set Is Active.
     *
     * @param string $isActive Is Active.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Set slide sort order.
     *
     * @param int $slideSortOrder Slide Sort Order.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setSlideSortOrder($slideSortOrder)
    {
        return $this->setData(self::SLIDE_SORT_ORDER, $slideSortOrder);
    }

    /**
     * Set slide logged in.
     *
     * @param bool $slideLoggedin Slide Logged In.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setSlideLoggedin($slideLoggedin)
    {
        return $this->setData(self::SLIDE_LOGGEDIN, $slideLoggedin);
    }

    /**
     * Set slide start date.
     *
     * @param datetime $slideStartdate Slide Start Date.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setSlideStartdate($slideStartdate)
    {
        return $this->setData(self::SLIDE_STARTDATE, $slideStartdate);
    }

    /**
     * Set slide end date.
     *
     * @param datetime $slideEnddate Slide End Date.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setSlideEnddate($slideEnddate)
    {
        return $this->setData(self::SLIDE_ENDDATE, $slideEnddate);
    }

    /**
     * Set slide type.
     *
     * @param string $slideType Slide Type.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setSlideType($slideType)
    {
        return $this->setData(self::SLIDE_TYPE, $slideType);
    }

    /**
     * Set hosted image.
     *
     * @param bool $hostedImage Hosted Image.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setHostedImage($hostedImage)
    {
        return $this->setData(self::HOSTED_IMAGE, $hostedImage);
    }

    /**
     * Set hosted image url.
     *
     * @param string $hostedImageUrl Hosted Image URL.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setHostedImageUrl($hostedImageUrl)
    {
        return $this->setData(self::HOSTED_IMAGE_URL, $hostedImageUrl);
    }

    /**
     * Set hosted thumbnail url.
     *
     * @param string $hostedImageThumburl Hosted Thumbnail URL.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setHostedImageThumburl($hostedImageThumburl)
    {
        return $this->setData(
            self::HOSTED_IMAGE_THUMBURL,
            $hostedImageThumburl
        );
    }

    /**
     * Set image.
     *
     * @param string $image Image.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Set alt text.
     *
     * @param string $altText Alt Text.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setAltText($altText)
    {
        return $this->setData(self::ALT_TEXT, $altText);
    }

    /**
     * Set url.
     *
     * @param string $url URL.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * Set url target.
     *
     * @param string $urlTarget URL Target.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setUrlTarget($urlTarget)
    {
        return $this->setData(self::URL_TARGET, $urlTarget);
    }

    /**
     * Set video ID.
     *
     * @param string $videoId Video ID.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setVideoId($videoId)
    {
        return $this->setData(self::VIDEO_ID, $videoId);
    }

    /**
     * Set video autoplay.
     *
     * @param bool $videoAutoplay Video Autoplay.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setVideoAutoplay($videoAutoplay)
    {
        return $this->setData(self::VIDEO_AUTOPLAY, $videoAutoplay);
    }

    /**
     * Set caption HTML.
     *
     * @param string $captionHtml Caption HTML.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setCaptionHtml($captionHtml)
    {
        return $this->setData(self::CAPTION_HTML, $captionHtml);
    }

    /**
     * Set caption position.
     *
     * @param string $captionPosition Caption Position.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setCaptionPosition($captionPosition)
    {
        return $this->setData(self::CAPTION_POSITION, $captionPosition);
    }

    /**
     * Set caption Animation.
     *
     * @param string $captionAnimation Caption Animation.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     */
    public function setCaptionAnimation($captionAnimation)
    {
        return $this->setData(self::CAPTION_ANIMATION, $captionAnimation);
    }

}
