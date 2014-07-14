<?php 
include_once 'lib/dbUtils.php';

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

$sql = "CREATE TEMPORARY TABLE temp_table_1 (";
$sql .= "dte DATE NOT NULL)";

$result=resultFromQuery($sql);

//Obtener lista de fechas



$sql = "INSERT INTO temp_table_1(dte) ";
$sql .= "(SELECT * FROM ( SELECT mediapension.dataIN + INTERVAL a + b DAY dte FROM (SELECT 0 a UNION SELECT 1 a UNION SELECT 2 UNION SELECT 3     UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7     UNION SELECT 8 UNION SELECT 9 ) d,  (SELECT 0 b UNION SELECT 10 UNION SELECT 20      UNION SELECT 30 UNION SELECT 40) m, mediapension WHERE mediapension.dataIN + INTERVAL a + b DAY  <=  mediapension.dataOUT ORDER BY a + b ) e GROUP BY dte)";

$result=resultFromQuery($sql);

$sql = "SELECT * FROM temp_table_1 WHERE date(dte) <= curdate() - interval 1 day;";

$result=resultFromQuery($sql);

/*


*/

while ($fecha=mysql_fetch_array($result, MYSQL_ASSOC)){
	
	//comeranentotal
	$sql = "SELECT COALESCE(SUM(MP.qtdedepax * SS.ComidasDiarias), 0) 'deberiancomer' ";
	$sql .= "FROM   mediapension MP ";
	$sql .= "LEFT JOIN servicios SS on MP.idservicios = SS.idservicios ";
	$sql .= "WHERE 1 ";
	$sql .= "AND ('".$fecha['dte']."' BETWEEN MP.dataIN AND MP.dataOUT) ";
	$sql .= "AND MP.habilitado = 1;";
	
	$waiting =resultFromQuery($sql);
	
	while ($deberiancomer=mysql_fetch_array($waiting, MYSQL_ASSOC)){
		
		//comieronentotal
		$sql = "SELECT COALESCE(SUM(MPA.qtdedepax), 0) 'Comieron' ";
		$sql .= "FROM mediapension_admisiones MPA ";
		$sql .= "LEFT JOIN mediapension MP ON MPA.idmediapension = MP.idmediapension ";
		$sql .= "WHERE 1 ";
		$sql .= "AND date(MPA.data) = '".$fecha['dte']."' ";
		$sql .= "AND MP.habilitado = 1;";
		
		$sqlResult = resultFromQuery($sql);
		
		while ($comieron=mysql_fetch_array($sqlResult, MYSQL_ASSOC)){
		
		$consumosrestaurantes[] = array(
									'Fecha' => $fecha['dte'],
									'Total' => $deberiancomer['deberiancomer'],
									'Comieron' => $comieron['Comieron']);
		}
	}	
}

return json_encode($consumosrestaurantes);

?>

