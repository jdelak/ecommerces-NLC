<?php


namespace Ntic\MasterId\Controller\Adminhtml\MasterCode;

class Delete extends \Ntic\MasterId\Controller\Adminhtml\MasterCode
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
        $id = $this->getRequest()->getParam('mastercode_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Ntic\MasterId\Model\MasterCode');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Mastercode.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['mastercode_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Mastercode to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
