<?php

    namespace Ntic\Common\Block;
    use Ntic\Common\Helper\HandlerContactMaster;

    class ContactMaster extends \Magento\Framework\View\Element\Template {
        public $handlerContactMaster;

        public function __construct(\Magento\Framework\View\Element\Template\Context $context) {
            parent::__construct($context);
            // Appel helper "HandlerContactMaster"
            $this->handler_contact_master = \Magento\Framework\App\ObjectManager::getInstance()->get("\Ntic\Common\Helper\HandlerContactMaster");
        }

        /**
         * Liste des utilisateurs de source "RDV"
         *
         * RDV => Type_id = 3
         */
        public function listRDV() {
            return $this->handler_contact_master->getListContactByTypeId(3);
        }

        /**
         * Liste des utilisateurs de source "LEAD"
         *
         * LEAD => Type_id = 1
         */
        public function listLead() {
            return $this->handler_contact_master->getListContactByTypeId(1);
        }

        /**
         * Liste des utilisateurs de source "GODFATHER" (parrainage)
         *
         * Godfather => Type_id = 4
         */
        public function listGodfather() {
            return $this->handler_contact_master->getListContactByTypeId(4);
        }

        /**
         * Liste des utilisateurs de source "GODFATHER" (parrainage) qui appartient au seller
         *
         * Godfather => Type_id = 4
         * @param valeur du champ recherche (autocomplete)
         */
        public function listAjaxGodfather($search) {
            return $this->handler_contact_master->getListGodfather($search);
        }

        /**
         * Liste des utilisateurs de source "GODFATHER" (parrainage) qui appartient au seller
         *
         * Godfather => Type_id = 4
         * @param id du seller
         */
        public function listAjaxCalendar($id) {
            return $this->handler_contact_master->getListCalendar($id);
        }

        /**
         * Retourne le User par son ID
         *
         * @return retour SQL (array)
         */
        public function getUser($id) {
            return $this->handler_contact_master->getUserById($id);
        }

        /**
         * Save
         *
         * @param Nom de la table
         * @param (array)
         * @return SQL (array)
         */
        public function save($table, $post) {
            return $this->handler_contact_master->save($table, $post);
        }

        /**
         * Update
         *
         * @param id des nouvelles valeurs
         * @param Nom de la table
         * @param (array)
         * @return SQL (array)
         */
        public function update($id, $table, $newValues) {
            return $this->handler_contact_master->update($id, $table, $newValues);
        }

        /**
         * Liste des parrains via le seller
         *
         * @param $id
         * @return SQL (array)
         */
        public function listGodfatherByCustomer($id){
            return $this->handler_contact_master->getListGodfatherByCustomer($id);
        }

        /**
         * Supprime les parrains
         *
         * @param id (int)
         * @return SQL (array)
         */
        public function deleteGodfather($id){
            return $this->handler_contact_master->deleteGodfatherById($id);
        }

        /**
         * verifie si le contact existe dans la base de données
         *
         * @param $inputName
         * @param $post
         * @return boolean
         */
        public function isContactExist($inputName, $post){
            return $this->handler_contact_master->isContactExist($inputName, $post);
        }

        /**
         * récupère le dernier id d'une table
         *
         * @return int
         */
        public function getLastId(){
            return $this->handler_contact_master->getLastId();
        }

        /**
         * récupère le contact par son external id
         *
         * @param $id
         * @return SQL (array)
         */
        public function getContactByExtID($id){
            return $this->handler_contact_master->getContactByExtID($id);
        }

        /**
         * requete de comptage
         *
         * @param $query
         * @return mixed
         */
        public function getCount($query){
            return $this->handler_contact_master->getCount($query);
        }

        /**
         * récupère les attribute dans la base contact_master
         *
         * @param $name
         * @return mixed
         */
        public function getAttributeByName($name){
            return $this->handler_contact_master->getAttributeByName($name);
        }


    }
?>