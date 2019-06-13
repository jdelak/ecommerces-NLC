<?php

namespace Ntic\UpdateProducts\Helper\Navision;
class NTLMSoapClient extends \SoapClient {

    function __doRequest($request, $location, $action, $version, $null=null ){
        $headers = array(
            'Method: POST',
            'Connection: Keep-Alive',
            'User-Agent: PHP-SOAP-CURL',
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "'.$action.'"',
        );
        $this->__last_request_headers = $headers;
        $ch = curl_init($location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_USERPWD, 'LEXELCLOUD\JPEUGEOT:t05qYnHb');
        $response = curl_exec($ch);
        return $response;
    }

    function __getLastRequestHeaders() {
        return implode("\n", $this->__last_request_headers)."\n";
    }
}