<?php
/**
 * Project: ss
 * User: Christian Steusloff
 * Date: 13.12.13
 * Time: 15:42
 */

require_once 'oracleConnection.class.php';

function validate($master_query,$user_query){
    $master = new oracleConnection();
    $user = new oracleConnection();

    $check = new oracleConnection();

    $master->Query($master_query);
    $user->Query($user_query);

    // check same number of columns
    if($master->numColumns() == $user->numColumns()){
        // check same number of rows
        if($master->numRows() == $user->numRows()){
            // query without ORDER BY
            if(strpos($master_query,"ORDER BY") === false
            //&& strpos($user_query,"ORDER BY") === false
            ){
                $check->Query($master_query." MINUS ".$user_query);
                $cnt1 = $check->numRows();
                $header1 = $check->printTable();
                $check->Query($user_query." MINUS ".$master_query);
                $cnt2 = $check->numRows();
                $header2 = $check->printTable();
                // check same content (no order)
//                var_dump($cnt1);
//                echo(" --- ");
//                var_dump($cnt2);
                if($cnt1 == $cnt2 && $cnt1 == 0){
                    // check column order and header names
                    if(strcmp($header1,$header2) == 0){
                        echo("correct Solution");
                    } else {
                        echo("incorrect Solution - headernames");
                    }
                } else {
                    echo("incorrect Solution -  different  content");
                }
            } else {
                // check same output
                if(strcmp($master->printTable(),$user->printTable()) == 0){
                    echo("correct Solution");
                } else {
                    echo("incorrect Solution - order");
                }
            }
        } else {
            echo("incorrect Solution - number of data");
        }
    } else {
        echo("incorrect Solution  - number of columns");
    }
}