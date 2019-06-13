<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.0
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Api\Data;

/**
 * Flexslider Group interface.
 *
 * @api
 */
interface GroupInterface
{
    /**#@+
     * Constants for keys of data[]. Identical to name the getter in snake case
     */
    const GROUP_ID = 'group_id';
    const TITLE = 'title';
    const IDENTIFIER = 'identifier';
    const IS_ACTIVE = 'is_active';
    const GROUP_SORT_ORDER = 'group_sort_order';
    const GROUP_RANDOM_SLIDES = 'group_random_slides';
    const GROUP_LOGGEDIN = 'group_loggedin';
    const GROUP_STARTDATE = 'group_startdate';
    const GROUP_ENDDATE = 'group_enddate';
    const GROUP_POSITION = 'group_position';
    const GROUP_TYPE = 'group_type';
    const OVERLAY_POSITION = 'overlay_position';
    const OVERLAY_TEXTCOLOR = 'overlay_textcolor';
    const OVERLAY_BGCOLOR = 'overlay_bgcolor';
    const OVERLAY_HOVERCOLOR = 'overlay_hovercolor';
    const THEME = 'theme';
    const CUSTOM_THEME = 'custom_theme';
    const WIDTH = 'width';
    const THUMBNAIL_SIZE = 'thumbnail_size';
    const NAV_SHOW = 'nav_show';
    const NAV_STYLE = 'nav_style';
    const NAV_POSITION = 'nav_position';
    const NAV_COLOR = 'nav_color';
    const PAGINATION_SHOW = 'pagination_show';
    const PAGINATION_STYLE = 'pagination_style';
    const PAGINATION_POSITION = 'pagination_position';
    const PAGINATION_COLOR = 'pagination_color';
    const PAGINATION_HOVER_COLOR = 'pagination_hover_color';
    const PAGINATION_ACTIVE_COLOR = 'pagination_active_color';
    const LOADER_SHOW = 'loader_show';
    const LOADER_POSITION = 'loader_position';
    const LOADER_COLOR = 'loader_color';
    const LOADER_BGCOLOR = 'loader_bgcolor';
    const CAPTION_TEXTCOLOR = 'caption_textcolor';
    const CAPTION_BGCOLOR = 'caption_bgcolor';
    const GROUP_ANIMATION = 'group_animation';
    const GROUP_ANIMATION_DIRECTION = 'group_animation_direction';
    const GROUP_ANIMATION_DURATION = 'group_animation_duration';
    const GROUP_ANIMATION_EASING = 'group_animation_easing';
    const GROUP_AUTOSLIDE = 'group_autoslide';
    const GROUP_AUTOLOOP = 'group_autoloop';
    const GROUP_PAUSEONACTION = 'group_pauseonaction';
    const GROUP_PAUSEONHOVER = 'group_pauseonhover';
    const GROUP_SLIDE_DURATION = 'group_slide_duration';
    const CAROUSEL_MINITEMS = 'carousel_minitems';
    const CAROUSEL_MAXITEMS = 'carousel_maxitems';
    const CAROUSEL_MOVE = 'carousel_move';
    const GROUP_ANIMATION_REVERSE = 'group_animation_reverse';
    /**#@-*/

    /**
     * Get ID.
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
     * Get groupSortOrder.
     *
     * @return int|null
     */
    public function getGroupSortOrder();

    /**
     * Get getGroupRandomSlides.
     *
     * @return int
     */
    public function getGroupRandomSlides();

    /**
     * Get getGroupLoggedin.
     *
     * @return int
     */
    public function getGroupLoggedin();

    /**
     * Get getGroupStartdate.
     *
     * @return datetime
     */
    public function getGroupStartdate();

    /**
     * Get getGroupEnddate.
     *
     * @return datetime
     */
    public function getGroupEnddate();

    /**
     * Get getGroupPosition.
     *
     * @return int
     */
    public function getGroupPosition();

    /**
     * Get getGroupType.
     *
     * @return string
     */
    public function getGroupType();

    /**
     * Get getOverlayPosition.
     *
     * @return string
     */
    public function getOverlayPosition();

    /**
     * Get getOverlayTextcolor.
     *
     * @return string
     */
    public function getOverlayTextcolor();

    /**
     * Get getOverlayBgcolor.
     *
     * @return string
     */
    public function getOverlayBgcolor();

