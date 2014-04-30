<?php

// Reportes de liquidaciones

function crearTablesLiquidacionesMP(){

	/*
		Tablas temporales utilizadas para la cracion de los reportes de liquidaciones.
	*/

	$sql = " CREATE TABLE  `_temp_liquidaciones_mp` ( ";
	$sql .= " `id` INT( 11 ) NOT NULL AUTO_INCREMENT , ";
	$sql .= " `idmediapension` INT( 11 ) NOT NULL , ";
	$sql .= " `Titular` VARCHAR( 255 ) NOT NULL , ";
	$sql .= " `Q` INT( 11 ) NOT NULL , ";
	$sql .= " `Agencia` VARCHAR( 100 ) NOT NULL , ";
	$sql .= " `Posada` VARCHAR( 100 ) NOT NULL , ";
	$sql .= " `DataIN` DATE NOT NULL , ";
	$sql .= " `DataOUT` DATE NOT NULL , ";
	$sql .= " `numeroexterno` VARCHAR( 20 ) NOT NULL , ";
	$sql .= " `N` INT( 11 ) NOT NULL , ";
	$sql .= " `M` INT( 11 ) NOT NULL , ";
	$sql .= " `Servicio` VARCHAR( 100 ) NOT NULL , ";
	$sql .= " `USD` DECIMAL( 6, 2 ) NOT NULL , ";
	$sql .= " `Tarifa` DECIMAL( 6, 2 ) NOT NULL , ";
	$sql .= " PRIMARY KEY (  `id` ) ";
	$sql .= " ) ENGINE = INNODB DEFAULT CHARSET = latin1;	 ";
	$resultado = resultFromQuery($sql);	

	$sql = " CREATE TABLE  `_temp_liquidaciones_mp_cuentas` ( ";
	$sql .= " `id` INT( 11 ) NOT NULL AUTO_INCREMENT , ";
	$sql .= " `data` DATETIME NOT NULL , ";
	$sql .= " `precio` DECIMAL( 6, 2 ) NOT NULL , ";
	$sql .= " PRIMARY KEY (  `id` ) ";
	$sql .= " ) ENGINE = INNODB DEFAULT CHARSET = latin1;	 ";
	$resultado = resultFromQuery($sql);	
	
}

function crearTablesLiquidacionesHTL(){

	/*
		Tablas temporales utilizadas para la cracion de los reportes de liquidaciones.
	*/

	$sql = " CREATE TABLE  `_temp_liquidaciones_htl` ( ";
	$sql .= " `id` INT( 11 ) NOT NULL AUTO_INCREMENT , ";
	$sql .= " `idhoteleria` INT( 11 ) NOT NULL , ";
	$sql .= " `Titular` VARCHAR( 255 ) NOT NULL , ";
	$sql .= " `Q` INT( 11 ) NOT NULL , ";
	$sql .= " `Agencia` VARCHAR( 100 ) NOT NULL , ";
	$sql .= " `Posada` VARCHAR( 100 ) NOT NULL , ";
	$sql .= " `DataIN` DATE NOT NULL , ";
	$sql .= " `DataOUT` DATE NOT NULL , ";
	$sql .= " `numeroexterno` VARCHAR( 20 ) NOT NULL , ";
	$sql .= " `N` INT( 11 ) NOT NULL , ";
	$sql .= " `M` INT( 11 ) NOT NULL , ";
	$sql .= " `Servicio` VARCHAR( 100 ) NOT NULL , ";
	$sql .= " `USD` DECIMAL( 6, 2 ) NOT NULL , ";
	$sql .= " `Tarifa` DECIMAL( 6, 2 ) NOT NULL , ";
	$sql .= " PRIMARY KEY (  `id` ) ";
	$sql .= " ) ENGINE = INNODB DEFAULT CHARSET = latin1;	 ";
	$resultado = resultFromQuery($sql);	

	$sql = " CREATE TABLE  `_temp_liquidaciones_htl_cuentas` ( ";
	$sql .= " `id` INT( 11 ) NOT NULL AUTO_INCREMENT , ";
	$sql .= " `data` DATETIME NOT NULL , ";
	$sql .= " `precio` DECIMAL( 6, 2 ) NOT NULL , ";
	$sql .= " PRIMARY KEY (  `id` ) ";
	$sql .= " ) ENGINE = INNODB DEFAULT CHARSET = latin1;	 ";
	$resultado = resultFromQuery($sql);	
	
}

function crearTablesLiquidacionesFINAL(){

	/*
		Tablas temporales utilizadas para la cracion de los reportes de liquidaciones.
	*/

	$sql = " CREATE TABLE  `_temp_liquidaciones_final` ( ";
	$sql .= " `id` INT( 11 ) NOT NULL AUTO_INCREMENT , ";
	$sql .= " `iditem` VARCHAR( 11 )  , ";
	$sql .= " `Titular` VARCHAR( 255 ) , ";
	$sql .= " `Q` VARCHAR( 11 ) , ";
	$sql .= " `Agencia` VARCHAR( 100 ) , ";
	$sql .= " `Posada` VARCHAR( 100 ) , ";
	$sql .= " `DataIN` VARCHAR( 20 ) , ";
	$sql .= " `DataOUT` VARCHAR( 20 ) , ";
	$sql .= " `numeroexterno` VARCHAR( 20 ) , ";
	$sql .= " `N` VARCHAR( 11 ) , ";
	$sql .= " `M` VARCHAR( 11 ) , ";
	$sql .= " `Servicio` VARCHAR( 100 ) , ";
	$sql .= " `USD` VARCHAR( 11 ) , ";
	$sql .= " `Tarifa` VARCHAR( 11 ) , ";
	$sql .= " PRIMARY KEY (  `id` ) ";
	$sql .= " ) ENGINE = INNODB DEFAULT CHARSET = latin1;	 ";
	$resultado = resultFromQuery($sql);	

}

function eliminarTablesLiquidacionesMP(){
	$sql = " DROP TABLE IF EXISTS `_temp_liquidaciones_mp` ";
	$resultado = resultFromQuery($sql);	

	$sql = " DROP TABLE IF EXISTS `_temp_liquidaciones_mp_cuentas` ";
	$resultado = resultFromQuery($sql);	
}

function eliminarTablesLiquidacionesHTL(){
	$sql = " DROP TABLE IF EXISTS `_temp_liquidaciones_htl` ";
	$resultado = resultFromQuery($sql);	

	$sql = " DROP TABLE IF EXISTS `_temp_liquidaciones_htl_cuentas` ";
	$resultado = resultFromQuery($sql);	
}

function eliminarTablesLiquidacionesFINAL(){
	$sql = " DROP TABLE IF EXISTS `_temp_liquidaciones_final` ";
	$resultado = resultFromQuery($sql);	
}

function unionTablesLiquidacionesFINAL(){

	// Mediapension
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) (select idmediapension, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa from _temp_liquidaciones_mp); ";
	$resultado = resultFromQuery($sql);		

	// Espacios.
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('','','','','','','','','','','','',''); ";
	$resultado = resultFromQuery($sql);		
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('','','','','','','','','','','','',''); ";
	$resultado = resultFromQuery($sql);		

	// Total Mediapension
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('.','','','','','','','','','','TOTAL MP',(SELECT SUM(USD) FROM `_temp_liquidaciones_mp` WHERE 1),''); ";
	$resultado = resultFromQuery($sql);		

	// Espacios.
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('','','','','','','','','','','','',''); ";
	$resultado = resultFromQuery($sql);		
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('','','','','','','','','','','','',''); ";
	$resultado = resultFromQuery($sql);		
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('','','','','','','','','','','','',''); ";
	$resultado = resultFromQuery($sql);		


	// Hoteleria
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) (select idhoteleria, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa from _temp_liquidaciones_htl); ";
	$resultado = resultFromQuery($sql);		
	
	// Espacios.
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('','','','','','','','','','','','',''); ";
	$resultado = resultFromQuery($sql);		
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('','','','','','','','','','','','',''); ";
	$resultado = resultFromQuery($sql);		

	// Total Hoteleria
	$sql = " insert into _temp_liquidaciones_final (iditem, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) values ('.','','','','','','','','','','TOTAL HTL',(SELECT SUM(USD) FROM `_temp_liquidaciones_htl` WHERE 1),''); ";
	$resultado = resultFromQuery($sql);		

}

function liquidacionCrear($ID, $idresponsablesDepago, $titulo){
	// Crear liquidacion y obtener id para luego modificar las mediapension que estan vinculadas con esta liquidacion.
	$sqlInsertMP = " INSERT INTO liquidaciones (fecha, responsable, idresponsablesDepago, idestados, importeMP, importeHTL, titulo) ";
	$sqlInsertMP .= " VALUES (";
	$sqlInsertMP .= " CURDATE(),";
	$sqlInsertMP .= " ".$ID.",";
	$sqlInsertMP .= " ".$idresponsablesDepago.",";
	$sqlInsertMP .= " '0',";
	$sqlInsertMP .= " (SELECT SUM(USD) FROM `_temp_liquidaciones_mp` WHERE 1), ";
	$sqlInsertMP .= " (SELECT SUM(USD) FROM `_temp_liquidaciones_htl` WHERE 1), ";
	$sqlInsertMP .= " '".$titulo."'";
	$sqlInsertMP .= " );";
	$resultadoInsertMP = resultFromQuery($sqlInsertMP);		
	$idliquidaciones = mysql_insert_id();

	// ?Porque no funciona esto? UPDATE mediapension SET idliquidaciones = 1 WHERE idmediapension IN (SELECT GROUP_CONCAT( `idmediapension` ORDER BY `idmediapension` ASC SEPARATOR ',' ) `idSmediapensionES` FROM _temp_liquidaciones_mp);

	// Asigno ultimo ID de Liquidaciones en Tabla de Mediapension.
	$sqlLiquidacionMP = " SELECT GROUP_CONCAT( `idmediapension` ORDER BY `idmediapension` ASC SEPARATOR ',' ) `idSmediapensionES` FROM _temp_liquidaciones_mp; ";
	$resultadoLiquidacionMP = resultFromQuery($sqlLiquidacionMP);
	if ($rowLiquidacionMP = siguienteResult($resultadoLiquidacionMP)){
		if ($rowLiquidacionMP->idSmediapensionES){
			$updateMP = " UPDATE mediapension SET idliquidaciones = ".$idliquidaciones." WHERE idmediapension IN (".$rowLiquidacionMP->idSmediapensionES.");";
			$resultadoUpdateMP = resultFromQuery($updateMP);
		}
	}

	// Asigno ultimo ID de Liquidaciones en Tabla de Hoteleria.
	$sqlLiquidacionHTL = " SELECT GROUP_CONCAT( `idhoteleria` ORDER BY `idhoteleria` ASC SEPARATOR ',' ) `idShoteleriaES` FROM _temp_liquidaciones_htl; ";
	$resultadoLiquidacionHTL = resultFromQuery($sqlLiquidacionHTL);
	if ($rowLiquidacionHTL = siguienteResult($resultadoLiquidacionHTL)){
		if ($rowLiquidacionHTL->idShoteleriaES){
			$updateHTL = " UPDATE hoteleria SET idliquidaciones = ".$idliquidaciones." WHERE idhoteleria IN (".$rowLiquidacionHTL->idShoteleriaES.");";
			$resultadoUpdateHTL = resultFromQuery($updateHTL);
		}
	}

}

