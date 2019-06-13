<?php
namespace Ntic\Pay\Helper\Payzen;
use Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    const PAYZEN_CONFIG = array(
        'shopID'        => '71261919',                                  // shopId
        'certTest'      => '8804070155613005',                          // certificate, TEST-version
        'certProd'      => '4949198458854853',                          // certificate, PRODUCTION-version
        'ctxMode'       => 'TEST',                                      // PRODUCTION || TEST
        'wsdl'          => 'https://secure.payzen.eu/vads-ws/v5?wsdl',  // URL of PayZen SOAP V5 WSDL
        'ns'            => 'http://v5.ws.vads.lyra.com/Header',         //ns,
    );


    public function getConfig(){
        return $this->PAYZEN_CONFIG;
    }
}
