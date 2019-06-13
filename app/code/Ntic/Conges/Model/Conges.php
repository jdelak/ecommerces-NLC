<?php


namespace Ntic\Conges\Model;

use Ntic\Conges\Api\Data\CongesInterface;

class Conges extends \Magento\Framework\Model\AbstractModel implements CongesInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ntic\Conges\Model\ResourceModel\Conges');
    }

    /**
     * Get conges_id
     * @return string
     */
    public function getCongesId()
    {
        return $this->getData(self::CONGES_ID);
    }

    /**
     * Set conges_id
     * @param string $congesId
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setCongesId($congesId)
    {
        return $this->setData(self::CONGES_ID, $congesId);
    }

    /**
     * Get start_date
     * @return string
     */
    public function getStartAt()
    {
        return $this->getData(self::START_AT);
    }

    /**
     * Set start_date
     * @param string $start_date
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setStartAt($start_at)
    {
        return $this->setData(self::START_AT, $start_at);
    }

    /**
     * Get end_date
     * @return string
     */
    public function getEndAt()
    {
        return $this->getData(self::END_AT);
    }

    /**
     * Set end_date
     * @param string $end_date
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setEndAt($end_at)
    {
        return $this->setData(self::END_AT, $end_at);
    }

    /**
     * Get demandeur_id
     * @return string
     */
    public function getDemandeurId()
    {
        return $this->getData(self::DEMANDEUR_ID);
    }

    /**
     * Set demandeur_id
     * @param string $demandeur_id
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setDemandeurId($demandeur_id)
    {
        return $this->setData(self::DEMANDEUR_ID, $demandeur_id);
    }

    /**
     * Get created_at
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $created_at
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setCreatedAt($created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }

    /**
     * Get updated_at
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     * @param string $updated_at
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setUpdatedAt($updated_at)
    {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    /**
     * Get type
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set type
     * @param string $type
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get accepted
     * @return string
     */
    public function getAccepted()
    {
        return $this->getData(self::ACCEPTED);
    }

    /**
     * Set accepted
     * @param string $accepted
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setAccepted($accepted)
    {
        return $this->setData(self::ACCEPTED, $accepted);
    }
}
