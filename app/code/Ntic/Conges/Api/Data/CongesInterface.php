<?php


namespace Ntic\Conges\Api\Data;

interface CongesInterface
{

    const START_AT = 'start_at';
    const TYPE = 'type';
    const CREATED_AT = 'created_at';
    const CONGES_ID = 'conges_id';
    const UPDATED_AT = 'updated_at';
    const END_AT = 'end_at';
    const ACCEPTED = 'accepted';
    const DEMANDEUR_ID = 'demandeur_id';


    /**
     * Get conges_id
     * @return string|null
     */
    public function getCongesId();

    /**
     * Set conges_id
     * @param string $conges_id
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setCongesId($congesId);

    /**
     * Get start_date
     * @return string|null
     */
    public function getStartAt();

    /**
     * Set start_date
     * @param string $start_date
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setStartAt($start_at);

    /**
     * Get end_date
     * @return string|null
     */
    public function getEndAt();

    /**
     * Set end_date
     * @param string $end_date
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setEndAt($end_at);

    /**
     * Get demandeur_id
     * @return string|null
     */
    public function getDemandeurId();

    /**
     * Set demandeur_id
     * @param string $demandeur_id
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setDemandeurId($demandeur_id);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $created_at
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setCreatedAt($created_at);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updated_at
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setUpdatedAt($updated_at);

    /**
     * Get type
     * @return string|null
     */
    public function getType();

    /**
     * Set type
     * @param string $type
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setType($type);

    /**
     * Get accepted
     * @return string|null
     */
    public function getAccepted();

    /**
     * Set accepted
     * @param string $accepted
     * @return \Ntic\Conges\Api\Data\CongesInterface
     */
    public function setAccepted($accepted);
}
