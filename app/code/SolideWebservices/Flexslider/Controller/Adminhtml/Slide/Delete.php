<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.2
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Controller\Adminhtml\Slide;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed('SolideWebservices_Flexslider::slide');
    }

    /**
     * Delete action.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('slide_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $slide = "";
            try {
                // init model and delete
                $model = $this->_objectManager
                    ->create('SolideWebservices\Flexslider\Model\Slide');
                $model->load($id);
                $title = $model->getSlideTitle();
                $model->delete();
                $this->messageManager
                    ->addSuccess(__('This slide has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_flexslider_slide_on_delete',
                    ['title' => $title, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_flexslider_slide_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['slide_id' => $id]
                );
            }
        }
        $this->messageManager
            ->addError(__('We can\'t find a slide to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
