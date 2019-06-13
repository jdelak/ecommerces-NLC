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

/**
 * Flexslider Group code form block
 */
class Code extends \Magento\Backend\Block\Widget\Form\Generic implements
\Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Construct.
     *
     * @param \Magento\Backend\Block\Template\Context $context     Context.
     * @param \Magento\Framework\Registry             $registry    Registry.
     * @param \Magento\Framework\Data\FormFactory     $formFactory FormFactory.
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory
    ) {
        parent::__construct($context, $registry, $formFactory);

        $this->setTemplate('group/code.phtml');
    }

    /**
     * Get group collection.
     *
     * @return \SolideWebservices\Flexslider\Model\Group
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
        return __('Use Code Inserts');
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
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }

}
