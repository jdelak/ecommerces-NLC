<?php


namespace Ntic\Conges\Controller\Adminhtml\Conges;

class Delete extends \Ntic\Conges\Controller\Adminhtml\Conges
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
        $id = $this->getRequest()->getParam('conges_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Ntic\Conges\Model\Conges');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Conges.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['conges_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Conges to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
