<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.3
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Block\Adminhtml\Group\Edit\Tab;

/**
 * Flexslider Group category form block
 */
class Category extends \Magento\Backend\Block\Widget\Form\Generic implements
\Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('flexslider_group');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('flexslider_');

        $fieldset = $form->addFieldset(
                            'category_fieldset',
                            ['legend' => __('Group Categories')]
                        );

        $fieldset->addField(
            'category_ids',
            'Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Category',
            [
            'name' => 'categories[]',
            'label' => __('Visible in'),
            'title' => __('Visible in'),
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Display on Categories');
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
