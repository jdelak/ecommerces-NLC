<?php


namespace Ntic\PayCustom\Controller\Adminhtml\Configpaymentfraction;

class Delete extends \Ntic\PayCustom\Controller\Adminhtml\Configpaymentfraction
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
        $id = $this->getRequest()->getParam('config_payment_fraction_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Ntic\PayCustom\Model\ConfigPaymentFraction');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the Config Payment Fraction.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['config_payment_fraction_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a Config Payment Fraction to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
