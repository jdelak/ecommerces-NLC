<?php
namespace Sendinblue\Sendinblue\Controller\Adminhtml\index\Ajax;

/**
 * Interceptor class for @see \Sendinblue\Sendinblue\Controller\Adminhtml\index\Ajax
 */
class Interceptor extends \Sendinblue\Sendinblue\Controller\Adminhtml\index\Ajax implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function ajaxSubscribeConfig()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'ajaxSubscribeConfig');
        if (!$pluginInfo) {
            return parent::ajaxSubscribeConfig();
        } else {
            return $this->___callPlugins('ajaxSubscribeConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function ajaxOrderStatus()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'ajaxOrderStatus');
        if (!$pluginInfo) {
            return parent::ajaxOrderStatus();
        } else {
            return $this->___callPlugins('ajaxOrderStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function ajaxSmtpStatus()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'ajaxSmtpStatus');
        if (!$pluginInfo) {
            return parent::ajaxSmtpStatus();
        } else {
            return $this->___callPlugins('ajaxSmtpStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function ajaxSmsOperations()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'ajaxSmsOperations');
        if (!$pluginInfo) {
            return parent::ajaxSmsOperations();
        } else {
            return $this->___callPlugins('ajaxSmsOperations', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sibObject()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sibObject');
        if (!$pluginInfo) {
            return parent::sibObject();
        } else {
            return $this->___callPlugins('sibObject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function viewObject()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'viewObject');
        if (!$pluginInfo) {
            return parent::viewObject();
        } else {
            return $this->___callPlugins('viewObject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveTemplateValue()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveTemplateValue');
        if (!$pluginInfo) {
            return parent::saveTemplateValue();
        } else {
            return $this->___callPlugins('saveTemplateValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadContact()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadContact');
        if (!$pluginInfo) {
            return parent::loadContact();
        } else {
            return $this->___callPlugins('loadContact', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subsUnsubsContact()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'subsUnsubsContact');
        if (!$pluginInfo) {
            return parent::subsUnsubsContact();
        } else {
            return $this->___callPlugins('subsUnsubsContact', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function _processUrlKeys()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '_processUrlKeys');
        if (!$pluginInfo) {
            return parent::_processUrlKeys();
        } else {
            return $this->___callPlugins('_processUrlKeys', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route = '', $params = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        if (!$pluginInfo) {
            return parent::getUrl($route, $params);
        } else {
            return $this->___callPlugins('getUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActionFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        if (!$pluginInfo) {
            return parent::getActionFlag();
        } else {
            return $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequest');
        if (!$pluginInfo) {
            return parent::getRequest();
        } else {
            return $this->___callPlugins('getRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResponse');
        if (!$pluginInfo) {
            return parent::getResponse();
        } else {
            return $this->___callPlugins('getResponse', func_get_args(), $pluginInfo);
        }
    }
}
