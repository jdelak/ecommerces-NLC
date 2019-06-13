<?php


namespace Ntic\Pay\Model;

use Ntic\Pay\Api\Data\CertifInterface;

class Certif extends \Magento\Framework\Model\AbstractModel implements CertifInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ntic\Pay\Model\ResourceModel\Certif');
    }

    /**
     * Get certif_id
     * @return string
     */
    public function getCertifId()
    {
        return $this->getData(self::CERTIF_ID);
    }

    /**
     * Set certif_id
     * @param string $certifId
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    public function setCertifId($certifId)
    {
        return $this->setData(self::CERTIF_ID, $certifId);
    }

    /**
     * Get shopId
     * @return string
     */
    public function getShopId()
    {
        return $this->getData(self::SHOPID);
    }

    /**
     * Set shopId
     * @param string $shopId
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    public function setShopId($shopId)
    {
        return $this->setData(self::SHOPID, $shopId);
    }

    /**
     * Get certTest
     * @return string
     */
    public function getCertTest()
    {
        return $this->getData(self::CERTTEST);
    }

    /**
     * Set certTest
     * @param string $certTest
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    public function setCertTest($certTest)
    {
        return $this->setData(self::CERTTEST, $certTest);
    }

    /**
     * Get certProd
     * @return string
     */
    public function getCertProd()
    {
        return $this->getData(self::CERTPROD);
    }

    /**
     * Set certProd
     * @param string $certProd
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    public function setCertProd($certProd)
    {
        return $this->setData(self::CERTPROD, $certProd);
    }

    /**
     * Get ctxMode
     * @return string
     */
    public function getCtxMode()
    {
        return $this->getData(self::CTXMODE);
    }

    /**
     * Set ctxMode
     * @param string $ctxMode
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    public function setCtxMode($ctxMode)
    {
        return $this->setData(self::CTXMODE, $ctxMode);
    }

    /**
     * Get wsdl
     * @return string
     */
    public function getWsdl()
    {
        return $this->getData(self::WSDL);
    }

    /**
     * Set wsdl
     * @param string $wsdl
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    public function setWsdl($wsdl)
    {
        return $this->setData(self::WSDL, $wsdl);
    }

    /**
     * Get ns
     * @return string
     */
    public function getNs()
    {
        return $this->getData(self::NS);
    }

    /**
     * Set ns
     * @param string $ns
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    public function setNs($ns)
    {
        return $this->setData(self::NS, $ns);
    }

    /**
     * Get store
     * @return string
     */
    public function getStore()
    {
        return $this->getData(self::STORE);
    }

    /**
     * Set store
     * @param string $store
     * @return \Ntic\Pay\Api\Data\CertifInterface
     */
    public function setStore($store)
    {
        return $this->setData(self::STORE, $store);
    }
}
