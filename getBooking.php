<?php

include_once 'lib/sessionLib.php';

setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

$sql = "SELECT B.*, H.titular, H.idpaises, H.email ";
$sql .= "FROM booking B ";
$sql .= "LEFT JOIN huespedes H ";
$sql .= "ON B.idhuespedes = H.idhuespedes ";
$sql .= "WHERE 1 ";
$sql .= "AND booking_id = " . htmlentities($_POST['booking_id']);

$result = resultFromQuery($sql); 


if ($row = siguienteResult($result)) {
	
	$data = ucwords(strftime("%d %B %Y", strtotime( $row->dataIN )));
	$data .= ' - ';
	$data .= ucwords(strftime("%d %B %Y", strtotime( $row->dataOUT )));
	
	$data = utf8_encode($data);
	
	$booking[] = array(
		'booking_id' => $row->booking_id,
		'room_id' => $row->room_id,
		
		'idhuespedes' => $row->idhuespedes,
		'name' => $row->titular,
		'idpaises' => $row->idpaises,
		'email' => $row->email,
		'label' => $row->label,
		'quantity' => $row->quantity,
		'data' => $data,
		'idservicios' => $row->idservicios,
		'idoperadoresturisticos' => $row->idoperadoresturisticos,
		'idresponsablesDepago' => $row->idresponsablesDepago,
		'price' => $row->price,
		'pay' => $row->pay,
		'currency' => $row->idcurrency,
		'note' => $row->note,
		'color' => $row->color
		);
}


echo json_encode($booking); 
 
?>
