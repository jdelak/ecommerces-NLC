<?php
namespace Ntic\UpdateProducts\Helper\Navision;


use Ntic\UpdateProducts\Helper\Navision\NTLMSoapClient;
use Ntic\UpdateProducts\Helper\Navision\NTLMStream;

define('DEBUG', true);

class GetProductNAV
{
    public function createOrderServiceNAV()
    {
        $result = false;
        try{
            // Initialize Soap Client
            $baseURL = "http://37.157.9.150:7057/NAV100_LEXEL_PROJET_LOCAL/WS/LEXEL/Page/ItemWS"; // ex. https://<servername>.cloudapp.net:7047/NAV/WS/
            $client = new NTLMSoapClient($baseURL);

            $objOrder = new \stdClass();
            $objOrder->filter = '';
            $objOrder->bookmarkKey = '';
            $objOrder->setSize = '';
            $result = $client->__soapCall("ReadMultiple",array('return'=>$objOrder));
        }catch (Exception $e) {
            $e->getMessage();
        }

        return $result;
    }
}
