<?php
/**
 * Copyright © 2016 Rouven Alexander Rieker
 * See LICENSE.md bundled with this module for license details.
 */
namespace Semaio\AdvancedLogin\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ConfigProvider
 *
 * @package Semaio\AdvancedLogin\Model
 */
class ConfigProvider
{
    const XML_PATH_ADVANCEDLOGIN_LOGIN_MODE = 'customer/advancedlogin/login_mode';
    const XML_PATH_ADVANCEDLOGIN_LOGIN_ATTRIBUTE = 'customer/advancedlogin/login_attribute';
    const XML_PATH_ADVANCEDLOGIN_LOGIN_ATTRIBUTE_LABEL = 'customer/advancedlogin/login_attribute_label';
    const XML_PATH_CUSTOMER_ACCOUNT_SHARE_SCOPE = 'customer/account_share/scope';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * ConfigProvider constructor.
     *
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(ScopeConfigInterface $scopeConfig, StoreManagerInterface $storeManager)
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve the configured login mode
     * #####################################################################
     * ################### CHANGEMENT 20170323 #############################
     * #####################################################################
     * [ntic-web-digital]
     * web developer : eymardyvann@gmail.com
     *
     * Je récupère le "LoginMode" en rapport avec le store ID car nous ne passions pas sur la config du store view c'est dommage.
     * @return int
     */
    public function getLoginMode()
    {
        //return (int)$this->scopeConfig->getValue(self::XML_PATH_ADVANCEDLOGIN_LOGIN_MODE);
        $storeID = $this->storeManager->getStore()->getId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->create('\Magento\Framework\App\ResourceConnection');
        $conn = $connection->getConnection();
        $select = $conn->select()->from(
                ['c' => 'core_config_data'],
                ['value']
            )
            ->where('c.scope_id=?',"$storeID" )
            ->where('c.path=?', self::XML_PATH_ADVANCEDLOGIN_LOGIN_MODE );
        $login_mode = $conn->fetchOne($select);
        return (int)$login_mode;

    }

    public function getDebug()
    {
        /*$storeID = $this->storeManager->getStore()->getId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->create('\Magento\Framework\App\ResourceConnection');
        $conn = $connection->getConnection();
        $select = $conn->select()
            ->from(
                ['c' => 'core_config_data'],
                ['value']
            )
            ->where('c.scope_id=?',"$storeID" )
            ->where('c.path=?', self::XML_PATH_ADVANCEDLOGIN_LOGIN_MODE );
        $data = $conn->fetchOne($select);
        return $data;*/
        //return $this->scopeConfig->getValue( );
        //return $this->storeManager->getStore()->getId();
        //return $this->scopeConfig->getValue();

    }

    /**
     * Retrieve the custoemr account share scope
     *
     * @return int
     */
    public function getCustomerAccountShareScope()
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_ACCOUNT_SHARE_SCOPE);
    }

    /**
     * Retrieve the customer attribute for login
     *
     * @return string
     */
    public function getLoginAttribute()
    {
        $attribute = (string)$this->scopeConfig->getValue(self::XML_PATH_ADVANCEDLOGIN_LOGIN_ATTRIBUTE);
        $attribute = trim($attribute);
        if ($attribute == '') {
            return false;
        }

        return $attribute;
    }

    /**
     * Retrieve the customer attribute label
     *
     * @return string
     */
    public function getLoginAttributeLabel()
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_ADVANCEDLOGIN_LOGIN_ATTRIBUTE_LABEL);
    }
}
