<?php

include_once 'lib/sessionLib.php';

$date = strtotime($_REQUEST['date']);
$dateStart = date('Y-m-01', $date);
$dateEnd = date('Y-m-t', $date);

$sql = "SELECT selected_date, ";
$sql .= "COALESCE(SUM(MP.qtdedepax * SS.ComidasDiarias), 0) comeran ";
$sql .= "FROM  (select adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date ";
$sql .= "FROM  (select 0 t0 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0, ";
$sql .= "(select 0 t1 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1, ";
$sql .= "(select 0 t2 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2, ";
$sql .= "(select 0 t3 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3, ";
$sql .= "(select 0 t4 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v ";

$sql .= "LEFT JOIN mediapension MP ";
$sql .= "ON selected_date BETWEEN MP.dataIN AND MP.dataOUT - interval 1 day ";
$sql .= "LEFT JOIN servicios SS  ON MP.idservicios = SS.idservicios ";
$sql .= "WHERE 1 ";
$sql .= "AND MP.habilitado = 1 ";
$sql .= "AND selected_date BETWEEN '".$dateStart."' AND '".$dateEnd."' ";
$sql .= "GROUP BY selected_date; ";


$result = resultFromQuery($sql); 

$dump = '[';
while ($fila = siguienteResult($result)) {
	$dump .= '['.date('j', strtotime($fila->selected_date)).', '.$fila->comeran.'],';
}

$dump = rtrim($dump, ',');
$dump .= ']';

echo $dump; 
 
?>
