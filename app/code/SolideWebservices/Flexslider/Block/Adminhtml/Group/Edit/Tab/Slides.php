<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.1
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Block\Adminhtml\Group\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use SolideWebservices\Flexslider\Model\SlideFactory;
use Magento\Framework\Registry;

/**
 * Flexslider Slides form block
 */
class Slides extends \Magento\Backend\Block\Widget\Grid\Extended implements
\Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * Variable.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Variable.
     *
     * @var \SolideWebservices\Flexslider\Model\SlideFactory
     */
    protected $_slideFactory;

    /**
     * Construct.
     *
     * @param Context      $context       Context.
     * @param Data         $backendHelper BackendHelper.
     * @param SlideFactory $slideFactory  SlideFactory.
     * @param Registry     $coreRegistry  CoreRegistry.
     * @param array        $data          Data.
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        SlideFactory $slideFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_slideFactory = $slideFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('slideGrid');
        $this->setDefaultSort('slide_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getGroup()->getId()) {
            $this->setDefaultFilter(['in_slides'=>1]);
        }
    }

    /**
     * Prepare collection.
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_slideFactory->create()->getCollection()
                        ->addGroupFilter($this->getGroup()->getId());

        $this->setCollection($collection);
        return parent::_prepareCollection();
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
            'slide_id',
            [
            'header' => __('ID'),
            'type' => 'number',
            'index' => 'slide_id',
            'width' => '50px',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'slide_title',
            [
            'header' => __('Title'),
            'index' => 'title',
            'header_css_class' => 'col-name',
            'column_css_class' => 'col-name'
            ]
        );
        $this->addColumn(
            'image',
            [
            'header' => __('Image'),
            'filter' => false,
            'align' => 'center',
            'renderer' => 'SolideWebservices\Flexslider\Block\Adminhtml\Group\Helper\Renderer\Image',
            ]
        );
        $this->addColumn(
            'slide_is_active',
            [
            'header' => __('Status'),
            'width' => '90px',
            'index'    => 'is_active',
            'type' => 'options',
            'options' => [
                    1 => __('Enabled'),
                    0 => __('Disabled'),
                ]
            ]
        );
        $this->addColumn(
            'edit',
            [
            'header' => __('Edit'),
            'filter' => false,
            'sortable' => false,
            'header_css_class' => 'col-action',
            'column_css_class' => 'col-action',
            'renderer' => 'SolideWebservices\Flexslider\Block\Adminhtml\Group\Helper\Renderer\Editslide',
            ]
        );
        return parent::_prepareColumns();
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
            '*/*/slidesGrid',
            [
                'group_id' => $this->getGroup()->getId()
            ]
        );
    }

    /**
     * Retrieve currently edited group model.
     *
     * @return array|null
     */
    public function getGroup()
    {
        return $this->_coreRegistry->registry('flexslider_group');
    }

    /**
     * Prepare label for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Slides of this Group');
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
        return $this->getUrl('flexslider/group/slides', ['_current' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabClass()
    {
        return 'ajax only';
    }

}
