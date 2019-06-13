<?php
namespace Ntic\Common\Controller\AdditionalInfo;

use Magento\Framework\App\ObjectManager;


class Save extends \Magento\Framework\App\Action\Action {

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        // CREATE CUSTOMER

        var_dump($_POST);

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

            $websiteId = $storeManager->getWebsite()->getWebsiteId();
            $store = $storeManager->getStore();  // Get Store ID


            $this->messageManager->addSuccess(" à été ajouté en tant que nouveau client !");

            $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            return $result->setPath('fiche-client');

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