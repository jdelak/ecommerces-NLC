<?php


namespace Ntic\Pay\Controller\Adminhtml\Certif;

class Delete extends \Ntic\Pay\Controller\Adminhtml\Certif
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
        $id = $this->getRequest()->getParam('certif_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Ntic\Pay\Model\Certif');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Certif.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['certif_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Certif to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
