<?php

namespace Ntic\Common\Plugin\Customer;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

class LoginPost
{
    protected $coreRegistry;
    protected $url;
    protected $resultFactory;

    public function __construct(Registry $registry, UrlInterface $url, ResultFactory $resultFactory)
    {
        $this->coreRegistry = $registry;
        $this->url = $url;
        $this->resultFactory = $resultFactory;
    }
    public function aroundExecute(\Magento\Customer\Controller\Account\LoginPost $subject, \Closure $proceed)
    {

        // your custom code before the original execute function
        //$this->doSomethingBeforeExecute();
        // call the original execute function
        $returnValue = $proceed();
        // your custom code after the original execute function
        //var_dump($_SESSION);
        if ($returnValue) {
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            //$_SESSION['seller_ntic'] = $_SESSION['customer_base']['customer_id'];
            $result->setUrl($this->url->getUrl(''));
            return $result;
        }

        return $returnValue;
    }
}
?>