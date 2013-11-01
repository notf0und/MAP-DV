<?php 
include "lib/sessionLib.php";
$idlocales = 2;

echo "Inicio actualizacion...<br>";

// Funciones de Actualizaciones de Locales y Server

function actualizarLocales($idlocales){
	
//	$actualizar = actualizarEnServer($idlocales);
	$actualizarManual = actualizacionDeDatosManual($idlocales);
	
	// $crear = crearTablesDeLocalesConInfo($idlocales);
	/*
	 * 
	 * 1. Selecciono todo lo que tengo para subir y lo subo.
	 * 2. Bajo todo!
	 * 3. Dejo registro de la actualizacion
	 * 
	 * */

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

	if ($idlocales>0){

/*
 * 
 *  ACTUALIZO MEDIAPENSION
 * 
 * */		

		// Setting.
		

		$sql = " SELECT * FROM locales where idlocales =  ".$idlocales; // SELECCION DEL LOCAL
		$resultado = resultFromQuery($sql);	

		if ($row = mysql_fetch_object($resultado)) {
			$db_hostname_local = $row->db_hostname;
			$db_database_local = $row->db_database;
			$db_username_local = $row->db_username;
			$db_password_local = $row->db_password;
		}

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
		
		$sql = " select * from ".$db_database_local.".mediapension MP "; // MP sin actualizar
		$sql .= " inner join ".$db_database_local.".huespedes H on MP.idhuespedes = H.idhuespedes ";
		$sql .= " where MP.actualizado = 0; ";
	echo $sql.'<br>';	
		

		$resultadoMP = resultFromQuery($sql);	

		while ($rowMP = mysql_fetch_object($resultadoMP)) {
			
			echo 'entro !!!';

			$idmediapension = $rowMP->idmediapension;
			$idmediapension_local = $rowMP->idmediapension;
			echo $idmediapension_local;
			$data = $rowMP->data;
			$numeroexterno = $rowMP->numeroexterno;
			$idposadas = $rowMP->idposadas;
			$idoperadoresturisticos = $rowMP->idoperadoresturisticos;
			$idagencias = $rowMP->idagencias;
			$idresponsablesDePago = $rowMP->idresponsablesDePago;
			$idhuespedes = $rowMP->idhuespedes;
			$qtdedepax = $rowMP->qtdedepax;
			$dataIN = $rowMP->dataIN;
			$dataOUT = $rowMP->dataOUT;
			$qtdedecomidas = $rowMP->qtdedecomidas;
			$idservicios = $rowMP->idservicios;
			$mensajeinterno = $rowMP->mensajeinterno;
			$mensajegarcon = $rowMP->mensajegarcon;
			$idlocales = $rowMP->idlocales;
			$idliquidaciones = $rowMP->idliquidaciones;
			$actualizado = $rowMP->actualizado;
			$nomedopax = $rowMP->titular;
			$idpaises = $rowMP->idpaises;
			
			$dbConnection_server = mysql_dbConnect($db_hostname_server, $db_database_server, $db_username_server, $db_password_server);
			$select_db = mysql_select_db($db_database_server, $dbConnection_server);
			echo "DB:".$select_db;
			
			if ($idmediapension > -1) {
				echo "idmediapension:".$idmediapension;

				//INSERT HUESPED
				$sql = "insert ".$db_database_server.".huespedes (titular, idpaises) values (";
				$sql .= "'".$nomedopax."',";
				$sql .= "'".$idpaises."') ";
				echo "sql:".$sql;
				$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
				$idhuespedes = mysql_insert_id();
				echo "idhuespedes:".$idhuespedes;

				bitacoras($_SESSION["idusuarios"], 'Insertar Huesped MP: ID '.$idhuespedes);

				//INSERT MEDIAPENSION
				$sql = "insert ".$db_database_server.".mediapension (numeroexterno, idoperadoresturisticos, idposadas, idagencias, idresponsablesDePago, idhuespedes, qtdedepax, dataIN, dataOUT, qtdedecomidas, idservicios, idlocales, mensajeinterno, mensajegarcon, actualizado) values (";
				$sql .= "'".$numeroexterno."',";
				$sql .= "".$idoperadoresturisticos.",";
				$sql .= "".$idposadas.",";
				$sql .= "".$idagencias.",";
				$sql .= "".$idresponsablesDePago.",";
				$sql .= "".$idhuespedes.",";
				$sql .= "".$qtdedepax.",";
				$sql .= "'".$dataIN."',";
				$sql .= "'".$dataOUT."',";
				$sql .= "".$qtdedecomidas.",";
				$sql .= "".$idservicios.",";
				$sql .= "".$idlocales.",";
				$sql .= "'".$mensajeinterno."',";
				$sql .= "'".$mensajegarcon."',";
				$sql .= "'1') ";
				$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
				$idmediapension = mysql_insert_id();
				bitacoras($_SESSION["idusuarios"], 'Insertar MP: ID '.$idmediapension);

				//INSERT MEDIAPENSION ADMICION
				// consulto todas las admisiones locales y luego realizo un insert por cada admision referenciada con $idmediapension_local
				
				$sql = " select * from ".$db_database_local.".mediapension_admisiones where idmediapension = ".$idmediapension_local;
				$resultado = resultFromQuery($sql);	

				while ($row_admision = mysql_fetch_object($resultado)) {

					$idamision_local = $row_admision->id;
					$datadiaria = date("Y-m-d");
					//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
					$precio = 0;
					$sql = "insert ".$db_database_server.".mediapension_admisiones (data, idmediapension, qtdedepax, tarifa) values (";
					$sql .= "'".$row_admision->data."',";
					$sql .= "".$idmediapension.",";
					$sql .= "".$row_admision->qtdedepax.",";
					$sql .= "".$precio.") ";
					$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
					$idadmision = mysql_insert_id();

					//INSERT MEDIAPENSION TICKTS 
					
					$sql = " select * from ".$db_database_local.".mediapension_tickets where idmediapension_admisiones = ".$idamision_local;
					$resultado = resultFromQuery($sql);	

					while ($row_tickets = mysql_fetch_object($resultado)) {
						$sql = "insert ".$db_database_server.".mediapension_tickets (idtickets, idmediapension_admisiones, idlocales, fecha, actualizado) values (";
						$sql .= "'".$row_tickets->idtickets."',";
						$sql .= "".$idadmision.",";
						$sql .= "".$row_tickets->idlocales.",";
						$sql .= "".$row_tickets->fecha.",";
						$sql .= "1) ";
						$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
						$idtickets = mysql_insert_id();
					}
					
					$sql = " update ".$db_database_local.".mediapension_admisiones set actualizado = 1 where idmediapension = ".$idmediapension_local;
					$resultado = resultFromQuery($sql);									


				}
				
				$sql = " update ".$db_database_local.".mediapension_admisiones set actualizado = 1 where idmediapension = ".$idmediapension_local;
				$resultado = resultFromQuery($sql);	
				
			
			}
				

		}


		//INSERT ADMISIONES SOLAS
		
		$sql = " select * from ".$db_database_local.".mediapension_admisiones where actualizado = 0 ";
		$resultado = resultFromQuery($sql);	

		while ($row_admision = mysql_fetch_object($resultado)) {
			$datadiaria = date("Y-m-d");
			//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
			$precio = 0;
			$sql = "insert ".$db_database_server.".mediapension_admisiones (data, idmediapension, qtdedepax, tarifa) values (";
			$sql .= "'".$row_admision->data."',";
			$sql .= "".$row_admision->idmediapension.",";
			$sql .= "".$row_admision->qtdedepax.",";
			$sql .= "".$precio.") ";
			$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
			$idadmision = mysql_insert_id();
		}

		$sql = " update ".$db_database_local.".mediapension_admisiones set actualizado = 1 where actualizado = 0";
		$resultado = resultFromQuery($sql);	

	 
	}
	return true;
}

