<?php
/**
 * User: Christian Steusloff
 * Date: 27.11.13
 * Time: 19:26
 */

require_once 'define.inc.php';
require_once 'sqlConnection.class.php';

class oracleConnection extends sqlConnection {

    /**
     * @var oci-object - error massage
     */
    private $errortext;

    /**
     * @return string
     */
    public function getErrortext()
    {
        return $this->errortext['message'];
    }

    /**
     * @param string $host
     * @param string $database
     * @param string $user
     * @param string $password
     * @param string $port
     */
    public function __construct($host =DB_HOST, $database = DB_DATABASE, $user = DB_USER, $password = DB_PASSWORD, $port = DB_PORT) {
        $this->hostname = $host;
        $this->database = $database;
        $this->username = $user;
        $this->password = $password;
        $this->port = $port;
        $this->openConnection();
    }

    /**
     * Open the connection
     *
     * @return boolean
     */
    public function openConnection()
    {
        try{
            //var_dump($this->username,$this->password,$this->hostname.'/'.$this->database.':'.$this->port,DB_CHARSET);
            $this->conn = oci_connect($this->username,$this->password,$this->hostname.'/'.$this->database.':'.$this->port,DB_CHARSET);
            if(!$this->conn){
                $e = oci_error();
                // TODO Ausgabe
            }
        } catch(Exception $e){
            // TODO Ausgabe
        }
    }

    /**
     * Try to running SQL query
     *
     * @param string $sql SQL Query
     * @return mixed
     */
    public function Query($sql = '')
    {
        try{
            if(is_resource($this->recordset)){
                // Frees resources associated with Oracle's cursor or statement
                oci_free_statement($this->recordset);
            }
            $this->sqlquery = empty($sql) ? $this->sqlquery : $sql;
            $this->recordset = oci_parse($this->conn,$this->sqlquery);
            oci_execute($this->recordset);
            $this->errortext = oci_error($this->recordset);
        } catch(Exception $e){
            // TODO Ausgabe
        }
    }

    /**
     * Returns an (associative) array with the following record
     *
     * @param bool $assoc
     * @return bool
     */
    public function Fetch($assoc = true)
    {
        if(!empty($this->recordset)){
            try{
                if($assoc == true){
                    $this->row = @oci_fetch_assoc($this->recordset);
                } else {
                    $this->row = @oci_fetch_array($this->recordset);
                }
                return is_array($this->row);
            } catch(Exception $e){
                // TODO Ausgabe
            }
        } else {
            return false;
        }
    }

    /**
     * Returns the number of affected Rows by SQL query
     *
     * @return int
     */
    public function affectedRows()
    {
        if(!empty($this->recordset)){
            return oci_num_rows($this->conn);
        } else {
            return 0;
        }
    }

    /**
     * Returns the number of columns by SQL query
     *
     * @return int
     */
    public function numColumns()
    {
        if(!empty($this->recordset)){
            return oci_num_fields($this->recordset);
        } else {
            return 0;
        }
    }

    /**
     * Returns the number of rows by SQL query
     *
     * @return int
     */
    public function numRows()
    {
        $numRows = 0;
        if(!empty($this->recordset)){
            $this->getStatementType();
            if(strcasecmp($this->getStatementType(),"SELECT") == 0){
                $tmp = $this->sqlquery;
                $this->Query('SELECT COUNT(*) AS NUM FROM ('.$this->sqlquery.')');
                $this->Fetch();
                $numRows = $this->row["NUM"];
                $this->Query($tmp);
            } else {
                return $this->affectedRows();
            }
        }

        return $numRows;
    }

    /**
     * Returns the type of SQL query statement
     *
     * @return string
     */
    public function getStatementType(){
        if(!empty($this->recordset)){
            return oci_statement_type($this->recordset);
        } else {
            return "unknown";
        }

    }


    /**
     * Destroy
     */
    public function __destruct(){
        try{
            $this->closeConnection();
        } catch(Exception $e) {
            // TODO Ausgabe
        }
    }

    /**
     * Close the connection
     *
     * @return boolean
     */
    public function closeConnection()
    {
        // get database type
        $type = (is_resource($this->conn) ? get_resource_type($this->conn) : 'unknown');
        if(strstr($type,"oci8")){
            oci_close($this->conn);
        } else {
            // TODO Ausgabe
        }
    }

    /**
     * Returns info of connection
     *
     * @return string
     */
    public function connectionInfo()
    {
        return $this->username.'@'.$this->database.':'.$this->hostname;
    }

    /**
     * Returns the columnname
     *
     * @param $number
     * @return string
     */
    public function getFieldname($num){
        if(!empty($this->recordset)){
            return oci_field_name($this->recordset,$num);
        } else {
            return '';
        }
    }

    /**
     * Returns a complete table
     *
     * @param string $classname - css class from table
     * @return string
     */
    public function printTable($classname = null,$tablename = null){
        if(!empty($this->recordset)){
            $tablestr = "<div ";
            if(is_null($classname)){
            $tablestr .= ">\n";
            } else {
                $tablestr .= "class='".$classname."'>\n";
            }
            if(!is_null($tablename)){
                $tablestr .= "<span>{$tablename}</span>";
            }
            $tablestr .= "<table>\n";
            $tablestr .= "<thead><tr>\n";

            for($i=1;$i <= $this->numColumns();$i++){
                $tablestr .= "<th>";
                $tablestr .= ucfirst(strtolower($this->getFieldname($i)));
                $tablestr .= "</th>";
            }
            $tablestr .= "</tr></thead><tbody>\n";
            while($this->Fetch()){
                $tablestr .= "<tr>\n";
                foreach($this->row as $item){
                    $tablestr .=  "<td>".($item !== null ? $item : "")."</td>\n";
                }
                $tablestr .= "</tr>\n";
            }
            $tablestr .= "<tbody></table>\n";

            $tablestr .= "</div>";
            return $tablestr;
        } else {
            return "";
        }
    }
}