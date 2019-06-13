<?php


namespace Ntic\Subscription\Api\Data;

interface ContractInterface
{

    const CONTRACT_ID = 'contract_id';
    const STATUS = 'status';
    const HONORED = 'honored';
    const DATA_NAV = 'data_nav';
    const STORE_ID = 'store_id';
    const DATE_SUSCRIPTION = 'date_suscription';
    const INDICE = 'indice';
    const ORDER_ID = 'order_id';
    const MONTH_SUBSCRIPTION = 'month_subscription';
    const DAY_SUBSCRIPTION = 'day_subscription';
    const ORDER_NAV_ID = 'order_nav_id';


    /**
     * Get contract_id
     * @return string|null
     */
    public function getContractId();

    /**
     * Set contract_id
     * @param string $contract_id
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setContractId($contractId);

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store_id
     * @param string $store_id
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setStoreId($store_id);

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $order_id
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setOrderId($order_id);

    /**
     * Get date_suscription
     * @return string|null
     */
    public function getDateSuscription();

    /**
     * Set date_suscription
     * @param string $date_suscription
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setDateSuscription($date_suscription);

    /**
     * Get month_subscription
     * @return string|null
     */
    public function getMonthSubscription();

    /**
     * Set month_subscription
     * @param string $month_subscription
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setMonthSubscription($month_subscription);

    /**
     * Get day_subscription
     * @return string|null
     */
    public function getDaySubscription();

    /**
     * Set day_subscription
     * @param string $day_subscription
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setDaySubscription($day_subscription);

    /**
     * Get honored
     * @return string|null
     */
    public function getHonored();

    /**
     * Set honored
     * @param string $honored
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setHonored($honored);

    /**
     * Get data_nav
     * @return string|null
     */
    public function getDataNav();

    /**
     * Set data_nav
     * @param string $data_nav
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setDataNav($data_nav);

    /**
     * Get indice
     * @return string|null
     */
    public function getIndice();

    /**
     * Set indice
     * @param string $indice
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setIndice($indice);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setStatus($status);

    /**
     * Get order_nav_id
     * @return string|null
     */
    public function getOrderNavId();

    /**
     * Set order_nav_id
     * @param string $order_nav_id
     * @return \Ntic\Subscription\Api\Data\ContractInterface
     */
    public function setOrderNavId($order_nav_id);
}