    /**
     * Get getOverlayHovercolor.
     *
     * @return string
     */
    public function getOverlayHovercolor();

    /**
     * Get getTheme.
     *
     * @return string
     */
    public function getTheme();

    /**
     * Get getCustomTheme.
     *
     * @return string|null
     */
    public function getCustomTheme();

    /**
     * Get getWidth.
     *
     * @return int
     */
    public function getWidth();

    /**
     * Get getThumbnailSize.
     *
     * @return int
     */
    public function getThumbnailSize();

    /**
     * Get getNavShow.
     *
     * @return bool
     */
    public function getNavShow();

    /**
     * Get getNavStyle.
     *
     * @return string
     */
    public function getNavStyle();

    /**
     * Get getNavPosition.
     *
     * @return string
     */
    public function getNavPosition();

    /**
     * Get getNavColor.
     *
     * @return string
     */
    public function getNavColor();

    /**
     * Get getPaginationShow.
     *
     * @return bool
     */
    public function getPaginationShow();

    /**
     * Get getPaginationStyle.
     *
     * @return string
     */
    public function getPaginationStyle();

    /**
     * Get getPaginationPosition.
     *
     * @return string
     */
    public function getPaginationPosition();

    /**
     * Get getPaginationColor.
     *
     * @return string
     */
    public function getPaginationColor();

    /**
     * Get getPaginationHoverColor.
     *
     * @return string
     */
    public function getPaginationHoverColor();

    /**
     * Get getPaginationActiveColor.
     *
     * @return string
     */
    public function getPaginationActiveColor();

    /**
     * Get getLoaderShow.
     *
     * @return bool
     */
    public function getLoaderShow();

    /**
     * Get getLoaderPosition.
     *
     * @return string
     */
    public function getLoaderPosition();

    /**
     * Get getLoaderColor.
     *
     * @return string
     */
    public function getLoaderColor();

    /**
     * Get getLoaderBgcolor.
     *
     * @return string
     */
    public function getLoaderBgcolor();

    /**
     * Get getCaptionTextcolor.
     *
     * @return string
     */
    public function getCaptionTextcolor();

    /**
     * Get getCaptionTextcolor.
     *
     * @return string
     */
    public function getCaptionBgcolor();

    /**
     * Get getGroupAnimation.
     *
     * @return string
     */
    public function getGroupAnimation();

    /**
     * Get getGroupAnimationDirection.
     *
     * @return string
     */
    public function getGroupAnimationDirection();

    /**
     * Get getGroupAnimationDuration.
     *
     * @return int
     */
    public function getGroupAnimationDuration();

    /**
     * Get getGroupAnimationEasing.
     *
     * @return string
     */
    public function getGroupAnimationEasing();

    /**
     * Get getGroupAutoslide.
     *
     * @return bool
     */
    public function getGroupAutoslide();

    /**
     * Get getGroupAutoloop.
     *
     * @return bool
     */
    public function getGroupAutoloop();

    /**
     * Get getGroupPauseonaction.
     *
     * @return bool
     */
    public function getGroupPauseonaction();

    /**
     * Get getGroupPauseonhover.
     *
     * @return bool
     */
    public function getGroupPauseonhover();

    /**
     * Get getGroupSlideDuration.
     *
     * @return int
     */
    public function getGroupSlideDuration();

    /**
     * Get getCarouselMinitems.
     *
     * @return int
     */
    public function getCarouselMinitems();

    /**
     * Get getCarouselMaxitems.
     *
     * @return int
     */
    public function getCarouselMaxitems();

    /**
     * Get getCarouselMove.
     *
     * @return int
     */
    public function getCarouselMove();

    /**
     * Get getGroupAnimationReverse.
     *
     * @return bool
     */
    public function getGroupAnimationReverse();

    /**
     * Set ID.
     *
     * @param int $id The group id.
     *
     * @return GroupInterface
     */
    public function setId($id);

    /**
     * Set title.
     *
     * @param string $title The group title.
     *
     * @return GroupInterface
     */
    public function setTitle($title);

    /**
     * Set indentifier.
     *
     * @param string $identifier The group identifier.
     *
     * @return GroupInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set is active.
     *
     * @param bool $isActive Boolean for group enabled or disabled.
     *
     * @return GroupInterface
     */
    public function setIsActive($isActive);

