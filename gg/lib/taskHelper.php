<?php
/**
 * Project: ss
 * User: Christian Steusloff
 * Date: 14.12.13
 * Time: 13:16
 */

require_once 'define.inc.php';

class taskHelper {

    /**
     * The permission on tables
     *
     * @var int - permission on tables
     */
    private $permission;

    /**
     *
     * Select = 1
     * Insert/Update/Delete = 2
     * Create = 3
     * Drop = 4
     *
     *
     * @param int (array) $permission
     */
    public function setPermission($permission)
    {
        if(is_array($permission)){
            $this->permission = array_sum($permission);
        } else {
            $this->permission = $permission;
        }
        return $this->getPermission();
    }

    /**
     * @return int
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @var string - task subject
     */
    private $topic;
    /**
     * @var string - task description
     */
    private $text;
    /**
     * @var string - sql query solution
     */
    private $solution;
    /**
     * @var string array - necessary tables
     */
    private $tables;

    private $table_ids;
    private $table_names;

    /**
     * @param mixed $table_ids
     */
    public function setTableIds($table_ids)
    {
        $this->table_ids = $table_ids;
    }

    /**
     * @return mixed
     */
    public function getTableIds()
    {
        return $this->table_ids;
    }

    /**
     * @param mixed $table_names
     */
    public function setTableNames($table_names)
    {
        $this->table_names = $table_names;
    }

    /**
     * @return mixed
     */
    public function getTableNames()
    {
        return $this->table_names;
    }


    private $task_id;

    /**
     * @param mixed $task_id
     */
    public function setTaskId($task_id)
    {
        $this->task_id = $task_id;
    }

    /**
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @param string $solution
     */
    public function setSolution($solution)
    {
        return $this->solution = $solution;
    }

    /**
     * @return string
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * @param string $tables
     */
    public function setTables($tables)
    {
        return $this->tables = $tables;
    }

