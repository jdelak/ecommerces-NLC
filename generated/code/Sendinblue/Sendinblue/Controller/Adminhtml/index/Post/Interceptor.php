<?php
namespace Sendinblue\Sendinblue\Controller\Adminhtml\index\Post;

/**
 * Interceptor class for @see \Sendinblue\Sendinblue\Controller\Adminhtml\index\Post
 */
class Interceptor extends \Sendinblue\Sendinblue\Controller\Adminhtml\index\Post implements \Magento\Framework\Interception\InterceptorInterface
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
    public function apiKeyPostProcessConfiguration()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'apiKeyPostProcessConfiguration');
        if (!$pluginInfo) {
            return parent::apiKeyPostProcessConfiguration();
        } else {
            return $this->___callPlugins('apiKeyPostProcessConfiguration', func_get_args(), $pluginInfo);
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
    public function saveNotifyValue()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveNotifyValue');
        if (!$pluginInfo) {
            return parent::saveNotifyValue();
        } else {
            return $this->___callPlugins('saveNotifyValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveOrderSms()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveOrderSms');
        if (!$pluginInfo) {
            return parent::saveOrderSms();
        } else {
            return $this->___callPlugins('saveOrderSms', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveShippedSms()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveShippedSms');
        if (!$pluginInfo) {
            return parent::saveShippedSms();
        } else {
            return $this->___callPlugins('saveShippedSms', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendSmsCampaign()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sendSmsCampaign');
        if (!$pluginInfo) {
            return parent::sendSmsCampaign();
        } else {
            return $this->___callPlugins('sendSmsCampaign', func_get_args(), $pluginInfo);
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
