<?php


namespace Ntic\MasterId\Controller\Adminhtml;

abstract class MasterCode extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Ntic_MasterId::top_level';
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Ntic'), __('Ntic'))
            ->addBreadcrumb(__('Mastercode'), __('Mastercode'));
        return $resultPage;
    }
}