function liquidacionCancelar($ID, $idresponsablesDepago){
	$_SESSION['idliquidaciones'] = $_POST['ID'];

	$sql = " DELETE FROM liquidaciones WHERE idliquidaciones = ".$_SESSION['idliquidaciones'];
	$resultadoStringSQL = resultFromQuery($sql);		

	$sql = " UPDATE mediapension SET idliquidaciones = 0 WHERE idliquidaciones = ".$_SESSION['idliquidaciones'];
	$resultadoStringSQL = resultFromQuery($sql);		

	$sql = " UPDATE hoteleria SET idliquidaciones = 0 WHERE idliquidaciones = ".$_SESSION['idliquidaciones'];
	$resultadoStringSQL = resultFromQuery($sql);		
}

function liquidacionCambiarEstado($idliquidaciones, $idestados, $titulo){
	$sql = " UPDATE liquidaciones SET idestados = ".$idestados.", titulo = '".$titulo."' WHERE idliquidaciones = ".$idliquidaciones;
	$resultadoStringSQL = resultFromQuery($sql);		
}

function liquidacionServicios($idresponsablesDePago, $id, $dataIN, $dataOUT){

	eliminarTablesLiquidacionesMP();//DROP table _temp_liquidaciones_mp
	crearTablesLiquidacionesMP();// CREATE table _temp_liquidaciones_mp
	insertoBloqueDeMediapension($idresponsablesDePago, $id, $dataIN, $dataOUT);

	eliminarTablesLiquidacionesHTL();
	crearTablesLiquidacionesHTL();
	insertoBloqueDeHoteleria($idresponsablesDePago, $id, $dataIN, $dataOUT);

	eliminarTablesLiquidacionesFINAL();
	crearTablesLiquidacionesFINAL();
	unionTablesLiquidacionesFINAL();

}

function liquidacionServiciosReview($idresponsablesDePago, $id, $idliquidaciones){

	eliminarTablesLiquidacionesMP();//DROP table _temp_liquidaciones_mp
	crearTablesLiquidacionesMP();// CREATE table _temp_liquidaciones_mp
	insertoBloqueDeMediapensionReview($idresponsablesDePago, $id, $idliquidaciones);

	eliminarTablesLiquidacionesHTL();
	crearTablesLiquidacionesHTL();
	insertoBloqueDeHoteleriaReview($idresponsablesDePago, $id, $idliquidaciones);

	eliminarTablesLiquidacionesFINAL();
	crearTablesLiquidacionesFINAL();
	unionTablesLiquidacionesFINAL();

}


function insertoBloqueDeMediapension($idresponsablesDePago, $id, $dataIN, $dataOUT){
	
	$sql = " SELECT idresponsablesDePago, nombre, tabla ";
	$sql .= " FROM responsablesDePago "; 
	$sql .= " WHERE 1 "; 
	$sql .= " AND idresponsablesDePago = ".$idresponsablesDePago;
	
	 
	$resultadoResponsables = resultFromQuery($sql);	

	if ($rowLine = siguienteResult($resultadoResponsables)) {	
		$tabla = $rowLine->tabla;
		$nombre = $rowLine->nombre;
	}

	$sql = "SELECT  MP.idmediapension, H.Titular 'Titular', MP.qtdedepax 'Q', A.Nombre 'Agencia', P.Nombre 'Posada', P.idposadas 'idposadas', MP.DataIN 'DataIN', MP.DataOUT 'DataOUT', MP.numeroexterno, MP.hoteleria ,  ";
	$sql .= " 	DATEDIFF(MP.DataOUT, MP.DataIN) 'N', (MP.qtdedepax*DATEDIFF(MP.DataOUT, MP.DataIN)) 'M', S.Nombre 'Servicio', S.idservicios 'idservicios' ";
	$sql .= " 	FROM `mediapension` MP  ";
	$sql .= " 	LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes  ";
	$sql .= " 	LEFT JOIN agencias A ON MP.idagencias = A.idagencias  ";
	$sql .= " 	LEFT JOIN posadas P ON MP.idposadas = P.idposadas  ";
	$sql .= " 	LEFT JOIN servicios S ON MP.idservicios = S.idservicios  ";
	$sql .= " 	WHERE 1  ";
	//$sql .= "AND (MP.dataIN BETWEEN '2014-01-16' AND '2014-01-31') ";
	$sql .= "AND (MP.dataIN BETWEEN '".$dataIN."' AND '".$dataOUT."') ";
	//$sql .= " 	AND MP.DataIN >= '".$dataIN."' AND MP.DataIN <= '".$dataOUT."'	 "; // las consultas en la fecha, no son un error, sino que se toma con la fecha de salida de la media pension.
	$sql .= " 	AND MP.idliquidaciones = 0 ";
	$sql .= " 	AND MP.habilitado = 1";
	$sql .= " 	AND MP.id".$tabla." = ".$id;
	$sql .= " 	AND MP.idresponsablesDePago = ".$idresponsablesDePago;
	
	$resultado = resultFromQuery($sql);	
	
	while ($row = siguienteResult($resultado)) {
		// echo 'idmediapension : '.$row->idmediapension.' - data: '.$row->DataIN.' - ('.$row->N.' Noches - '.$row->Q.' Q - '.$row->M.' MP) <br>';
		// Inserto en tabla temporal para saber las diferencias en los precios que pueda llegar a existir
		$start = strtotime($row->DataIN);
		$end = strtotime($row->DataOUT.' -1 day');
		
		for ($i=$start;$i<=$end;$i = $i + 86400 ){
			

			$fechaActual = date("Y-m-d",$i);
			$data = $fechaActual;
			$idposadas = $row->idposadas;
			$idservicios = $row->idservicios;
			$mindays = $row->N;
			//$precio = valordiaria($fechaActual, $row->idposadas, $row->idservicios); // Version antigua

			$precio = valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas, false, $mindays);
			
			mysql_query("LOCK TABLES _temp_liquidaciones_mp_cuentas WRITE;");
			
			$sql = " INSERT INTO _temp_liquidaciones_mp_cuentas (data, precio) VALUES (";
			$sql .= " '".$fechaActual."',";
			$sql .= " ".$precio."";
			$sql .= " ); ";
			
			$resultadoInsertLine = resultFromQuery($sql);
			
			mysql_query("UNLOCK TABLES;");
			
		}
		
		//hago un disctinct de los valores

		$sql = " SELECT count(*) Noches, precio, MIN(data) AS min, MAX(data) AS max, SUM(precio) AS Total ";
		$sql .= " FROM  `_temp_liquidaciones_mp_cuentas` ";
		$sql .= " GROUP BY precio ";
		$resultadoDISTINCT= resultFromQuery($sql);	

		//e inserto SUM de cada distinct
		while ($rowLine = siguienteResult($resultadoDISTINCT)) {
			$sql = " INSERT INTO _temp_liquidaciones_mp (idmediapension, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) VALUES (";
			$sql .= " ".$row->idmediapension.",";
			$sql .= " '".$row->Titular."',";
			$sql .= " ".$row->Q.",";
			$sql .= " '".$row->Agencia."',";
			$sql .= " '".$row->Posada."',";
			$sql .= " '".$rowLine->min."',";
			$sql .= " '".$rowLine->max."',";
			$sql .= " '".$row->numeroexterno."',";
			$sql .= " ".$rowLine->Noches.",";
			$sql .= " ".$rowLine->Noches*$row->Q.",";
			$sql .= " '".$row->Servicio."',";
		if($row->hoteleria){
			$sql .= " 0,";
			$sql .= " 0";
		}else{
			$sql .= " ".$rowLine->Total*$row->Q.",";
			$sql .= " ".$rowLine->precio."";
		}
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	
		
		}
		// borrar lineas
		$sql = " TRUNCATE _temp_liquidaciones_mp_cuentas ";
		$resultadoTRUNCATE = resultFromQuery($sql);	
	
	}
	

}

function insertoBloqueDeMediapensionReview($idresponsablesDePago, $id, $idliquidaciones){
	
	$sql = " SELECT idresponsablesDePago, nombre, tabla ";
	$sql .= " FROM responsablesDePago "; 
	$sql .= " WHERE 1 "; 
	$sql .= " AND idresponsablesDePago = ".$idresponsablesDePago;
	
	 
	$resultadoResponsables = resultFromQuery($sql);	

	if ($rowLine = siguienteResult($resultadoResponsables)) {	
		$tabla = $rowLine->tabla;
		$nombre = $rowLine->nombre;
	}

	$sql = "SELECT  MP.idmediapension, H.Titular 'Titular', MP.qtdedepax 'Q', A.Nombre 'Agencia', P.Nombre 'Posada', P.idposadas 'idposadas', MP.DataIN 'DataIN', MP.DataOUT 'DataOUT', MP.numeroexterno, MP.hoteleria ,  ";
	$sql .= " 	DATEDIFF(MP.DataOUT, MP.DataIN) 'N', (MP.qtdedepax*DATEDIFF(MP.DataOUT, MP.DataIN)) 'M', S.Nombre 'Servicio', S.idservicios 'idservicios' ";
	$sql .= " 	FROM `mediapension` MP  ";
	$sql .= " 	LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes  ";
	$sql .= " 	LEFT JOIN agencias A ON MP.idagencias = A.idagencias  ";
	$sql .= " 	LEFT JOIN posadas P ON MP.idposadas = P.idposadas  ";
	$sql .= " 	LEFT JOIN servicios S ON MP.idservicios = S.idservicios  ";
	$sql .= " 	WHERE 1  ";
	//$sql .= "AND (MP.dataIN BETWEEN '2014-01-16' AND '2014-01-31') ";
	//$sql .= " 	AND MP.DataIN >= '".$dataIN."' AND MP.DataIN <= '".$dataOUT."'	 "; // las consultas en la fecha, no son un error, sino que se toma con la fecha de salida de la media pension.
	$sql .= " 	AND MP.idliquidaciones = ".$idliquidaciones;
	
	$resultado = resultFromQuery($sql);	
	
	while ($row = siguienteResult($resultado)) {
		// echo 'idmediapension : '.$row->idmediapension.' - data: '.$row->DataIN.' - ('.$row->N.' Noches - '.$row->Q.' Q - '.$row->M.' MP) <br>';
		// Inserto en tabla temporal para saber las diferencias en los precios que pueda llegar a existir
		$start = strtotime($row->DataIN);
		$end = strtotime($row->DataOUT.' -1 day');
		
		for ($i=$start;$i<=$end;$i = $i + 86400 ){
			

			$fechaActual = date("Y-m-d",$i);
			$data = $fechaActual;
			$idposadas = $row->idposadas;
			$idservicios = $row->idservicios;
			$mindays = $row->N;
			//$precio = valordiaria($fechaActual, $row->idposadas, $row->idservicios); // Version antigua

			$precio = valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas, false, $mindays);
			
			mysql_query("LOCK TABLES _temp_liquidaciones_mp_cuentas WRITE;");
			
			$sql = " INSERT INTO _temp_liquidaciones_mp_cuentas (data, precio) VALUES (";
			$sql .= " '".$fechaActual."',";
			$sql .= " ".$precio."";
			$sql .= " ); ";
			
			$resultadoInsertLine = resultFromQuery($sql);
			
			mysql_query("UNLOCK TABLES;");
			
		}
		
		//hago un disctinct de los valores

		$sql = " SELECT count(*) Noches, precio, MIN(data) AS min, MAX(data) AS max, SUM(precio) AS Total ";
		$sql .= " FROM  `_temp_liquidaciones_mp_cuentas` ";
		$sql .= " GROUP BY precio ";
		$resultadoDISTINCT= resultFromQuery($sql);	

		//e inserto SUM de cada distinct
		while ($rowLine = siguienteResult($resultadoDISTINCT)) {
			$sql = " INSERT INTO _temp_liquidaciones_mp (idmediapension, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) VALUES (";
			$sql .= " ".$row->idmediapension.",";
			$sql .= " '".$row->Titular."',";
			$sql .= " ".$row->Q.",";
			$sql .= " '".$row->Agencia."',";
			$sql .= " '".$row->Posada."',";
			$sql .= " '".$rowLine->min."',";
			$sql .= " '".$rowLine->max."',";
			$sql .= " '".$row->numeroexterno."',";
			$sql .= " ".$rowLine->Noches.",";
			$sql .= " ".$rowLine->Noches*$row->Q.",";
			$sql .= " '".$row->Servicio."',";
		if($row->hoteleria){
			$sql .= " 0,";
			$sql .= " 0";
		}else{
			$sql .= " ".$rowLine->Total*$row->Q.",";
			$sql .= " ".$rowLine->precio."";
		}
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	
		
		}
		// borrar lineas
		$sql = " TRUNCATE _temp_liquidaciones_mp_cuentas ";
		$resultadoTRUNCATE = resultFromQuery($sql);	
	
	}
	

}

