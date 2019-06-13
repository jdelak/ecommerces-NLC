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

namespace SolideWebservices\Flexslider\Block\Adminhtml\Slide;

/**
 * Flexslider Admin Group Page
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Variable.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Construct.
     *
     * @param \Magento\Backend\Block\Widget\Context $context  Context.
     * @param \Magento\Framework\Registry           $registry CoreRegistry.
     * @param array                                 $data         Data.
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize flexslider slide edit block.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'slide_id';
        $this->_blockGroup = 'SolideWebservices_Flexslider';
        $this->_controller = 'adminhtml_slide';

        parent::_construct();

        if ($this->_isAllowedAction('SolideWebservices_Flexslider::slide')) {
            $this->buttonList->update('save', 'label', __('Save Slide'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'saveAndContinueEdit',
                                'target' => '#edit_form'
                            ],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('SolideWebservices_Flexslider::slide')) {
            $this->buttonList->update('delete', 'label', __('Delete Slide'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Prepare collection.
     *
     * @param string $resourceId Resource ID.
     *
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button tab_id will be replaced.
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            ['_current' => true, 'back' => 'edit', 'active_tab' => '']
        );
    }

}
