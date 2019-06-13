<?php


namespace Ntic\PayCustom\Controller\Adminhtml\Configpaymentabo;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('config_payment_abo_id');
        
            $model = $this->_objectManager->create('Ntic\PayCustom\Model\ConfigPaymentAbo')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This Config Payment Abo no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the Config Payment Abo.'));
                $this->dataPersistor->clear('ntic_paycustom_config_payment_abo');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['config_payment_abo_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Config Payment Abo.'));
            }
        
            $this->dataPersistor->set('ntic_paycustom_config_payment_abo', $data);
            return $resultRedirect->setPath('*/*/edit', ['config_payment_abo_id' => $this->getRequest()->getParam('config_payment_abo_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
