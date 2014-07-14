<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];
?>
<html lang="en">
<head>
<title>Matrix Admin</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/colorpicker.css" />
<link rel="stylesheet" href="css/datepicker.css" />
<link rel="stylesheet" href="css/uniform.css" />
<link rel="stylesheet" href="css/select2.css" />
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
<!--main-container-part-->
<div id="content">
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span6">
			<div class="widget-box">
			<?php
				$sqlQuery = "SELECT MPA.data, MPA.qtdedepax, MP.numeroexterno, H.Titular, P.Nombre 'Posada', O.Nombre 'Operador', A.Nombre 'Agencia', R.Nombre 'Responsable', S.Nombre 'Servicio', MP.mensajegarcon FROM mediapension_admisiones MPA ";
				$sqlQuery .= " LEFT JOIN mediapension MP ON MPA.idmediapension = MP.idmediapension ";
				$sqlQuery .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
				$sqlQuery .= " LEFT JOIN servicios S ON MP.idservicios = S.idservicios ";
				$sqlQuery .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
				$sqlQuery .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
				$sqlQuery .= " LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ";
				$sqlQuery .= " LEFT JOIN responsablesDePago R ON MP.idresponsablesDePago = R.idresponsablesDePago ";
				$sqlQuery .= " WHERE MPA.id = ".$_GET["id"];
				$result = resultFromQuery($sqlQuery);
				if ($row = siguienteResult($result)) {
					$sqlticketid = " SELECT `idtickets` + 1 `idtickets` FROM `mediapension_tickets` WHERE `idlocales` = ".$_SESSION["idlocales"]." ORDER BY `idtickets` DESC LIMIT 1 ";
					$resultticketid = resultFromQuery($sqlticketid);
					$rowticketid = siguienteResult($resultticketid);
					$sql = "insert mediapension_tickets (idtickets,  idmediapension_admisiones, idlocales) values (";
					$sql .= "'".$rowticketid->idtickets."','".$_GET["id"]."','".$_SESSION["idlocales"]."') ";
					$resultadoStringSQL = resultFromQuery($sql);		
			?>
				<h1>RISTORANTE DA VINCI</h1>
				Data: <?php echo $rowticketid->idtickets;?></br>
				idtickets: <?php echo $row->data;?></br>
				Nome do Pax: <?php echo $row->Titular;?> X <?php echo $row->qtdedepax;?></br>
				Pousada: <?php echo $row->Posada;?></br>
				Operador: <?php echo $row->Operador;?></br>
				# de Voucher: <?php echo $row->numeroexterno;?></br></br>
				Mensaje garçon: <?php echo $row->mensajegarcon;?></br></br></br>

<?php

$localcfg = parse_ini_file("./local-config.ini", true);
$localcfg = $localcfg['config'];

$terminalname = $localcfg['terminal_name'];

function center($message){
	$len = (30 - strlen($message)) / 2;

	$msg = " ";
	for($i = 0; $i < $len; $i++){
		$msg .= " ";
	}
	$msg .= $message;
	
	if(is_float($len)){
		$len = $len -1;
	}
	
	for($i = 0; $i < $len; $i++){
		$msg .= " ";
	}	
	$msg .= " ";
	$msg .= "\012";
	return $msg;
	
}

function left($message){
	$len = 30 - strlen($message);

	$msg = "  ";
	$msg .= $message;

	for($i = 0; $i < $len; $i++){
		$msg .= " ";
	}	
	$msg .= "\012";
	return $msg;
}	

function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}


//Clears the data in the print buffer and resets the printer modes to the modes that were
//in effect when the power was turned on. 
$clearbuffer = "\x1b\x40";
$reset = hexToStr('1B 3F 0A 00');

$CR = "\012";