    /**
     * @return string
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        return $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        return $this->topic = $topic;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $topic - subject from task
     * @param string $text - task description
     * @param string array $tables - names of usable tables
     * @param int array $permission - access rights of table
     * @param string $solution - SQL query
     */
    public function createTask($topic,$text,$tables,$permission,$solution){

        // set object-attributes
        $this->setTopic($topic);
        $this->setText($text);
        $this->setPermission($permission);
        $this->setSolution($solution);

        // create Task in SYS_TASK
        $db = new oracleConnection();
        $db->Query("INSERT INTO SYS_TASK (Taskname,tasktext,permission,solution)
                    VALUES ('".$this->getTopic()."',
                            '".$this->getText()."',
                            '".$this->getPermission()."',
                            '".$this->getSolution()."')");
        //var_dump($db->sqlquery);

        // get task-id from last new task (this current create task)
        $db->Query("SELECT MAX(ID) FROM SYS_TASK WHERE taskname = '".$this->getTopic()."'");
        $db->Fetch(false);
        $task_id = $db->row[0];

        // add necessary tables and get IDs
        $table_ids = $this->addDatabaseTables($tables);

        // create connection between SYS_TASK and SYS_TABLE
        $this->setNeedTables($task_id, $table_ids);

        // set object-attribute table-name and table-id
        $this->setDatabaseTables();

        $db->closeConnection();
    }

    public function __construct($task_id = null) {
        if(!is_null($task_id)){
            $this->loadTask($task_id);
        }
    }

    public function loadTask($task_id){
        $db = new oracleConnection();
        $db->Query("SELECT taskname,
                           tasktext,
                           permission,
                           solution
                    FROM SYS_TASK WHERE ID = {$task_id}");
        $db->sqlquery;
        $db->Fetch();
        $this->setTopic($db->row['TASKNAME']);
        $this->setText($db->row['TASKTEXT']);
        $this->setPermission($db->row['PERMISSION']);
        $this->setSolution($db->row['SOLUTION']);
        $this->setTaskId($task_id);
        $this->setDatabaseTables();
    }




    public function getPermissionSelect(){
        $select_array = array(1,3,5,7,9,11,13,15);
        return in_array($this->getPermission(),$select_array);
    }
    public function getPermissionModify(){
        $mod_array = array(2,3,6,7,10,11,14,15);
        return in_array($this->getPermission(),$mod_array);
    }
    public function getPermissionCreate(){
        $create_array = array(4,5,6,7,12,13,14,15);
        return in_array($this->getPermission(),$create_array);
    }
    public function getPermissionDrop(){
        $drop_array = array(8,9,10,11,12,13,14,15);
        return in_array($this->getPermission(),$drop_array);
    }


    /**
     * Tablenames are unique, insert via merge if it's in this table
     *
     * @param string array - names of tables
     * @return int array - Table IDs
     */
    private function addDatabaseTables($table_array){

        echo("Tabllen:");
        var_dump($table_array);

        $db = new oracleConnection();
        $insert_query = "MERGE INTO SYS_TABLES tab USING (";
        foreach($table_array as $table){
            $insert[] = "SELECT '".$table."' as name FROM DUAL";
        }
        $insert_query .= implode(" UNION ALL ",$insert);
        $insert_query .= ") src ON (src.name = tab.name) WHEN NOT MATCHED THEN INSERT(name) VALUES (src.name)";

        $db->Query($insert_query);
        $db->closeConnection();
        return $this->getDatabaseTablesByTableNames($table_array);
    }


    /**
     * @param string array $table_names
     * @return int array - Table IDs
     */
    private function getDatabaseTablesByTableNames($table_names){
        foreach($table_names as $table){
            $search[] = "name = '".$table."'";
        }

        $id_result = array();
        $db = new oracleConnection();
        $db->Query("SELECT ID FROM SYS_TABLES WHERE ".implode(" OR ",$search));
        while($db->Fetch()){
            //var_dump($db->row);
            $id_result[] = $db->row['ID'];
        }
        $db->closeConnection();
        return $id_result;
    }

//    /**
//     *
//     * @return int array - Table IDs
//     */
//    private function getDatabaseTables(){
//        $id_result = array();
//        $name_result = array();
//        $db = new oracleConnection();
//        $db->Query("SELECT n.table_id,t.name FROM SYS_NEEDTABLES n,SYS_TABLES t ON n.table_id = t.ID WHERE task_id = ".$this->getTaskId());
//        while($db->Fetch()){
//            var_dump($db->row);
//            $id_result[] = $db->row['table_id'];
//            $name_result[] = $db->row['name'];
//        }
//        $this->setTables($name_result);
//        $db->closeConnection();
//        return $id_result;
//    }

    private function setDatabaseTables(){
        $tablename_array = array();
        $tableid_array = array();
        $db = new oracleConnection();
        $db->Query("SELECT n.table_id, t.name
                    FROM SYS_NEEDTABLES n,
                    SYS_TABLES t
                    WHERE n.table_id = t.ID
                    AND task_id = ".$this->getTaskId());
        while($db->Fetch()){
            //var_dump($db->row);
            $tablename_array[] = $db->row['NAME'];
            $tableid_array[] = $db->row['TABLE_ID'];
        }

        $this->setTableNames($tablename_array);
        $this->setTableIds($tableid_array);
        $db->closeConnection();

    }


    private function setNeedTables($task_id,$table_ids){
        $db = new oracleConnection();
        $insert_query = "INSERT ALL ";
        foreach($table_ids as $id){
            $insert_query .= "into SYS_NEEDTABLES(task_id,table_id) VALUES ('".$task_id."','".$id."') ";
        }
        $insert_query .= "SELECT * FROM DUAL";
        $db->Query($insert_query);
        $db->closeConnection();
    }

    public function printTable($classname = null){
        if(is_null($classname)){
            $classname = "defaultTableClassName";
        }
        $db = new oracleConnection();

        $tablestr = "";
        foreach($this->getTableNames() as $table){
            $db->Query("SELECT * FROM {$table}");
            $tablestr .= $db->printTable($classname,substr(strtoupper($table),strlen(ADMIN_TAB_PREFIX)));
        }
        return $tablestr;
    }
} 