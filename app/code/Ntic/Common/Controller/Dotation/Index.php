<?php
namespace Ntic\Common\Controller\Dotation;
use Magento\Framework\App\ObjectManager;

class Index extends \Magento\Framework\App\Action\Action {

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */

    public function execute()
    {

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }



}
?>