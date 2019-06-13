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

namespace SolideWebservices\Flexslider\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Directory\Helper\Data as DataHelper;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Backend\Model\UrlInterface;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory as GroupCollection;
use SolideWebservices\Flexslider\Model\ResourceModel\Slide\CollectionFactory as SlideCollection;
use Magento\Store\Model\StoreManagerInterface;


/**
 * Flexslider Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Variable.
     *
     * @var UrlInterface
     */
    protected $_backendUrl;

    /**
     * Variable.
     *
     * @var DataHelper
     */
    protected $_directoryData;

    /**
     * Variable.
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Variable.
     *
     * @varCategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Variable.
     *
     * @var GroupCollection
     */
    protected $_groupCollectionFactory;

    /**
     * Variable.
     *
     * @var SlideCollection
     */
    protected $_slideCollectionFactory;

    /**
     * Variable.
     *
     * @var inherit
     */
    protected $_scopeConfig;

    /**
     * Construct.
     *
     * @param Context               $context                Context.
     * @param DataHelper            $directoryData          DirectoryData.
     * @param CategoryFactory       $categoryFactory        CategoryFactory.
     * @param UrlInterface          $backendUrl             BackendUrl.
     * @param GroupCollection       $groupCollectionFactory GroupCollection.
     * @param SlideCollection       $slideCollectionFactory SlideCollection.
     * @param StoreManagerInterface $storeManager           StoreManager.
     */
    public function __construct(
        Context $context,
        DataHelper $directoryData,
        CategoryFactory $categoryFactory,
        UrlInterface $backendUrl,
        GroupCollection $groupCollectionFactory,
        SlideCollection $slideCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_directoryData = $directoryData;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_backendUrl = $backendUrl;
        $this->_storeManager = $storeManager;
        $this->_categoryFactory = $categoryFactory;
        $this->_groupCollectionFactory = $groupCollectionFactory;
        $this->_slideCollectionFactory = $slideCollectionFactory;
    }

    /**
     * Check if extension is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getConfig('flexslider/general/enabled');
    }

    /**
     * Retrieve media URL.
     *
     * @param string $path   Path.
     * @param bool   $secure Secure.
     *
     * @return string
     */
    public function getBaseUrlMedia($path = '', $secure = false)
    {
        return $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA,
            $secure
        ) . $path;
    }

    /**
     * Get store config.
     *
     * @param string $path  Path.
     * @param int    $store Store.
     *
     * @return string
     */
    public function getConfig($path, $store = null)
    {
        return $this->_scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get base url in http or https.
     *
     * @return string
     */
    public function base_url()
    {
        return sprintf(
            "%s://%s",
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http',
            $_SERVER['HTTP_HOST']
        );
    }

    /**
     * Get enabled positions.
     *
     * @param string $positions Positions.
     *
     * @return string || bool
     */
    public function getEnabledPositions($positions)
    {
        switch($positions) {
            case 'selected':
                return $this->getConfig('flexslider/advanced_settings/enable_selected_positions');
                break;
            case 'global':
                return $this->getConfig('flexslider/advanced_settings/enable_global_positions');
                break;
            case 'customer':
                return $this->getConfig('flexslider/advanced_settings/enable_customer_positions');
                break;
            case 'checkout':
                return $this->getConfig('flexslider/advanced_settings/enable_checkout_positions');
                break;
            default:
                return true;
        }
    }

    /**
     * See if there is a slider group with overlay navigation.
     *
     * @return bool
     */
    public function overlayEnabled()
    {
        $groupCollection = $this->_groupCollectionFactory->create()
            ->addFieldToFilter('group_type', ['eq' => 'overlay'])
            ->addFieldToFilter('is_active', ['eq' => 1]);


        if ($groupCollection->getSize() > 0) {
            return true;
        }
    }

    /**
     * See if there is are video slides.
     *
     * @return bool
     */
    public function videosEnabled()
    {
        $slideCollection = $this->_slideCollectionFactory->create()
            ->addFieldToFilter('is_enabled', ['eq' => 1])
            ->addFieldToFilter('slidetype', ['neq' => 'image']);

        if ($slideCollection->getSize() > 0) {
            return true;
        }
    }

}
