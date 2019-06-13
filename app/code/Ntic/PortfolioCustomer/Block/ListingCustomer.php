<?php


namespace Ntic\PortfolioCustomer\Block;
use Magento\Framework\View\Element\Template;

class ListingCustomer extends \Magento\Framework\View\Element\Template
{
    public function __construct(Template\Context $context, array $data = array())
    {
        parent::__construct($context, $data);
        //$this->setData('table1',array());
    }
    public function listContact($id){
        /* INITIALISATION */
        $list_contact = array();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $contactModel = $objectManager->create('Ntic\PortfolioCustomer\Model\PortfolioCustomer');
        $collection = $contactModel->getCollection()->addFieldToFilter('seller_id', array('eq'=> $id));

        /* CONTRUSTION DU TABLEAU */
        foreach ($collection as $contact){
            $list_contact[] = $contact->getData();
        }
        if (!$list_contact) {
            return false;
        }
        return (array) $list_contact;
    }
}