function insertoBloqueDeHoteleria($idresponsablesDePago, $id, $dataIN, $dataOUT){

	$sql = " SELECT idresponsablesDePago, nombre, tabla ";
	$sql .= " FROM responsablesDePago "; 
	$sql .= "WHERE 1 "; 
	$sql .= " AND idresponsablesDePago = ".$idresponsablesDePago;
	 
	$resultadoResponsables= resultFromQuery($sql);	

	if ($rowLine = siguienteResult($resultadoResponsables)) {	
		$tabla = $rowLine->tabla;
		$nombre = $rowLine->nombre;
	}

	$sql = " 	SELECT HTL.idhoteleria, H.Titular 'Titular', HTL.qtdedepax 'Q', A.Nombre 'Agencia', P.Nombre 'Posada', P.idposadas 'idposadas', HTL.DataIN 'DataIN', HTL.DataOUT 'DataOUT', HTL.numeroexterno,  ";
	$sql .= " 	DATEDIFF(HTL.DataOUT, HTL.DataIN) 'N', (HTL.qtdedepax*DATEDIFF(HTL.DataOUT, HTL.DataIN)) 'M', S.Nombre 'Servicio', S.idservicios 'idservicios' ";
	$sql .= " 	FROM `hoteleria` HTL  ";
	$sql .= " 	LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes  ";
	$sql .= " 	LEFT JOIN agencias A ON HTL.idagencias = A.idagencias  ";
	$sql .= " 	LEFT JOIN posadas P ON HTL.idposadas = P.idposadas  ";
	$sql .= " 	LEFT JOIN servicios S ON HTL.idservicios = S.idservicios  ";
	$sql .= " 	WHERE 1  ";

	$sql .= "AND (HTL.dataIN BETWEEN '".$dataIN."' AND '".$dataOUT."') ";

	$sql .= "AND HTL.idliquidaciones = 0 ";  
	$sql .= "AND HTL.habilitado = 1 ";  	

	$sql .= "AND HTL.id".$tabla." = ".$id.' ';
	$sql .= "AND HTL.idresponsablesDePago = ".$idresponsablesDePago;
	
	$resultado = resultFromQuery($sql);	

	while ($row = siguienteResult($resultado)) {
		// echo 'idmediapension : '.$row->idmediapension.' - data: '.$row->DataIN.' - ('.$row->N.' Noches - '.$row->Q.' Q - '.$row->M.' MP) <br>';
		// Inserto en tabla temporal para saber las diferencias en los precios que pueda llegar a existir
		$start = strtotime($row->DataIN);
		$end = strtotime($row->DataOUT.' -1 day');
		for ( $i = $start; $i <= $end; $i += 86400 ){

			$fechaActual = date("Y-m-d",$i);
			$data = $fechaActual;
			$idposadas = $row->idposadas;
			$idservicios = $row->idservicios;
			//$precio = valordiaria($fechaActual, $row->idposadas, $row->idservicios); // Version antigua
			$precio = valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas, true);

			$sql = " INSERT INTO _temp_liquidaciones_htl_cuentas (data, precio) VALUES (";
			$sql .= " '".$fechaActual."',";
			$sql .= " ".$precio."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	

		}
		//hago un disctinct de los valores

		$sql = " SELECT count(*) Noches, precio, MIN(data) AS min, MAX(data) AS max, SUM(precio) AS Total ";
		$sql .= " FROM  `_temp_liquidaciones_htl_cuentas` ";
		$sql .= " GROUP BY precio ";
		$resultadoDISTINCT= resultFromQuery($sql);	

		//e inserto SUM de cada distinct
		while ($rowLine = siguienteResult($resultadoDISTINCT)) {
			$sql = " INSERT INTO _temp_liquidaciones_htl (idhoteleria, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) VALUES (";
			$sql .= " ".$row->idhoteleria.",";
			$sql .= " '".$row->Titular."',";
			$sql .= " ".$row->Q.",";
			$sql .= " '".$row->Agencia."',";
			$sql .= " '".$row->Posada."',";
			$sql .= " '".$rowLine->min."',";
			$sql .= " '".$rowLine->max."',";
			$sql .= " '".$row->numeroexterno."',";
			$sql .= " ".$rowLine->Noches.",";
			$sql .= " ".$rowLine->Noches*$row->Q.",";
			$sql .= " '".$row->Servicio."',";
			$sql .= " ".$rowLine->Total.",";
			$sql .= " ".$rowLine->precio."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	
		
		}
		// borrar lineas
		$sql = " TRUNCATE _temp_liquidaciones_htl_cuentas ";
		$resultadoTRUNCATE = resultFromQuery($sql);	
	
	}
	

}

function insertoBloqueDeHoteleriaReview($idresponsablesDePago, $id, $idliquidaciones){

	$sql = " SELECT idresponsablesDePago, nombre, tabla ";
	$sql .= " FROM responsablesDePago "; 
	$sql .= "WHERE 1 "; 
	$sql .= " AND idresponsablesDePago = ".$idresponsablesDePago;
	 
	$resultadoResponsables= resultFromQuery($sql);	

	if ($rowLine = siguienteResult($resultadoResponsables)) {	
		$tabla = $rowLine->tabla;
		$nombre = $rowLine->nombre;
	}

	$sql = " 	SELECT HTL.idhoteleria, H.Titular 'Titular', HTL.qtdedepax 'Q', A.Nombre 'Agencia', P.Nombre 'Posada', P.idposadas 'idposadas', HTL.DataIN 'DataIN', HTL.DataOUT 'DataOUT', HTL.numeroexterno,  ";
	$sql .= " 	DATEDIFF(HTL.DataOUT, HTL.DataIN) 'N', (HTL.qtdedepax*DATEDIFF(HTL.DataOUT, HTL.DataIN)) 'M', S.Nombre 'Servicio', S.idservicios 'idservicios' ";
	$sql .= " 	FROM `hoteleria` HTL  ";
	$sql .= " 	LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes  ";
	$sql .= " 	LEFT JOIN agencias A ON HTL.idagencias = A.idagencias  ";
	$sql .= " 	LEFT JOIN posadas P ON HTL.idposadas = P.idposadas  ";
	$sql .= " 	LEFT JOIN servicios S ON HTL.idservicios = S.idservicios  ";
	$sql .= " 	WHERE 1  ";
	$sql .= "AND HTL.idliquidaciones = ".$idliquidaciones;  

	
	$resultado = resultFromQuery($sql);	

	while ($row = siguienteResult($resultado)) {
		// echo 'idmediapension : '.$row->idmediapension.' - data: '.$row->DataIN.' - ('.$row->N.' Noches - '.$row->Q.' Q - '.$row->M.' MP) <br>';
		// Inserto en tabla temporal para saber las diferencias en los precios que pueda llegar a existir
		$start = strtotime($row->DataIN);
		$end = strtotime($row->DataOUT.' -1 day');
		for ( $i = $start; $i <= $end; $i += 86400 ){

			$fechaActual = date("Y-m-d",$i);
			$data = $fechaActual;
			$idposadas = $row->idposadas;
			$idservicios = $row->idservicios;
			//$precio = valordiaria($fechaActual, $row->idposadas, $row->idservicios); // Version antigua
			$precio = valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas, true);

			$sql = " INSERT INTO _temp_liquidaciones_htl_cuentas (data, precio) VALUES (";
			$sql .= " '".$fechaActual."',";
			$sql .= " ".$precio."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	

		}
		//hago un disctinct de los valores

		$sql = " SELECT count(*) Noches, precio, MIN(data) AS min, MAX(data) AS max, SUM(precio) AS Total ";
		$sql .= " FROM  `_temp_liquidaciones_htl_cuentas` ";
		$sql .= " GROUP BY precio ";
		$resultadoDISTINCT= resultFromQuery($sql);	

		//e inserto SUM de cada distinct
		while ($rowLine = siguienteResult($resultadoDISTINCT)) {
			$sql = " INSERT INTO _temp_liquidaciones_htl (idhoteleria, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) VALUES (";
			$sql .= " ".$row->idhoteleria.",";
			$sql .= " '".$row->Titular."',";
			$sql .= " ".$row->Q.",";
			$sql .= " '".$row->Agencia."',";
			$sql .= " '".$row->Posada."',";
			$sql .= " '".$rowLine->min."',";
			$sql .= " '".$rowLine->max."',";
			$sql .= " '".$row->numeroexterno."',";
			$sql .= " ".$rowLine->Noches.",";
			$sql .= " ".$rowLine->Noches*$row->Q.",";
			$sql .= " '".$row->Servicio."',";
			$sql .= " ".$rowLine->Total.",";
			$sql .= " ".$rowLine->precio."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	
		
		}
		// borrar lineas
		$sql = " TRUNCATE _temp_liquidaciones_htl_cuentas ";
		$resultadoTRUNCATE = resultFromQuery($sql);	
	
	}
	

}

