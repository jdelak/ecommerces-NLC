<?php


namespace Ntic\Pay\Api\Data;

interface CertifInterface
{

    const SHOPID = 'shopId';
    const CERTIF_ID = 'certif_id';
    const NS = 'ns';
    const STORE = 'store';
    const WSDL = 'wsdl';
    const CERTPROD = 'certProd';
    const CTXMODE = 'ctxMode';
    const CERTTEST = 'certTest';


    /**
     * Get certif_id
     * @return string|null
     */
    
    public function getCertifId();

    /**
     * Set certif_id
     * @param string $certif_id
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    
    public function setCertifId($certifId);

    /**
     * Get shopId
     * @return string|null
     */
    
    public function getShopId();

    /**
     * Set shopId
     * @param string $shopId
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    
    public function setShopId($shopId);

    /**
     * Get certTest
     * @return string|null
     */
    
    public function getCertTest();

    /**
     * Set certTest
     * @param string $certTest
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    
    public function setCertTest($certTest);

    /**
     * Get certProd
     * @return string|null
     */
    
    public function getCertProd();

    /**
     * Set certProd
     * @param string $certProd
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    
    public function setCertProd($certProd);

    /**
     * Get ctxMode
     * @return string|null
     */
    
    public function getCtxMode();

    /**
     * Set ctxMode
     * @param string $ctxMode
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    
    public function setCtxMode($ctxMode);

    /**
     * Get wsdl
     * @return string|null
     */
    
    public function getWsdl();

    /**
     * Set wsdl
     * @param string $wsdl
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    
    public function setWsdl($wsdl);

    /**
     * Get ns
     * @return string|null
     */
    
    public function getNs();

    /**
     * Set ns
     * @param string $ns
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    
    public function setNs($ns);

    /**
     * Get store
     * @return string|null
     */
    
    public function getStore();

    /**
     * Set store
     * @param string $store
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    
    public function setStore($store);
}