    /**
     * Set group sort order.
     *
     * @param int $groupSortOrder Group sort order.
     *
     * @return GroupInterface
     */
    public function setGroupSortOrder($groupSortOrder);

    /**
     * Set group random slides.
     *
     * @param bool $groupRandomSlides Boolean for setting group random slides.
     *
     * @return GroupInterface
     */
    public function setGroupRandomSlides($groupRandomSlides);

    /**
     * Set group loggedin.
     *
     * @param bool $groupLoggedin Boolean to only show for logged in customers.
     *
     * @return GroupInterface
     */
    public function setGroupLoggedin($groupLoggedin);

    /**
     * Set group startdate.
     *
     * @param datetime $groupStartdate Show group only from this datetime.
     *
     * @return GroupInterface
     */
    public function setGroupStartdate($groupStartdate);

    /**
     * Set group enddate.
     *
     * @param datetime $groupEnddate Show group only before this datetime.
     *
     * @return GroupInterface
     */
    public function setGroupEnddate($groupEnddate);

    /**
     * Set group position.
     *
     * @param int $groupPosition Group position on the page.
     *
     * @return GroupInterface
     */
    public function setGroupPosition($groupPosition);

    /**
     * Set group type.
     *
     * @param string $groupType Group type.
     *
     * @return GroupInterface
     */
    public function setGroupType($groupType);

    /**
     * Set overlay position.
     *
     * @param string $overlayPosition Overlay position.
     *
     * @return GroupInterface
     */
    public function setOverlayPosition($overlayPosition);

    /**
     * Set overlay textcolor.
     *
     * @param string $overlayTextcolor Overlay textcolor.
     *
     * @return GroupInterface
     */
    public function setOverlayTextcolor($overlayTextcolor);

    /**
     * Set overlay bgcolor.
     *
     * @param string $overlayBgcolor Overlay background color.
     *
     * @return GroupInterface
     */
    public function setOverlayBgcolor($overlayBgcolor);

    /**
     * Set overlay hovercolor.
     *
     * @param string $overlayHovercolor Overlay hover color.
     *
     * @return GroupInterface
     */
    public function setOverlayHovercolor($overlayHovercolor);

    /**
     * Set theme.
     *
     * @param string $theme Group theme style.
     *
     * @return GroupInterface
     */
    public function setTheme($theme);

    /**
     * Set custom theme.
     *
     * @param string $customTheme Custom theme through css.
     *
     * @return GroupInterface
     */
    public function setCustomTheme($customTheme);

    /**
     * Set width.
     *
     * @param int $width Maximum width of slider.
     *
     * @return GroupInterface
     */
    public function setWidth($width);

    /**
     * Set thumbnail size.
     *
     * @param int $thumbnailSize Thumbnail size.
     *
     * @return GroupInterface
     */
    public function setThumbnailSize($thumbnailSize);

    /**
     * Set nav show.
     *
     * @param bool $navShow Boolean for showing the navigation.
     *
     * @return GroupInterface
     */
    public function setNavShow($navShow);

    /**
     * Set nav style.
     *
     * @param string $navStyle Navigation style.
     *
     * @return GroupInterface
     */
    public function setNavStyle($navStyle);

    /**
     * Set navPosition.
     *
     * @param string $navPosition Navigation position.
     *
     * @return GroupInterface
     */
    public function setNavPosition($navPosition);

    /**
     * Set nav color.
     *
     * @param string $navColor Navigation color.
     *
     * @return GroupInterface
     */
    public function setNavColor($navColor);

    /**
     * Set pagination show.
     *
     * @param bool $paginationShow Boolean for showing the pagination.
     *
     * @return GroupInterface
     */
    public function setPaginationShow($paginationShow);

    /**
     * Set pagination style.
     *
     * @param string $paginationStyle Pagination style.
     *
     * @return GroupInterface
     */
    public function setPaginationStyle($paginationStyle);

    /**
     * Set pagination position.
     *
     * @param string $paginationPosition Pagination position.
     *
     * @return GroupInterface
     */
    public function setPaginationPosition($paginationPosition);

    /**
     * Set pagination color.
     *
     * @param string $paginationColor Pagination color.
     *
     * @return GroupInterface
     */
    public function setPaginationColor($paginationColor);

