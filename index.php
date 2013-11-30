<?php
/**
 * User: Christian Steusloff
 * Date: 27.11.13
 * Time: 20:58
 */
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style type="text/css">
    table.task{
        border-collapse:collapse;
        border:1px solid green;
    }

    table.task th{
        background-color:green;
        color:white;
        border: 1px solid black;
    }

    table.task td {
        border: 1px solid black;
    }

    table.task tr:nth-child(odd) {
        background-color: cornsilk;
    }
    table.task tr:nth-child(even) {
        background-color: aquamarine;
    }

</style>

<?php 
require_once 'define.inc.php';
require_once 'oracleconnection.class.php';
require_once 'SqlFormatter.php';

// Verbindung aufbauen
$db = new oracleConnection();

// SQL-Anfrage
$query = "SELECT ID,Nachname,Vorname,Jahr FROM personen ORDER BY ID";
$query2 = "SELECT ID,Nachname,Vorname,Jahr FROM personen WHERE Jahr > '25/01/2011'";


// Anzeigen
echo(SqlFormatter::format($query));

// Ausführen
$db->Query($query);

// Tabelle ausgeben
echo($db->printTable("task"));
echo("Datensätze: ".$db->numRows()."<br>");

// Anzeigen
echo(SqlFormatter::format($query2));
// Ausführen
$db->Query($query2);
// Tabelle ausgeben
echo($db->printTable("task"));
echo("Datensätze: ".$db->numRows()."<br>");

echo("<p><b>");
validate($query,$query2);
echo("</b></p>");

// Gleichheit testen
// Ausführen


// only SELECT
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
            if(strpos($master_query,"ORDER BY") === false){
                $check->Query($master_query." MINUS ".$user_query);
                $cnt1 = $check->numRows();
                $header1 = $check->printTable();
                $check->Query($user_query." MINUS ".$master_query);
                $cnt2 = $check->numRows();
                $header2 = $check->printTable();
                if($cnt1 == $cnt2 && $cnt1 == 0){
                    if(strcmp($header1,$header2) == 0){
                        echo("correct Solution");
                    } else {
                        echo("incorrect Solution - headernames");
                    }
                } else {
                    echo("incorrect Solution -  different  content");
                }
            } else {
                // TODO Sortierung
            }
        } else {
            echo("incorrect Solution - number of data");
        }
    } else {
        echo("incorrect Solution  - number of columns");
    }

}

// Zugriff anzeigen
echo("<br>Zugriff: ".$db->connectionInfo());

// Verbindung beenden
$db->closeConnection();
