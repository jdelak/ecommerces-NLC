<?php


namespace Ntic\Calendar\Controller\Index;

use Magento\Framework\App\ObjectManager;
use Ntic\Calendar\Controller\Index\Ajax;

class Index extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }


}
