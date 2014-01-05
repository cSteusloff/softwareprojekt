<?php
/**
 * Project: ss
 * User: Christian Steusloff
 * Date: 19.12.13
 * Time: 14:36
 */

//namespace library\sqlValidator;
//
use lib\sqlConnection\sqlConnection;

class sqlValidator {

    //use library\sqlConnection;

    /**
     * @var sqlConnection
     */
    private $sqlConnection = null;

    /**
     * @param null $sqlConnection
     */
    public function setSqlConnection(sqlConnection $sqlConnection)
    {
        $this->sqlConnection = $sqlConnection;
    }

    /**
     * @return null
     */
    public function getSqlConnection()
    {
        return $this->sqlConnection;
    }

    /**
     * @var string - sql Query
     */
    private $master_solution;

    /**
     * @var string - sql Query
     */
    private $user_solution;

    /**
     * @var string - mistake message
     */
    private $mistake;

    /**
     * @param string $mistake
     */
    public function setMistake($mistake)
    {
        $this->mistake = $mistake;
    }

    /**
     * @return string
     */
    public function getMistake()
    {
        return $this->mistake;
    }

    /**
     * @param string $master_solution
     */
    public function setMasterSolution($master_solution)
    {
        $this->master_solution = $master_solution;
    }

    /**
     * @return string
     */
    public function getMasterSolution()
    {
        return $this->master_solution;
    }

    /**
     * @param string $user_solution
     */
    public function setUserSolution($user_solution)
    {
        $this->user_solution = $user_solution;
    }

    /**
     * @return string
     */
    public function getUserSolution()
    {
        return $this->user_solution;
    }

    /**
     * @param sqlConnection $sqlConnection
     */
    function __construct($sqlConnection)
    {
        // instanceof klappt nur auf Oracle nicht auf SQL-Allgemein
        //var_dump($sqlConnection instanceof \oracleConnection);

        $this->setSqlConnection($sqlConnection);
    }

    public function validate($master_solution,$user_solution){
        $this->setMasterSolution($master_solution);
        $this->setUserSolution($user_solution);
        $this->_validate();
    }

    public function __clone(){
        // TODO: Operationen beim klonen;
    }

    /**
     *
     */
    private function _validate(){

        var_dump($this->getSqlConnection());

        $master_con = clone $this->getSqlConnection();
        $user_con =  clone $this->getSqlConnection();
        $check_con = clone $this->getSqlConnection();

        // check same number of columns
        if($master_con->numColumns == $user_con->numColums){
            // check same number of rows
            if($master_con->numRows() == $user_con->numRows()){
                // master without ORDER BY
                if(strpos($master_con->sqlquery,"ORDER BY") === false){
                    // TODO: remove ORDER BY from user_con->sqlquery
                    // if is query output the same - MINUS return empty content
                    $check_con->Query($master_con->sqlquery." MINUS ".$user_con->sqlquery);
                    // must be zero
                    $emptyContentOneDirection = $check_con->numRows();
                    // String as HTML-Table with names of header
                    $headerOneDirection = $check_con->printTable();
                    $check_con->Query($user_con->sqlquery." MINUS ".$master_con->sqlquery);
                    // must be zero
                    $emptyContentOtherDirection = $check_con->numRows();
                    // String as HTML-Table with names of header
                    $headerOtherDirection = $check_con->printTable();
                    if($emptyContentOneDirection == 0 && $emptyContentOtherDirection == 0){
                        if(strcmp($headerOneDirection,$headerOtherDirection) == 0){
                            $this->setMistake("correct Solution");
                        } else {
                            $this->setMistake("incorrect Solution - names of header incorrect");
                        }
                    } else {
                        $this->setMistake("incorrect Solution - different content");
                    }
                }   else {
                    // TODO: exact Match
                }
            } else {
                $this->setMistake("incorrect Solution - number of data");
            }
        } else {
            $this->setMistake("incorrect Solution  - number of columns");
        }
        echo ($master_con->numColumns());
    }
}