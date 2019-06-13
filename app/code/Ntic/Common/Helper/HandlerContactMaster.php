<?php
namespace Ntic\Common\Helper;
use Magento\Framework\App\ObjectManager;

class HandlerContactMaster extends \Magento\Framework\App\Helper\AbstractHelper {
    private $pdo;
    private $test_database = false;

    //\Ntic\Sponsor\Helper\HandlerSponsor $handlerSponsor
    public function __construct() {
        // Connection to database
        if($this->test_database == true) {
            $this->pdo = new \PDO('mysql:dbname=contact_master_test;host=localhost', 'root', 'Kay8lWiBMQwu');
        } else {
            $this->pdo = new \PDO('mysql:dbname=contact_master;host=localhost', 'root', 'Kay8lWiBMQwu');
        }

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * execute query
     *
     * @param the query
     * @return the result
     */
    public function query($query) {
        return $this->pdo->query($query);
    }

    /**
     * Prepare query
     *
     * @param sql query
     * @param array of value
     */
    protected function prepare($query, $array) {
        $stmt = $this->pdo->prepare($query) or die(print_r($this->pdo->errorInfo()));
        $stmt->execute($array);
    }

    /**
     * Get the last id
     *
     * @return result
     */
    public function getLastId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * Get SQL list
     *
     * @param the query
     * @return the result list
     */
    public function getList($query) {
        $result = $this->query($query);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get one result
     *
     * @param the query
     * @return one result
     */
    public function getOne($query) {
        $result = $this->query($query);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Simple query with exec
     *
     * @param $query
     */
    public function queryExec($query) {
        $stmt = $this->query($query);
        $stmt->execute();
    }

    /**
     * Get count
     *
     *
     * @param the query
     * @return int
     */
    public function getCount($query) {
        $result = $this->query($query);
        return count($result->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Check if contact exist
     *
     * @param $inputName (str)
     * @param $post (str)
     * @return true / false
     */
    public function isContactExist($inputName, $post) {
        $count_contact = $this->getCount("
            SELECT ".$inputName."
            FROM contact 
            WHERE ".$inputName." = '".$post."'
        ");

        if($count_contact > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Insert on database
     *
     * @param $array
     */
    public function save($table, $array) {
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                // Implode on value
                $row = implode(', ', array_keys($value));
                // Implode on value, add : on each value (except the first one)
                $row_prepare = implode(', :', array_keys($value));

                $this->query = "
                    INSERT INTO " . $table . " (".$row.") VALUE (:".$row_prepare.")
                ";

                // VALUE FOR PREPARE
                foreach ($value as $key_value => $data_value) {
                    if(empty($data_value)){
                        $data_value = NULL;
                    }
                    $result[':' . $key_value] = $data_value;
                }
            }

            // SAVE
            $this->prepare($this->query, $result);
        }
    }

    /**
     * Met à jour les information d'un contact
     *
     * @param  id des nouvelles valeurs (int)
     * @param  nom de la table
     * @param  les données pour la mise à jour (array)
     * @return retour SQL (array)
     */
    public function update($id, $table, $newValues) {
        $this->query = " UPDATE " .$table. " SET ";
        $sql = "";

        foreach ($newValues as $key => $value) {
            foreach ($value as $row => $item) {
                $sql .= $row .' = ' . $item .', ';
            }
        }

        foreach ($id as $keyId => $valueId) {
            $where = $keyId .' = "' . $valueId.'"';
        }

        $sql = substr($sql, 0, strlen($sql) - 2);
        $this->query .= " (" . $sql . ") WHERE ".$where;

        $this->pdo->exec($this->query);
    }

    /**
     * Liste contact
     *
     * @param type_id
     * @return retour SQL (array)
     */
    public function getListContactByTypeId($type_id) {
        $contact_profile = $this->getList("
            SELECT contact_id
            FROM contact_profile
            WHERE type_id = ".$type_id."
        ");

        if(!empty($contact_profile)) {
            foreach ($contact_profile as $key => $contact) {
                $request_contact[] = $this->getOne("
                  SELECT contact.id, lastname, firstname, email, tel1, tel2, tel3, prefix, birthday, addr1, addr2, zip_code, country, city, contact_id   
                  FROM contact
                  LEFT JOIN contact_address ON contact_address.contact_id = contact.id 
                  WHERE contact.id = '".$contact['contact_id']."'
                ");
            }

            return $request_contact;
        } else {
            return 'Aucun contact avec cette source';
        }
    }

    /**
     * Cherche un contact par l'id
     *
     * @param id du contact (int)
     * @return retour SQL (array)
     */
    public function getUserById($id) {
        $contact = $this->getOne("
            SELECT contact.id, lastname, firstname, email, tel1, tel2, tel3, prefix, birthday, addr1, addr2, zip_code, country, city, contact_id   
            FROM contact
            LEFT JOIN contact_address ON contact_address.contact_id = contact.id 
            WHERE contact.id = '".$id."'
        ");

        return $contact;
    }

    /**
     * Get contact by ext id
     *
     * @param $ext_id
     * @return array
     */
    public function getContactByExtID($ext_id) {
        return $this->getOne("
                SELECT *
                FROM contact_profile
                INNER JOIN contact ON contact_profile.contact_id = contact.id 
                WHERE contact_profile.ext_id = '".$ext_id."'
            ");
    }

    /**
     * Cherche les utilisateurs de type "PARRAIN" qui appartient au seller
     *
     * @param valeur du champ recherche (autocomplete)
     */
    public function getListGodfather($search) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->getCollection();
        $portfolioCustomer = $objectManager->create('Ntic\PortfolioCustomer\Model\PortfolioCustomer')->getCollection();

        $list_special_seller = $portfolioCustomer
            ->addFieldToFilter('seller_id', ['eq' => $_SESSION['ntic_user']])
            ->join(
                array('c' => $customerObj->getMainTable()),
                'main_table.customer_id = c.'.$customerObj->getIdFieldName(),
                array('lastname'=>'c.lastname','firstname'=>'c.firstname')
            )
        ;

        // LISTE DES CLIENTS DES SELLER
        foreach ($list_special_seller as $customer) {
            $getUsers[] = $this->getOne("
                SELECT contact_profile.contact_id, lastname, firstname
                FROM contact_profile
                INNER JOIN contact ON contact.id = contact_profile.contact_id
                WHERE contact_profile.ext_id = ".$customer->getCustomerId()."
                AND (contact.lastname LIKE '".$search."%' OR contact.firstname LIKE '".$search."%')
            ");

            // TODO : rajouter le AND contact_profile.type_id = 4
            // Une fois les contacts de magento pousser dans contact_master
        }

        return $getUsers;
    }

    /**
     * Cherche les utilisateurs de type "CALENDAR" pour le jour actuelle
     */
    public function getListCalendar($id) {
        $current_date = date('Y-m-d');

        // Connection BDD calendar_ntic
        $this->pdo = new \PDO('mysql:dbname=calendar_ntic;host=localhost','calendar_ntic','v4rtuE4ZP8Ub');

        $events = $this->getList("
            SELECT event_id, event_name, start_date, type, contact, userId, isCustomer
            FROM events
            WHERE isCustomer = 0
            AND contact != 0
            AND userId = '".$id."'
        ");

        // TODO : rajouter ce code sur la requete après les tests
        // AND start_date LIKE '%".$current_date."%'

        return $events;
    }

    /**
     * Liste des parrains via le seller
     *
     * @param $id
     */
    public function getListGodfatherByCustomer($id) {
        $customers = $this->getList("
            SELECT *
            FROM contact_sponsor
            INNER JOIN contact ON contact.id = contact_sponsor.slave_contact_sponsor
            WHERE main_contact_sponsor = ".$id."
        ");

        return $customers;
    }

    /**
     * Supprime une personne parrainé
     *
     * @param $id
     */
    public function deleteGodfatherById($id){
        $this->queryExec("
            DELETE
            FROM contact
            WHERE id = ".$id."
        ");
    }

    /**
     * Get attribute id by name
     *
     * @param $name
     */
    public function getAttributeByName($name) {
        return $this->getList("
            SELECT *
            FROM attribute
            WHERE name LIKE '%".$name."%'
        ");
    }
    
}