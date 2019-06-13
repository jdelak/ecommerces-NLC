<?php


namespace Ntic\Pay\Controller\Adminhtml\Certif;

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
        $data['store'] = implode(',', $this->getRequest()->getParam('store_id'));
        if ($data) {
            $id = $this->getRequest()->getParam('certif_id');
        
            $model = $this->_objectManager->create('Ntic\Pay\Model\Certif')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Certif no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Certif.'));
                $this->dataPersistor->clear('ntic_pay_certif');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['certif_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Certif.'));
            }
        
            $this->dataPersistor->set('ntic_pay_certif', $data);
            return $resultRedirect->setPath('*/*/edit', ['certif_id' => $this->getRequest()->getParam('certif_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
