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
 * Flexslider Page form block
 */
class Page extends \Magento\Backend\Block\Widget\Form\Generic implements
\Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare form.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('flexslider_group');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('flexslider_');

        $fieldset = $form->addFieldset(
                        'page_fieldset',
                        ['legend' => __('Group Pages')]
                    );

        $fieldset->addField(
            'page_id',
            'multiselect',
            [
                'name' => 'pages[]',
                'label' => __('Visible in'),
                'title' => __('Visible in'),
                'style' => 'width:400px',
                'values' => $model->getAvailablePages(),
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
        return __('Display on Pages');
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
}
