<?php


namespace Ntic\Conges\Controller\Conges;

class Edit extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager ->get('Magento\Framework\App\ResourceConnection');
        $_db = $resource->getConnection();
        $table = $_db->getTableName('ntic_conges_conges');

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('conges_id');
        $accepted = $this->getRequest()->getParam('accepted');
        $comment = $this->getRequest()->getParam('comment');


        $model = $this->_objectManager->create('Ntic\Conges\Model\Conges');

        // 2. Initial checking
        if ($id) {
            $data = [
                'updated_at' => date("Y-m-d H:i:s"),
                'accepted' => $accepted
            ];
            $_db->update($table, $data, ['conges_id = ?' => $id,]);
        }

        return true;
    }
}
