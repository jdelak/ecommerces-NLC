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

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;


class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry.
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * Variable.
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Construct.
     *
     * @param Context     $context           Context.
     * @param PageFactory $resultPageFactory ResultPageFactory.
     * @param Registry    $registry          Registry.
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
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
     * Init actions.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }

    /**
     * Edit Flexslider group.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('group_id');
        $model = $this->_objectManager
            ->create('SolideWebservices\Flexslider\Model\Group');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager
                    ->addError(__('This group no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager
            ->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('flexslider_group', $model);

        $resultPage = $this->_initAction();

        $resultPage->getConfig()
            ->getTitle()->prepend(__('Flexslider - Group Management'));
        $resultPage->getConfig()->getTitle()
        ->prepend($model->getId() ? sprintf(__('Flexslider - Edit Group "%s"'),
                    $model->getTitle()) : __('Flexslider - New Group'));
        return $resultPage;
    }
}