// ----------------------------------------------------------------------------------------------------
//Cancelar Agencias
function agenciasCancelar($ID){
	$_SESSION['idagencias'] = $ID;
		
	//Delete agencias row
	$sql = " DELETE FROM agencias WHERE idagencias = ".$_SESSION['idagencias'];
	$resultadoStringSQL = resultFromQuery($sql);		
	
	//Update agencias on table hoteleria to 0 -> Posada inexistente
	$sql = " UPDATE hoteleria SET idagencias = 0 WHERE idagencias = ".$_SESSION['idagencias'];
	$resultadoStringSQL = resultFromQuery($sql);		

	//Update agencias on table mediapension to 0 -> Posada inexistente
	$sql = " UPDATE mediapension SET idagencias = 0 WHERE idagencias = ".$_SESSION['idagencias'];
	$resultadoStringSQL = resultFromQuery($sql);
	
	//Update agencias on table reservas to 0 -> Posada inexistente
	$sql = " UPDATE reservas SET idagencias = 0 WHERE idagencias = ".$_SESSION['idagencias'];
	$resultadoStringSQL = resultFromQuery($sql);
	
	bitacoras($_SESSION["idusuarios"], $_SESSION["username"].' elimino la agencia: ID '.$_SESSION['idagencias']);
}

//Cancelar Posadas
function posadasCancelar($ID){
	$_SESSION['idposadas'] = $ID;
		
	//Delete posadas row
	$sql = " DELETE FROM posadas WHERE idposadas = ".$_SESSION['idposadas'];
	$resultadoStringSQL = resultFromQuery($sql);		
	
	//Update posadas on table habitaciones to 0 -> Posada inexistente
	$sql = " UPDATE habitaciones SET idposadas = 0 WHERE idposadas= ".$_SESSION['idposadas'];
	$resultadoStringSQL = resultFromQuery($sql);	
	
	//Update posadas on table hoteleria to 0 -> Posada inexistente
	$sql = " UPDATE hoteleria SET idposadas = 0 WHERE idposadas = ".$_SESSION['idposadas'];
	$resultadoStringSQL = resultFromQuery($sql);		

	//Update posadas on table mediapension to 0 -> Posada inexistente
	$sql = " UPDATE mediapension SET idposadas = 0 WHERE idposadas = ".$_SESSION['idposadas'];
	$resultadoStringSQL = resultFromQuery($sql);
	
	//Update posadas on table posadas_listasdeprecios to 0 -> Posada inexistente
	$sql = " UPDATE posadas_listasdeprecios SET idposadas = 0 WHERE idposadas = ".$_SESSION['idposadas'];
	$resultadoStringSQL = resultFromQuery($sql);
	
	//Update posadas on table reservas to 0 -> Posada inexistente
	$sql = " UPDATE reservas SET idposadas = 0 WHERE idposadas = ".$_SESSION['idposadas'];
	$resultadoStringSQL = resultFromQuery($sql);
	
	//Update posadas on table reservas_admisiones to 0 -> Posada inexistente
	$sql = " UPDATE reservas_admisiones SET idposadas = 0 WHERE idposadas = ".$_SESSION['idposadas'];
	$resultadoStringSQL = resultFromQuery($sql);
	
}

//Cancelar operadores turisticos
function operadoresturisticosCancelar($ID){
	$_SESSION['idoperadoresturisticos'] = $ID;
		
	//Delete operadoresturisticos row
	$sql = " DELETE FROM operadoresturisticos WHERE idoperadoresturisticos = ".$_SESSION['idoperadoresturisticos'];
	$resultadoStringSQL = resultFromQuery($sql);		
	
	//Update idoperadoresturisticos on table hoteleria to 0 -> Operador inexistente
	$sql = " UPDATE hoteleria SET idoperadoresturisticos = 0 WHERE idoperadoresturisticos = ".$_SESSION['idoperadoresturisticos'];
	$resultadoStringSQL = resultFromQuery($sql);		

	//Update idoperadoresturisticos on table mediapension to 0 -> Operador inexistente
	$sql = " UPDATE mediapension SET idoperadoresturisticos = 0 WHERE idoperadoresturisticos = ".$_SESSION['idoperadoresturisticos'];
	$resultadoStringSQL = resultFromQuery($sql);
	
	//Update idoperadoresturisticos on table reservas to 0 -> Operador inexistente
	$sql = " UPDATE reservas SET idoperadoresturisticos = 0 WHERE idoperadoresturisticos = ".$_SESSION['idoperadoresturisticos'];
	$resultadoStringSQL = resultFromQuery($sql);
}


// ----------------------------------------------------------------------------------------------------

// Funciones de Actualizaciones de Locales y Server

function actualizarLocales($idLocales){
	// Setting.
	
	if ($idlocales>0){
		
		$setting = " SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO; ";
		$resultado = resultFromQuery($setting);	
		
		$sql = " SELECT * FROM locales where idlocales = 0 "; // SELECCION DEL SERVER
		$resultado = resultFromQuery($sql);	

		if ($row = mysql_fetch_object($resultado)) {
			$db_hostname_server = $row->db_hostname;
			$db_database_server = $row->db_database;
			$db_username_server = $row->db_username;
			$db_password_server = $row->db_password;
		}

		$dbConnection_server = &mysql_dbConnect($db_hostname_server, $db_username_server, $db_password_server);
		$resultado_server = mysql_select_db($db_database_server, $dbConnection_server);
		
		$sql = " SELECT * FROM locales where idlocales =  ".$idlocales; // SELECCION DEL LOCAL
		$resultado = resultFromQuery($sql);	

		if ($row = mysql_fetch_object($resultado)) {
			$db_hostname_local = $row->db_hostname;
			$db_database_local = $row->db_database;
			$db_username_local = $row->db_username;
			$db_password_local = $row->db_password;
		}
		
		$actualizar = actualizarEnServer($idlocales);
		$crear = crearTablesDeLocalesConInfo($idlocales);

		/*
		 * 
		 * 1. Selecciono todo lo que tengo para subir y lo subo.
		 * 
		 * 2. Bajo todo!
		 * 3. Dejo registro de la actualizacion
		 * 
		 * */
	}
}

function actualizarEnServer($idlocales){
	echo 'Actualizo';
	/*
	 * 
	 * Seleccion las tablas que tienen W = True
	 * Por cada table, subo los que no tienen actualizado = 1
	 * Dejo registro de la actualizacion.
	 * 
	 * */
}
	
function crearTablesDeLocalesConInfo($idlocales){

	/*
	 * CAMPO DE ACCION: Esta funcion solo debe ser ejecutada desde las terminales.
	 * 
	 * 1. Creo tabla a partir de la original.
	 * 
	 */

	if ($idlocales>0){


		$sql = " SELECT * FROM actualizables";
		$resultado = resultFromQuery($sql);	

		while ($row = mysql_fetch_object($resultado)) {
			echo $row->tablename.'<BR>';
			$drop = " drop table if exists ".$db_database_local.".".$row->tablename."; ";
			$create = " create table ".$db_database_local.".".$row->tablename." select * from ".$db_database_server.".".$row->tablename."; ";
			$resultadoDrop = resultFromQuery($drop);	
			echo $resultadoDrop.' DROP<BR>';
			$resultadoCreate = resultFromQuery($create);	
			echo $resultadoCreate.' CREATE<BR><BR>';
			if ($row->W==1){
				$querystring = str_replace("#server_local#", $db_database_local, $row->querystring);
				$resultadoQuerystring = resultFromQuery($querystring);
				echo $resultadoQuerystring.' QUERY<BR><BR>';
					
				$alter = " alter table ".$db_database_local.".".$row->tablename." add column actualizado int not null default 0; ";
				$resultadoAlter = resultFromQuery($alter);
				echo $resultadoAlter.' ALTER<BR><BR>';

				$update = " update ".$db_database_local.".".$row->tablename." set actualizado = 1; ";
				$resultadoUpdate = resultFromQuery($update);
				echo $resultadoUpdate.' UPDATE<BR><BR>';

				$alter2 = " alter table ".$db_database_local.".".$row->tablename." modify ".$row->field_id." int NOT NULL auto_increment; ";
				$resultadoAlter2 = resultFromQuery($alter2);
				echo $resultadoAlter2.' ALTER 2<BR><BR>';
			}
			echo ' <HR>';
		}
		 
/*

			$alter2 = " alter table DA_CN.".$row->tablename." modify ".$row->field_id." int NOT NULL PRIMARY KEY auto_increment; ";
			$resultadoAlter2 = resultFromQuery($alter2);	
			echo $resultadoAlter2.' ALTER 2<BR>';


*/

		 /* 
		 * 2. Hago que sea AUTO INCREMENTAL.
		 * 3. Modifico el numero de ID correspondiente al ultimo existente.
		 * 4. Agrego campos 'idlocales' y 'datamodificacion'.
		 * 
		 * */
	}
}

// Bitacoras

function bitacoras($idusuarios, $texto){

	/*
		Cargo la tabla bitacora con el usuario y contedido de lo que se esta haciendo.
		Lisado de lugares en donde fue aplicada la funcion.
		* Login (correcto e incorrecto)
		* admitirServicio
		* admitirMediapension
			* Modificacion de Voucher MP
			* Insertar Huesped MP
			* Insertar MP
		*   
	*/

	$sql = " INSERT INTO bitacoras (idusuarios, texto) VALUES (";
	$sql .= " ".$idusuarios.",";
	$sql .= " '".$texto."'";
	$sql .= " ) ";
	$resultadoInsertLine = resultFromQuery($sql);	
	
}

// Funciones de Reservas

