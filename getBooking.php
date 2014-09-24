<?php

include_once 'lib/sessionLib.php';

setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

$sql = "SELECT B.*, H.titular, H.idpaises, H.email, O.checkIN, O.checkOUT ";
$sql .= "FROM booking B ";

$sql .= "LEFT JOIN huespedes H ";
$sql .= "ON B.idhuespedes = H.idhuespedes ";

$sql .= "LEFT JOIN occupation O ";
$sql .= "ON B.booking_id = O.booking_id ";

$sql .= "WHERE 1 ";
$sql .= "AND B.booking_id = " . htmlentities($_POST['booking_id']);

$result = resultFromQuery($sql); 

if ($row = siguienteResult($result)) {
	
	$data = ucwords(strftime("%d %B %Y", strtotime( $row->dataIN )));
	$data .= ' - ';
	$data .= ucwords(strftime("%d %B %Y", strtotime( $row->dataOUT )));
	
	$data = utf8_encode($data);

	if($row->checkOUT != NULL)
	{
		$status = 2;
	}
	elseif($row->checkIN != NULL)
	{
		$status = 1;
	}
	else{
		$status = 0;
	}

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
		'status' => $status,
		'idservicios' => $row->idservicios,
		'idresponsable' => $row->idresponsable,
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
