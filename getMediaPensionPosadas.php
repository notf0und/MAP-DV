<?php

include_once 'lib/sessionLib.php';

$date = strtotime($_POST['date']);
$dateStart = date('Y-m-01', $date);
$dateEnd = date('Y-m-t', $date);

$sql = "SELECT selected_date, ";
$sql .= "COALESCE(P.nombre, 'Sem Especificar') Posada, ";
$sql .= "COALESCE(SUM(MP.qtdedepax * SS.ComidasDiarias), 0) comeran ";
$sql .= "FROM  (select adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date ";
$sql .= "FROM  (select 0 t0 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0, ";
$sql .= "(select 0 t1 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1, ";
$sql .= "(select 0 t2 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2, ";
$sql .= "(select 0 t3 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3, ";
$sql .= "(select 0 t4 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v ";

$sql .= "LEFT JOIN mediapension MP ";
$sql .= "ON selected_date BETWEEN MP.dataIN AND MP.dataOUT ";
$sql .= "LEFT JOIN servicios SS  ON MP.idservicios = SS.idservicios ";
$sql .= "LEFT JOIN posadas P  ON MP.idposadas = P.idposadas ";



$sql .= "WHERE 1 ";
$sql .= "AND MP.habilitado = 1 ";
$sql .= "AND MP.idResponsablesDePago = 2 ";
$sql .= "AND selected_date BETWEEN '".$dateStart."' AND '".$dateEnd."' ";
$sql .= "GROUP BY P.idposadas; ";


$result = resultFromQuery($sql); 




while ($fila = siguienteResult($result)) {
	$dump[] = array(
        'label' => $fila->Posada,
        'data' => (int)$fila->comeran
        );
}

        
echo json_encode($dump); 
 
?>
