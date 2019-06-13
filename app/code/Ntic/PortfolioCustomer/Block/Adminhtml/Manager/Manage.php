<?php
/*
 * Servant au  haut de la page avec les boutons qui vont bien
 */

namespace Ntic\PortfolioCustomer\Block\Adminhtml\Manager;

use \bitExpert\ForceCustomerLogin\Api\Data\WhitelistEntryFactoryInterface;
use \Ntic\PortfolioCustomer\Controller\Adminhtml\PortfolioCustomer\Datatable;

class Manage extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected $entityFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        WhitelistEntryFactoryInterface $entityFactory,
        array $data
    ) {
        $this->entityFactory = $entityFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }



    public function listAllSeller(){
        /* INITIALISATION */
        $list_contact = array();
        $objetmanager = \Magento\Framework\App\ObjectManager::getInstance();
        $portfolio = $objetmanager->create('Ntic\PortfolioCustomer\Model\PortfolioCustomer')->getCollection();
        $customer = $objetmanager->create('Magento\Customer\Model\Customer')->getCollection();

        $customerGroupsCollection = $objetmanager->create('\Magento\Customer\Model\ResourceModel\Group\Collection');
        $customerGroups = $customerGroupsCollection->toOptionArray();
        $key = array_search('seller', array_column($customerGroups, 'label'));
        $list_customer = $customer->addAttributeToSelect('*')->addFieldToFilter('group_id', array('eq'=> $key));



        /* CONTRUSTION DU TABLEAU */
        foreach ($list_customer as $contact){
            $list_contact[] = $contact->getData();
        }
        if (!$list_contact) {
            return false;
        }

        return (array) $list_contact;

    }
    protected function _prepareForm()
    {
        // Try to fetch entity if id is provided
        $whitelistEntry = $this->entityFactory->create()->load($this->_request->getParam('id', 0));
        if (!$whitelistEntry->getId()) {
            $whitelistEntry->setLabel(\base64_decode($this->_request->getParam('label')));
            $whitelistEntry->setUrlRule(\base64_decode($this->_request->getParam('url_rule')));
            $whitelistEntry->setStoreId(\base64_decode($this->_request->getParam('store_id')));
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'create_whitelist_entry_form',
                'action' => $this->getUrl('ForceCustomerLogin/Whitelist/Save'),
                'method' => 'post'
            ]
        ]);
        $form->setHtmlIdPrefix('');

        $fieldsetBase = $form->addFieldset(
            'base_fieldset',
            [
                'class' => 'fieldset-wide'
            ]
        );

        if ($whitelistEntry->getId()) {
            $fieldsetBase->addField('whitelist_entry_id', 'hidden', [
                'name' => 'whitelist_entry_id',
                'value' => $whitelistEntry->getId()
            ]);
        }

        $fieldsetBase->addField('label', 'text', [
            'name' => 'label',
            'label' => __('Label'),
            'title' => __('Label'),
            'value' => $whitelistEntry->getLabel(),
            'required' => true
        ]);

        $fieldsetBase->addField('url_rule', 'text', [
            'name' => 'url_rule',
            'label' => __('Url Rule'),
            'title' => __('Url Rule'),
            'value' => $whitelistEntry->getUrlRule(),
            'required' => true
        ]);

        $fieldsetBase->addField('store_id', 'select', [
            'name' => 'store_id',
            'label' => __('Store'),
            'title' => __('Store'),
            'value' => $whitelistEntry->getStoreId(),
            'options' =>  $this->getStoresAsArray(),
            'required' => true
        ]);
        $form->setData('store_id', $whitelistEntry->getStoreId());



        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}