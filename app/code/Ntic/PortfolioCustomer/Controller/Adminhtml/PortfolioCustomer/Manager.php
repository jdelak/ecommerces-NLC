<?php


namespace Ntic\PortfolioCustomer\Controller\Adminhtml\PortfolioCustomer;

class Manager extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    const ADMIN_RESOURCE = 'Ntic_PortfolioCustomer::top_level';

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Customer::customer');
        $resultPage->getConfig()->getTitle()->prepend(__("Portfolio Customer Manager"));
        return $resultPage;
    }
}