    /**
     * Set pagination hover color.
     *
     * @param string $paginationHoverColor Pagination hover color.
     *
     * @return GroupInterface
     */
    public function setPaginationHoverColor($paginationHoverColor);

    /**
     * Set pagination active color.
     *
     * @param string $paginationActiveColor Pagination active color.
     *
     * @return GroupInterface
     */
    public function setPaginationActiveColor($paginationActiveColor);

    /**
     * Set loader show.
     *
     * @param bool $loaderShow Boolean for showing the loader.
     *
     * @return GroupInterface
     */
    public function setLoaderShow($loaderShow);

    /**
     * Set loader position.
     *
     * @param string $loaderPosition Loader position.
     *
     * @return GroupInterface
     */
    public function setLoaderPosition($loaderPosition);

    /**
     * Set loader color.
     *
     * @param string $loaderColor Loader color.
     *
     * @return GroupInterface
     */
    public function setLoaderColor($loaderColor);

    /**
     * Set loader bgcolor.
     *
     * @param string $loaderBgcolor Loader background color.
     *
     * @return GroupInterface
     */
    public function setLoaderBgcolor($loaderBgcolor);

    /**
     * Set caption textcolor.
     *
     * @param string $captionTextcolor Caption text color.
     *
     * @return GroupInterface
     */
    public function setCaptionTextcolor($captionTextcolor);

    /**
     * Set caption bgcolor.
     *
     * @param string $captionBgcolor Caption background color.
     *
     * @return GroupInterface
     */
    public function setCaptionBgcolor($captionBgcolor);

    /**
     * Set group animation.
     *
     * @param string $groupAnimation Group animation type.
     *
     * @return GroupInterface
     */
    public function setGroupAnimation($groupAnimation);

    /**
     * Set group animation direction.
     *
     * @param string $groupAnimationDirection Group animation direction.
     *
     * @return GroupInterface
     */
    public function setGroupAnimationDirection($groupAnimationDirection);

    /**
     * Set group animation duration.
     *
     * @param int $groupAnimationDuration Duration of group animation.
     *
     * @return GroupInterface
     */
    public function setGroupAnimationDuration($groupAnimationDuration);

    /**
     * Set group animation easing.
     *
     * @param string $groupAnimationEasing Easing effect for group animation.
     *
     * @return GroupInterface
     */
    public function setGroupAnimationEasing($groupAnimationEasing);

    /**
     * Set group autoslide.
     *
     * @param bool $groupAutoslide Boolean for starting autoslide.
     *
     * @return GroupInterface
     */
    public function setGroupAutoslide($groupAutoslide);

    /**
     * Set group autoloop.
     *
     * @param bool $groupAutoloop Boolean for auto looping group.
     *
     * @return GroupInterface
     */
    public function setGroupAutoloop($groupAutoloop);

    /**
     * Set group pauseonaction.
     *
     * @param bool $groupPauseonaction Boolean for pause on navigation.
     *
     * @return GroupInterface
     */
    public function setGroupPauseonaction($groupPauseonaction);

    /**
     * Set group pauseonhover.
     *
     * @param bool $groupPauseonhover Boolean for pause on hover.
     *
     * @return GroupInterface
     */
    public function setGroupPauseonhover($groupPauseonhover);

    /**
     * Set group slideduration.
     *
     * @param int $groupSlideDuration Duration of the slide transition.
     *
     * @return GroupInterface
     */
    public function setGroupSlideDuration($groupSlideDuration);

    /**
     * Set carousel minitems.
     *
     * @param int $carouselMinitems Minimum items in a carousel.
     *
     * @return GroupInterface
     */
    public function setCarouselMinitems($carouselMinitems);

    /**
     * Set carousel maxitems.
     *
     * @param int $carouselMaxitems Maximum items in a carousel.
     *
     * @return GroupInterface
     */
    public function setCarouselMaxitems($carouselMaxitems);

    /**
     * Set carousel move.
     *
     * @param int $carouselMove Number of items to move on carousel nav.
     *
     * @return GroupInterface
     */
    public function setCarouselMove($carouselMove);

    /**
     * Set group animation reverse.
     *
     * @param bool $groupAnimationReverse Boolean for reversing the animation.
     *
     * @return GroupInterface
     */
    public function setGroupAnimationReverse($groupAnimationReverse);

}
