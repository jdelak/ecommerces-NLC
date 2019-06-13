<?php


namespace Ntic\InformationCustomer\Controller\FollowTime;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Magento\Framework\Controller\ResultFactory;
use Ntic\Common\Block\ContactMaster;

class Save extends \Magento\Framework\App\Action\Action{

    protected $resultPageFactory;
    private $post;
    private $contactMaster;
    protected $_objectManager;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Element\Template\Context $contextView
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->post = $_POST;
        $this->_objectManager = $context->getObjectManagerInterface();
        $this->contactMaster = new ContactMaster($contextView);

        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $this->saveContactInfo();

        return $resultPage;
    }

    public function saveContactInfo(){

        $idAttribute = 0;
        $storeManager = $this->_objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        foreach ($this->post['name']  as $index => $value){
            if($value!=''){
                $contactId = $this->contactMaster->getContactByExtID(109)['id'];

                if($idAttribute == 0){
                    $contactInfo[] = array(
                        'attribute_id'  => $this->post['id'][$index],
                        'value'         => $value
                    );
                    $this->contactMaster->save('contact_info',$contactInfo);
                    $idAttribute = $this->contactMaster->getLastId();
                    $contactProfile[] = array(
                        'contact_id'    => $contactId,
                        'type_id'       => 8,
                        'info_id'       => $idAttribute,
                        'source'        => $storeManager->getStore()->getCode(),
                        'created_at'    => date('Y-m-d H:i:s'),
                        'ip'            => $_SERVER['REMOTE_ADDR'],
                        'ext_id'        => 109
                    );
                    $this->contactMaster->save('contact_profile',$contactProfile);
                }else{
                    $contactInfo[] = array(
                        'id'            => $idAttribute,
                        'attribute_id'  => $this->post['id'][$index],
                        'value'         => $value
                    );
                    $this->contactMaster->save('contact_info',$contactInfo);
                }

            }
        }
    }
}