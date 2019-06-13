<?php

namespace Ntic\Common\Model\Plugin\Controller\Account;

use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlFactory;
use Magento\Framework\Message\ManagerInterface;

class RestrictCustomerEmail
{

    /** @var \Magento\Framework\UrlInterface */
    protected $urlModel;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * RestrictCustomerEmail constructor.
     * @param UrlFactory $urlFactory
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        UrlFactory $urlFactory,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager

    )
    {
        $this->urlModel = $urlFactory->create();
        $this->resultRedirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param \Closure $proceed
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        \Closure $proceed
    )
    {
        /** @var \Magento\Framework\App\RequestInterface $request */
        $email = $subject->getRequest()->getParam('email');
        list($nick, $domain) = explode('@', $email, 2);
        if (in_array($domain, ['163.com', 'mail.ru','mailforspam.com'], true)) {

            $this->messageManager->addErrorMessage(
                'Votre domaine de mail est désactivé pour l\'inscription à notre site'
            );
            $defaultUrl = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setUrl($defaultUrl);

        }
        return $proceed();
    }
}