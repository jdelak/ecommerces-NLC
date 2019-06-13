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

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Cms\Model\Page;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;

/**
 * Flexslider head block
 */
class Head extends \Magento\Framework\View\Element\Template
{
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
     * @var inheret
     */
    protected $_storeManager;

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
     * @param Registry          $coreRegistry           CoreRegistry.
     * @param Page              $page                   Page.
     * @param CollectionFactory $groupCollectionFactory GroupCollectionFactory.
     * @param array             $data                   Data.
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Page $page,
        CollectionFactory $groupCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $coreRegistry;
        $this->_page = $page;
        $this->_storeManager = $context->getStoreManager();
        $this->_groupCollectionFactory = $groupCollectionFactory;
    }

    /**
     * Get collection.
     *
     * @return collection
     */
    public function _getCollection()
    {
        $identifier = $this->getIdentifier();
        $groupPosition = $this->getPosition();
        $isGlobal = $this->getGlobal();
        $scope = $this->getScope();

        $groupCollection = $this->_groupCollectionFactory->create()
            ->addEnabledFilter()
            ->addGroupDateFilter();

        if (!$this->_storeManager->isSingleStoreMode()) {
            $storeId = $this->_storeManager->getStore()->getId();
            $groupCollection->addStoreFilter($storeId);
        }

        if ($identifier) {
            $groupCollection->addIdentifierFilter($identifier);
        } else {
            if ($this->_coreRegistry->registry('current_product')) {
                $groupCollection->addProductFilter(
                    $this->_coreRegistry->registry('current_product')->getId()
                );
            } elseif ($this->_coreRegistry->registry('current_category')) {
                $groupCollection->addCategoryFilter(
                    $this->_coreRegistry->registry('current_category')->getId()
                );
            } elseif ($this->_page->getId()) {
                $groupCollection->addPageFilter($this->_page->getId());
            }
        }

        return $groupCollection;

    }

}
