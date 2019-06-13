<?php


namespace Ntic\PayCustom\Controller\Adminhtml\Configpaymentabo;

class Delete extends \Ntic\PayCustom\Controller\Adminhtml\Configpaymentabo
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('config_payment_abo_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Ntic\PayCustom\Model\ConfigPaymentAbo');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the Config Payment Abo.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['config_payment_abo_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a Config Payment Abo to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
