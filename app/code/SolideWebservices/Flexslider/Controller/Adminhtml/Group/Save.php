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

namespace SolideWebservices\Flexslider\Controller\Adminhtml\Group;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Variable.
     *
     * @var \Magento\Backend\Helper\Js
     */
    protected $jsHelper;

    /**
     * Construct.
     *
     * @param \Magento\Backend\Helper\Js          $jsHelper JSHelper.
     * @param \Magento\Backend\App\Action\Context $context  Context.
     */
    public function __construct(
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->jsHelper = $jsHelper;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed('SolideWebservices_Flexslider::group');
    }

    /**
     * Save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager
                ->create('SolideWebservices\Flexslider\Model\Group');

            $id = $this->getRequest()->getParam('group_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            $products = $this->getRequest()->getPost('products', -1);
            if ($products != -1) {
                $model->setProductsData(
                    $this->jsHelper->decodeGridSerializedInput($products)
                );
            }

            $this->_eventManager->dispatch(
                'flexslider_group_prepare_save',
                ['post' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this group.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')
                    ->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['group_id' => $model->getId(), '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e, __('Something went wrong while saving the group.')
                );
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath(
                '*/*/edit',
                ['group_id' => $this->getRequest()->getParam('group_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
