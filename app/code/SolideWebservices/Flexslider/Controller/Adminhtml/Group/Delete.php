<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.4
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Controller\Adminhtml\Group;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization
        ->isAllowed('SolideWebservices_Flexslider::group');
    }

    /**
     * Delete action.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('group_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $group = "";
            try {
                // init model and delete
                $model = $this->_objectManager
                        ->create('SolideWebservices\Flexslider\Model\Group');
                $model->load($id);
                $title = $model->getTitle();

                if($model->countSlideIds($id) == 0) {
                    $model->delete();
                    $this->messageManager
                            ->addSuccess(__('The group has been deleted.'));
                    $this->_eventManager->dispatch(
                        'adminhtml_flexslider_group_on_delete',
                        ['title' => $title, 'status' => 'success']
                    );
                    return $resultRedirect->setPath('*/*/');
                } else {
                    $this->_eventManager->dispatch(
                        'adminhtml_flexslider_group_on_delete',
                        ['title' => $title, 'status' => 'fail']
                    );
                    $this->messageManager->addError(__('This group has slides connected, please disconnect all slides before deleting the group.'));
                    return $resultRedirect->setPath(
                                                '*/*/edit',
                                                ['group_id' => $id]
                                            );
                }
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_flexslider_group_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath(
                                            '*/*/edit',
                                            ['group_id' => $id]
                                        );
            }
        }
        $this->messageManager
            ->addError(__('We can\'t find a group to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