$str = '1D 76 30 00 28 00 91 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF FF FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF FF FF FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF FF FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF FF FF FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF FF FF FF FF FF FF FF E0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF FF FF FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF FF FF FF FF FF FF FF FF FF 9F 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF FF FF FF FF FF FF FF FF 8F C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF FF FF FF FF FF FF FF FF 0F E0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF FF FF FF FF FF FF FF FE 0F F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF FF FF FF FF FF FF FE 0F FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF FF FF FF FF FF FF FF FF FF FE 1F FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF FF FF FF FF FF FF FF FE 1F FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF FF FF FF FF FF FF FF FF FE 3F FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF E0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF EF FF FF FF FF FF FF FF FF 3F FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF C7 FF EF FF FF FF FF FF FE 3F 8F FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF 87 FF CF FF FF FF FF FF FC 3F 87 FF FF E0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF 83 FF 8F F8 FF FF FF FF F8 3F 03 FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF 83 FF 87 F0 FF FF FF FF F0 3F 03 EF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF 83 FF 87 E0 FF FF FF FF E0 FE 01 E7 FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF FF FF FF 83 FF 87 E0 FF FF FF FF E0 FE 00 C7 FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF FF FF FF 83 FF 87 E0 FF FF FF FF C1 FC 00 0F FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF FF FF 83 FF 87 E1 FF FC 7F FF 83 FC E0 0F FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF 81 FF 87 E3 FF F8 7F FF 83 F8 F0 1F FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF C1 FF 87 FF FF F0 3C 3F 07 F8 F8 3F FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF FF E0 FF 87 FF FF F0 38 1E 0F F1 FC FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF FF E0 FF 87 FF FF E0 38 0E 0F E3 FF FF FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF FF E0 FF 87 FF FF E0 30 0E 0F C3 FF FF FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF FF E0 FF 87 FF FF E0 20 00 0F 8F FF FF FF FF E0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF FF F0 7F 87 FF FF C0 23 00 02 0F FF FF FF FF E0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF FF F8 7F 87 FF FF 88 23 80 80 1F FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF FF F8 3F 8F F8 FF 88 47 C1 80 3F FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF F8 3F 8F F8 7F 98 0F E3 80 7F FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF FC 1F 8F F8 3F 18 0F FF E3 FF FF A7 FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F E0 00 FF FF FF FF FF FC 1F 8F F0 3E 38 0F FF FF FF FF BB FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF 80 00 1F FF FF FF FF FE 0F 8F E0 1E 38 1F FF FF FF FF A7 FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF 80 00 03 FF FF FF FF FE 0F 8F E0 0C 78 3F FF FF FF FF AF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF C0 00 01 FF FF FF FF FE 0F 8F C0 08 78 3F FF FF FF FF B7 FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF E0 00 00 FF FF FF FF FF 07 0F C6 00 F8 3F FF FF FF FF BA FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF 18 00 7F FF 9F FF FF 83 0F EF 01 FC 7F FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FE 1F 80 3F FE 1F FF FF 83 0F FF 83 FE FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FE 1F E0 3F FE 1F FF FF C0 1F FF 87 FF FF FF FF FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FC 1F F8 3F FC 3F FF FF E0 1F FF FF FC 01 FF FF FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FE 1F FC 1F F8 3F FF FF E0 1F FF FF E0 00 1F FF FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FE 1F FE 1F F8 7F FF FF F0 0F FF FF 80 00 07 FF FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FE 1F FE 1F F0 FF FF FF F8 1F FF FF 00 00 01 FF FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FE 1F FF 1F E0 EF FF FF FC 1F FF FE 00 00 00 7F FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FE 0F FF 1F E0 8F FF FF FC 3F FF FC 00 00 00 3F FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FE 0F FF 1F C1 87 FF FF F8 3F FF F8 00 00 00 1F FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FE 0F FF 1F 83 07 BF FF FC 7F FF F8 00 00 00 0F FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FE 0F FF 1F 82 03 3F FF FE FF FF F8 00 00 00 07 FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF 0F FE 1F 82 00 3F FF FF FF FF F0 00 00 00 03 FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF 0F FE 3F 04 00 3F FF FF FF FF F0 00 00 00 03 FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF 0F FE 3E 0C 40 7F FF FF FF FF F0 00 00 00 01 FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF 0F FE 3E 08 E0 FF FF FF FF FF F0 00 00 00 01 FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF 8F FE 3C 00 F1 FF FF FF FF FF F8 00 00 00 01 FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF 8F FC 78 01 FF FF FF FF FF FF F8 00 00 00 01 FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF 87 FC 60 03 FF FF FF FF FF FF F8 00 00 00 01 FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF 87 F8 C3 03 FF FF FF FF FF FF FC 00 00 00 01 FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF 87 F8 83 07 FF FF FF FF FF FF FC 00 00 00 01 FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF 87 F0 0F 0F FF FF FF FF FF FF FE 00 00 00 01 FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF C7 E0 7F 9F FF FF FF FF FF FF FF 00 00 00 01 FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF C7 E0 FF FF FF FF FF FF FF FF FF 80 00 00 03 FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF C7 C1 FF FF FF FF FF FF FF FF FF C0 00 00 03 FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF E7 83 FF FF FF FF FF FF FF FF FF F0 00 00 07 FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF E6 03 FF FF FF FF FF FF FF FF FF F8 00 00 0F FF FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF EE 0F FF FF FF FF FF FF FF FF FF FE 00 00 3F F0 01 FF FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FC 1F FF FF FF FF FF FF FF FF FF FF C0 00 FF 80 00 01 FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF F8 3F FF FF FF FF FF FF FF FF FF FF FE 1F FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF F8 FF FF FF FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF 83 FF FF FF FF FF FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF 81 FF FF FF FF FF FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF B8 EF FC FF FF FF FC FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF FF 9C CF E9 FF FF FF E9 FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF B9 9F E0 FF FF FF E0 FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF B3 FF F9 FF FE FF F9 9F FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF 8F FF 3B CE 7E FB FB 1F FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF 1F BE 23 86 39 E3 F3 3F FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF 8F 99 63 34 B2 E2 62 3F FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF A6 1B 52 23 A0 D0 12 7F FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF 23 86 31 23 02 23 31 0F FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF 38 CE 73 1F 27 E7 F3 BF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FE FE FF FF FF EF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 7F FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF FF FF FF FF FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF FF FF FF F8 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 07 FF FF FF FF FF FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 FF FF FF FF FF FF FF FF FF E0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF FF FF FF FF FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FF FF 80 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF FF FF 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 03 FF FF FF FF FF FF FC 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 3F FF FF FF FF FF F0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 01 FF FF FF FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 0F FF FF FF FE 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1F FF FF C0 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 1B 3F 0A 00';
$str = str_replace(' ', '', $str);
$logo = hexToStr($str);


