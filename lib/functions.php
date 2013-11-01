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

	eliminarTablesLiquidacionesMP();
	crearTablesLiquidacionesMP();
	insertoBloqueDeMediapension($idresponsablesDePago, $id, $dataIN, $dataOUT);

	eliminarTablesLiquidacionesHTL();
	crearTablesLiquidacionesHTL();
	insertoBloqueDeHoteleria($idresponsablesDePago, $id, $dataIN, $dataOUT);

	eliminarTablesLiquidacionesFINAL();
	crearTablesLiquidacionesFINAL();
	unionTablesLiquidacionesFINAL();

}

function insertoBloqueDeMediapension($idresponsablesDePago, $id, $dataIN, $dataOUT){

	$sql = " SELECT idresponsablesDePago, nombre, tabla ";
	$sql .= " FROM responsablesDePago "; 
	$sql .= " WHERE 1 "; 
	$sql .= " AND idresponsablesDePago = ".$idresponsablesDePago;
	 
	$resultadoResponsables= resultFromQuery($sql);	

	if ($rowLine = siguienteResult($resultadoResponsables)) {	
		$tabla = $rowLine->tabla;
		$nombre = $rowLine->nombre;
	}

	$sql = " 	SELECT  MP.idmediapension, H.Titular 'Titular', MP.qtdedepax 'Q', A.Nombre 'Agencia', P.Nombre 'Posada', P.idposadas 'idposadas', MP.DataIN 'DataIN', MP.DataOUT 'DataOUT', MP.numeroexterno, MP.hoteleria ,  ";
	$sql .= " 	DATEDIFF(MP.DataOUT, MP.DataIN) 'N', (MP.qtdedepax*DATEDIFF(MP.DataOUT, MP.DataIN)) 'M', S.Nombre 'Servicio', S.idservicios 'idservicios' ";
	$sql .= " 	FROM `mediapension` MP  ";
	$sql .= " 	LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes  ";
	$sql .= " 	LEFT JOIN agencias A ON MP.idagencias = A.idagencias  ";
	$sql .= " 	LEFT JOIN posadas P ON MP.idposadas = P.idposadas  ";
	$sql .= " 	LEFT JOIN servicios S ON MP.idservicios = S.idservicios  ";
	$sql .= " 	WHERE 1  ";
	$sql .= " 	AND MP.DataIN >= '".$dataIN."' AND MP.DataIN <= '".$dataOUT."'	 "; // las consultas en la fecha, no son un error, sino que se toma con la fecha de salida de la media pension.
	$sql .= " 	AND MP.idliquidaciones = 0";  	
	if ($id > 0){
		$sql .= " 	AND MP.id".$tabla." = ".$id;
		$sql .= " 	AND MP.idresponsablesDePago = ".$idresponsablesDePago;
	}
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
			$precio = valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas);

			$sql = " INSERT INTO _temp_liquidaciones_mp_cuentas (data, precio) VALUES (";
			$sql .= " '".$fechaActual."',";
			$sql .= " ".$precio."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	

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
	$sql .= " 	AND HTL.DataIN >= '".$dataIN."' AND HTL.DataIN <= '".$dataOUT."'	 "; // las consultas en la fecha, no son un error, sino que se toma con la fecha de salida de la hoteleria.
	$sql .= " 	AND HTL.idliquidaciones = 0";  	
	if ($id > 0){
		$sql .= " 	AND HTL.id".$tabla." = ".$id;
		$sql .= " 	AND HTL.idresponsablesDePago = ".$idresponsablesDePago;
	}
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
			$precio = valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas);

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

function voucherCancelar($ID){
	$_SESSION['voucher'] = $ID;

	$sql = " UPDATE mediapension SET habilitado = 0 WHERE idmediapension = ".$_SESSION['voucher'];
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
	echo $sqlPrecios;
	$resultado = resultFromQuery($sqlPrecios);
	if ($rowLine = siguienteResult($resultado)){
		$precio = $rowLine->precio;
	}
	return $precio;
}

