<?php

/*
 * This file is part of the Force Login module for Magento2.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\ForceCustomerLogin\Controller;

use \bitExpert\ForceCustomerLogin\Api\Controller\LoginCheckInterface;
use \bitExpert\ForceCustomerLogin\Api\Repository\WhitelistRepositoryInterface;
use \bitExpert\ForceCustomerLogin\Model\ResourceModel\WhitelistEntry\Collection;
use \bitExpert\ForceCustomerLogin\Model\Session;
use \Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\App\DeploymentConfig;
use \Magento\Backend\Setup\ConfigOptionsList as BackendConfigOptionsList;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Response\Http as ResponseHttp;

/**
 * Class LoginCheck
 * @package bitExpert\ForceCustomerLogin\Controller
 */
class LoginCheck extends Action implements LoginCheckInterface
{
    /**
     * @var UrlInterface
     */
    protected $url;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var DeploymentConfig
     */
    protected $deploymentConfig;
    /**
     * @var WhitelistRepositoryInterface
     */
    protected $whitelistRepository;
    /**
     * @var ModuleCheck
     */
    protected $moduleCheck;
    /**
     * @var ResponseHttp
     */
    protected $response;

    /**
     * Creates a new {@link \bitExpert\ForceCustomerLogin\Controller\LoginCheck}.
     *
     * @param Context $context
     * @param Session $session
     * @param ScopeConfigInterface $scopeConfig
     * @param DeploymentConfig $deploymentConfig
     * @param WhitelistRepositoryInterface $whitelistRepository
     * @param ModuleCheck $moduleCheck
     * @param ResponseHttp $response
     */
    public function __construct(
        Context $context,
        Session $session,
        ScopeConfigInterface $scopeConfig,
        DeploymentConfig $deploymentConfig,
        WhitelistRepositoryInterface $whitelistRepository,
        ModuleCheck $moduleCheck,
        ResponseHttp $response
    ) {
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
        $this->deploymentConfig = $deploymentConfig;
        $this->whitelistRepository = $whitelistRepository;
        $this->moduleCheck = $moduleCheck;
        $this->response = $response;
        parent::__construct($context);
    }

    /**
     * Manages redirect
     */
    public function execute()
    {
        if ($this->moduleCheck->isModuleEnabled() === false) {
            return;
        }

        $url = $this->_url->getCurrentUrl();
        $path = \parse_url($url, PHP_URL_PATH);
        $targetUrl = $this->getTargetUrl();

        // current path is already pointing to target url, no redirect needed
        if ($targetUrl === $path) {
            return;
        }

        $ignoreUrls = $this->getUrlRuleSetByCollection($this->whitelistRepository->getCollection());
        $extendedIgnoreUrls = $this->extendIgnoreUrls($ignoreUrls);

        // check if current url is a match with one of the ignored urls
        foreach ($extendedIgnoreUrls as $ignoreUrl) {
            if (\preg_match(\sprintf('#^.*%s/?.*$#i', $this->quoteRule($ignoreUrl)), $path)) {
                return;
            }
        }

        $this->session->setAfterLoginReferer($path);

        $this->response->setNoCacheHeaders();
        $this->response->setRedirect($this->getRedirectUrl($targetUrl));
        $this->response->sendResponse();
    }

    /**
     * Quote delimiter in whitelist entry rule
     * @param string $rule
     * @param string $delimiter
     * @return string
     */
    protected function quoteRule($rule, $delimiter = '#')
    {
        return \str_replace($delimiter, \sprintf('\%s', $delimiter), $rule);
    }

    /**
     * @param Collection $collection
     * @return string[]
     */
    protected function getUrlRuleSetByCollection(Collection $collection)
    {
        $urlRuleSet = array();
        foreach ($collection->getItems() as $whitelistEntry) {
            /** @var $whitelistEntry \bitExpert\ForceCustomerLogin\Model\WhitelistEntry */
            \array_push($urlRuleSet, $whitelistEntry->getUrlRule());
        }
        return $urlRuleSet;
    }

    /**
     * Add dynamic urls to forced login whitelist.
     *
     * @param array $ignoreUrls
     * @return array
     */
    protected function extendIgnoreUrls(array $ignoreUrls)
    {
        $adminUri = \sprintf(
            '/%s',
            $this->deploymentConfig->get(BackendConfigOptionsList::CONFIG_PATH_BACKEND_FRONTNAME)
        );

        \array_push($ignoreUrls, $adminUri);

        return $ignoreUrls;
    }

    /**
     * @param string $targetUrl
     * @return string
     */
    protected function getRedirectUrl($targetUrl)
    {
        return \sprintf(
            '%s%s',
            $this->_url->getBaseUrl(),
            $targetUrl
        );
    }

    /**
     * @return string
     */
    protected function getTargetUrl()
    {
        return $this->scopeConfig->getValue(
            self::MODULE_CONFIG_TARGET,
            ScopeInterface::SCOPE_STORE
        );
    }
}