function reservasVector($calendario){
	$param = mb_split(",", $calendario);
	$visualizarMes = $param[1];
	$visualizarAno = $param[2];
	$idhabitaciones = $param[3];
	$cantidadDeDiasDelMes = getMonthDays($visualizarMes, $visualizarAno);

	// Consulto el idhabitaciones para el mes en visualizacion en la tabla de reservas_admisiones
	// ordenado por fecha
	$sql = " SELECT `idreservas`, MIN(`data`) min , MAX(`data`) max ";
	$sql .= " FROM `reservas_admisiones`  ";
	$sql .= " WHERE 1  ";
	$sql .= " AND `idhabitaciones` = $idhabitaciones  ";
	$sql .= " AND MONTH(`data`) = $visualizarMes ";
	$sql .= " AND YEAR(`data`) = $visualizarAno ";
	$sql .= " GROUP BY `idreservas` ";
	$sql .= " ORDER BY `data` ";
	$resultado = resultFromQuery($sql);		
	$rangosOcupados = array();
	while ($rowLine = siguienteResult($resultado)){
		$diasOcupados = array();
		$min = date('j',strtotime($rowLine->min));
		$max = date('j',strtotime($rowLine->max));
		for ($i = $min; $i <= $max; $i++) {
			array_push($diasOcupados, $i);
		}
		array_push($rangosOcupados, array($min, $max, $diasOcupados));
		$min = '';
		$max = ''; 
		array_filter($diasOcupados);
	}
	
	(int)$tamanoRango = sizeof($rangosOcupados);

	// Inicio contruccion del vector de dias.
	$impVector = ' <table style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;"> ';
	$impVector .= ' 	<tr style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;"> ';


	for ($i = 1; $i <= $cantidadDeDiasDelMes; $i++) {
		
		$impVector .= ' 			<td style="margin: 0px; padding: 5px; border: 0px; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;"> ';
		$impVector .= ' 				<table border=2 style="width: 20px;"> ';
		$impVector .= ' 					<tr> ';
		$impVector .= ' 						<td style="margin: 0px; padding: 0px; border: 1; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 5px;" colspan="2">'.$i.'</td> ';
		$impVector .= ' 					</tr> ';
		$impVector .= ' 					<tr> ';

					$x = 0; // X es el indice del array rangosOcupados utilizado en el switch mas abajo .
					for ($z = 0; $z <= $tamanoRango; $z++){
						if (in_array($i, $rangosOcupados[$z][2])) {
							$x = $z;
						}
					}			
					
					if (in_array($i, $rangosOcupados[$x][2])) {
						switch (true){
							case $i==$rangosOcupados[$x][0]:
								if ($habitacionOcupada){
									$impVector .= '<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="orange">&nbsp;</td> ';
								}else{
									$impVector .= '<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="white">&nbsp;</td> ';
								}
								$impVector .= '<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="red">&nbsp;</td> ';
								break;
							case $i>=$rangosOcupados[$x][0]+1&&$i<$rangosOcupados[$x][1]:
								$impVector .= '<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="green">&nbsp;</td> ';
								$impVector .= '<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="green">&nbsp;</td> ';
								break;
							case $i==$rangosOcupados[$x][1]:
								$impVector .= '<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="orange">&nbsp;</td> ';
								$impVector .= '<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="white">&nbsp;</td> ';
								break;
							}
						$habitacionOcupada = 1;
					}else{
						$impVector .= ' 					<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="white">&nbsp;</td> ';
						$impVector .= ' 					<td style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;border-collapse: collapse; border-spacing: 0;" bgcolor="white">&nbsp;</td> ';
						$habitacionOcupada = 0;
					}

		$impVector .= '						</tr>';
		$impVector .= '					</table>';
		$impVector .= '				</td>';

	}

	$impVector .= '	</tr>';
	$impVector .= '</table>	';

	return $impVector;

}

function admitirServicioReservas($idreservas, $idposadas, $idhabitaciones, $qtdedepax, $data, $precio){
	$sql = "insert reservas_admisiones (idreservas, idposadas, idhabitaciones, qtdedepax, data, tarifa) values (";
	$sql .= "".$idreservas.",";
	$sql .= "".$idposadas.",";
	$sql .= "".$idhabitaciones.",";
	$sql .= "".$qtdedepax.",";
	$sql .= "'".$data."',";
	$sql .= "".$precio.") ";
	$resultadoStringSQL = resultFromQuery($sql);		
	$idadmision = mysql_insert_id();
	return $idadmision;
}

// Funciones de Mediapension

function precioListasdeprecios($idposadas_internas_idservicios, $idlistasdeprecios){
	$sqlPrecios = "select precio from servicios_listasdeprecios where idlistasdeprecios = ".$idlistasdeprecios;
	$sqlPrecios .= " and CONCAT(idposadas_internas, '_', idservicios) = '".$idposadas_internas_idservicios."';";
	//echo $sqlPrecios;
	$resultado = resultFromQuery($sqlPrecios);
	if ($rowLine = siguienteResult($resultado)){
		$precio = $rowLine->precio;
	}
	return isset($precio) ? $precio : false;
}

function guardarPrecio($idlistasdeprecios, $idservicios, $precio, $idposadas_internas){
	// consulto si el $idservicios and $idlistasdeprecios exite para ver si hago un UPDATE o INSERT
	$sqlExiste = " select * from servicios_listasdeprecios where idlistasdeprecios ='".$idlistasdeprecios."' and idservicios = '".$idservicios."' and idposadas_internas = '".$idposadas_internas."';";
	$resultado = mysql_fetch_array(resultFromQuery($sqlExiste));
	
	echo $resultado;
	echo $resultado[0];
	
	if ($resultado[0] == null){	
		$query = "insert into servicios_listasdeprecios (idlistasdeprecios, idservicios, precio, idposadas_internas) values ('".$idlistasdeprecios."','".$idservicios."','".$precio."','".$idposadas_internas."');"; 
	}else{
		$query = "update servicios_listasdeprecios set precio = ".$precio." where idlistasdeprecios ='".$idlistasdeprecios."' and idservicios = '".$idservicios."' and idposadas_internas = '".$idposadas_internas."';";
	}
	$result = mysql_query($query);
	echo $query."<br><br><br>";
	return $result;
}

function admitirServicio($idmediapension, $qtdedepaxagora, $precio, $idlocales=0){
	$sql = "insert mediapension_admisiones (data, idmediapension, qtdedepax, tarifa, actualizado, idlocales) values (NOW() ,";
	$sql .= "".$idmediapension.",";
	$sql .= "".$qtdedepaxagora.",";
	$sql .= "".$precio.",";
	
	if ($idlocales > 0){
		$sql .= "0, ";
	}
	else{
		$sql .= "1, ";
	}
	
	$sql .= "".$idlocales.") ";
	$resultadoStringSQL = resultFromQuery($sql);		
	$idadmision = mysql_insert_id();
	return $idadmision;
}

function valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas, $hoteleria = false, $mindays = false){
	/*
	Si la siguiente consulta viene vacia, es porque la posada no tiene una lista de precios particualar para media pension 
	y va a usar los precios genericos. Osea los estan para idposadas = 0.
	*/

	/*
	 * version 2
	 * valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas)
	 * 
	 * 1) Consulto si el idresponsablesDePago, id (id del operadore, agencia, etc... segun corresponde.) 
	 * y idposada, tiene una lista de precios asignada.
	 * 
	 * 		SI 2a) Mestro el valor segun el id de servicio recibido.
	 * 
	 * 		NO 2b) Selecciono el precio que esta asiganado a idresponsablesDePago AND idposadas = 0 y muestro
	 * 
	 * 			el precio preseleccionado.
	 * 	 * 
	 * Lugares en donde se usa la funcion:
	 * 
	 * 		posts.php accion -> admitirServicio
	 * 
	 * 		funcions.php funciones insertoBloqueDeMediapension, insertoBloqueDeHoteleria
	 * 
	 * NUEVO ORDER DE PRIORIDADES PARA OBTENER EL PRECIO:
	 * 
	 * valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas)
	 * 
	 * 1) 
	 * 
	 * 
	 * 
	*/

	$sql = "SELECT SLDP.precio precio FROM listasdeprecios LDP ";
	$sql .= " LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios ";
	$sql .= " WHERE 1 ";
	$sql .= " AND LDP.idresponsablesDePago = ".$idresponsablesDePago;
	$sql .= " AND LDP.iditem = ".$id;
	$sql .= " AND SLDP.idservicios = ".$idservicios;
	
	
	//echo $idposadas.'<br>';
	isset($hoteleria) && $hoteleria == true ? $sql .= " AND SLDP.idposadas_internas = ".$idposadas : '';
	//$sql .= " AND SLDP.idposadas_internas = ".$idposadas;
	$sql .= " AND  '".$data."' BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT ";
	isset($mindays) && $mindays != '' ? $sql .= "AND LDP.mindays <= ".$mindays." ORDER BY LDP.mindays DESC LIMIT 1" : '';
	
	/*
	echo "<hr><font color='white'>";
	echo $sql;
	echo "</font>";
	*/

	$resultado = mysql_fetch_array(resultFromQuery($sql));	
	
	if ($resultado[0] == null){
		
		
		if ($idresponsablesDePago == 2){
			
			$sql = "SELECT SLDP.precio precio FROM listasdeprecios LDP ";
			$sql .= " LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios ";
			$sql .= " LEFT JOIN grupos_precios GP ON LDP.idlistasdeprecios = GP.idlistasdeprecios ";
			$sql .= " WHERE 1 ";
			$sql .= " AND LDP.idresponsablesDePago = ".$idresponsablesDePago;
			$sql .= " AND LDP.iditem = 92";
			$sql .= " AND GP.idelement = ".$id;
			$sql .= " AND SLDP.idservicios = ".$idservicios;
			$sql .= " AND  '".$data."' BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT ";
			isset($mindays) ? $sql .= "AND LDP.mindays <= ".$mindays." ORDER BY LDP.mindays DESC LIMIT 1" : '';
				
			$resultado = mysql_fetch_array(resultFromQuery($sql));
			
			if ($resultado[0] == null){

				$sql = "SELECT SLDP.precio precio FROM listasdeprecios LDP ";
				$sql .= " LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios ";
				$sql .= " WHERE 1 ";
				$sql .= " AND LDP.idresponsablesDePago = ".$idresponsablesDePago;
				$sql .= " AND LDP.iditem = 0";
				$sql .= " AND SLDP.idservicios = ".$idservicios;
				isset($hoteleria) && $hoteleria == true ? $sql .= " AND SLDP.idposadas_internas = ".$idposadas : '';
				$sql .= " AND  '".$data."'  BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT ";
				/*
				echo "<hr><font color='green'>";
				echo $sql;
				echo "</font>";
				*/
				$resultado = mysql_fetch_array(resultFromQuery($sql));	
				
				if ($resultado[0] == null){
					$sql = "SELECT SLDP.precio precio FROM listasdeprecios LDP ";
					$sql .= " LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios ";
					$sql .= " WHERE 1 ";
					$sql .= " AND LDP.idresponsablesDePago = ".$idresponsablesDePago;
					$sql .= " AND LDP.iditem = 0";
					$sql .= " AND SLDP.idservicios = ".$idservicios;
					isset($hoteleria) && $hoteleria == true ? $sql .= " AND SLDP.idposadas_internas = ".$idposadas : '';
					$sql .= " AND  '".$data."'  BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT ";
				
				}
			}
		}
		else{

			$sql = "SELECT SLDP.precio precio FROM listasdeprecios LDP ";
			$sql .= " LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios ";
			$sql .= " WHERE 1 ";
			$sql .= " AND LDP.idresponsablesDePago = ".$idresponsablesDePago;
			$sql .= " AND LDP.iditem = 0";
			$sql .= " AND SLDP.idservicios = ".$idservicios;
			isset($hoteleria) && $hoteleria == true ? $sql .= " AND SLDP.idposadas_internas = ".$idposadas : '';
			$sql .= " AND  '".$data."'  BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT ";
			/*
			echo "<hr><font color='green'>";
			echo $sql;
			echo "</font>";
			*/
			$resultado = mysql_fetch_array(resultFromQuery($sql));		
		}
	}
	if ($resultado[0] == null){
		$valor = 0;
	}else{
		$valor = $resultado[0];
	}
	
	
	return $valor;

}

