<?php
/**
 * Project: ss
 * User: Christian Steusloff
 * Date: 14.12.13
 * Time: 19:50
 */

class taskHandler {

//    public function __construct() {
//
//    }


    public function getSelect(){
        $db = new oracleConnection();
        $db->Query("SELECT ID,TASKNAME FROM SYS_TASK ORDER BY TASKNAME");
        $select = array();
        while($db->Fetch()){
            $select[$db->row['ID']] = $db->row['TASKNAME'];
        }
        $db->closeConnection();
        return $select;
    }
} 