function actualizacionDeDatosManual($idlocales){
/* EN JF	
	$dbOrigen = 'dasamericas';
	$dbOrigenHost = 'davincimp.no-ip.info';
	$dbOrigenUser = 'root';
	$dbOrigenPass = 'password';
	$remotePath = '/tmp/dasamericasBackup.sql';
*/
	$dbOrigen = 'dasamericas';
	$dbOrigenHost = 'localhost';
	$dbOrigenUser = 'root';
	$dbOrigenPass = 'password';
	$remotePath = '/tmp/dasamericasBackup.sql';


	$dbDestino = 'DA_JF';
	$dbDestinoUser = 'root';
	$dbDestinoPass = 'password';
	$localPath = '/tmp/dasamericasBackup.sql';
	
	if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
	// log in at server1.example.com on port 22
	if(!($con = ssh2_connect("localhost", 22))){
		echo "Sin conexion!";
	} else {
		// try to authenticate with username root, password secretpassword
		if(!ssh2_auth_password($con, $_SESSION["idlocales_PRN_USER"], $_SESSION["idlocales_PRN_PASS"])) {
			echo "Error en autenticacion!";
		} else {
			// allright, we're in!
			echo "ok: logged in...\n";
			echo "<br>";
			// execute a command
			$sqldump = "mysqldump --opt -h ".$dbOrigenHost." -u ".$dbOrigenUser." -p".$dbOrigenPass." ".$dbOrigen." > ".$localPath."";
			echo "<br>".$sqldump;
			echo "<br>";
			if (!(ssh2_exec($con, $sqldump))) {
				echo "Error al ejecutar comando!";
				echo "<br>";
			} else {
				echo "Ejecutando comandos... <br>";
				echo "<br>";
				echo "Backup OK <br>";
				echo "<br>";
				$sqlrestore = "mysql -u ".$dbDestinoUser." -p".$dbDestinoPass." ".$dbDestino." < ".$remotePath."";
				echo "<br>".$sqlrestore;
				echo "<br>";
				$restore = ssh2_exec($con, $sqlrestore);
				var_dump($restore);
				echo "<br>";
				echo "Documento actualizado OK <br>";
				echo "<br>";
	
	/*
				$delete = ssh2_exec($con, "rm /tmp/DA/dasamericasBackup.sql");
				echo "Documento Deleted OK <br>";
	*/
			
			}
		}
	}
	
	
}


