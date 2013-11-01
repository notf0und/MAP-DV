<?php 
include "lib/sessionLib.php";

$idlocales = 1;

$resultado = crearTablesDeLocalesConInfo($idlocales);
echo $resultado;

	echo '<hr>';

/*
$sql = " SELECT * FROM locales where idlocales = ".$idlocales;
$resultado = resultFromQuery($sql);	

while ($row = mysql_fetch_object($resultado)) {
	$db_hostname = $row->db_hostname;
	$db_database = $row->db_database;
	$db_username = $row->db_username;
	$db_password = $row->db_password;
}
	echo '<hr>';

$dbConnection2 = &mysql_dbConnect($db_hostname, $db_database, $db_username, $db_password);
$sql = " SELECT * FROM agencias ";
$resultado = resultFromQuery($sql, $dbConnection2);	

while ($row = mysql_fetch_object($resultado)) {
	echo $row->nombre.'<BR>';
}
	echo '<hr>';
*/
?>
