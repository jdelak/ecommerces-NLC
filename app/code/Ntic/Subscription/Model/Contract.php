<?php


namespace Ntic\Subscription\Model;

use Ntic\Subscription\Api\Data\ContractInterface;

class Contract extends \Magento\Framework\Model\AbstractModel implements ContractInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ntic\Subscription\Model\ResourceModel\Contract');
    }

    /**
     * Get contract_id
     * @return string
     */
    public function getContractId()
    {
        return $this->getData(self::CONTRACT_ID);
    }

    /**
     * Set contract_id
     * @param string $contractId
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setContractId($contractId)
    {
        return $this->setData(self::CONTRACT_ID, $contractId);
    }

    /**
     * Get store_id
     * @return string
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set store_id
     * @param string $store_id
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setStoreId($store_id)
    {
        return $this->setData(self::STORE_ID, $store_id);
    }

    /**
     * Get order_id
     * @return string
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Set order_id
     * @param string $order_id
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setOrderId($order_id)
    {
        return $this->setData(self::ORDER_ID, $order_id);
    }

    /**
     * Get date_suscription
     * @return string
     */
    public function getDateSuscription()
    {
        return $this->getData(self::DATE_SUSCRIPTION);
    }

    /**
     * Set date_suscription
     * @param string $date_suscription
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setDateSuscription($date_suscription)
    {
        return $this->setData(self::DATE_SUSCRIPTION, $date_suscription);
    }

    /**
     * Get month_subscription
     * @return string
     */
    public function getMonthSubscription()
    {
        return $this->getData(self::MONTH_SUBSCRIPTION);
    }

    /**
     * Set month_subscription
     * @param string $month_subscription
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setMonthSubscription($month_subscription)
    {
        return $this->setData(self::MONTH_SUBSCRIPTION, $month_subscription);
    }

    /**
     * Get day_subscription
     * @return string
     */
    public function getDaySubscription()
    {
        return $this->getData(self::DAY_SUBSCRIPTION);
    }

    /**
     * Set day_subscription
     * @param string $day_subscription
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setDaySubscription($day_subscription)
    {
        return $this->setData(self::DAY_SUBSCRIPTION, $day_subscription);
    }

    /**
     * Get honored
     * @return string
     */
    public function getHonored()
    {
        return $this->getData(self::HONORED);
    }

    /**
     * Set honored
     * @param string $honored
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setHonored($honored)
    {
        return $this->setData(self::HONORED, $honored);
    }

    /**
     * Get data_nav
     * @return string
     */
    public function getDataNav()
    {
        return $this->getData(self::DATA_NAV);
    }

    /**
     * Set data_nav
     * @param string $data_nav
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setDataNav($data_nav)
    {
        return $this->setData(self::DATA_NAV, $data_nav);
    }

    /**
     * Get indice
     * @return string
     */
    public function getIndice()
    {
        return $this->getData(self::INDICE);
    }

    /**
     * Set indice
     * @param string $indice
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setIndice($indice)
    {
        return $this->setData(self::INDICE, $indice);
    }

    /**
     * Get status
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get order_nav_id
     * @return string
     */
    public function getOrderNavId()
    {
        return $this->getData(self::ORDER_NAV_ID);
    }

    /**
     * Set order_nav_id
     * @param string $order_nav_id
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setOrderNavId($order_nav_id)
    {
        return $this->setData(self::ORDER_NAV_ID, $order_nav_id);
    }
}