$resultado = actualizarLocales($idlocales);
echo $resultado;
echo '<hr>';

/*
$sql = " SELECT * FROM locales where idlocales = ".$idlocales;
$resultado = resultFromQuery($sql);	

while ($row = mysql_fetch_object($resultado)) {
	$db_hostname = $row->db_hostname;
	$db_database = $row->db_database;
	$db_username = $row->db_username;
	$db_password = $row->db_password;
}
	echo '<hr>';

$dbConnection2 = &mysql_dbConnect($db_hostname, $db_database, $db_username, $db_password);
$sql = " SELECT * FROM agencias ";
$resultado = resultFromQuery($sql, $dbConnection2);	

while ($row = mysql_fetch_object($resultado)) {
	echo $row->nombre.'<BR>';
}
	echo '<hr>';
*/
	
function crearTablesDeLocalesConInfo($idlocales){

	/*
	 * CAMPO DE ACCION: Esta funcion solo debe ser ejecutada desde las terminales.
	 * 
	 * 1. Creo tabla a partir de la original.
	 * 
	 */

	if ($idlocales>0){

		// Setting.
		
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


		$sql = " SELECT * FROM ".$db_database_server.".actualizables";
		$resultado = resultFromQuery($sql);	

		while ($row = mysql_fetch_object($resultado)) {
			echo $row->tablename.'<BR>';
			$drop = " drop table if exists ".$db_database_local.".".$row->tablename."; ";
			$create = " create table ".$db_database_local.".".$row->tablename." select * from ".$db_database_server.".".$row->tablename."; ";
			echo $create;
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

	echo 'Actualizo';
	return true;
}


?>
