<?php


namespace Ntic\PortfolioCustomer\Controller\Adminhtml\PortfolioCustomer;

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
            $id = $this->getRequest()->getParam('portfolio_customer_id');
        
            $model = $this->_objectManager->create('Ntic\PortfolioCustomer\Model\PortfolioCustomer')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This Portfolio Customer no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the Portfolio Customer.'));
                $this->dataPersistor->clear('ntic_portfoliocustomer_portfolio_customer');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['portfolio_customer_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Portfolio Customer.'));
            }
        
            $this->dataPersistor->set('ntic_portfoliocustomer_portfolio_customer', $data);
            return $resultRedirect->setPath('*/*/edit', ['portfolio_customer_id' => $this->getRequest()->getParam('portfolio_customer_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
