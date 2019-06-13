<?php


namespace Ntic\Calendar\Block\Contact;

use Magento\Framework\App\ObjectManager;
use Magento\Customer\Model\Session;
use Ntic\Calendar\Helper as permission;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class Edit extends \Magento\Framework\View\Element\Template
{
    private $objectManager;
    private $_customerSession;
    private $request;

    public function __construct(
        Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context
    )
    {
        parent::__construct($context);
        $this->_customerSession = $customerSession;
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }


    public function checkPermission() {
        return permission\CheckCustomerPermission::isManager();
    }

    public function getName()
    {
        return permission\CheckCustomerPermission::userInfos();
    }

    public function getId()
    {
        return permission\CheckCustomerPermission::getUserId();
    }

    public function getSellerCode()
    {
        return permission\CheckCustomerPermission::getUserSellerCode();
    }


    public function test()
    {
        return \Ntic\Calendar\Cron\SendMail::debug();
     }

}
