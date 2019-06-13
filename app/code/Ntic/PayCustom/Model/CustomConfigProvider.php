<?php
namespace Ntic\PayCustom\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Store\Model\StoreManagerInterface;

class CustomConfigProvider implements ConfigProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $url = $storeManager->getStore();
        $config = [
            'payment' => [
                'customPayment' => [
                    'redirectUrl' => $url->getUrl('custompayment'),
                ]
            ]
        ];
        return $config;
    }
}