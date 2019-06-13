<?php
/*
 * Servant au  haut de la page avec les boutons qui vont bien
 */

namespace Ntic\PortfolioCustomer\Block\Adminhtml\Manager;

class Top extends \Magento\Backend\Block\Widget\Container
{
    /**
     * {@inheritDoc}
     */
    protected function _prepareLayout()
    {

        $addButtonProps = [
            'id' => 'add_new_entry',
            'label' => __('Manage'),
            'class' => 'primary add',
            'button_class' => '',
            'onclick' => "setLocation('" . $this->getCreateUrl() . "')",
            'class_name' => 'Magento\Backend\Block\Widget\Button'
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    /**
     * Retrieve create url
     *
     * @return string
     */
    protected function getCreateUrl()
    {
        return $this->getUrl(
            'ForceCustomerLogin/Whitelist/Create'
        );
    }


}