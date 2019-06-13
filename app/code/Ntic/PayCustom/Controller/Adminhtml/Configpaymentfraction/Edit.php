<?php


namespace Ntic\PayCustom\Controller\Adminhtml\Configpaymentfraction;

class Edit extends \Ntic\PayCustom\Controller\Adminhtml\Configpaymentfraction
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('config_payment_fraction_id');
        $model = $this->_objectManager->create('Ntic\PayCustom\Model\ConfigPaymentFraction');
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Config Payment Fraction no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('ntic_paycustom_config_payment_fraction', $model);
        
        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Config Payment Fraction') : __('New Config Payment Fraction'),
            $id ? __('Edit Config Payment Fraction') : __('New Config Payment Fraction')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Config Payment Fractions'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Config Payment Fraction'));
        return $resultPage;
    }
}