function calcularDias($idmediapension, $qtycomidas, $admisiones){
	
	
	$totalComidas = $qtycomidas;
	$qtyadmisiones = $admisiones;
	$table = '<TABLE name="Dias" >';
	$table .= '<thead>';
	$table = $table . '<TR>';
	
	$bloqueActual = floor($qtyadmisiones / 7);
	
	for ($i = 1; $i <= 7; $i++)
	{
		
		if (((($bloqueActual) * 7) + $i) <= $totalComidas){
			
			if ((($bloqueActual) * 7) + $i <= $qtyadmisiones)
			{
				$table = $table . '<TH>X</TH>';
			}
			else
			{
			$table = $table . '<TH>'. ((($bloqueActual) * 7) + $i) .'</TH>';
			}
		}
	}
	
	$table = $table . '<TH>';
	$comidasrestantes = $qtycomidas-$admisiones;
	$table .= ' < '.$comidasrestantes.' servicios restantes | <a href="#myModal" data-toggle="modal" class="" onclick="document.getElementById(\'modal-body\').innerHTML=\'<object id=foo name=foo type=text/html width=530 height=310 data=mediapension.admisiones.php?idmediapension='.$idmediapension.'></object>\'">ver detalle</a>'.' ';
	$table .= ' | <a href="#myModal" data-toggle="modal" class="" onclick="document.getElementById(\'modal-body\').innerHTML=\'<object id=foo name=foo type=text/html width=530 height=250 data=mediapension.admitir.php?idmediapension='.$idmediapension.'></object>\'">admitir servicio</a>'.' > ';
	$table = $table . '</TH>';
	$table = $table . '</TR>';
	$table .= '</thead>';
	$table .= '</TABLE>';


	return $table;
}

function voucherCancelar($ID){
	$_SESSION['voucher'] = $ID;

	$sql = " UPDATE mediapension SET habilitado = 0 WHERE idmediapension = ".$_SESSION['voucher'];
	$resultadoStringSQL = resultFromQuery($sql);
	
	bitacoras($_SESSION["idusuarios"], 'Apagado o Voucher MP: ID '.$_SESSION['voucher']);	
}

// Funciones Genericas

function tableFromResultGDA($result, $name = '', $deletableRows = false, $modifiableRows = false, $link, $paginado) {
	return mysql_tableFromResultGDA($result, $name, $deletableRows, $modifiableRows, $link, $paginado);
};

function mysql_tableFromResultGDA($result, $name = '', $deletableRows = false, $modifiableRows = false, $link, $paginado) {
	
	if ($paginado){
		$table = '<TABLE class="table table-bordered data-table" name="'.$name.'" >';
	}else{
		$table = '<TABLE class="table table-bordered table-striped" name="'.$name.'" >';
	}
	if ($deletableRows || $modifiableRows) {
		$table .= '<form name="'.$name.'Form" method="POST">';
	}
	
	$table .= '<thead>';
	
	for ($i = 0; $i < dbFieldCount($result); $i++) {
		$colname = dbFieldName($result,$i);
		if (!(($colname == 'admisiones') || ($colname == 'idmediapension') || ($colname == 'idhabitaciones'))){
			$table .= '<TH>' . dbFieldName($result,$i) . '</TH>';
		}
	}
	
	$table .= '</thead>';
	$table .= '<tbody>';
		
	while ($row = siguienteResult($result)) {
		
		$table = $table . '<TR>';
		for ($j = 0; $j < dbFieldCount($result); $j++) {
			$colname = dbFieldName($result, $j);
				if (!(($colname == 'admisiones') || ($colname == 'idmediapension') || ($colname == 'idhabitaciones'))){
					switch ($colname) {
						case 'Dias':
								$table = $table . '<TD class="span9">' .calcularDias($row->idmediapension, $row->Dias, $row->admisiones). '</TD>';
								break;
							case 'calendario':
								$table = $table . '<TD class="span9"> ' . reservasVector($row->calendario.','.$row->idhabitaciones) . ' </TD>';
								break;
							default:
								$table = $table . '<TD>' . $row->$colname . '</TD>';
								break;
						}
					}
				}
			if ($deletableRows) {
				$colname = dbFieldName($result, 0);
				$id = $row->$colname;
				$table .= '<TD class="controls"><input type="submit" name="deleteRow['.$colname.']['.$id.']" onclick="javascript:deleteRowEvent('."'".$name."','".$colname."','".$id."'".');" value="Apagar"></TD>';
			}
			if ($modifiableRows) {
				$colname = dbFieldName($result, 0);
				$id = $row->$colname;
				$table .= '<TD class="controls"><input type="button" name="modifyRow" onclick="javascript:modifyRowEvent('."'".$name."','".$colname."','".$id."'".');" value="Editar"></TD>';
			}

			$table = $table . '</TR>';
		};
		$table .= '</tbody>';
	if ($deletableRows || $modifiableRows) {
		$table .= '</form>';
	}
		$table = $table . '</TABLE>';
	return $table;
};

function getMonthDays($Month, $Year) { 
   //Si la extensión que mencioné está instalada, usamos esa. 
   if( is_callable("cal_days_in_month")) 
   { 
      return cal_days_in_month(CAL_GREGORIAN, $Month, $Year); 
   } 
   else 
   { 
      //Lo hacemos a mi manera. 
      return date("t",mktime(0,0,0,$Month,1,$Year)); 
   } 
} 

function dateFormatMySQL($date){
	
	
		$dtlatin = DateTime::createFromFormat("d/m/Y", $date);
		$dtlatin !== false && !array_sum($dtlatin->getLastErrors());
		
		$dtmysql= DateTime::createFromFormat("Y-m-d", $date);
		$dtmysql !== false && !array_sum($dtmysql->getLastErrors());
		
		if ($dtlatin){
			return $dtlatin->format('Y-m-d');
		}
		elseif ($dtmysql){
			return $dtmysql->format('Y-m-d');
		}
		else{
			return 'NULL';
		}
		
	
}
	/* ESTO FUNCIONA, PERO CARGA TODO JUNTO, SIN IDENTIFICAR DIFERENCIAS DE PRECIOS.
	$sql = " INSERT INTO _temp_liquidaciones_mp (idmediapension, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio) ";
	$sql .= " ( ";
	$sql .= " 	SELECT MP.idmediapension, H.Titular 'Nome PAX', MP.qtdedepax 'Q', A.Nombre 'Agencia', P.Nombre 'Posada', MP.DataIN, MP.DataOUT, MP.numeroexterno,  ";
	$sql .= " 	DATEDIFF(MP.DataOUT, MP.DataIN) 'N', (MP.qtdedepax*DATEDIFF(MP.DataOUT, MP.DataIN)) 'M', S.Nombre 'Servicio' ";
	$sql .= " 	FROM `mediapension` MP  ";
	$sql .= " 	LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes  ";
	$sql .= " 	LEFT JOIN agencias A ON MP.idagencias = A.idagencias  ";
	$sql .= " 	LEFT JOIN posadas P ON MP.idposadas = P.idposadas  ";
	$sql .= " 	LEFT JOIN servicios S ON MP.idservicios = S.idservicios  ";
	$sql .= " 	WHERE 1  ";
	$sql .= " 	AND MP.DataIN >= '".$dataIN."' AND MP.DataIN <= '".$dataOUT."'	 ";
	if ($idoperadoresturisticos > 0){
		$sql .= " 	AND MP.idoperadoresturisticos = ".$idoperadoresturisticos;
	}
	$sql .= " ) ";

	$resultado = resultFromQuery($sql);	
	echo $resultado;
	*/

	
	
	
	
		/* 
		
		FUNCIONA NO BORRAR !!! 
		Este Bloque nos va a servir para identificar lo que realmente consumio el operador, PERO NO ES LO QUE TENEMOS QUE COBRAR.
		SOLO VA A SERVIR Estadisticamente.
		
		$sql = " SELECT idmediapension, tarifa, SUM(tarifa) AS total_amount ";
		$sql .= " FROM `mediapension_admisiones` ";
		$sql .= " WHERE idmediapension = ".$row->idmediapension;
		$sql .= " GROUP BY idmediapension, tarifa	 ";
		echo $sql;
		$resultadoLine = resultFromQuery($sql);	
		while ($rowLine = siguienteResult($resultadoLine)) {
			$sql = " INSERT INTO _temp_liquidaciones_mp (idmediapension, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) VALUES (";
			$sql .= " ".$row->idmediapension.",";
			$sql .= " '".$row->Titular."',";
			$sql .= " ".$row->Q.",";
			$sql .= " '".$row->Agencia."',";
			$sql .= " '".$row->Posada."',";
			$sql .= " '".$row->DataIN."',";
			$sql .= " '".$row->DataOUT."',";
			$sql .= " '".$row->numeroexterno."',";
			$sql .= " ".$row->N.",";
			$sql .= " ".$row->M.",";
			$sql .= " '".$row->Servicio."',";
			$sql .= " ".$rowLine->total_amount.",";
			$sql .= " ".$rowLine->tarifa."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	
		}
		*/