function guardarPrecio($idlistasdeprecios, $idservicios, $precio, $idposadas_internas){
	// consulto si el $idservicios and $idlistasdeprecios exite para ver si hago un UPDATE o INSERT
	$sqlExiste = " select * from servicios_listasdeprecios where idlistasdeprecios ='".$idlistasdeprecios."' and idservicios = '".$idservicios."' and idposadas_internas = '".$idposadas_internas."';";
	$resultado = mysql_fetch_array(resultFromQuery($sqlExiste));	
	
	if ($resultado[0] == null){	
		$query = "insert into servicios_listasdeprecios (idlistasdeprecios, idservicios, precio, idposadas_internas) values ('".$idlistasdeprecios."','".$idservicios."','".$precio."','".$idposadas_internas."');"; 
	}else{
		$query = "update servicios_listasdeprecios set precio = ".$precio." where idlistasdeprecios ='".$idlistasdeprecios."' and idservicios = '".$idservicios."' and idposadas_internas = '".$idposadas_internas."';";
	}
	$result = mysql_query($query);
	echo $query."<br><br><br>";
	return $result;
}

function admitirServicio($idmediapension, $qtdedepaxagora, $precio){
	$sql = "insert mediapension_admisiones (data, idmediapension, qtdedepax, tarifa) values (NOW() ,";
	$sql .= "".$idmediapension.",";
	$sql .= "".$qtdedepaxagora.",";
	$sql .= "".$precio.") ";
	$resultadoStringSQL = resultFromQuery($sql);		
	$idadmision = mysql_insert_id();
	return $idadmision;
}

function valordiaria($data, $idresponsablesDePago, $id, $idservicios, $idposadas){

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
	$sql .= " AND  '".$data."'  BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT ";
	/*
	echo "<hr><font color='white'>";
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
	$sql .= " AND  '".$data."'  BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT ";
	/*
	echo "<hr><font color='green'>";
	echo $sql;
	echo "</font>";
	*/
		$resultado = mysql_fetch_array(resultFromQuery($sql));		
	}

	if ($resultado[0] == null){
		$valor = 0;
	}else{
		$valor = $resultado[0];
	}
	
	return $valor;

}

function calcularDias($idmediapension, $qtycomidas, $dataIN, $dataOUT, $admisiones){
	$daysToShow = $qtycomidas;
	$qtyadmisiones = $admisiones;
	$table = '<TABLE name="Dias" >';
	$table .= '<thead>';
	$table = $table . '<TR>';

	for ($i = 1; $i <= $daysToShow; $i++)
	{
		if ($i <= $qtyadmisiones){
			$table = $table . '<TH>X</TH>';
		}else{
			$table = $table . '<TH>'. $i .'</TH>';
		}
	}
	$table = $table . '<TH>';
	$comidasrestantes = $qtycomidas-$admisiones;
	$table .= ' < '.$comidasrestantes.' servicios restantes | <a href="#myModal" data-toggle="modal" class="" onclick="document.getElementById(\'modal-body\').innerHTML=\'<object id=foo name=foo type=text/html width=530 height=350 data=mediapension.admisiones.php?idmediapension='.$idmediapension.'></object>\'">ver detalle</a>'.' ';
	$table .= ' | <a href="#myModal" data-toggle="modal" class="" onclick="document.getElementById(\'modal-body\').innerHTML=\'<object id=foo name=foo type=text/html width=530 height=250 data=mediapension.admitir.php?idmediapension='.$idmediapension.'></object>\'">admitir servicio</a>'.' > ';
	$table = $table . '</TH>';
	$table = $table . '</TR>';
	$table .= '</thead>';
	$table .= '</TABLE>';


	return $table;
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
								$table = $table . '<TD class="span9">' . calcularDias($row->idmediapension, $row->Dias, $row->dataIN, $row->dataOUT, $row->admisiones) . '</TD>';
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
				$table .= '<TD class="controls"><input type="submit" name="deleteRow['.$colname.']['.$id.']" onclick="javascript:deleteRowEvent('."'".$name."','".$colname."','".$id."'".');" value="Eliminars"></TD>';
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
		$table = $table . '</TABLE><BR>';
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
	
	
?>
