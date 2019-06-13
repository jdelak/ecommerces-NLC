<?php


namespace Ntic\Conges\Controller\Index;

use Magento\Framework\App\ObjectManager;

class Index extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }


}
