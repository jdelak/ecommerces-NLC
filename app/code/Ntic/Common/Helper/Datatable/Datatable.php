<?php
namespace Ntic\Common\Helper\Datatable;
/*
 * Script:    DataTables server-side script for PHP and MySQL
 * Copyright: 2012 - John Becker, Beckersoft, Inc.
 * Copyright: 2010 - Allan Jardine
 * License:   GPL v2 or BSD (3-point)
 */

class Datatable {

    private $_db;
    private $params;
    private $objectManager;
    private $resource;


    /**
     * Constructor
     *
     * @param (array) $params les POST et GET
     */
    public function __construct($params) {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->resource = $this->objectManager->get('Magento\Framework\App\ResourceConnection');
        $this->_db = $this->resource->getConnection();
        $this->params = $params;
    }


    public function get($table, $index_column, $columns) {
        // Paging
        $sLimit = "";
        if ( isset( $this->params['iDisplayStart'] ) && $this->params['iDisplayLength'] != '-1' ) {
            $sLimit = "LIMIT ".intval( $this->params['iDisplayStart'] ).", ".intval( $this->params['iDisplayLength'] );
        }

        // Ordering
        $sOrder = "";
        if ( isset( $this->params['iSortCol_0'] ) ) {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $this->params['iSortingCols'] ) ; $i++ ) {
                if ( $this->params[ 'bSortable_'.intval($this->params['iSortCol_'.$i]) ] == "true" ) {
                    $sortDir = (strcasecmp($this->params['sSortDir_'.$i], 'ASC') == 0) ? 'ASC' : 'DESC';
                    $sOrder .= "`".$columns[ intval( $this->params['iSortCol_'.$i] ) ]."` ". $sortDir .", ";
                }
            }

            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ) {
                $sOrder = "";
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if ( isset($this->params['sSearch']) && $this->params['sSearch'] != "" ) {
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($columns) ; $i++ ) {
                if ( isset($this->params['bSearchable_'.$i]) && $this->params['bSearchable_'.$i] == "true" ) {
                    $sWhere .= "`".$columns[$i]."` LIKE :search OR ";
                }
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }

        // Individual column filtering
        for ( $i=0 ; $i<count($columns) ; $i++ ) {
            if ( isset($this->params['bSearchable_'.$i]) && $this->params['bSearchable_'.$i] == "true" && $this->params['sSearch_'.$i] != '' ) {
                if ( $sWhere == "" ) {
                    $sWhere = "WHERE ";
                }
                else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`".$columns[$i]."` LIKE :search".$i." ";
            }
        }

        // SQL queries get data to display
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $columns))."` FROM `".$table."` ".$sWhere." ".$sOrder." ".$sLimit;
        $statement = $this->_db->prepare($sQuery);

        // Bind parameters
        if ( isset($this->params['sSearch']) && $this->params['sSearch'] != "" ) {
            $statement->bindValue(':search', '%'.$this->params['sSearch'].'%', \PDO::PARAM_STR);
        }
        for ( $i=0 ; $i<count($columns) ; $i++ ) {
            if ( isset($this->params['bSearchable_'.$i]) && $this->params['bSearchable_'.$i] == "true" && $this->params['sSearch_'.$i] != '' ) {
                $statement->bindValue(':search'.$i, '%'.$this->params['sSearch_'.$i].'%', PDO::PARAM_STR);
            }
        }
        $statement->execute();
        $rResult = $statement->fetchAll();

        $iFilteredTotal = current($this->_db->query('SELECT FOUND_ROWS()')->fetch());

        // Get total number of rows in table
        $sQuery = "SELECT COUNT(`".$index_column."`) FROM `".$table."`";
        $iTotal = current($this->_db->query($sQuery)->fetch());

        // Output
        $output = array(
            "sEcho" => intval($this->params['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        // Return array of values
        foreach($rResult as $aRow) {
            $row = array();
            for ( $i = 0; $i < count($columns); $i++ ) {
                if ( $columns[$i] == "version" ) {
                    // Special output formatting for 'version' column
                    $row[] = ($aRow[ $columns[$i] ]=="0") ? '-' : $aRow[ $columns[$i] ];
                }
                else if ( $columns[$i] != ' ' ) {
                    $row[] = $aRow[ $columns[$i] ];
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode( $output );
    }


}

/*
 * Alternatively, you may want to use the same class for several differnt tables for different pages.
 * By adding something similar to the following to your .htaccess file you can control this a little more...
 *
 * RewriteRule ^pagename/data/?$ data.php?_page=PAGENAME [L,NC,QSA]
 *

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_REQUEST['_page'])) {
        	if($_REQUEST['_page'] === 'PAGENAME') {
	            $table_data->get('table_name', 'index_column', array('column1', 'column2', 'columnN'));
	        }
        }
        break;
    default:
        header('HTTP/1.1 400 Bad Request');
}
*/
?>