function calcularSalario($employee_id, $month = false, $year = false){
	
	$salario = array('employee' => array('employee_id' => $employee_id));
	
	//Informacion sobre el empleado y detalles sobre salario
	//SELECT
	$sqlQuery = "SELECT ";
	$sqlQuery .= "E.employee_id id, ";
	$sqlQuery .= "CONCAT(P.firstname, ' ', P.lastname) fullname, ";
	$sqlQuery .= "BS.basesalary base, ";
	$sqlQuery .= "E.bonussalary, ";
	$sqlQuery .= "E.admission, ";
	$sqlQuery .= "E.contract contract, ";
	$sqlQuery .= "E.experiencecontract experiencecontract, ";
	$sqlQuery .= "E.unhealthy, ";
	$sqlQuery .= '(SELECT count(S.son_id) FROM employee E LEFT JOIN son S ON E.profile_id = S.profile_id WHERE E.employee_id = '.$salario['employee']['employee_id'].') sons, ';
	$sqlQuery .= "E.transport, ";
	$sqlQuery .= "F.status food, ";
	$sqlQuery .= "E.decline decline, ";
	$sqlQuery .= 'SY.status syndicate ';	
		
	//FROM
	$sqlQuery .= "FROM employee E ";

	$sqlQuery .= "LEFT JOIN jobcategory JC ON E.jobcategory_id = JC.jobcategory_id ";
	
	$sqlQuery .= "LEFT JOIN basesalary BS ON BS.basesalary_id = (select basesalary_id from basesalary where jobcategory_id = E.jobcategory_id AND MONTH(valid_from) <= ".$month." AND YEAR(valid_from) <= ".$year." ORDER by valid_from DESC LIMIT 1)";
	
	$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
	
	//Select latest food
	$sqlQuery .= "LEFT JOIN foodemployee F ON E.employee_id = F.employee_id ";
	$sqlQuery .= "AND F.created = (SELECT MAX(created) FROM foodemployee Z WHERE Z.employee_id = E.employee_id) ";
	
	//Select latest syndicate status
	$sqlQuery .= "LEFT JOIN syndicate SY ON E.employee_id = SY.employee_id ";
	$sqlQuery .= "AND SY.created = (SELECT MAX(created) FROM syndicate X WHERE X.employee_id = E.employee_id) ";
	
	
	//WHERE
	$sqlQuery .= "WHERE E.employee_id = ".$salario['employee']['employee_id'].' ';
	
	//Execute query
	$resultado = resultFromQuery($sqlQuery);	
	
	if ($row = siguienteResult($resultado)) {
		
		//Nombre completo
		$salario['employee']['fullname'] = $row->fullname;
			
		$salario['employee']['Admission'] = $row->admission;
		
		$salario['employee']['Contract'] = $row->contract;
		
		$salario['employee']['Experience Contract'] = $row->experiencecontract;
		
		$salario['employee']['Decline Date'] = $row->decline;
		
		//Faltas
		$period = daysforPoint($employee_id, $month, $year);
		$non = countperiodNonAttendance($period, $employee_id);
		$salario['employee']['Non Attendance'] = $non;
		
		//StartData
		//si el mes que se busca es menor al de admision, mensaje que aun no trabajaba
		if (isset ($salario['employee']['Admission']) && date('Y-m', strtotime($year.'-'.$month)) < date('Y-m', strtotime($salario['employee']['Admission']))){
			//no hace nada
		}
		//si el mes que se busca es igual al de admision, $dStart=admision
		elseif(isset ($salario['employee']['Admission']) && date('Y-m', strtotime($year.'-'.$month)) == date('Y-m', strtotime($salario['employee']['Admission']))){
			
			$dStart = new DateTime(date('Y-m-d', strtotime($salario['employee']['Admission'])));
		}
		//si el mes que se busca es mayor que el de la admision, $dStart=$year-$mont-01
		else{
			$dStart = new DateTime(date($year.'-'.$month.'-01'));
		}		
		
		//EndData
		if (isset ($salario['employee']['Decline Date']) && date('Y-m', strtotime($year.'-'.$month)) > date('Y-m', strtotime($salario['employee']['Decline Date']))){
			//no hace nada
		}
		//si el mes que se busca es igual al de despido, $dStart=admision
		elseif(isset ($salario['employee']['Decline Date']) && date('Y-m', strtotime($year.'-'.$month)) == date('Y-m', strtotime($salario['employee']['Decline Date']))){

			$dEnd = new DateTime(date('Y-m-d', strtotime($salario['employee']['Decline Date'])));
		}
		//si el mes que se busca es mayor que el de despido, $dStart=$year-$mont-01
		else{
			$dEnd = new DateTime(date('Y-m-t', strtotime($year.'-'.$month.'-01')));
		}
			
		//Available days between startData & endData
		$salario['employee']['Worked Days'] = 0;
		if (isset($dStart) && isset($dEnd)){
			
			$dSearch = date('Y-m', strtotime($year.'-'.$month));
			
			$dDiff = $dStart->diff($dEnd);
			$aDays = $dDiff->days + 1;
			
			//Dias trabajados
			$salario['employee']['Worked Days'] = $aDays - $salario['employee']['Non Attendance'];
			
			//Salario Base - Abono
			if(($dSearch == date('Y-m', strtotime($salario['employee']['Admission'])) || $dSearch == date('Y-m', strtotime($salario['employee']['Decline Date']))) && cal_days_in_month(CAL_GREGORIAN, $month, $year) > $aDays){
				
				$salario['+']['Salario Base'] = round(($row->base / 30) * $aDays, 2);
				$_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 4 ? $salario['+']['Abono'] = round(($row->bonussalary / 30) * $aDays, 2) : false;
			}
			else{
				$salario['+']['Salario Base'] = $row->base;
				
				$_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 4 ? $salario['+']['Abono'] = $row->bonussalary : false;
			}
			
			
			//Salario abono
			
			
			//Insalubridad
			$row->unhealthy != 0 ? $salario['+']['Insalubridade'] = 67.8 : '';
			
			//Cantidad de hijosAdmission
			$row->sons > 0 ? $salario['+']['Salario Familia'] = $row->sons * 24.66 : '';
			
			//Faltas
			if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 4){
				$salario['employee']['Non Attendance'] != 0 ? $descFaltas = round((($row->base + $row->bonussalary) / 30) * $salario['employee']['Non Attendance'], 2) : '';
				//$salario['employee']['Non Attendance'] != 0 ? $salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')'] = round((($row->base + $row->bonussalary) / 30) * $salario['employee']['Non Attendance'], 2) : '';
			}
			else{
				$salario['employee']['Non Attendance'] != 0 ? $descFaltas = round(($row->base / 30) * $salario['employee']['Non Attendance'], 2) : '';
				//$salario['employee']['Non Attendance'] != 0 ? $salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')'] = round(($row->base / 30) * $salario['employee']['Non Attendance'], 2) : '';
			}
			
			$sql = 'SELECT date FROM nonattendance WHERE employee_id = '.$salario['employee']['employee_id'].' AND MONTH(date) = '.$month.' AND YEAR(date) = '.$year;
			
			$result = resultFromQuery($sql);
			
			$textFaltas = 'Día/s:<strong>';
			while($sqlfaltas = siguienteResult($result)){
				$textFaltas .= ' '.date('d', strtotime($sqlfaltas->date)).', ';
			}
			$textFaltas = substr($textFaltas, 0, -2);
			$textFaltas .= '.</strong>';
			
			$salario['employee']['Non Attendance'] != 0 ? $salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')'][][$textFaltas] = $descFaltas : '';
			
			
			
		
			//INSS
			if ($salario['employee']['Contract'] || $salario['employee']['Experience Contract'] != 0){
				if (isset($salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')'])){
					$salario['-']['INSS'] = round(($salario['+']['Salario Base'] -  round(($row->base / 30) * $salario['employee']['Non Attendance'], 2)) * 0.08, 2);
				}
				else{
					$salario['-']['INSS'] = round($salario['+']['Salario Base'] * 0.08, 2);
				}
			}
			
			//Descuentos por Transporte
			if ($row->transport != NULL && $row->transport != '0'){
				
				if($dSearch == date('Y-m', strtotime($salario['employee']['Admission'])) || $dSearch == date('Y-m', strtotime($salario['employee']['Decline Date']))){
					$salario['-']['Transporte'] = round((transportDiscount($salario)/30)*$salario['employee']['Worked Days'], 2);
				}				
				elseif (isset($salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')'])){
					$salario['-']['Transporte'] = round(transportDiscount($salario) - ((transportDiscount($salario)/30)*$salario['employee']['Non Attendance']), 2);
				}
				else{
					$salario['-']['Transporte'] = round(transportDiscount($salario), 2);
				}

				
			}
			
			//Descuentos de sindicato
			if ($row->syndicate == 1 || $row->syndicate == NULL){
				$salario['-']['Sindicato'] = 10;
			}
			
			//Desconto por alimentação
			
			if ($row->food != 0){
				
				$sql = "select value from foodemployeeval where date(created) < LAST_DAY('".$year."-".$month."-01') ORDER BY created DESC LIMIT 1;";
				$result = resultFromQuery($sql);
				
				
				if ($row = siguienteResult($result)){
					$salario['-']['Alimentação'] = $row->value;
				}
				else{
					$salario['-']['Alimentação'] = 67.8;
				}
					
				 
				if($dSearch == date('Y-m', strtotime($salario['employee']['Admission'])) || $dSearch == date('Y-m', strtotime($salario['employee']['Decline Date']))){
					$salario['-']['Alimentação'] = round(($salario['-']['Alimentação']/30)*$salario['employee']['Worked Days'], 2);
				}				
				elseif (isset($salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')'])){
					$salario['-']['Alimentação'] = round($salario['-']['Alimentação'] - (($salario['-']['Alimentação']/30)*$salario['employee']['Non Attendance']), 2);
				}
				else{
					$salario['-']['Alimentação'] = round($salario['-']['Alimentação'], 2);
				}
				 
			}
			
			//Pagos recibidos durante el ultimo mes
	
			//SELECT
			$sqlQuery = "SELECT ";
			$sqlQuery .= "PAY.payment_id id, ";
			$sqlQuery .= "PAY.ammount, ";
			$sqlQuery .= "DATE_FORMAT(PAY.date, '%d/%m') date, ";
			$sqlQuery .= "PT.type, ";
			$sqlQuery .= "PAY.details ";

			//FROM
			$sqlQuery .= "FROM payment PAY ";

			$sqlQuery .= "LEFT JOIN paymenttype PT ON PAY.paymenttype_id = PT.paymenttype_id ";

			$sqlQuery .= "WHERE PAY.enabled = 1 ";
			$sqlQuery .= "AND PAY.employee_id = ".$salario['employee']['employee_id'].' ';
			
			if (isset($month) && $month != ''){
				$sqlQuery .= "AND month(PAY.date) = ".$month.' ';
			}
			
			if (isset($year) && $year != ''){
				$sqlQuery .= "AND year(PAY.date) = ".$year." ";
			}
			
			$sqlQuery .= "ORDER BY PAY.date";
			
			$resultado = resultFromQuery($sqlQuery);
			
			while ($row = mysql_fetch_object($resultado)) {
					
				$salario['-'][$row->type. " ".$row->date][][$row->details] = $row->ammount;
				
				isset($salario['adelantos']) ? $salario['adelantos'] += $row->ammount : $salario['adelantos'] = $row->ammount;
			}
			
			$salario['Total'] = round(array_sum($salario['+']) - (isset($salario['-']) ? array_sum($salario['-']) : 0) - (isset($salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')']) ? array_sum($salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')'][0]) : 0) - (isset($salario['adelantos']) ? $salario['adelantos'] : 0), 2);

		}
		else{
			unset($salario['+']);
			unset($salario['-']);
			
			$salario['+']['Salario Base'] = 0;
			$salario['Total'] = 0;
		}
			
		
	}

	return $salario;
}

function transportDiscount($salario){

	$salarioneto = array_sum($salario['+']);

	if ($salarioneto <= 1000){
		$transportdiscount = 45.60;
	}
	elseif ($salarioneto > 1000 && $salarioneto <= 1200){
		$transportdiscount = 50;
	}
	else{
		$transportdiscount = 60;
	}
	
	//$transportdiscount = round($salario['+']['Salario Base'] * 0.06, 2);

	return $transportdiscount;
}

