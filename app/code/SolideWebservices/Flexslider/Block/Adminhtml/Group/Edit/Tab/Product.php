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

namespace SolideWebservices\Flexslider\Block\Adminhtml\Group\Edit\Tab;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as DataHelper;
use Magento\Store\Model\Store;
use Magento\Catalog\Model\ProductFactory;
use Magento\Directory\Model\Currency;
use Magento\Store\Model\ScopeInterface;

/**
 * Flexslider Product form block
 */
class Product extends \Magento\Backend\Block\Widget\Grid\Extended implements
\Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * Variable.
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Variable.
     *
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $type;

    /**
     * Variable.
     *
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $status;

    /**
     * Variable.
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $visibility;

    /**
     * Variable.
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Variable.
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * Construct.
     *
     * @param CollectionFactory $productCollectionFactory ProductColFactory.
     * @param ProductType       $type                     Type.
     * @param ProductStatus     $status                   Status.
     * @param ProductVisibility $visibility               Visibility.
     * @param Registry          $coreRegistry             CoreRegistry.
     * @param ProductFactory    $productFactory           ProductFactory.
     * @param Context           $context                  Context.
     * @param DataHelper        $backendHelper            BackendHelper.
     * @param array             $data                     Data.
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        ProductType $type,
        ProductStatus $status,
        ProductVisibility $visibility,
        Registry $coreRegistry,
        ProductFactory $productFactory,
        Context $context,
        DataHelper $backendHelper,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->type = $type;
        $this->status = $status;
        $this->visibility = $visibility;
        $this->coreRegistry = $coreRegistry;
        $this->productFactory = $productFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Construct.
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('product_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getGroup()->getId()) {
            $this->setDefaultFilter(['in_products'=>1]);
        }
    }

    /**
     * Prepare collection.
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $adminStore = Store::DEFAULT_STORE_ID;
        $collection->joinAttribute(
            'product_name',
            'catalog_product/name',
            'entity_id',
            null,
            'left',
            $adminStore
        );
        $collection->joinAttribute(
            'status',
            'catalog_product/status',
            'entity_id',
            null, 'left',
            $adminStore
        );
        $collection->joinAttribute(
            'visibility',
            'catalog_product/visibility',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $this->setCollection($collection);
        parent::_prepareCollection();

        return $this;
    }

    /**
     * Prepare mass action.
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * Add columns to grid.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_products',
            [
            'type'   => 'checkbox',
            'name'   => 'in_products',
            'align'  => 'center',
            'index'  => 'entity_id',
            'values' => $this->getGroup()
                        ->lookupProductIds($this->getGroup()->getId()),
            'header_css_class' => 'col-select',
            'column_css_class' => 'col-select'
            ]
        );
        $this->addColumn(
            'entity_id',
            [
            'header' => __('ID'),
            'sortable' => true,
            'index' => 'entity_id',
            'type' => 'number',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'name',
            [
            'header' => __('Name'),
            'index' => 'product_name',
            'header_css_class' => 'col-name',
            'column_css_class' => 'col-name'
            ]
        );
        $this->addColumn(
            'type',
            [
            'header' => __('Type'),
            'index' => 'type_id',
            'type' => 'options',
            'options' => $this->type->getOptionArray(),
            'header_css_class' => 'col-type',
            'column_css_class' => 'col-type'
            ]
        );
        $this->addColumn(
            'status',
            [
            'header' => __('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => $this->status->getOptionArray(),
            'header_css_class' => 'col-status',
            'column_css_class' => 'col-status'
            ]
        );

        $this->addColumn(
            'visibility',
            [
            'header' => __('Visibility'),
            'index' => 'visibility',
            'type' => 'options',
            'options' => $this->visibility->getOptionArray(),
            'header_css_class' => 'col-visibility',
            'column_css_class' => 'col-visibility'
            ]
        );
        $this->addColumn(
            'sku',
            [
            'header' => __('SKU'),
            'index' => 'sku',
            'header_css_class' => 'col-sku',
            'column_css_class' => 'col-sku'
            ]
        );
        return $this;
    }

    /**
     * Get row url.
     *
     * @param array $item Item.
     *
     * @return '#'
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * Rerieve grid URL.
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/productsGrid',
            [
                'group_id' => $this->getGroup()->getId()
            ]
        );
    }

    /**
     * Get selected products.
     *
     * @return $selected[]
     */
    public function getSelectedProducts()
    {
        $selected = $this->getGroup()
                    ->lookupProductIds($this->getGroup()->getId());
        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
    }

    /**
     * Retrieve currently edited group model.
     *
     * @return array|null
     */
    public function getGroup()
    {
        return $this->coreRegistry->registry('flexslider_group');
    }

    /**
     * Add filter.
     *
     * @param array $column Column.
     *
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_products') {
            $productIds = $this->getGroup()
                            ->lookupProductIds($this->getGroup()->getId());
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter(
                    'entity_id',
                    ['in'=>$productIds]
                );
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter(
                        'entity_id',
                        ['nin'=>$productIds]
                    );
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare label for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Display on Products');
    }

    /**
     * Prepare title for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabUrl()
    {
        return $this->getUrl('flexslider/group/products', ['_current' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabClass()
    {
        return 'ajax only';
    }
}
