<?php
require_once("lib/dbUtils.php"); 
require_once("lib/excel.php"); 
require_once("lib/excel-ext.php"); 

$sql = "SELECT idmediapension, dataIN, dataOUT FROM  `_temp_liquidaciones_mp` ";
$resultado = resultFromQuery($sql);
while($datatmp = mysql_fetch_assoc($resultado)) { 
	$data[] = $datatmp; 
}  

createExcel("reporte.xls", $data);
exit;
?>
