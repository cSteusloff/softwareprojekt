<?php
/**
 * Created by PhpStorm.
 * User: cSteusloff
 * Date: 09.12.13
 * Time: 21:51
 */
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">
    table.task{
        border-collapse:collapse;
        border:1px solid green;
        margin: 3px;
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

    #container{
        width: 500px;
        border: solid black 2px;
    }
</style>


<script src="masonry.pkgd.min.js"></script>

<?php
require_once '../define.inc.php';
require_once '../oracleconnection.class.php';

// Verbindung aufbauen
$db = new oracleConnection();

// SQL-Anfrage
$query = "SELECT * FROM personen ORDER BY ID";

?>

<div id="container" class="js-masonry"
     data-masonry-options='{ "columnWidth": 2, "itemSelector": ".task" }'>>

        <?php
        // Ausführen & Tabelle ausgeben
        $db->Query($query);
        echo($db->printTable("task"));
        ?>

        <?php
        // Ausführen & Tabelle ausgeben
        $db->Query("SELECT ID FROM PERSONEN WHERE ID=4");
        echo($db->printTable("task"));
        ?>

    <?php
    // Ausführen & Tabelle ausgeben
    $db->Query("SELECT ID,Nachname From PERSONEN WHERE ID=5");
    echo($db->printTable("task"));
    ?>

    <?php
    // Ausführen & Tabelle ausgeben
    $db->Query("SELECT Vorname,Nachname FROM Personen");
    echo($db->printTable("task"));
    ?>

    <?php
    // Ausführen & Tabelle ausgeben
    $db->Query("SELECT Nachname FROM Personen WHERE ID > 3");
    echo($db->printTable("task"));
    ?>

</div>






