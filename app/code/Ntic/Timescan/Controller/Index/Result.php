<?php


namespace Ntic\Timescan\Controller\Index;

use Magento\Framework\App\ObjectManager;


class Result extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
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

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set("Résultats de votre TimeScan");

        return $resultPage;

    }

    

}