function dayDropdown($name="day", $selected=null){
        $wd = '<select name="'.$name.'" id="'.$name.'" class="span4 m-wrap">';

        $days = array(
                1 => 'Segunda',
                2 => 'Terça',
                3 => 'Quarta',
                4 => 'Quinta',
                5 => 'Sexta',
                6 => 'Sábado',
                7 => 'Domingo');
        /*** the current day ***/
        $selected = is_null($selected) ? date('N', time()) : $selected;

        for ($i = 1; $i <= 7; $i++)
        {
                $wd .= '<option value="'.$i.'"';
                if ($i === array_search($selected, $days))
                {
                        $wd .= ' selected';
                }
                /*** get the day ***/
                $wd .= '>'.$days[$i].'</option>';
        }
        $wd .= '</select>';
        return $wd;
}	

function nextDay($dayname, $date){
	
	$day = date('d', strtotime($date));
	$month = date('m', strtotime($date));
	$year = date('Y', strtotime($date));
	$monthend = date('t', strtotime($date));
	
	setlocale(LC_ALL, 'pt_BR');
	
	for ($i = $day+1; $i <= $monthend; $i++){
		if (mb_convert_encoding(ucfirst(strftime("%A", strtotime($year.'-'.$month.'-'.$i))), "UTF-8", "iso-8859-1") === $dayname){
			return $year.'-'.$month.'-'.$i;
			break;
		}
		
		
	}
	return false;
	
	
	
}

function checkConnection(){
    //Initiates a socket connection
    $conn = @fsockopen("davincimp.no-ip.info", 8080, $errno, $errstr, 30);
    if ($conn)
    {
        $status = 1; 
        fclose($conn);
    }
    else
    {
        $status = 0;
    }
    return $status;
}

function comboDate($mode = false, $selected = false){
	
	$combo = '';
	
	if (isset($mode) && $mode == 'month'){
		
		for ($i=1; $i<=12; $i++){
			$combo .= '<option value="'.$i.'" ';
			$combo .= isset($selected) && $i==$selected ? 'selected' : '';
			$combo .= '>'.$i.'</option>';
			$combo .= "\r\n";
		}
	}	
	elseif (isset($mode) && $mode == 'year'){
		
		for ($i=2013; $i<=2014; $i++){
			$combo .= '<option value="'.$i.'" ';
			$combo .= isset($selected) && $i==$selected ? 'selected' : '';
			$combo .= '>'.$i.'</option>';
			$combo .= "\r\n";
		}
	}
	return $combo;
}

function daysforPoint($employee_id, $month, $year){

	//PointStart
	$sql = "SELECT admission ";
	$sql .= "FROM employee ";
	$sql .= "WHERE 1 ";
	$sql .= "AND employee_id = ".$employee_id." ";

	
	$result = resultFromQuery($sql);
	if($row = siguienteResult($result)){
		if($row->admission <= date('Y-m-01', strtotime($year.'-'.$month))){
			$point['start'] = $year.'-'.$month.'-01';
		}
		else{
			//Buscar el momento el que el empleado comenzó a trabajar
			$point['start'] = $row->admission;
		}
	}
	
	
	//pointEND
	$d1 = new DateTime($year.'-'.$month);
	$d2 = new DateTime(date('Y-m'));
		
	//Buscar si el empleado tiene fecha de despido en el mes seleccionado
	$sql = "SELECT decline ";
	$sql .= "FROM employee ";
	$sql .= "WHERE 1 ";
	$sql .= "AND employee_id = ".$employee_id." ";

	
	$result = resultFromQuery($sql);
	
	if ($row = siguienteResult($result)){
		if($row->decline && $row->decline < date("Y-m-d", strtotime($point['start']))){
			$point['start'] = $row->decline;
			$point['end'] = $row->decline;
		}
		elseif($row->decline && $row->decline <= date('Y-m-t', strtotime($year.'-'.$month))){
			if($d1 < $d2){
				$point['end'] = date("Y-m-t", strtotime($year.'-'.$month));
			}
			elseif ($d1 == $d2){
				if ($row->decline && date("Y-m-d", strtotime($row->decline)) > date("Y-m-d")){
					$point['end'] = date("Y-m-d");
				}
				else{
					$point['end'] = $row->decline;
				}
			}
			else{
				$point['end'] = '1231231';
				//si el mes es futuro, no deberia establecer un pointEnd
			}
			
		}
		else{
			if($d1 < $d2){
				$point['end'] = date("Y-m-t", strtotime($year.'-'.$month));
			}
			elseif ($d1 == $d2){
				$point['end'] = date("Y-m-d");
			}
			else{
				$point['end'] = '1231231';
				//si el mes es futuro, no deberia establecer un pointEnd
			}				
		}
		
	}
	
	return $point;
}

function employeeWorkedDay($employee_id, $date){
	
	$sql = "SELECT entrada.employee_id, ";
    $sql .= "DATE(entrada.date_time) data, ";
    $sql .= "MIN(entrada.point_id) identrada, ";
    $sql .= "MAX(salida.point_id) idsalida, "; 
    $sql .= "MIN(entrada.date_time) dtentrada, "; 
	$sql .= "MAX(salida.date_time) dtsalida, "; 
    $sql .= "TIME_FORMAT(TIME(MIN(entrada.date_time)), '%H:%i') entrada, ";  
    $sql .= "TIME_FORMAT(TIME(MAX(salida.date_time)), '%H:%i') salida ";       
    
    $sql .= "FROM point AS entrada ";   
    
    $sql .= "LEFT JOIN point AS salida ";
    $sql .= "ON entrada.employee_id = salida.employee_id ";  
    
    $sql .= "LEFT JOIN employee AS E ";
    $sql .= "ON entrada.employee_id = E.employee_id ";
    
    $sql .= "WHERE 1 ";
    $sql .= "AND entrada.employee_id = ".$employee_id." ";
    $sql .= "AND salida.in_out = 0 ";
    $sql .= "AND date(entrada.date_time) = '".$date."' ";
    $sql .= "AND entrada.date_time > DATE_ADD(date(entrada.date_time), interval HOUR(E.fromhour) - 9 hour) ";
    $sql .= "AND salida.date_time < DATE_ADD(DATE_ADD(date(entrada.date_time), interval 1 day), interval HOUR(E.fromhour) - 9  hour); ";
	
	$result = resultFromQuery($sql);

	if($row = siguienteResult($result)){
		$return['Data'] = $date;
		
		if ($row->data == NULL){
			
			//Buscar motivo de ausencia de punto
			$return['Worked'] = '0';
			$return['Motive'] = employeeClearance($employee_id, $date);
		}
		elseif($row->dtentrada >= $row->dtsalida && date('Y-m-d', strtotime($date)) == date('Y-m-d')){
			$return['Worked'] = '0';
			$return['Motive'] = 'Trabalhando';
		}
		elseif($row->dtentrada >= $row->dtsalida){
			$return['Worked'] = '0';
			$return['Motive'] = 'Erro no registro';
		}
		else{
			//Si la consulta devuelve informacion, es porque hay informacion relativa al punto

			
			/////////////////////////////
			$sql = "SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'";
			resultFromQuery($sql);
			
			$sql = "SELECT SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS hours_worked ";
			$sql .= "FROM point ";
			$sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
			$sql .= "AND date_time > '".$row->dtentrada."' ";
			$sql .= "AND date_time < '".$row->dtsalida."'";
			
			$result = resultFromQuery($sql);
			$intervalo = siguienteResult($result);
			
			$sql = "SELECT SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS hours_worked ";
			$sql .= "FROM point ";
			$sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
			$sql .= "AND date_time >= '".$row->dtentrada."' ";
			$sql .= "AND date_time <= '".$row->dtsalida."'";
			/////////////////////////////
			$result = resultFromQuery($sql);
			$hsworked = siguienteResult($result);

			
			if(abs($hsworked->hours_worked) > 200){
				$return['Worked'] = '0';
				$return['Motive'] = 'Erro no registro';
				
			}
			else{
						
				$return['Entrada'] = $row->entrada;
				$return['Intervalo'] = intHourstoNormal($intervalo->hours_worked);
				$return['Salida'] = $row->salida;
				$return['Worked'] = $hsworked->hours_worked;
			}
			
		}
		
		return $return;
	}
	else{
		return '<code>AQUI NO VA NADA</code>';
	}
}

function employeeClearance($employee_id, $date){
	
	$sql = "SELECT DAYNAME(valid_from) clearance, ";
	$sql .= "DAYNAME('".$date."') todayname ";
	$sql .= "FROM clearance ";
	$sql .= "WHERE 1 ";
	$sql .= "AND employee_id = ".$employee_id." ";
	$sql .= "AND valid_from <= '".$date."' ";
	$sql .= "ORDER BY valid_from DESC ";
	$sql .= "LIMIT 1 ";
	
	$rclearance = resultFromQuery($sql);
	
	if ($rowclearance = siguienteResult($rclearance)){
			
		if($rowclearance->clearance != $rowclearance->todayname){
			//buscar feriados
			$sql = "SELECT * ";
			$sql .= "FROM holiday ";
			$sql .= "WHERE 1 ";
			$sql .= "AND day = '".$date."'";
			$rholiday = resultFromQuery($sql);
			
			if ($rowholiday = siguienteResult($rholiday)){
				$motive = 'Feriado';
			}
			
			//buscar folgas extra
			$sql = "SELECT * ";
			$sql .= "FROM extraclearance ";
			$sql .= "WHERE 1 ";
			$sql .= "AND employee_id = '".$employee_id."'";
			$sql .= "AND date = '".$date."' ";

			$rholiday = resultFromQuery($sql);
			
			if ($rowholiday = siguienteResult($rholiday)){
				$motive = 'Folga Extra';
			}
			
			
			if(!isset($motive)){
				$motive = 'Ausente';
			}
			
		}
		else{
			$motive = 'Folga';
		}
	}
	else{
		$motive = 'Ausente ou día de folga sem registrar';
	}
	return $motive;
}

function countperiodNonAttendance($period, $employee_id){
	
	$start = date('d', strtotime($period['start']));
	$end = date('d', strtotime($period['end']));
	$yearmonth = date('Y-m', strtotime($period['start']));
	
	$total = 0;
	
	for($i = $start; $i < $end; $i++){
		$worked = employeeWorkedDay($employee_id, $yearmonth.'-'.$i);
		
		if(isset($worked['Motive']) && $worked['Motive'] == 'Ausente'){
			$total++;
		}
	}
	
	return $total;
}

function intHourstoNormal($int){
	$int = abs($int);
	$num_hours = $int; //some float
	$hours = floor($num_hours);
	$mins = round(($num_hours - $hours) * 60);
	
	if ($mins < 10){
		$mins = '0'.$mins;
	}
	
	return $hours.":".$mins;
	
}
	
?>
