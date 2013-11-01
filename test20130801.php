<?php 
include "lib/sessionLib.php";

//$precio = valordiaria($datadiaria, $idresponsablesDePago, $id, $idservicios, $idposadas);
//valordiaria($datadiaria, $idposadas, $idservicios);
//echo "Precio: ".$precio;

/*
$idlistasdeprecios = 1;
$precio = 88.00;
$idposadas_internas = 0;
guardarPrecio($idlistasdeprecios, $idservicios, $precio, $idposadas_internas);
*/
$datadiaria = date("Y-m-d");
$datadiaria = '2013-06-01';
$dataIN = '2013-07-01';
$dataOUT = '2013-07-31';
$idposadas = 4;
$idoperadoresturisticos = 2;
$idagencias = 10;
$idservicios = 1;
$idresponsablesDePago = 1;
$id = 1;

		$start = strtotime($dataIN);
		$end = strtotime($dataOUT.' -1 day');
		for ( $i = $start; $i <= $end; $i += 86400 ){

			$fechaActual = date("Y-m-d",$i);
			$data = $fechaActual;
			//$precio = valordiaria($fechaActual, $row->idposadas, $row->idservicios); // Version antigua
			$precio = valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas);
			
			echo $data.' : '.$precio.'<br></br>';
			$sql = " INSERT INTO _temp_liquidaciones_mp_cuentas (data, precio) VALUES (";
			$sql .= " '".$fechaActual."',";
			$sql .= " ".$precio."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	


		}

 ?>
