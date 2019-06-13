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

namespace SolideWebservices\Flexslider\Api\Data;

/**
 * Flexslider Slide interface.
 *
 * @api
 */
interface SlideInterface
{
    /**#@+
     * Constants for keys of data[]. Identical to name of getter in snake case
     */
    const SLIDE_ID = 'slide_id';
    const TITLE = 'title';
    const IDENTIFIER = 'identifier';
    const IS_ACTIVE = 'is_active';
    const SLIDE_SORT_ORDER = 'slide_sort_order';
    const SLIDE_LOGGEDIN = 'slide_loggedin';
    const SLIDE_STARTDATE = 'slide_startdate';
    const SLIDE_ENDDATE = 'slide_enddate';
    const SLIDE_TYPE = 'slide_type';
    const HOSTED_IMAGE = 'hosted_image';
    const HOSTED_IMAGE_URL = 'hosted_image_url';
    const HOSTED_IMAGE_THUMBURL = 'hosted_image_thumburl';
    const IMAGE = 'image';
    const ALT_TEXT = 'alt_text';
    const URL = 'url';
    const URL_TARGET = 'url_target';
    const VIDEO_ID = 'video_id';
    const VIDEO_AUTOPLAY = 'video_autoplay';
    const CAPTION_HTML = 'caption_html';
    const CAPTION_POSITION = 'caption_position';
    const CAPTION_ANIMATION = 'caption_animation';
    /**#@-*/

    /**
     * Get Slide ID.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get title.
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get identifier.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Is active.
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Get slideSortOrder.
     *
     * @return int|null
     */
    public function getSlideSortOrder();

    /**
     * Get getSlideLoggedin.
     *
     * @return int
     */
    public function getSlideLoggedin();

    /**
     * Get getSlideStartdate.
     *
     * @return datetime
     */
    public function getSlideStartdate();

    /**
     * Get getSlideEnddate.
     *
     * @return datetime
     */
    public function getSlideEnddate();

    /**
     * Get getSlideType.
     *
     * @return string
     */
    public function getSlideType();

    /**
     * Get getHostedImage.
     *
     * @return bool
     */
    public function getHostedImage();

    /**
     * Get getHostedImageUrl.
     *
     * @return string
     */
    public function getHostedImageUrl();

    /**
     * Get getHostedImageThumburl.
     *
     * @return string
     */
    public function getHostedImageThumburl();

    /**
     * Get getImage.
     *
     * @return string
     */
    public function getImage();

    /**
     * Get getAltText.
     *
     * @return string
     */
    public function getAltText();

    /**
     * Get getUrl.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Get getUrlTarget.
     *
     * @return string
     */
    public function getUrlTarget();

    /**
     * Get getVideoId.
     *
     * @return string
     */
    public function getVideoId();

    /**
     * Get getVideoAutoplay.
     *
     * @return bool
     */
    public function getVideoAutoplay();

    /**
     * Get getCaptionHtml.
     *
     * @return string
     */
    public function getCaptionHtml();

    /**
     * Get getCaptionPosition.
     *
     * @return string
     */
    public function getCaptionPosition();

    /**
     * Get getCaptionAnimation.
     *
     * @return bool
     */
    public function getCaptionAnimation();

    /**
     * Set ID.
     *
     * @param int $slideId The slide id.
     *
     * @return SlideInterface
     */
    public function setId($slideId);

    /**
     * Set title.
     *
     * @param string $title The slide title.
     *
     * @return SlideInterface
     */
    public function setTitle($title);

    /**
     * Set indentifier.
     *
     * @param string $identifier The slide identifier.
     *
     * @return SlideInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set is active.
     *
     * @param bool $isActive Boolean for slide enabled or disabled.
     *
     * @return SlideInterface
     */
    public function setIsActive($isActive);

    /**
     * Set slide sort order.
     *
     * @param int $slideSortOrder Slide sort order.
     *
     * @return SlideInterface
     */
    public function setSlideSortOrder($slideSortOrder);

    /**
     * Set slide loggedin.
     *
     * @param bool $slideLoggedin Boolean to only show for logged in customers.
     *
     * @return SlideInterface
     */
    public function setSlideLoggedin($slideLoggedin);

    /**
     * Set slide startdate.
     *
     * @param datetime $slideStartdate Show slide only from this datetime.
     *
     * @return SlideInterface
     */
    public function setSlideStartdate($slideStartdate);

    /**
     * Set slide enddate.
     *
     * @param datetime $slideEnddate Show slide only before this datetime.
     *
     * @return SlideInterface
     */
    public function setSlideEnddate($slideEnddate);

    /**
     * Set slide type.
     *
     * @param string $slideType Slide type.
     *
     * @return SlideInterface
     */
    public function setSlideType($slideType);

    /**
     * Set hosted image.
     *
     * @param string $hostedImage Determines if a image is hosted elsewhere.
     *
     * @return SlideInterface
     */
    public function setHostedImage($hostedImage);

    /**
     * Set hosted image url.
     *
     * @param string $hostedImageUrl The url of a hosted image.
     *
     * @return SlideInterface
     */
    public function setHostedImageUrl($hostedImageUrl);

    /**
     * Set hosted image thumburl.
     *
     * @param string $hostedImageThumburl The thumbnail url of a hosted image.
     *
     * @return SlideInterface
     */
    public function setHostedImageThumburl($hostedImageThumburl);

    /**
     * Set image.
     *
     * @param string $image The path of an uploaded image.
     *
     * @return SlideInterface
     */
    public function setImage($image);

    /**
     * Set alt text.
     *
     * @param string $altText Alternative text to be used for images.
     *
     * @return SlideInterface
     */
    public function setAltText($altText);

    /**
     * Set url.
     *
     * @param string $url The onclick url of an image.
     *
     * @return SlideInterface
     */
    public function setUrl($url);

    /**
     * Set url target.
     *
     * @param string $urlTarget The target for the onclick url of an image.
     *
     * @return SlideInterface
     */
    public function setUrlTarget($urlTarget);

    /**
     * Set video id.
     *
     * @param string $videoId The id of a youtube or vimeo video.
     *
     * @return SlideInterface
     */
    public function setVideoId($videoId);

    /**
     * Set video autoplay.
     *
     * @param bool $videoAutoplay Boolean that determines if a video autostarts.
     *
     * @return SlideInterface
     */
    public function setVideoAutoplay($videoAutoplay);

    /**
     * Set caption html.
     *
     * @param string $captionHtml Caption text in full html.
     *
     * @return SlideInterface
     */
    public function setCaptionHtml($captionHtml);

    /**
     * Set caption position.
     *
     * @param string $captionPosition Determines place where caption is shown.
     *
     * @return SlideInterface
     */
    public function setCaptionPosition($captionPosition);

    /**
     * Set caption animation.
     *
     * @param bool $captionAnimation Determines if caption has an animation.
     *
     * @return SlideInterface
     */
    public function setCaptionAnimation($captionAnimation);

}
