<?php

echo("Oracle<br><br>");

$conn = oci_connect('test', 'test', 'cSteusloff-PC/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, 'SELECT * FROM personen');

?>

<style type="text/css">
#main {
  border-width:1px;
  border-style:solid;
  border-color:blue;
  width: 500px;
  height: 300px;
}
</style>

<?php

echo("<div id='main'>");

oci_execute($stid);

echo "<table border='1' style='float:left;margin:5px'>\n";
$ncols = oci_num_fields($stid);
echo "<tr>\n";
for($i = 1; $i <= $ncols; $i++){
	echo "<th>".oci_field_name($stid, $i)."</th>";
}
echo "</tr>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? $item : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

oci_execute($stid);
echo "<table border='1' style='float:left;margin:5px'>\n";
$ncols = oci_num_fields($stid);
echo "<tr>\n";
for($i = 1; $i <= $ncols; $i++){
	echo "<th>".oci_field_name($stid, $i)."</th>";
}
echo "</tr>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? $item : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";


echo("</div>");
?>
<br>
<table onclick="alert(this.offsetWidth)" border=1>
<tr><td>xxxx</td></tr>
<tr><td>xxxx</td></tr>
<tr><td>xxxx</td></tr>
</table>


<table style="width: 38px" border=1>
<tr><td>xxxx</td></tr>
<tr><td>xxxx</td></tr>
<tr><td>xxxx</td></tr>
</table>
