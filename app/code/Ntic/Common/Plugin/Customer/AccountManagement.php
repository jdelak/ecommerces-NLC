<?php

namespace Ntic\Common\Plugin\Customer;

use Magento\Framework\Exception\LocalizedException;

class AccountManagement
{

    /**
     * Authenticate a customer
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManagement
     * @param \Magento\Customer\Api\Data\CustomerInterface $result
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterAuthenticate(
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        $result
    )
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $AccessPermission = $objectManager->get('Ntic\AccessPermission\Model\AccessPermission')->getCollection();
        $storeId = $storeManager->getStore()->getStoreId();
        $storeCode = $storeManager->getStore()->getCode();
        $groupId = $result->getGroupId();
        // SI le store code commence par "ruche" alors permission accordée
        // REGLE NTIC de notre groupe
        if( $storeCode == 'default' || substr($storeCode,0,5) == 'ruche'){
            $thisPermission = $AccessPermission->addFieldToFilter('store_id',['eq' => $storeId ])->addFieldToFilter('group_id',['eq' => $groupId ]);
            if( count($thisPermission) == 0){
                throw new LocalizedException(__('The customer group does not allow.') );
            }else{
                // ENREGISTREMENT DE LA SESSION USER NTIC
                $_SESSION['ntic_user']= $result->getId();
                $_SESSION['gift_godfather'] = 0;
            }
        }

        return $result;
    }
}