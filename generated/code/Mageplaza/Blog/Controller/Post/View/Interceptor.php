<?php
namespace Mageplaza\Blog\Controller\Post\View;

/**
 * Interceptor class for @see \Mageplaza\Blog\Controller\Post\View
 */
class Interceptor extends \Mageplaza\Blog\Controller\Post\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Json\Helper\Data $jsonHelper, \Mageplaza\Blog\Model\CommentFactory $commentFactory, \Mageplaza\Blog\Model\LikeFactory $likeFactory, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Mageplaza\Blog\Helper\Data $helperBlog, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Customer\Api\AccountManagementInterface $accountManagement, \Magento\Customer\Model\Url $customerUrl, \Magento\Customer\Model\Session $customerSession, \Mageplaza\Blog\Model\TrafficFactory $trafficFactory)
    {
        $this->___init();
        parent::__construct($jsonHelper, $commentFactory, $likeFactory, $dateTime, $context, $resultForwardFactory, $storeManager, $helperBlog, $resultPageFactory, $accountManagement, $customerUrl, $customerSession, $trafficFactory);
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
    public function commentActions($action, $user, $data, $model, $cmtId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'commentActions');
        if (!$pluginInfo) {
            return parent::commentActions($action, $user, $data, $model, $cmtId);
        } else {
            return $this->___callPlugins('commentActions', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isLikedComment($cmtId, $userId, $model)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isLikedComment');
        if (!$pluginInfo) {
            return parent::isLikedComment($cmtId, $userId, $model);
        } else {
            return $this->___callPlugins('isLikedComment', func_get_args(), $pluginInfo);
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
