<?php

include_once 'lib/sessionLib.php';

$date = strtotime($_POST['date']);
$dateStart = date('Y-m-01', $date);
$dateEnd = date('Y-m-t', $date);

$sql = "SELECT DATE(MPA.data) data, ";
$sql .= "COALESCE(SUM(MPA.qtdedepax), 0) consumos ";
$sql .= "FROM mediapension_admisiones MPA ";
$sql .= "WHERE 1 ";
$sql .= "AND MPA.habilitado = 1 ";
$sql .= "AND date(MPA.data) between '".$dateStart."' AND '".$dateEnd."' ";
$sql .= "GROUP BY DATE(MPA.data);";

$result = resultFromQuery($sql); 

$dump = '[';
while ($fila = siguienteResult($result)) {
	$dump .= '['.date('j', strtotime($fila->data)).', '.$fila->consumos.'],';
}

$dump = rtrim($dump, ',');
$dump .= ']';
echo $dump;


?>
