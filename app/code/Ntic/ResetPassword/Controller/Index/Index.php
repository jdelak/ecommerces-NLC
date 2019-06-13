<?php

namespace Ntic\ResetPassword\Controller\Index;
use Magento\Customer\Model\AccountManagement;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_objectManager;
    protected $storeManager;
    protected $_transportBuilder;
    protected $customerAccountManagement;


    public function __construct(

        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\User\Helper\Data $user_helper,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Magento\Customer\Model\Customer $customers

    ) {

        $this->_objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $this->storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->_userHelper= $user_helper;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->_customers = $customers;
        parent::__construct($context);
    }

    public function execute()
    {
        try{

            /*

            $websiteId = $this->storeManager->getWebsite()->getWebsiteId();
            $customer = $this->_objectManager->create('\Magento\Customer\Model\Customer');
            $customer->setWebsiteId($websiteId);
            $mail = 'julien.delacourt@lexel-paris.com';
            $customerExist = $customer->loadByEmail($mail);
            $name = $customerExist->getName();

            $templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId());
            $templateVars = array(
                'store' => $this->storeManager->getStore(),
                'customer.id' => $customerExist->getId(),
                'customer.name' => $name,
                'mail' => $mail,
                'name'   => $name,
                'customer.rp_token' => $this->getToken()
            );



            $sender = [
                'email' => 'marie@lexel-paris.com',
                'name' => 'Service Client Lexel Paris'
            ];


            $senderToInfo = [
                'email' => $mail,
            ];

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('reset_password_lexel') // My email template
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars([$templateVars])
                ->setFrom($sender)
                ->addTo($senderToInfo)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();

            var_dump('email sent to '.$mail);


        */
        /*
        $collection = $this->getCollection();
        foreach ($collection as $customer) {
            $this->customerAccountManagement->initiatePasswordReset(
                $customer->getEmail(),
                AccountManagement::EMAIL_RESET
                );
        }
         */
            $this->customerAccountManagement->initiatePasswordReset(
                'julien.delacourt@lexel-paris.com',
                AccountManagement::EMAIL_RESET
            );
        }catch (\Exception $e) {
            var_dump('reset mail not sent !');
        }


    }

    public function getToken(){
        return $this->_userHelper->generateResetPasswordLinkToken();
    }

    public function getCollection()
    {
        //Get customer collection
        return $this->_customers->getCollection();
    }

}