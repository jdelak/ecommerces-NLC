<?php


namespace Ntic\AccessPermission\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();

        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

//var_dump($storeManager->getStore()->getData());
        echo $storeManager->getStore()->getStoreId() . '<br />';
        echo $storeManager->getStore()->getCode() . '<br />';
        echo $storeManager->getStore()->getWebsiteId() . '<br />';
        echo $storeManager->getStore()->getName() . '<br />';
        echo $storeManager->getStore()->getStoreUrl() . '<br />';
        //return $this->resultPageFactory->create();
    }
}
