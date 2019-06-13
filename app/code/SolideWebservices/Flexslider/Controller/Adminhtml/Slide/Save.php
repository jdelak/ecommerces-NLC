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

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Variable.
     *
     * @var \SolideWebservices\Flexslider\Helper\Image
     */
    protected $_imageHelper;

    /**
     * Construct.
     *
     * @param Action\Context $context Context.
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed('SolideWebservices_Flexslider::slide');
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
                ->create('SolideWebservices\Flexslider\Model\Slide');

            $id = $this->getRequest()->getParam('slide_id');
            if ($id) {
                $model->load($id);
            }

            if (isset($data['image'])) {
                $imageData = $data['image'];
                unset($data['image']);
            } else {
                $imageData = [];
            }
            $model->addData($data);

            try {
                $imageHelper = $this->_objectManager
                    ->get('SolideWebservices\Flexslider\Helper\Image');
                if (isset($imageData['delete']) && $imageData['value']) {
                    $imageHelper->removeImage($imageData['value']);
                    $data['image'] = '';
                }

                $imageFile = $imageHelper->uploadImage('image');
                if ($imageFile) {
                    $data['image'] = $imageFile;
                }
            } catch (\Exception $e) {
                if ($e->getCode() == 0) {
                    $this->messageManager->addError($e->getMessage());
                }
                if (isset($data['image']) && isset($data['image']['value'])) {
                    if (isset($data['image']['delete'])) {
                        $data['image'] = null;
                        $data['delete_image'] = true;
                    } else if (isset($data['image']['value'])) {
                        $data['image'] = $data['image']['value'];
                    } else {
                        $data['image'] = null;
                    }
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'flexslider_slide_prepare_save',
                ['post' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this slide.'));
                $this->_objectManager
                    ->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['slide_id' => $model->getId(), '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager
                    ->addException(
                        $e, __('Something went wrong while saving the slide.')
                    );
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath(
                '*/*/edit',
                ['slide_id' => $this->getRequest()->getParam('slide_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
