<?php
namespace Ntic\Common\Controller\Customer;

use Magento\Framework\App\ObjectManager;


class Save extends \Magento\Framework\App\Action\Action {

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        // CREATE CUSTOMER
        if(isset($_POST['create-customer']) && $_POST['create-customer'] == 1) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $portfoliocustomer = $objectManager->create('Ntic\PortfolioCustomer\Model\PortfolioCustomer');

            $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory');
            $websiteId = $storeManager->getWebsite()->getWebsiteId();
            $store = $storeManager->getStore();  // Get Store ID

            $customer = $customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->setEmail($_POST['email']);
            $customer->setFirstname($_POST['firstname']);
            $customer->setLastname($_POST['name']);
            $customer->setPassword($_POST['password']);
            $customer->setPrefix($_POST['prefix']);
            $customer->setGender($_POST['gender']);
            $customer->setDob($_POST['dob']);
            $customer->save();

            $addresss = $objectManager->get('\Magento\Customer\Model\AddressFactory');
            $address = $addresss->create();
            $address->setCustomerId($customer->getId())
                ->setPrefix($_POST['prefix'])
                ->setFirstname($_POST['firstname'])
                ->setLastname($_POST['name'])
                ->setCountryId($_POST['country'])
                ->setPostcode($_POST['zipcode'])
                ->setCity($_POST['ville'])
                ->setTelephone($_POST['phone'])
                ->setStreet(array('0' => $_POST['address1'], '1' => $_POST['address2']))
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');

            $address->save();

            // ATTACH SELLER / CUSTOMER
            $portfoliocustomer->setSellerId($_SESSION['ntic_user']);
            $portfoliocustomer->setCustomerId($customer->getId());
            $portfoliocustomer->save();

            $this->messageManager->addSuccess(strtoupper($_POST['name'])." ".$_POST['firstname']." à été ajouté en tant que nouveau client !");

            $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            return $result->setPath('customer-listing');
        }
    }

    /**
     * Vérifie les données saisie
     *
     * @param $post
     * @return true / false
     */
    public function checkIntegrity($post) {
        // TODO : Faire les intégrité des champs 
    }
}
?>