$handle = fopen("/dev/lp0", "w");


fwrite($handle, $reset);
//First line

//Line 2
fwrite($handle, $logo);
//Line 3

//Line 4
$message = center($terminalname);
fwrite($handle, $message);
//Line 4

//Line 5
$message = "";
for ($i = 0; $i < 32; $i++){
	$message .= chr(205);
}
$message .= "\012";

fwrite($handle, $message);
//Line 5

//Line 6 (data)
$message = left("Data: ".date('H:i:s d/m/Y', strtotime($row->data)));
fwrite($handle, $message);

//Line 7 (Ticket ID)
$message = left("Ticket: ".$rowticketid->idtickets);
fwrite($handle, $message);

//Line 8 (line)
$message = "";
for ($i = 0; $i < 32; $i++){
	$message .= chr(205);
}
$message .= "\012";
fwrite($handle, $message);


//Line 9 (Nome do pax)
$message = left("Nome do Pax: ".$row->Titular);
fwrite($handle, $message);

//Line 10 (Qtde Pax)
$message = left("Qtde Pax: ".$row->qtdedepax);
fwrite($handle, $message);

//Line 11 (Numero de voucher)
$message = left("Voucher: ".$row->numeroexterno);
fwrite($handle, $message);

//Line 11 (Serviço)
$message = left("Servi".chr(135)."o: ".$row->Servicio);
fwrite($handle, $message);

//Line 12 (line)
$message = "";
for ($i = 0; $i < 32; $i++){
	$message .= chr(205);
}
$message .= "\012";
fwrite($handle, $message);

//Line 13 (Pousada)
$message = left("Pousada: ".$row->Posada);
fwrite($handle, $message);

//Line 14 (Operador)
$message = left("Operador: ".$row->Operador);
fwrite($handle, $message);

//Line 15 (Agencia)
$message = left("Agencia: ".$row->Agencia);
fwrite($handle, $message);

//Line 16 (Responsavel)
$message = left("Responsavel: ".$row->Responsable);
fwrite($handle, $message);

//Line 17(line)
$message = "";
for ($i = 0; $i < 32; $i++){
	$message .= chr(205);
}
$message .= "\012";
fwrite($handle, $message);

//Line 16 (Mesa)
$message = left("Mesa:");
fwrite($handle, $message);

//Line 17 (Garçom)
$message = left("Sequencia: ");
fwrite($handle, $message);

//Line 17 (Garçom)
$message = left("Gar".chr(135)."om: ");
fwrite($handle, $message);

//Line 18 (Messagem)
if($row->mensajegarcon != ''){
	$message = left("Messagem ao Gar".chr(135)."om: ".$row->mensajegarcon);
	fwrite($handle, $message);
}

//Line 19(line)
$message = chr(201);

for ($i = 0; $i < 30; $i++){
	$message .= chr(205);
}
$message .= chr(187);
$message .= "\012";
fwrite($handle, $message);

//Line 20
$message = "\x1b\x21\x16";
$message .= chr(186);
$message .= "Firma:                        ";
$message .= chr(186);
$message .= "\012";
fwrite($handle, $message);
fwrite($handle, $clearbuffer);

//Line 21(close line)
$message = chr(200);

for ($i = 0; $i < 30; $i++){
	$message .= chr(205);
}
$message .= chr(188);
$message .= "\012";
fwrite($handle, $message);


$message = "\012\012\012";
fwrite($handle, $message);



fclose($handle);

?>





			<?php
				}
			?>

			
				<button onclick="window.location.href='mediapension.lista.php'" class="btn btn-success">Cerrar</button>	
			</div>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
</body>
</HTML>
