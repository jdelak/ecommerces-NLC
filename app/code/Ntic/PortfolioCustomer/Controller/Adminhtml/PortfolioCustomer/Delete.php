<?php


namespace Ntic\PortfolioCustomer\Controller\Adminhtml\PortfolioCustomer;

class Delete extends \Ntic\PortfolioCustomer\Controller\Adminhtml\PortfolioCustomer
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
        $id = $this->getRequest()->getParam('portfolio_customer_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Ntic\PortfolioCustomer\Model\PortfolioCustomer');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the Portfolio Customer.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['portfolio_customer_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a Portfolio Customer to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
