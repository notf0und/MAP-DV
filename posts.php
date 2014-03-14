<?php 
	include "lib/sessionLib.php";
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	//include "dBug.php";
	//new dBug($_POST);




if (isset( $_POST['accion'] )) { 

/* Usuarios */

	if ($_POST['accion'] == 'validarUsuario') {
		$sesionDmasD = new SesionDmasD;
		$sesionDmasD->initialize();
		$_SESSION["sesionDmasD"] = &$sesionDmasD;		
		
		if (esLoginValido($sesionDmasD->usuario, $_POST['username'], $_POST['password'])) {
			if (isset($sesionDmasD->usuario->empleado->id)) {
				loadEstadosDeItems($sesionDmasD);
				loadUsuarioDelSistema($sesionDmasD);
				$sesionDmasD->periodoDeEvaluacion = periodoDeEvaluacionFromDBPara($sesionDmasD->usuario->empleado->id);
				loadEvaluacionDeSesion($sesionDmasD);
				$sesionDmasD->evaluacionEnRevision = &$sesionDmasD->evaluacionDeEmpleado;
				$sesionDmasD->empleadoEnRevision = &$sesionDmasD->usuario->empleado;
				personalizarObjetivosCualitativos($sesionDmasD);
				
				loadMensajes($sesionDmasD);
			}
			echo '<script languaje="javascript"> self.location="perfil.php"</script>';
		} else {
			echo '<script languaje="javascript"> self.location="index.php"</script>';
		}
	}

	if ($_POST['accion'] == 'login') {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$idlocales = $_POST['idlocales'];
		
		//Protect for MySQL injection
		$username = stripslashes($username);
		$password = stripslashes($password);
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);

		$sql = "SELECT * FROM usuarios  ";
		$sql .= " WHERE 1 ";
		$sql .= " AND username = '".$username."'";
		$sql .= " AND password = '".md5($password)."'";
		
		$resultadoStringSQL = resultFromQuery($sql);		
		
		if ($row = siguienteResult($resultadoStringSQL)) {
			$_SESSION["username"] = $row->username;		
			$_SESSION["idusuarios_tipos"] = $row->idusuarios_tipos;		
			$_SESSION["idusuarios"] = $row->idusuarios;		
			$_SESSION["NombreCompleto"] = $row->NombreCompleto;
			$_SESSION["login"] = 1;	
			$_SESSION["idlocales"] = $idlocales;
			/* BEGIN Chanchuyo | A continuacion se hara cun chanchuyo rapido para identificar a los locales ... ARREGLAR CON PRIORIDAD*/
			
			$sql = "UPDATE usuarios set last_login = current_timestamp where idusuarios = ".$row->idusuarios;
			$result = resultFromQuery($sql);
			

			bitacoras($_SESSION["idusuarios"], 'Login usuario: '.$_SESSION["username"]);
			echo '<script languaje="javascript"> self.location="index.php"</script>';
		} else {
			bitacoras(0, 'Login incorrecto: user '.$username.' pass '.$password);
			echo '<script languaje="javascript"> self.location="start.php?error=1"</script>';
		}
		
	}

	if ($_POST['accion'] == 'userChangePassword') {
		$username = $_SESSION["username"];
		$oldpassword = $_POST['oldpassword'];
		$password = $_POST['password'];
		
		//Protect for MySQL injection
		$username = stripslashes($username);
		$oldpassword = stripslashes($oldpassword);
		$password = stripslashes($password);
		$username = mysql_real_escape_string($username);
		$oldpassword = mysql_real_escape_string($oldpassword);
		$password = mysql_real_escape_string($password);
		
		$sql = "SELECT * FROM usuarios  ";
		$sql .= " WHERE 1 ";
		$sql .= " AND username = '".$username."'";
		$sql .= " AND password = '".md5($oldpassword)."';";

		$resultadoStringSQL = resultFromQuery($sql);		
		
		if ($row = siguienteResult($resultadoStringSQL))
		{
			$sql = "UPDATE usuarios SET";
			$sql .= " password = '".md5($password)."'";
			$sql .= " WHERE username = '".$username."'";
			$sql .= " AND password = '".md5($oldpassword)."';";
		
			$update = resultFromQuery($sql);
			echo 'Senha modificada';
			bitacoras($_SESSION["idusuarios"], $_SESSION["username"].' altero sua senha por: '.$password);
			echo '<script languaje="javascript"> self.location="index.php"</script>';
		}
		else 
		{
			echo 'Senha actual errada';
			echo '<script languaje="javascript"> self.location="user.changepassword.php"</script>';
		}
		
		
		
	}

/* Administradores */

	if ($_POST['accion'] == 'nuevaAgenciaTipoUser2') {

		$idagencias = $_POST['idagencias'];
		$nomedoagencia = $_POST['nomedoagencia'];

		//INSERT AGENCIA
		$sql = "insert agencias (nombre) values (";
		$sql .= "'".$nomedoagencia."') ";
		$resultadoStringSQL = resultFromQuery($sql);		

		echo '<script languaje="javascript"> self.location="mediapension.php"</script>';
	}

	if ($_POST['accion'] == 'admitirPosadas') {

		$idposadas = $_POST['idposadas'];
		$nombre = $_POST['nombre'];
		$telefono = $_POST['telefono'];

		if ($idposadas > -1) {

			//UPDATE POSADA
			$sql = "update posadas set ";
			$sql .= " nombre = '".$nombre."',";
			$sql .= " telefono = '".$telefono."'";
			$sql .= "where idposadas = ".$idposadas;
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Posada: ID '.$idposadas);
			echo '<script languaje="javascript"> self.location="administradores.posadas.php"</script>';
			
		} else {
		
			//INSERT POSADA
			$sql = "insert posadas (nombre, telefono) values (";
			$sql .= "'".$nombre."',";
			$sql .= "'".$telefono."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idposadas = mysql_insert_id();

			bitacoras($_SESSION["idusuarios"], 'Insertar Posada: ID '.$idposadas);
			echo '<script languaje="javascript"> self.location="administradores.posadas.php"</script>';

		}
		
	}

	if ($_POST['accion'] == 'PosadasNew') {
		$_SESSION['idposadas'] = -1;
		echo '<script languaje="javascript"> self.location="administradores.posadas.edit.php"</script>';
	}

	if ($_POST['accion'] == 'PosadasModify') {
		$_SESSION['idposadas'] = $_POST['idposadas'];
		echo '<script languaje="javascript"> self.location="administradores.posadas.edit.php"</script>';
	}

	if ($_POST['accion'] == 'PosadasDelete') {
		posadasCancelar($_POST['idposadas']);
		echo '<script languaje="javascript"> self.location="administradores.posadas.php"</script>';
	}

	if ($_POST['accion'] == 'admitirAgencias') {

		$idagencias = $_POST['idagencias'];
		$nombre = $_POST['nombre'];
		$telefono = $_POST['telefono'];

		if ($idagencias > -1) {

			//UPDATE AGENCIAS
			$sql = "update agencias set ";
			$sql .= " nombre = '".$nombre."',";
			$sql .= " telefono = '".$telefono."'";
			$sql .= "where idagencias = ".$idagencias;
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Agencia: ID '.$idagencias);
			echo '<script languaje="javascript"> self.location="administradores.agencias.php"</script>';
			
		} else {
		
			//INSERT AGENCIAS
			$sql = "insert agencias (nombre, telefono) values (";
			$sql .= "'".$nombre."',";
			$sql .= "'".$telefono."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idagencias = mysql_insert_id();

			bitacoras($_SESSION["idusuarios"], 'Insertar Agencia: ID '.$idagencias);
			echo '<script languaje="javascript"> self.location="administradores.agencias.php"</script>';

		}
		
	}

	if ($_POST['accion'] == 'AgenciasNew') {
		$_SESSION['idagencias'] = -1;
		echo '<script languaje="javascript"> self.location="administradores.agencias.edit.php"</script>';
	}

	if ($_POST['accion'] == 'AgenciasModify') {
		$_SESSION['idagencias'] = $_POST['idagencias'];
		echo '<script languaje="javascript"> self.location="administradores.agencias.edit.php"</script>';
	}

	if ($_POST['accion'] == 'AgenciasDelete') {
		agenciasCancelar($_POST['idagencias']);
		echo '<script languaje="javascript"> self.location="administradores.agencias.php"</script>';
	}

	if ($_POST['accion'] == 'admitirOperadoresturisticos') {

		$idoperadoresturisticos = $_POST['idoperadoresturisticos'];
		$nombre = $_POST['nombre'];
		$telefono = $_POST['telefono'];

		if ($idoperadoresturisticos > -1) {

			//UPDATE operadoresturisticos
			$sql = "update operadoresturisticos set ";
			$sql .= " nombre = '".$nombre."',";
			$sql .= " telefono = '".$telefono."'";
			$sql .= "where idoperadoresturisticos = ".$idoperadoresturisticos;
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Operador Turistico: ID '.$idoperadoresturisticos);
			echo '<script languaje="javascript"> self.location="administradores.operadoresturisticos.php"</script>';
			
		} else {
		
			//INSERT operadoresturisticos
			$sql = "insert operadoresturisticos (nombre, telefono) values (";
			$sql .= "'".$nombre."',";
			$sql .= "'".$telefono."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idagencias = mysql_insert_id();

			bitacoras($_SESSION["idusuarios"], 'Insertar Operador Turistico: ID '.$idagencias);
			echo '<script languaje="javascript"> self.location="administradores.operadoresturisticos.php"</script>';

		}
		
	}

	if ($_POST['accion'] == 'OperadoresturisticosNew') {
		$_SESSION['idoperadoresturisticos'] = -1;
		echo '<script languaje="javascript"> self.location="administradores.operadoresturisticos.edit.php"</script>';
	}

	if ($_POST['accion'] == 'OperadoresturisticosModify') {
		$_SESSION['idoperadoresturisticos'] = $_POST['idoperadoresturisticos'];
		echo '<script languaje="javascript"> self.location="administradores.operadoresturisticos.edit.php"</script>';
	}
	
	if ($_POST['accion'] == 'OperadoresturisticosDelete') {
		$operador = operadoresturisticosCancelar($_POST['idoperadoresturisticos']);
		
		bitacoras($_SESSION["idusuarios"], 'Eliminado Operador Turistico ID:'.$_POST['idoperadoresturisticos'].' por el usuario '.$_POST['username']);
		
		echo '<script languaje="javascript"> self.location="administradores.operadoresturisticos.php"</script>';

	}

	if ($_POST['accion'] == 'admitirListasdeprecios') {

		$idlistasdeprecios = $_POST['idlistasdeprecios'];
		$nombre = $_POST['nombre'];
		$VigenciaIN = $_POST['VigenciaIN'];
		$VigenciaOUT = $_POST['VigenciaOUT'];
		$idresponsablesDePago = $_POST['idresponsablesDePago'];
		$iditem = $_POST['iditem'];

		if ($idlistasdeprecios > -1) {

			//UPDATE AGENCIAS
			$sql = "update listasdeprecios set ";
			$sql .= " nombre = '".$nombre."',";
			$sql .= " VigenciaIN = '".$VigenciaIN."',";
			$sql .= " VigenciaOUT = '".$VigenciaOUT."',";
			$sql .= " idresponsablesDePago = '".$idresponsablesDePago."',";
			$sql .= " iditem = '".$iditem."'";
			$sql .= "where idlistasdeprecios = ".$idlistasdeprecios;
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Listas de precios: ID '.$idlistasdeprecios);
			echo '<script languaje="javascript"> self.location="administradores.listasdeprecios.php"</script>';
			
		} else {
		
			//INSERT AGENCIAS
			$sql = "insert listasdeprecios (nombre, VigenciaIN, VigenciaOUT, idresponsablesDePago, iditem) values (";
			$sql .= "'".$nombre."',";
			$sql .= "'".$VigenciaIN."',";
			$sql .= "'".$VigenciaOUT."',";
			$sql .= "'".$idresponsablesDePago."',";
			$sql .= "'".$iditem."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idlistasdeprecios = mysql_insert_id();

			bitacoras($_SESSION["idusuarios"], 'Insertar Listas de precios: ID '.$idlistasdeprecios);
			echo '<script languaje="javascript"> self.location="administradores.listasdeprecios.php"</script>';

		}
		
	}

	if ($_POST['accion'] == 'admitirPrecios01') {

		$idlistasdeprecios = $_POST['idlistasdeprecios'];
		$iditem = $_POST['iditem'];

		if ($idlistasdeprecios > -1) {

			$sql = "update listasdeprecios set ";
			$sql .= " iditem = '".$iditem."'";
			$sql .= "where idlistasdeprecios = ".$idlistasdeprecios;
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Listas de precios: ID '.$idlistasdeprecios);
			echo '<script languaje="javascript"> self.location="administradores.listasdeprecios.precios.paso02.php?id='.$idlistasdeprecios.'"</script>';
			
		}
		
	}

	if ($_POST['accion'] == 'admitirPrecios02') {

		$idlistasdeprecios = $_POST['idlistasdeprecios'];
		$iditem = $_POST['iditem'];

		if ($idlistasdeprecios > -1) {

			// aca tengo que hacer ese splitloco y guardarPrecio($idlistasdeprecios, $idservicios, $precio) segun corresponda
			// Todavia esta rigida la parte de posadas involucradas 
			// si el iditem es igual a 0 solo cargo MP else recorr del 1 al 4 que son las podasas internas ... ESTO ESTA MAL ... mojorarlo.

			if ($iditem == 0){

				$sqlPosadas = " SELECT * FROM posadas WHERE idposadas < 5 ";
				$resultadoStringSQL = resultFromQuery($sqlPosadas);
				
				
				if ($_SESSION["idusuarios"] == 13){
				
				}

				while ($row = mysql_fetch_object($resultadoStringSQL)) {

					if ($row->idposadas==0){
						for($i=1;$i<6;$i++){
							$precio = $_POST[$row->idposadas.'_'.$i];
							$idservicios = $i;
							$idposadas_internas = $row->idposadas;
							$resultado = guardarPrecio($idlistasdeprecios, $idservicios, $precio, $idposadas_internas);
							echo ' resultado = :'.$resultado.' - <br>';
							echo ' servicio:'.$i.' - posada:'.$row->idposadas.' - precio:'.$precio.' <br>';
						}
					}else{
						for($i=6;$i<21;$i++){
							$precio = $_POST[$row->idposadas.'_'.$i];
							$idservicios = $i;
							$idposadas_internas = $row->idposadas;
							$resultado = guardarPrecio($idlistasdeprecios, $idservicios, $precio, $idposadas_internas);
							echo ' resultado = :'.$resultado.' - <br>';
							echo ' servicio:'.$i.' - posada:'.$row->idposadas.' - precio:'.$precio.' <br>';
						}
					}
				}
			}else{
				
				$sqlPosadas = " SELECT * FROM posadas WHERE idposadas < 5 ";
				$resultadoStringSQL = resultFromQuery($sqlPosadas);
				
				
				if ($_SESSION["idusuarios"] == 13){
					//new dBug($resultadoStringSQL);
				}

				while ($row = mysql_fetch_object($resultadoStringSQL)) {
				
					if ($row->idposadas==0){
						for($i=1;$i<6;$i++){
							$precio = $_POST[$row->idposadas.'_'.$i];
							$idservicios = $i;
							$idposadas_internas = $row->idposadas;
							$resultado = guardarPrecio($idlistasdeprecios, $idservicios, $precio, $idposadas_internas);
							echo ' resultado = :'.$resultado.' - <br>';
							echo ' servicio:'.$i.' - posada: 0 - precio:'.$precio.' <br>';
						}
					}
					else{
						for($i=6;$i<21;$i++){
							$precio = $_POST[$row->idposadas.'_'.$i];
							$idservicios = $i;
							$idposadas_internas = $row->idposadas;
							$resultado = guardarPrecio($idlistasdeprecios, $idservicios, $precio, $idposadas_internas);
							echo ' resultado = :'.$resultado.' - <br>';
							echo ' servicio:'.$i.' - posada: 0 - precio:'.$precio.' <br>';
						}
					}
				}
				
			}

	/*
			$sql = "update listasdeprecios set ";
			$sql .= " iditem = '".$iditem."'";
			$sql .= "where idlistasdeprecios = ".$idlistasdeprecios;
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Listas de precios: ID '.$idlistasdeprecios);
			echo '<script languaje="javascript"> self.location="administradores.listasdeprecios.precios.paso02.php"</script>';
	*/		
			echo '<script languaje="javascript"> self.location="administradores.listasdeprecios.php"</script>';

		}
		
	}

	if ($_POST['accion'] == 'administradoresServiceNew') {
		
		$referer = $_POST['referer'];
		
		if (isset($_POST['idservicios']) && $_POST['idservicios'] != ''){
			
			$sql = "UPDATE servicios SET ";
			$sql .= "nombre = '".$_POST['nombre']."', ";
			$sql .= "ComidasDiarias = ".$_POST['ComidasDiarias']." ";
			$sql .= "WHERE idservicios = ".$_POST['idservicios']." ";
			
			$result = resultFromQuery($sql);
		}
		else{ //INSERT
			$sql = "SELECT MAX(idservicios) as 'idservicios' FROM servicios; ";
			
			$result = resultFromQuery($sql);
			$row = siguienteResult($result);
			$idservicios = $row->idservicios + 1;
			
			$sql = "INSERT INTO servicios(idservicios, nombre, ComidasDiarias) VALUES(";
			$sql .= $idservicios.", ";
			$sql .= "'".$_POST['nombre']."', ";
			$sql .= $_POST['ComidasDiarias'].") ";
			
			$result = resultFromQuery($sql);
						
		}
				
		echo '<script languaje="javascript"> self.location="'.$referer.'"</script>';

	}
	
	if ($_POST['accion'] == 'administradoresServiceModify') {
		
		
		//echo $referer;
		$_SESSION['idservicios'] = $_POST['idservicios'];
		$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
		echo '<script languaje="javascript"> self.location="administradores.servicios.novo.php"</script>';
		
	}

	if ($_POST['accion'] == 'ListasdepreciosNew') {
		$_SESSION['idlistasdeprecios'] = -1;
		echo '<script languaje="javascript"> self.location="administradores.listasdeprecios.edit.php"</script>';
	}

	if ($_POST['accion'] == 'ListasdepreciosModify') {
		$_SESSION['idlistasdeprecios'] = $_POST['ID'];
		echo '<script languaje="javascript"> self.location="administradores.listasdeprecios.edit.php"</script>';
	}



/* Hoteleria */

	if ($_POST['accion'] == 'admitirHoteleria') {
		
		$referer = $_POST['referer'];
		
		$idhoteleria = $_POST['idhoteleria'];
		$numeroexterno = $_POST['numeroexterno'];
		$nomedopax = $_POST['nomedopax'];
		$idpaises = $_POST['idpaises'];
		$idoperadoresturisticos = $_POST['idoperadoresturisticos'];
		$idposadas = $_POST['idposadas'];
		$idagencias = $_POST['idagencias'];
		$idhuespedes = $_POST['idhuespedes'];
		$idresponsablesDePago = $_POST['idresponsablesDePago'];
		$qtdedepax = $_POST['qtdedepax'];
		$dataIN = $_POST['dataIN'];
		$dataOUT = $_POST['dataOUT'];
		$qtdedenoites = $_POST['qtdedenoites'];
		$idservicios = $_POST['idservicios'];
		$idlocales = $_SESSION["idlocales"];

		if ($idhoteleria > -1) {

			//UPDATE HOTELERIA
			$sql = "update hoteleria set ";
			$sql .= " numeroexterno = '".$numeroexterno."',";
			$sql .= " idoperadoresturisticos = ".$idoperadoresturisticos.",";
			$sql .= " idposadas = ".$idposadas.",";
			$sql .= " idagencias = ".$idagencias.",";
			$sql .= " idresponsablesDePago = ".$idresponsablesDePago.",";
			$sql .= " idhuespedes = ".$idhuespedes.",";
			$sql .= " qtdedepax = ".$qtdedepax.",";
			$sql .= " dataIN = '".$dataIN."',";
			$sql .= " dataOUT = '".$dataOUT."',";
			$sql .= " qtdedenoites = ".$qtdedenoites.",";
			$sql .= " idservicios = ".$idservicios.", ";
			$sql .= " idlocales = ".$idlocales." ";
			$sql .= "where idhoteleria = ".$idhoteleria;
			$resultadoStringSQL = resultFromQuery($sql);		

			//UPDATE HUESPED
			$sql = "update huespedes set ";
			$sql .= " titular = '".$nomedopax."', ";
			$sql .= " idpaises = '".$idpaises."' ";
			$sql .= " where idhuespedes = '".$idhuespedes."' ";
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Voucher HTL: ID '.$idhoteleria);
		} 
		else {
		
			//INSERT HUESPED
			$sql = "insert huespedes (titular, idpaises) values (";
			$sql .= "'".$nomedopax."',";
			$sql .= "'".$idpaises."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idhuespedes = mysql_insert_id();
			bitacoras($_SESSION["idusuarios"], 'Insertar Huesped HTL: ID '.$idhuespedes);

			//INSERT HOTELERIA
			$sql = "insert hoteleria (numeroexterno, idoperadoresturisticos, idposadas, idagencias, idresponsablesDePago, idhuespedes, qtdedepax, dataIN, dataOUT, qtdedenoites, idservicios, idlocales) values (";
			$sql .= "'".$numeroexterno."',";
			$sql .= "".$idoperadoresturisticos.",";
			$sql .= "".$idposadas.",";
			$sql .= "".$idagencias.",";
			$sql .= "".$idresponsablesDePago.",";
			$sql .= "".$idhuespedes.",";
			$sql .= "".$qtdedepax.",";
			$sql .= "'".$dataIN."',";
			$sql .= "'".$dataOUT."',";
			$sql .= "".$qtdedenoites.",";
			$sql .= "".$idservicios.",";
			$sql .= "".$idlocales.") ";
			
			$resultadoStringSQL = resultFromQuery($sql);		
			$idmediapension = mysql_insert_id();
			
			bitacoras($_SESSION["idusuarios"], 'Insertar HTL: ID '.$idhoteleria);
		}
		
		if (isset($referer)){
			echo '<script languaje="javascript"> self.location="'.$referer.'"</script>';
		}
		else{
			echo '<script languaje="javascript"> self.location="hoteleria.vouchers.php"</script>';
		}
		
	}

	if ($_POST['accion'] == 'VouchersHTLNew') {
		$_SESSION['idhoteleria'] = -1;
		$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
		echo '<script languaje="javascript"> self.location="hoteleria.vouchers.edit.php"</script>';
	}

	if ($_POST['accion'] == 'VouchersHTLModify') {
		$_SESSION['idhoteleria'] = $_POST['id'];
		$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
		echo '<script languaje="javascript"> self.location="hoteleria.vouchers.edit.php"</script>';
	}

	if ($_POST['accion'] == 'VouchersHTLDelete') {
		
		$sql = "update hoteleria set habilitado = 0 where 1 and idhoteleria = ".$_POST['id'];
		$resultadoStringSQL = resultFromQuery($sql);
		echo '<script languaje="javascript"> self.location="hoteleria.vouchers.php"</script>';
	}
		
/* Media Pension */

	if ($_POST['accion'] == 'admitirServicio') {

/*
 * MODIFICAR la accion para que pueda usar la nueva version de la funcion 
 * valordiaria($data, $idresponsablesDePago, $id, $idservicios)
 * Subir la modificacion a las terminales de tickets.
 * */

		$idmediapension = $_POST['idmediapension'];
		$qtdedepaxagora = $_POST['qtdedepaxagora'];

		$sql = "SELECT idservicios, idresponsablesDePago, idposadas, idagencias, idoperadoresturisticos FROM mediapension MP  ";
		$sql .= " WHERE 1 ";
		$sql .= " AND MP.idmediapension = ".$idmediapension;

		$resultadoStringSQL = resultFromQuery($sql);		
		$row = siguienteResult($resultadoStringSQL);
		$idposadas = $row->idposadas;
		$idagencias = $row->idagencias;
		$idoperadoresturisticos = $row->idoperadoresturisticos;
		$idresponsablesDePago = $row->idresponsablesDePago;
		$idservicios = $row->idservicios;

		$sql = " SELECT idresponsablesDePago, nombre, tabla ";
		$sql .= " FROM responsablesDePago "; 
		$sql .= " WHERE 1 "; 
		$sql .= " AND idresponsablesDePago = ".$idresponsablesDePago;
		 
		$resultadoResponsables= resultFromQuery($sql);	

		if ($rowLine = siguienteResult($resultadoResponsables)) {
			$id = ${'id'.$rowLine->tabla};
		}
		else
		{
			$id = 0;
		}

		$datadiaria = date("Y-m-d");
		//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
		$precio = valordiaria($datadiaria, $idresponsablesDePago, $id, $idservicios, $idposadas);
		
		$idlocales = $_SESSION["idlocales"];

		$idadmision = admitirServicio($idmediapension, $qtdedepaxagora, $precio, $idlocales);
		bitacoras($_SESSION["idusuarios"], 'Admitir servicio MP: ID '.$idmediapension);
		echo '<script languaje="javascript"> top.location="mediapension.print.php?id='.$idadmision.'"</script>';


	}

	if ($_POST['accion'] == 'AdmisionesMPDelete') {
		$id = array_search('Apagar', $_POST['deleteRow']['id']);
		
		$sql = 'DELETE FROM mediapension_admisiones where id = '.$id;
		
		$result = resultFromQuery($sql);		

		
		bitacoras($_SESSION["idusuarios"], 'Apagada admisão '.$id);
		echo '<script languaje="javascript"> top.location="mediapension.vouchers.php"</script>';	

	}

	if ($_POST['accion'] == 'admitirMediapension') {
		
		$referer = $_POST['referer'];

		$idmediapension = $_POST['idmediapension'];
		$numeroexterno = $_POST['numeroexterno'];//ok
		$nomedopax = $_POST['nomedopax'];//ok
		$idpaises = $_POST['idpaises'];//ok
		$idoperadoresturisticos = $_POST['idoperadoresturisticos'];//ok
		$idposadas = $_POST['idposadas'];//ok
		$idagencias = $_POST['idagencias'];//ok
		$idhuespedes = $_POST['idhuespedes'];//ok
		$idresponsablesDePago = $_POST['idresponsablesDePago'];//ok
		$qtdedepax = $_POST['qtdedepax'];//ok
		$qtdedepaxagora = isset($_POST['qtdedepaxagora']) ? $_POST['qtdedepaxagora'] : '';//ok//1
		$dataIN = $_POST['dataIN'];//ok
		$dataOUT = $_POST['dataOUT'];//ok
		$qtdedecomidas = $_POST['qtdedecomidas'];//ok
		$idservicios = $_POST['idservicios'];//ok
		$mensajeinterno = isset($_POST['qtdedepaxagora']) ? $_POST['mensajeinterno'] : '';//ok//1
		$mensajegarcon = isset($_POST['qtdedepaxagora']) ? $_POST['mensajegarcon'] : '';//ok//1
		$idlocales = $_SESSION["idlocales"];//ok
		
		//Set default values for comboboxes
		if ($idoperadoresturisticos === '') $idoperadoresturisticos = '0';
		if ($idposadas === '') $idposadas = '0';
		if ($idagencias === '') $idagencias = '0';
		if ($idresponsablesDePago === '') $idresponsablesDePago = '0';
		
		$idlocales = $_SESSION["idlocales"];
		
		if ($idlocales==0){
			$actualizado = 1;
			$hoteleria = $_POST['hoteleria'];
		}else{
			$actualizado = 0;
			$hoteleria = 0;
		}
		
		if ($idmediapension && $idmediapension != -1) {

			//UPDATE MEDIAPENSION
			$sql = "update mediapension set ";
			$sql .= " numeroexterno = '".$numeroexterno."',";
			$sql .= " idoperadoresturisticos = ".$idoperadoresturisticos.",";
			$sql .= " idposadas = ".$idposadas.",";
			$sql .= " idagencias = ".$idagencias.",";
			$sql .= " idresponsablesDePago = ".$idresponsablesDePago.",";
			$sql .= " idhuespedes = ".$idhuespedes.",";
			$sql .= " qtdedepax = ".$qtdedepax.",";
			$sql .= " dataIN = '".$dataIN."',";
			$sql .= " dataOUT = '".$dataOUT."',";
			$sql .= " qtdedecomidas = ".$qtdedecomidas.",";
			$sql .= " idservicios = ".$idservicios.", ";
			$sql .= " idlocales = ".$idlocales.", ";
			$sql .= " hoteleria = ".$hoteleria.", ";
			$sql .= " actualizado = ".$actualizado." ";
			$sql .= "where idmediapension = ".$idmediapension;
			$resultadoStringSQL = resultFromQuery($sql);		

			//UPDATE HUESPED
			$sql = "update huespedes set ";
			$sql .= " titular = '".$nomedopax."', ";
			$sql .= " idpaises = '".$idpaises."' ";
			$sql .= " where idhuespedes = '".$idhuespedes."' ";
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Voucher MP: ID '.$idmediapension);
			
		} else {
		
			//INSERT HUESPED
			$sql = "insert huespedes (titular, idpaises) values (";
			$sql .= "'".$nomedopax."',";
			$sql .= "'".$idpaises."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idhuespedes = mysql_insert_id();
			bitacoras($_SESSION["idusuarios"], 'Insertar Huesped MP: ID '.$idhuespedes);

			//INSERT MEDIAPENSION
			$sql = "insert mediapension (numeroexterno, idoperadoresturisticos, idposadas, idagencias, idresponsablesDePago, idhuespedes, qtdedepax, dataIN, dataOUT, qtdedecomidas, idservicios, idlocales, hoteleria, actualizado, mensajeinterno, mensajegarcon) values (";
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
			$sql .= "".$hoteleria.",";
			$sql .= "".$actualizado.",";
			$sql .= "'".$mensajeinterno."',";
			$sql .= "'".$mensajegarcon."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idmediapension = mysql_insert_id();
			bitacoras($_SESSION["idusuarios"], 'Insertar MP: ID '.$idmediapension);
			$idlocales = $_SESSION["idlocales"];
			echo $idlocales;
			
			if ($qtdedepaxagora)
			{ 
			bitacoras($_SESSION["idusuarios"], 'Insertar admision x '.$qtdedepaxagora);
			//INSERT MEDIAPENSION ADMICION
			$datadiaria = date("Y-m-d");
			//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
			$precio = 0;
			$idadmision = admitirServicio($idmediapension, $qtdedepaxagora, $precio, $idlocales);
			}
	
		}
		
		if ($_SESSION["idlocales"]>0){
				echo '<script languaje="javascript"> self.location="mediapension.print.php?id='.$idadmision.'"</script>';
			}else{
				if (isset($referer)){
					echo '<script languaje="javascript"> self.location="'.$referer.'"</script>';
				}
				else{
					echo '<script languaje="javascript"> self.location="mediapension.lista.php"</script>';
				}
			}		
	}

	if ($_POST['accion'] == 'borrarMediapension') {

		$idmediapension = $_POST['idmediapension'];

		if ($idmediapension > -1) {

			//UPDATE MEDIAPENSION
			$sql = "delete mediapension set ";
			$sql .= " numeroexterno = '".$numeroexterno."',";
			$sql .= " idoperadoresturisticos = ".$idoperadoresturisticos.",";
			$sql .= " idposadas = ".$idposadas.",";
			$sql .= " idagencias = ".$idagencias.",";
			$sql .= " idresponsablesDePago = ".$idresponsablesDePago.",";
			$sql .= " idhuespedes = ".$idhuespedes.",";
			$sql .= " qtdedepax = ".$qtdedepax.",";
			$sql .= " dataIN = '".$dataIN."',";
			$sql .= " dataOUT = '".$dataOUT."',";
			$sql .= " qtdedecomidas = ".$qtdedecomidas.",";
			$sql .= " idservicios = ".$idservicios.", ";
			$sql .= " idlocales = ".$idlocales." ";
			$sql .= "where idmediapension = ".$idmediapension;
			$resultadoStringSQL = resultFromQuery($sql);		

			//UPDATE HUESPED
			$sql = "update huespedes set ";
			$sql .= " titular = '".$nomedopax."', ";
			$sql .= " idpaises = '".$idpaises."' ";
			$sql .= " where idhuespedes = '".$idhuespedes."' ";
			$resultadoStringSQL = resultFromQuery($sql);		

			bitacoras($_SESSION["idusuarios"], 'Modificacion de Voucher MP: ID '.$idmediapension);
			echo '<script languaje="javascript"> self.location="mediapension.vouchers.php"</script>';
			
		} else {
		
			//INSERT HUESPED
			$sql = "insert huespedes (titular, idpaises) values (";
			$sql .= "'".$nomedopax."',";
			$sql .= "'".$idpaises."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idhuespedes = mysql_insert_id();
			bitacoras($_SESSION["idusuarios"], 'Insertar Huesped MP: ID '.$idhuespedes);

			//INSERT MEDIAPENSION
			$sql = "insert mediapension (numeroexterno, idoperadoresturisticos, idposadas, idagencias, idresponsablesDePago, idhuespedes, qtdedepax, dataIN, dataOUT, qtdedecomidas, idservicios, idlocales, mensajeinterno, mensajegarcon) values (";
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
			$sql .= "'".$mensajegarcon."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idmediapension = mysql_insert_id();
			bitacoras($_SESSION["idusuarios"], 'Insertar MP: ID '.$idmediapension);

			//INSERT MEDIAPENSION ADMICION
			$datadiaria = date("Y-m-d");
			//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
			$precio = 0;
			$idadmision = admitirServicio($idmediapension, $qtdedepaxagora, $precio);
			
			echo '<script languaje="javascript"> self.location="mediapension.print.php?id='.$idadmision.'"</script>';
		}
		
	}
	
	if ($_POST['accion'] == 'ReportesModify') {
		$_SESSION['idmediapension'] = -1;
		$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
		echo '<script languaje="javascript"> self.location="mediapension.vouchers.edit.php"</script>';
	}

	if ($_POST['accion'] == 'VouchersMPNew') {
		$_SESSION['idmediapension'] = -1;
		$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
		echo '<script languaje="javascript"> self.location="mediapension.vouchers.edit.php"</script>';
	}

	if ($_POST['accion'] == 'VouchersMPModify') {
		
		$_SESSION['idmediapension'] = $_POST['id'];
		$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
		echo '<script languaje="javascript"> self.location="mediapension.vouchers.edit.php"</script>';
	}

	if ($_POST['accion'] == 'VouchersMPDelete') {
		voucherCancelar($_POST['id']);
		echo '<script languaje="javascript"> self.location="mediapension.vouchers.php"</script>';
	}

/* Liquidaciones */

	if ($_POST['accion'] == 'liquidacionCrear') {
		$liquidacion = liquidacionCrear($_POST['ID'], $_POST['idresponsablesDepago'], $_POST['titulo']);
		echo '<script languaje="javascript"> self.location="liquidaciones.pendientes.php?liquidacion=1&filename='.$_POST['nombre'].' - '.$_POST['titulo'].'"</script>';
	}

	if ($_POST['accion'] == 'LiquidacionesDelete') {
		$liquidacion = liquidacionCancelar($_POST['ID']);
		echo '<script languaje="javascript"> self.location="liquidaciones.php"</script>';

	}

	if ($_POST['accion'] == 'LiquidacionesModify') {
		$_SESSION['idliquidaciones'] = $_POST['ID'];
		echo '<script languaje="javascript"> self.location="liquidaciones.cambiarestado.php"</script>';
	}

	if ($_POST['accion'] == 'LiquidacionesCambiarEstado') {
		$liquidacion = liquidacionCambiarEstado($_POST['idliquidaciones'], $_POST['idestados'], $_POST['titulo']);
		echo '<script languaje="javascript"> self.location="liquidaciones.php"</script>';
	}

/* Reservas */


	if ($_POST['accion'] == 'admitirReserva') {

		$idreservas = $_POST['idreservas'];
		$numeroexterno = $_POST['numeroexterno'];
		$numeroexternoMAP = $_POST['numeroexternoMAP'];
		$nomedopax = $_POST['nomedopax'];
		$idoperadoresturisticos = $_POST['idoperadoresturisticos'];
		$idposadas = $_POST['combo1'];
		$idagencias = $_POST['idagencias'];
		$qtdedepax = $_POST['qtdedepax'];
		$dataIN = $_POST['dataIN'];
		$dataOUT = $_POST['dataOUT'];
		$idservicios = $_POST['idservicios'];
		$idhabitaciones = $_POST['combo2'];

		if ($idreservas > -1) {
			$sql = "update ddv2_posiciones set ";
			$sql .= "nombre = '".$nombre."' ";
			$sql .= "where idddv2_posiciones = ".$_SESSION['idddv2_posiciones'];
		}
		else {
			
			//INSERT HUESPED
			$sql = "insert huespedes (titular) values (";
			$sql .= "'".$nomedopax."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idhuespedes = mysql_insert_id();

			//INSERT RESERVA
			$sql = "insert reservas (numeroexterno, idoperadoresturisticos, idposadas, idagencias, idhuespedes, qtdedepax, dataIN, dataOUT, idservicios) values (";
			$sql .= "'".$numeroexterno."',";
			$sql .= "".$idoperadoresturisticos.",";
			$sql .= "".$idposadas.",";
			$sql .= "".$idagencias.",";
			$sql .= "".$idhuespedes.",";
			$sql .= "".$qtdedepax.",";
			$sql .= "'".$dataIN."',";
			$sql .= "'".$dataOUT."',";
			$sql .= "'".$idservicios."') ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$idreservas = mysql_insert_id();

			//INSERT RESERVAS ADMICION ('while' por cada dia)

			$fechaagrabar = date("Y-m-d", strtotime($dataIN));
			while(strtotime($dataOUT) >= strtotime($dataIN)){
				if(strtotime($dataOUT) != strtotime($fechaagrabar)){
					$data = date("Y-m-d", strtotime($fechaagrabar));
					$precio = valordiaria($data, $idposadas, $idservicios);
					$result = admitirServicioReservas($idreservas, $idposadas, $idhabitaciones, $qtdedepax, $data, $precio);
					$fechaagrabar = date("Y-m-d", strtotime($fechaagrabar . " + 1 day"));
				}else{
					$data = date("Y-m-d", strtotime($fechaagrabar));
					$precio = valordiaria($data, $idposadas, $idservicios);
					$result = admitirServicioReservas($idreservas, $idposadas, $idhabitaciones, $qtdedepax, $data, $precio);
					break;
				}	
			}			
			$sql = " SELECT * FROM servicios WHERE ComidasDiarias > 0 AND idservicios = $idservicios ";
			$resultadoStringSQL = resultFromQuery($sql);		
			if ($row = siguienteResult($resultadoStringSQL)){
				//INSERT MEDIAPENSION
				// no hago admision de servios, solo cargo media pension
				$sql = "insert mediapension (numeroexterno, idoperadoresturisticos, idposadas, idagencias, idhuespedes, qtdedepax, dataIN, dataOUT, qtdedecomidas, idservicios, mensajeinterno, mensajegarcon) values (";
				$sql .= "'".$numeroexternoMAP."',";
				$sql .= "".$idoperadoresturisticos.",";
				$sql .= "".$idposadas.",";
				$sql .= "".$idagencias.",";
				$sql .= "".$idhuespedes.",";
				$sql .= "".$qtdedepax.",";
				$sql .= "'".$dataIN."',";
				$sql .= "'".$dataOUT."',";
				$sql .= "".$row->ComidasDiarias.",";
				$sql .= "".$idservicios.",";
				$sql .= "'".$mensajeinterno."',";
				$sql .= "'".$mensajegarcon."') ";
				$resultadoStringSQL = resultFromQuery($sql);		
				$idmediapension = mysql_insert_id(); 
			}	
		}
		
		//echo '<script languaje="javascript"> self.location="reservas.print.php?id='.$idreservas.'"</script>';
		echo '<script languaje="javascript"> self.location="reservas.mapa.php"</script>';
	}

	if ($_POST['accion'] == 'ReservasModify') {
		$_SESSION['idreservas'] = $_POST['ID'];
		echo '<script languaje="javascript"> self.location="reservas.nova.php"</script>';
	}

	if ($_POST['accion'] == 'ReservasDelete') {
		$_SESSION['idreservas'] = $_POST['ID'];

		$sql = " DELETE FROM reservas WHERE idreservas = ".$_SESSION['idreservas'];
		$resultadoStringSQL = resultFromQuery($sql);		

		$sql = " DELETE FROM reservas_admisiones WHERE idreservas = ".$_SESSION['idreservas'];
		$resultadoStringSQL = resultFromQuery($sql);		

		echo '<script languaje="javascript"> self.location="reservas.mapa.php"</script>';

	}

	if ($_POST['accion'] == 'nuevaReserva') {
		$_SESSION['idreservas'] = -1;
		echo '<script languaje="javascript"> self.location="reservas.nova.php"</script>';
	}

/* Area Contable */
	if ($_POST['accion'] == 'admitirEmpleado') {
		
		if ($_POST['edit'] == true) {
			
			//UPDATE
			//ADDRESS
			if ($_POST['city_id'] != 0){
				$table = 'address';	
				$column = getColumns($table);
				
				$value = array('', $_POST['city_id'], $_POST['neightborhood'], $_POST['address'], $_POST['addressnumber'], $_POST['addressfloor'], $_POST['addressapartment']);
				
				//if (isset($_POST['address_id']) && $_POST['address_id'] != ''){
				if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
					$address_id = $_POST[$table.'_id'];
					$condition = $table.'_id = '.$address_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$address_id = mysql_insert_id();
				}
			}
			
			//PROFILE
			//Validación de fechas
			include_once 'lib/functions.php';
			$birthdate = dateFormatMySQL($_POST['birthdate']);
			
			//Validacion de seleccion de ciudad de nacimiento
			$birth_city_id = isset($_POST['birth_city_id']) && $_POST['birth_city_id'] != 'Cidade' ? $_POST['birth_city_id'] : NULL;
			$education_id = isset($_POST['education_id']) && $_POST['education_id'] != 0 ? $_POST['education_id'] : NULL;
			
			
			$table = 'profile';
			
			$column = getColumns($table);

			$value = array('', $_POST['profile_firstname'], $_POST['profile_lastname'], $_POST['sex_id'], $birthdate, $birth_city_id, $_POST['fathername'], $_POST['mothername'], $_POST['civilstatus'], $_POST['marriedname'], $education_id, isset($address_id) ? $address_id : '');

			if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
				$profile_id = $_POST[$table.'_id'];
				$condition = $table.'_id = '.$profile_id;
				$sqlQuery = updateQuery($table, $column, $value, $condition);
				$resultado = resultFromQuery($sqlQuery);
			}
			else{
				$sqlQuery = insertQuery($table, $column, $value);
				$resultado = resultFromQuery($sqlQuery);
				$profile_id = mysql_insert_id();
			}
			
			//PHONE
			if ($_POST['phone1'] != ''){
				
				$table = 'phone_number';
				$column = getColumns($table);
				$value = array('', $_POST['phone1'], 5);
				
				$sql = 'SELECT * FROM profile_phone WHERE profile_id = '.$profile_id;
				$result = resultFromQuery($sql);
				
				if ($row = siguienteResult($result)){
					
					$phone_number_id = $row->phone_number_id;
					
					$condition = $table.'_id = '.$phone_number_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$phone_number_id = mysql_insert_id();
					
					$sql = "INSERT profile_phone(profile_id, phone_number_id) VALUES(";
					$sql .= $profile_id.', '.$phone_number_id.')';
					$result = resultFromQuery($sql);
					
				}
			}
				
			
			//Carteira de Trabalho
			if ($_POST['carteiranumber'] != ''){
			
				//Validacion de fechas
				$carteiradate = dateFormatMySQL($_POST['carteiradate']);
				
				$table = 'workingcard';
				
				$column = getColumns($table);
				
				$value = array('', $_POST['profile_id'], $_POST['carteiranumber'], $_POST['carteiraserie'], $carteiradate);
				
				if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
					$workingcard_id = $_POST[$table.'_id'];
					$condition = $table.'_id = '.$workingcard_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$workingcard_id = mysql_insert_id();
				}
			}
			
			//CPF
			if ($_POST['cpfnumber'] != ''){
				$table = 'cpfcard';
				
				$column = getColumns($table);
				
				$value = array('', $_POST['profile_id'], $_POST['cpfnumber']);
				
				if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
					$cpfcard_id = $_POST[$table.'_id'];
					$condition = $table.'_id = '.$cpfcard_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$cpfcard_id = mysql_insert_id();
				}
			}
			
			//INSERT VOTINGCARD (Titulo Eleitor)
			if ($_POST['eleitornumber'] != ''){
				
				//Validacion de fechas
				$eleitordate = dateFormatMySQL($_POST['eleitordate']);
				
				$table = 'votingcard';
				
				$column = getColumns($table);
				
				$value = array('', $_POST['profile_id'], $_POST['eleitornumber'], $_POST['eleitorzone'], $_POST['eleitorsection'], $eleitordate);
				
				if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
					$votingcard_id = $_POST[$table.'_id'];
					$condition = $table.'_id = '.$votingcard_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$votingcard_id = mysql_insert_id();
				}
			}
	
			//INSERT RGCARD (Registro de identidade RG)
			if ($_POST['idnumber'] != ''){
				
				//Validacion de fechas
				$iddate = dateFormatMySQL($_POST['iddate']);
				
				$table = 'rgcard';
				
				$column = getColumns($table);
				
				$value = array('', $_POST['profile_id'], $_POST['idnumber'], $_POST['idexpeditor'], $iddate);
				
				if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
					$rgcard_id = $_POST[$table.'_id'];
					$condition = $table.'_id = '.$rgcard_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$rgcard_id = mysql_insert_id();
				}
			}
			
			//PISCARD (Programa de integração Social PS)
			if ($_POST['pisnumber'] != ''){
				
				//Validacion de fechas
				$pisdate = dateFormatMySQL($_POST['pisdate']);
				
				$table = 'piscard';
				
				$column = getColumns($table);
				
				$value = array('', $_POST['profile_id'], $_POST['pisnumber'], $_POST['pisbanknumber'], $pisdate);
				
				if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
					$piscard_id = $_POST[$table.'_id'];
					$condition = $table.'_id = '.$piscard_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$piscard_id = mysql_insert_id();
				}
			}
		
			//MILITARYCARD (Certificado militar)
			if ($_POST['milcertnumber'] != ''){
				
				$table = 'militarycard';
				
				$column = getColumns($table);
				
				$value = array('', $_POST['profile_id'], $_POST['milcertnumber'], $_POST['milcertserie'], $_POST['milcertcategory']);
				
				if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
					$militarycard_id = $_POST[$table.'_id'];
					$condition = $table.'_id = '.$militarycard_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					//$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$militarycard_id = mysql_insert_id();
				}
			}
			
			//HABILITATION CARD
			if ($_POST['habilitationnumber'] != ''){
				
				//Validacion de fechas
				$habilitationdate = dateFormatMySQL($_POST['habilitationdate']);
				$habilitationvaliddate = dateFormatMySQL($_POST['habilitationvaliddate']);
				
				$table = 'habilitationcard';
				
				$column = getColumns($table);
				
				$value = array('', $_POST['profile_id'], $_POST['habilitationnumber'], $_POST['habilitationcategory'], $habilitationdate, $habilitationvaliddate);
				
				if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
					$habilitationcard_id = $_POST[$table.'_id'];
					$condition = $table.'_id = '.$habilitationcard_id;
					$sqlQuery = updateQuery($table, $column, $value, $condition);
					$resultado = resultFromQuery($sqlQuery);
				}
				else{
					$sqlQuery = insertQuery($table, $column, $value);
					$resultado = resultFromQuery($sqlQuery);
					$habilitationcard_id = mysql_insert_id();
				}
			}
			
			//Clearance
			if ($_POST['clearance'] != ''){
				
				$sql = "SET lc_time_names = 'pt_BR';";
				setlocale(LC_ALL, 'pt_BR');
				
				resultFromQuery($sql);
				
				
				$days = array(
                1 => 'Segunda',
                2 => 'Terça',
                3 => 'Quarta',
                4 => 'Quinta',
                5 => 'Sexta',
                6 => 'Sábado',
                7 => 'Domingo');
                
				$table = 'clearance';
				
				$column = getColumns($table);
				
				$today = mb_convert_encoding(ucfirst(strftime("%A", strtotime('now'))), "UTF-8", "iso-8859-1");
				
				//Si el dia seleccionado es el mismo que el actual, establece la fecha
				if ($today == $days[$_POST['clearance']]){
					$clearance = date('Y-m-d', strtotime('now'));
				}
				//Sino busca la siguiente fecha en la que cae el dia de la semana selecionado
				else{
					$clearance = nextDay($days[$_POST['clearance']], date('Y-m-d', strtotime('now')));
				}
				
				//Busca si la configuracion anterior del empleado no coincide con la seleccionada
				$sqlQuery = "SELECT DAYNAME(valid_from) clearance, valid_from from clearance ";
				$sqlQuery .= "WHERE 1 ";
				$sqlQuery .= "AND employee_id = ".$_POST['employee_id']." ";
				$sqlQuery .= "AND valid_from <= CURDATE() ";
				$sqlQuery .= "ORDER BY valid_from DESC LIMIT 1;";
				$resultadoStringSQL = resultFromQuery($sqlQuery);
				
				//Si devuelve algun valor
				if ($row = siguienteResult($resultadoStringSQL)){
					//Si el dia previo es distinto al seleccionado
					if(ucfirst($row->clearance) != $days[$_POST['clearance']]){
					
						//Verificar si existe algun registro en los proximos 6 días
						
						$sqlQuery = "SELECT DAYNAME(valid_from) clearance, valid_from from clearance ";
						$sqlQuery .= "WHERE 1 ";
						$sqlQuery .= "AND employee_id = ".$_POST['employee_id']." ";
						$sqlQuery .= "AND DATE(valid_from) BETWEEN CURDATE() + interval 1 day AND CURDATE() + interval 6 day ";
						$sqlQuery .= "ORDER BY valid_from ASC LIMIT 1;";
						
						if ($row = siguienteResult($resultadoStringSQL)){
							
							if(ucfirst($row->clearance) != $days[$_POST['clearance']]){
								//QUE HACER CUANDO LA FECHA PREVIA NI LA SIGUIENTE COINCIDEN E INTENTA INGRESAR OTRA?
							}
							else{
								$value = array('', $_POST['employee_id'], $clearance, 'current_timestamp');

								$sqlQuery = insertQuery($table, $column, $value);
								$resultado = resultFromQuery($sqlQuery);
								$clearance_id = mysql_insert_id();
							}
							
						}
						else{
							$value = array('', $_POST['employee_id'], $clearance, 'current_timestamp');

							$sqlQuery = insertQuery($table, $column, $value);
							$resultado = resultFromQuery($sqlQuery);
							$clearance_id = mysql_insert_id();
						}
					}	
				}
				else{
					//Verificar si existe algun registro en los proximos 6 días
						
					$sqlQuery = "SELECT DAYNAME(valid_from) clearance, valid_from from clearance ";
					$sqlQuery .= "WHERE 1 ";
					$sqlQuery .= "AND employee_id = ".$_POST['employee_id']." ";
					$sqlQuery .= "AND DATE(valid_from) BETWEEN CURDATE() + interval 1 day AND CURDATE() + interval 6 day ";
					$sqlQuery .= "ORDER BY valid_from ASC LIMIT 1;";
					$resultadoStringSQL = resultFromQuery($sqlQuery);
						
					if ($row = siguienteResult($resultadoStringSQL)){
						if(ucfirst($row->clearance) != $days[$_POST['clearance']]){
							//QUE HACER CUANDO NO HAY FECHA PREVIA Y LA SIGUIENTE NO COINCIDE CON LA QUE SE INTENTA INGRESAR?
						}
					}
					else{
						$value = array('', $_POST['employee_id'], $clearance, 'current_timestamp');
						$sqlQuery = insertQuery($table, $column, $value);
						$resultado = resultFromQuery($sqlQuery);
						$clearance_id = mysql_insert_id();
					}
				}
				//Sino busca si la anterior					
			}
			
			
			
			//EMPLOYEE
			//Validacion de fechas
			$admissiondate = dateFormatMySQL($_POST['admissiondate']);
			$contractdate = dateFormatMySQL($_POST['contractdate']);
			$decline = dateFormatMySQL($_POST['decline']);

			
			//Conversion de , a .
			$transportvalue = str_replace(',', '.', $_POST['transportvalue']);
			
			//Comprobacion de checkbox de insalubridad
			$unhealthy = isset($_POST['unhealthy']) ? 1  : '0';
			
			$table = 'employee';
				
			$column = getColumns($table);
				
			$value = array('', $_POST['profile_id'], $_POST['idempresa'], $_POST['jobcategory_id'], $_POST['bonussalary'], $admissiondate, $contractdate, $decline, $transportvalue, $_POST['traject'], $_POST['fromhour'], $_POST['tohour'], $_POST['intervalhour'], $_POST['experiencecontract'], $unhealthy);
				
			if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
				$employee_id = $_POST[$table.'_id'];
				$condition = $table.'_id = '.$employee_id;
				$sqlQuery = updateQuery($table, $column, $value, $condition);
				$resultado = resultFromQuery($sqlQuery);

			}
			else{
				$sqlQuery = insertQuery($table, $column, $value);
				$resultado = resultFromQuery($sqlQuery);
				$employee_id = mysql_insert_id();
			}
			echo '<script languaje="javascript"> self.location="funcionarios.lista.php"</script>';
		}
		else{
			//INSERT ADDRESS
			//Parametros que se van a pasar
			if ($_POST['city_id'] != 0){
				
				$sql = "INSERT address(";
				$sql .= $_POST['city_id'] != '' ? 'city_id' : '';//Validar, debe ir obligatoriamente
				$sql .= $_POST['neightborhood'] != '' ? ', neightborhood' : '';
				$sql .= $_POST['address'] != '' ? ', street' : '';
				$sql .= $_POST['addressnumber'] != '' ? ', number' : '';
				$sql .= $_POST['addressfloor'] != '' ? ', floor' : '';
				$sql .= $_POST['addressapartment'] != '' ? ', apartment' : '';
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= $_POST['city_id'] != '' ? $_POST['city_id'] : '';
				$sql .= $_POST['neightborhood'] != '' ? ', '."'".$_POST['neightborhood']."'" : '';
				$sql .= $_POST['address'] != '' ? ', '."'".$_POST['address']."'" : '';
				$sql .= $_POST['addressnumber'] != '' ? ', '."'".$_POST['addressnumber']."'" : '';
				$sql .= $_POST['addressfloor'] != '' ? ', '."'".$_POST['addressfloor']."'" : '';
				$sql .= $_POST['addressapartment'] != '' ? ', '."'".$_POST['addressapartment']."'" : '';
				$sql .= ")";
				
				$resultado = resultFromQuery($sql);
				$address_id = mysql_insert_id();
			}
			
			//INSERT PROFILE
			
			//Validación de fechas
			include_once 'lib/functions.php';
			$birthdate = dateFormatMySQL($_POST['birthdate']);
			
			//Parametros a pasar
			$sql = "INSERT profile(";
			$sql .= $_POST['profile_firstname'] != '' ? 'firstname' : '';//Validar, debe ir obligatoriamente
			$sql .= $_POST['profile_lastname'] != '' ? ', lastname' : '';
			$sql .= $_POST['sex_id'] != 0 ? ', sex_id' : '';
			$sql .= $birthdate ? ', birthdate' : '';
			$sql .= $_POST['birth_city_id'] != 'Cidade' ? ', birth_city_id' : '';
			$sql .= $_POST['fathername'] != '' ? ', fathername' : '';
			$sql .= $_POST['mothername'] != '' ? ', mothername' : '';
			$sql .= $_POST['civilstatus'] != '' ? ', civilstatus' : '';
			$sql .= $_POST['marriedname'] != '' ? ', marriedname' : '';
			$sql .= $_POST['education_id'] != 0 ? ', education_id' : '';
			$sql .= isset($address_id) ? ', address_id' : '';
			$sql .= ") ";
			
			//Valores a pasar
			$sql .= "VALUES (";		
			$sql .= $_POST['profile_firstname'] != '' ? "'".$_POST['profile_firstname']."'" : '';
			$sql .= $_POST['profile_lastname'] != '' ? ', '."'".$_POST['profile_lastname']."'" : '';
			$sql .= $_POST['sex_id'] != 0 ? ', '.$_POST['sex_id'] : '';
			$sql .= $birthdate ? ', '."'".$birthdate."'" : '';
			$sql .= $_POST['birth_city_id'] != 'Cidade' ? ', '.$_POST['birth_city_id'] : '';
			$sql .= $_POST['fathername'] != '' ? ', '."'".$_POST['fathername']."'" : '';
			$sql .= $_POST['mothername'] != '' ? ', '."'".$_POST['mothername']."'" : '';
			$sql .= $_POST['civilstatus'] != '' ? ', '.$_POST['civilstatus'] : '';
			$sql .= $_POST['marriedname'] != '' ? ', '."'".$_POST['marriedname']."'" : '';
			$sql .= $_POST['education_id'] != 0 ? ', '.$_POST['education_id'] : '';
			$sql .= isset($address_id) ? ', '.$address_id : '';
			$sql .= ")";
			
			$resultado = resultFromQuery($sql);
			$profile_id = mysql_insert_id();
			
			//INSERT PHONE
			if ($_POST['phone1'] != ''){
				//Parametros a pasar
				$sql = "INSERT phone_number(";
				$sql .= 'number';
				$sql .= ", phone_type_id";
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= "'".$_POST['phone1']."'";
				$sql .= ", 5"; //De momento los ingresa como celular a todos (5)
				$sql .= ")";
				
				$resultado = resultFromQuery($sql);
				$phone_number_id = mysql_insert_id();
				
				//INSERT PROFILE_PHONE
				//Parametros a pasar
				$sql = "INSERT profile_phone(profile_id, phone_number_id) ";
				
				//Valores a pasar
				$sql .= "VALUES (";	
				$sql .= $profile_id.", ";
				$sql .= $phone_number_id;	
				$sql .= ")";
				
				$resultado = resultFromQuery($sql);
			}
				
			//INSERT WORKINGCARD (Carteira de trabalho)
			if ($_POST['carteiranumber'] != ''){
				
				//Validacion de fechas
				$carteiradate = dateFormatMySQL($_POST['carteiradate']);
			
				//Parametros a pasar
				$sql = "INSERT workingcard(";
				$sql .= "profile_id";
				$sql .= $_POST['carteiranumber'] != '' ? ', number' : '';
				$sql .= $_POST['carteiraserie'] != '' ? ', serie' : '';
				$sql .= $carteiradate ? ', expedition_date' : '';
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= $profile_id;
				$sql .= $_POST['carteiranumber'] != '' ? ', '.$_POST['carteiranumber'] : '';
				$sql .= $_POST['carteiraserie'] != '' ? ', '."'".$_POST['carteiraserie']."'" : '';
				$sql .= $carteiradate ? ', '."'".$carteiradate."'" : '';
				$sql .= ")";
			
				$resultado = resultFromQuery($sql);
			}
			
			//INSERT CPFCARD (CPF)
			if ($_POST['cpfnumber'] != ''){
				//Parametros a pasar
				$sql = "INSERT cpfcard(";
				$sql .= "profile_id";
				$sql .= $_POST['cpfnumber'] != '' ? ', number' : '';
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= $profile_id;
				$sql .= $_POST['cpfnumber'] != '' ? ", '".$_POST['cpfnumber']."'" : '';
				$sql .= ")";
			
				$resultado = resultFromQuery($sql);
			}
			
			//INSERT VOTINGCARD (Titulo Eleitor)
			if ($_POST['eleitornumber'] != ''){
				
				//Validacion de fechas
				$eleitordate = dateFormatMySQL($_POST['eleitordate']);
				
				//Parametros a pasar
				$sql = "INSERT votingcard(";
				$sql .= "profile_id";
				$sql .= $_POST['eleitornumber'] != '' ? ', number' : '';
				$sql .= $_POST['eleitorzone'] != '' ? ', zone' : '';
				$sql .= $_POST['eleitorsection'] != '' ? ', section' : '';
				$sql .= $eleitordate ? ', emissiondate' : '';
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= $profile_id;
				$sql .= $_POST['eleitornumber'] != '' ? ', '."'".$_POST['eleitornumber']."'" : '';
				$sql .= $_POST['eleitorzone'] != '' ? ', '."'".$_POST['eleitorzone']."'" : '';
				$sql .= $_POST['eleitorsection'] != '' ? ', '."'".$_POST['eleitorsection']."'" : '';
				$sql .= $eleitordate ? ', '."'".$eleitordate."'" : '';
				$sql .= ")";
			
				$resultado = resultFromQuery($sql);
			}
			
			//INSERT RGCARD (Registro de identidade RG)
			if ($_POST['idnumber'] != ''){
				
				//Validacion de fechas
				$iddate = dateFormatMySQL($_POST['iddate']);
				
				//Parametros a pasar
				$sql = "INSERT rgcard(";
				$sql .= "profile_id";
				$sql .= $_POST['idnumber'] != '' ? ', number' : '';
				$sql .= $_POST['idexpeditor'] != '' ? ', expeditor' : '';
				$sql .= $iddate ? ', date' : '';
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= $profile_id;
				$sql .= $_POST['idnumber'] != '' ? ", '".$_POST['idnumber']."'" : '';
				$sql .= $_POST['idexpeditor'] != '' ? ", '".$_POST['idexpeditor']."'" : '';
				$sql .= $iddate ? ', '."'".$iddate."'" : '';
				$sql .= ")";
			
				$resultado = resultFromQuery($sql);
			}
			
			//INSERT PISCARD (Programa de integração Social PS)
			if ($_POST['pisnumber'] != ''){
				
				//Validacion de fechas
				$pisdate = dateFormatMySQL($_POST['pisdate']);
				
				//Parametros a pasar
				$sql = "INSERT piscard(";
				$sql .= "profile_id";
				$sql .= $_POST['pisnumber'] != '' ? ', number' : '';
				$sql .= $_POST['pisbanknumber'] != '' ? ', bank' : '';
				$sql .= $pisdate ? ', expeditiondate' : '';
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= $profile_id;
				$sql .= $_POST['pisnumber'] != '' ? ', '."'".$_POST['pisnumber']."'" : '';
				$sql .= $_POST['pisbanknumber'] != '' ? ', '."'".$_POST['pisbanknumber']."'" : '';
				$sql .= $pisdate ? ', '."'".$pisdate."'" : '';
				$sql .= ")";
			
				$resultado = resultFromQuery($sql);
			}
			
			//INSERT MILITARYCARD (Certificado militar)
			if ($_POST['milcertnumber'] != ''){
				//Parametros a pasar
				$sql = "INSERT militarycard(";
				$sql .= "profile_id";
				$sql .= $_POST['milcertnumber'] != '' ? ', number' : '';
				$sql .= $_POST['milcertserie'] != '' ? ', serie' : '';
				$sql .= $_POST['milcertcategory'] != '' ? ', category' : '';
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= $profile_id;
				$sql .= $_POST['milcertnumber'] != '' ? ', '.$_POST['milcertnumber'] : '';
				$sql .= $_POST['milcertserie'] != '' ? ', '."'".$_POST['milcertserie']."'" : '';
				$sql .= $_POST['milcertcategory'] != '' ? ', '."'".$_POST['milcertcategory']."'" : '';
				$sql .= ")";
			
				$resultado = resultFromQuery($sql);
			}
			
			//INSERT HABILITATIONCARD (Carteira de habilitação)
			if ($_POST['habilitationnumber'] != ''){
				
				//Validacion de fechas
				$habilitationdate = dateFormatMySQL($_POST['habilitationdate']);
				$habilitationvaliddate = dateFormatMySQL($_POST['habilitationvaliddate']);
				
				//Parametros a pasar
				$sql = "INSERT habilitationcard(";
				$sql .= "profile_id";
				$sql .= $_POST['habilitationnumber'] != '' ? ', number' : '';
				$sql .= $_POST['habilitationcategory'] != '' ? ', category' : '';
				$sql .= $habilitationdate ? ', expedition' : '';
				$sql .= $habilitationvaliddate ? ', valid' : '';
				$sql .= ") ";
				
				//Valores a pasar
				$sql .= "VALUES (";		
				$sql .= $profile_id;
				$sql .= $_POST['habilitationnumber'] != '' ? ', '."'".$_POST['habilitationnumber']."'" : '';
				$sql .= $_POST['habilitationcategory'] != '' ? ', '."'".$_POST['habilitationcategory']."'" : '';
				$sql .= $habilitationdate ? ', '."'".$habilitationdate."'" : '';
				$sql .= $habilitationvaliddate ? ', '."'".$habilitationvaliddate."'" : '';
				$sql .= ")";
			
				$resultado = resultFromQuery($sql);
			}
			
			//INSERT EMPLOYEE
			//Validacion de fechas
			$admissiondate = dateFormatMySQL($_POST['admissiondate']);
			$contractdate = dateFormatMySQL($_POST['contractdate']);
			$decline = dateFormatMySQL($_POST['decline']);
			
			//Conversion de , a .
			$transportvalue = str_replace(',', '.', $_POST['transportvalue']);
			
			//Parametros a pasar
			$sql = "INSERT employee(";
			$sql .= "profile_id";
			$sql .= $_POST['idempresa'] != '' ? ', idempresa' : '';
			$sql .= $_POST['jobcategory_id'] != '' ? ', jobcategory_id' : '';
			$sql .= $_POST['bonussalary'] != '' ? ', bonussalary' : '';
			$sql .= $_POST['admissiondate'] ? ', admission' : '';
			$sql .= $_POST['contractdate'] != '' ? ', contract' : '';
			$sql .= $_POST['decline'] != '' ? ', decline' : '';
			$sql .= $_POST['transport'] != 0 ? ', transport' : '';
			$sql .= $_POST['traject'] != '' ? ', traject' : '';
			$sql .= $_POST['fromhour'] != '' ? ', fromhour' : '';
			$sql .= $_POST['tohour'] != '' ? ', tohour' : '';
			$sql .= $_POST['intervalhour'] != '' ? ', intervalhour' : '';
			$sql .= $_POST['experiencecontract'] != 0 ? ', experiencecontract' : '';
			$sql .= ', created';
			$sql .= ") ";
			
			//Valores a pasar
			$sql .= "VALUES (";		
			$sql .= $profile_id;
			$sql .= $_POST['idempresa'] != '' ? ', '.$_POST['idempresa'] : '';
			$sql .= $_POST['jobcategory_id'] != '' ? ', '.$_POST['jobcategory_id'] : '';
			$sql .= $_POST['bonussalary'] != '' ? ', '."'".$_POST['bonussalary']."'" : '';
			$sql .= $_POST['admissiondate'] != '' ? ', '."'".$admissiondate."'" : '';
			$sql .= $_POST['contractdate'] != '' ? ', '."'".$contractdate."'" : '';
			$sql .= $_POST['decline'] != '' ? ', '."'".$decline."'" : '';
			$sql .= $_POST['transport'] != 0 ? ', '."'".$transportvalue."'" : '';
			$sql .= $_POST['traject'] != '' ? ', '."'".$_POST['traject']."'" : '';
			$sql .= $_POST['fromhour'] != '' ? ', '."'".$_POST['fromhour']."'" : '';
			$sql .= $_POST['tohour'] != '' ? ', '."'".$_POST['tohour']."'" : '';
			$sql .= $_POST['intervalhour'] != '' ? ', '."'".$_POST['intervalhour']."'" : '';
			$sql .= $_POST['experiencecontract'] != 0 ? ', '."'".$_POST['experiencecontract']."'" : '';
			$sql .= ', current_timestamp';
			
			$sql .= ")";
			
			$resultado = resultFromQuery($sql);
			
			echo '<script languaje="javascript"> self.location="salarios.php"</script>';
		}		
	}

	if ($_POST['accion'] == 'admitJobcategory') {
		
		if (isset($_POST['jobcategory_id'])){
			
			$jobcategory_id = $_POST['jobcategory_id'];
			$name = $_POST['name'];
			
			$sql = 'UPDATE jobcategory set name = ';
			$sql .= "'".$name."' ";
			$sql .= 'WHERE jobcategory_id = '.$jobcategory_id;
			
			$result = resultFromQuery($sql);
			
			echo '<script languaje="javascript"> self.location="'.$_SERVER['HTTP_REFERER'].'"</script>';
			
		}
		else{
				
			$name = $_POST['jobcategory_name'];
			$basesalary = $_POST['basesalary'];
			$valid_from = $_POST['valid_from'];
			
			//Insert Job Category
			$sql = "INSERT jobcategory (name, created) ";
			$sql .= "VALUES (";
			$sql .= "'".$name."',";
			$sql .= "current_timestamp) ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$jobcategory_id = mysql_insert_id();
			
			//Insert Base Salary
			$sql = "INSERT basesalary (jobcategory_id, basesalary, valid_from, created) ";
			$sql .= "VALUES (";
			$sql .= "'".$jobcategory_id."',";
			$sql .= "'".$basesalary."',";
			$sql .= "'".$valid_from."',";
			$sql .= "current_timestamp) ";
			$resultadoStringSQL = resultFromQuery($sql);		
			$basesalary_id = mysql_insert_id();
			
			bitacoras($_SESSION["idusuarios"], 'Insertar Salario Base ID: '.$basesalary_id);
			
			
		}
		
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Categoria Laboral: '.$jobcategory_id);
	}
	
	if ($_POST['accion'] == 'admitPayment') {
		
		if (isset($_POST['employee_id'])){
			
			//Validacion de fechas
			$date = (isset($_POST['date']) && $_POST['date'] != '') ? dateFormatMySQL($_POST['date']) : 'current_timestamp';
			
			$table = 'payment';	
			$column = getColumns($table);
			
			$ammount = str_replace(',', '.', $_POST['ammount']);
				
			$value = array('', $_POST['employee_id'], $_POST['paymenttype_id'], $_POST['paymentmethod_id'], $ammount, $_POST['details'], $date);
				
			//if (isset($_POST['address_id']) && $_POST['address_id'] != ''){
			if (isset($_POST[$table.'_id']) && $_POST[$table.'_id'] != ''){
				$payment_id = $_POST[$table.'_id'];
				$condition = $table.'_id = '.$payment_id;
				$sqlQuery = updateQuery($table, $column, $value, $condition);
				$resultado = resultFromQuery($sqlQuery);
				bitacoras($_SESSION["idusuarios"], 'Editar Pagamento: '.$payment_id);
			}
			else{
				$sqlQuery = insertQuery($table, $column, $value);
				$resultado = resultFromQuery($sqlQuery);
				$payment_id = mysql_insert_id();
				bitacoras($_SESSION["idusuarios"], 'Insertar Pagamento: '.$payment_id);
			}
			
			
			
			
			
			
			/*
			//Parametros a pasar
			$sql = "INSERT payment(";
			$sql .= "employee_id";
			$sql .= $_POST['paymenttype_id'] != 0 ? ', paymenttype_id' : '';
			$sql .= $_POST['paymentmethod_id'] != 0 ? ', paymentmethod_id' : '';
			$sql .= $_POST['ammount'] != '' ? ', ammount' : '';
			$sql .= $_POST['details'] != '' ? ', details' : '';
			$sql .= ", date) ";
			
			//Valores a pasar
			$sql .= "VALUES (";
			$sql .= $_POST['employee_id'];
			$sql .= $_POST['paymenttype_id'] != 0 ? ', '.$_POST['paymenttype_id'] : '';
			$sql .= $_POST['paymentmethod_id'] != 0 ? ', '.$_POST['paymentmethod_id'] : '';
			$sql .= $_POST['ammount'] != '' ? ', '.$_POST['ammount'] : '';
			$sql .= $_POST['details'] != '' ? ", '".$_POST['details']."'" : '';
			$sql .= $_POST['date'] != '' ? ", '".$date."') " : ", current_timestamp) ";
				
			
			$resultadoStringSQL = resultFromQuery($sql);		
			$payment_id = mysql_insert_id();
			
			
			bitacoras($_SESSION["idusuarios"], 'Insertar Pagamento: '.$payment_id);
			*/
		}
		
		if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 4){
			echo '<script languaje="javascript"> self.location="pagamentos.php"</script>';
		}
		else{
			echo '<script languaje="javascript"> self.location="funcionarios.lista.php"</script>';
		}
	}

	if ($_POST['accion'] == 'paymentModify') {
		
		if (isset($_POST['id'])){
			$_SESSION['payment_id'] = $_POST['id'];
		}
		
		echo '<script languaje="javascript"> self.location="pagamentos.novo.php"</script>';
	}

	if ($_POST['accion'] == 'paymentDelete') {
		
		if (isset($_POST['id'])){
			$sql = 'UPDATE payment ';
			$sql .= 'SET enabled = 0 ';
			$sql .= 'WHERE 1 ';
			$sql .= 'AND payment_id = '.$_POST['id'] ;
			
			$resultadoStringSQL = resultFromQuery($sql);
						
			bitacoras($_SESSION["idusuarios"], 'Deshabilitar payment id: '.$_POST['id']);
		}
		
		echo '<script languaje="javascript"> self.location="pagamentos.lista.php"</script>';
	}

	if ($_POST['accion'] == 'employeeModify') {
		$_SESSION['employee'] = $_POST['id'];
		echo '<script languaje="javascript"> self.location="funcionarios.novo.php"</script>';
	}

	if ($_POST['accion'] == 'admitEmployeeSon') {
		
		if (isset($_POST['employee_id']) && $_POST['employee_id'] != 0){
			
			//Obtener el perfil del empleado
			$sql = 'SELECT profile_id FROM employee WHERE employee_id = '.$_POST['employee_id'];
			$resultado = resultFromQuery($sql);
						
			if ($row = siguienteResult($resultado)) {
				$profile_id = $row->profile_id;
				
				$sql = 'INSERT son(profile_id, name, birthdate, is_alive) ';
				$sql .= 'VALUES( ';
				$sql .= $profile_id.', ';
				$sql .= "'".$_POST['name']."', ";
				$sql .= "'".$_POST['birthdate']."', ";
				$sql .= $_POST['is_alive'].') ';
				
				
				$resultado = resultFromQuery($sql);
				$son_id = mysql_insert_id();
				
				bitacoras($_SESSION["idusuarios"], 'Ingresado filho id: '.$son_id);
			}

		}
		
		echo '<script languaje="javascript"> self.location="funcionarios.lista.php"</script>';
	}

	if ($_POST['accion'] == 'employeeFood') {
		
		
		
		$employee_id = $_POST['employee_id'];
		
		$sql = "SELECT status FROM foodemployee WHERE employee_id = ".$employee_id." ORDER BY created DESC LIMIT 1;";
		
		$resultado = resultFromQuery($sql);
		
		if ($row = siguienteResult($resultado)) {
			$prevstatus = $row->status;
		}
		
		
		$sql = "INSERT INTO foodemployee(employee_id, status) VALUES (";
		$sql .= $employee_id.", ";
		
		if ($_POST['food'] == 'add' && $prevstatus != 1){
						
			$sql .= "1)";
			
			$resultado = resultFromQuery($sql);
			$foodemployee_id = mysql_insert_id();
				
			bitacoras($_SESSION["idusuarios"], 'Adicionada alimentaçao id: '.$foodemployee_id);
		}
		elseif ($_POST['food'] == 'remove' && $prevstatus != 0){
			
			$sql .= "0)";
			
			$resultado = resultFromQuery($sql);
			$foodemployee_id = mysql_insert_id();
				
			bitacoras($_SESSION["idusuarios"], 'Apagada alimentaçao id: '.$foodemployee_id);
		}
		echo '<script languaje="javascript"> self.location="'.$_SERVER['HTTP_REFERER'].'"</script>';
	}

	if ($_POST['accion'] == 'employeeSyndicate') {
		
		
		
		$employee_id = $_POST['employee_id'];
		
		$sql = "SELECT status FROM syndicate WHERE employee_id = ".$employee_id." ORDER BY created DESC LIMIT 1;";
		
		$resultado = resultFromQuery($sql);
		
		if ($row = siguienteResult($resultado)) {
			$prevstatus = $row->status;
		}
				
		
		$sql = "INSERT INTO syndicate(employee_id, status, created) VALUES (";
		$sql .= $employee_id.", ";
		
		if ($_POST['syndicate'] == 'add' && $prevstatus != 1){
						
			$sql .= "1, ";
			$sql .= "current_timestamp) ";
			
			$resultado = resultFromQuery($sql);
			$syndicate_id = mysql_insert_id();
				
			bitacoras($_SESSION["idusuarios"], 'Adicionado sindicato id: '.$syndicate_id);
		}
		elseif ($_POST['syndicate'] == 'remove' && ($prevstatus != 0 || $prevstatus == NULL)){
			
			$sql .= "0, ";
			$sql .= "current_timestamp) ";
			
			$resultado = resultFromQuery($sql);
			$syndicate_id = mysql_insert_id();
				
			bitacoras($_SESSION["idusuarios"], 'Apagado sindicato id: '.$foodemployee_id);
		}
		echo '<script languaje="javascript"> self.location="'.$_SERVER['HTTP_REFERER'].'"</script>';
	}

	if ($_POST['accion'] == 'employeeNonAttendance') {
		
		$employee_id = $_POST['employee_id'];
		
		//UPDATE
		//ADDRESS
		if ($_POST['employee_id'] != 0){
			$table = 'nonattendance';	
			$column = getColumns($table);
			
			$value = array('', $_POST['employee_id'], $_POST['date'], 'current_timestamp');
			
			$sqlQuery = insertQuery($table, $column, $value);
			
			$resultado = resultFromQuery($sqlQuery);
			$nonattendance_id = mysql_insert_id();
			
			echo '<script languaje="javascript"> self.location="funcionarios.lista.php"</script>';
		}
	}
		
/* Ponto */
	if ($_POST['accion'] == 'registrarPonto') {
			
		$senha = $_POST['senha'];
		$senha2 = $_POST['senha2'];
		$in_out = $_POST['in_out'];
		
		$config = parse_ini_file("local-config.ini", true);

		$ldbcfg = $config['local_database'];
		$cfg = $config['config'];
		
		$dblocal = mysqli_connect($ldbcfg['dbhost'], $ldbcfg['user'], $ldbcfg['password'], $ldbcfg['dbname']);
		
		if (mysqli_connect_errno())
		{
		 echo "Failed to connect to local MySQL: " . mysqli_connect_error();
		}
		
		//Si la terminal esta configurada para trabajar con puntos
		if ($cfg['point_station']){
			//Si hay conexión a internet
			if (checkConnection()){
				
				//Configuración del server
				$rdbcfg = $config['remote_database'];
					
				//Conexión a la DB
				$dbremote = mysqli_connect($rdbcfg['dbhost'], $rdbcfg['user'], $rdbcfg['password'], $rdbcfg['dbname'], 3306);
				
				if (mysqli_connect_errno())
				{
				 echo "Failed to connect to remote MySQL: " . mysqli_connect_error();
				}
				
				////////////////////UPDATE-START//////////////////
				//Verificar si hay puntos sin actualizar
				$sql = "SELECT * FROM point WHERE updated = 0";

				if (!$result = mysqli_query($dblocal, $sql)){
					die('Error al verificar si existen actualizaciones en la base de datos:<br> ' . mysqli_error($dblocal));
				}
				
				//Si hay puntos para actualizar
				if ($row = mysqli_fetch_array($result)){
			
					$sql = "SELECT * FROM point WHERE updated = 0";					

					if (!$result = mysqli_query($dblocal, $sql)){
						die('Error al verificar si existen actualizaciones en la base de datos:<br> ' . mysqli_error($dblocal));
					}
					
					//Prepara la cadena de inserción
					$sql = "INSERT point (employee_id, date_time, in_out, updated) ";
					$sql .= "VALUES ";
						
					//Todos los puntos sin actualizar
					while($point = mysqli_fetch_array($result)){
						
						if (mb_substr($sql, -1) != ' ' && mb_substr($sql, -2) != ', '){
							$sql  .= ', ';
						}
						$sql .= "(".$point['employee_id'].", ";
						$sql .= "'".$point['date_time']."', ";
						$sql .= $point['in_out'].", ";
						$sql .= "1)";
					}
					
					//Insert de puntos sin actualizar
					if (!mysqli_query($dbremote, $sql)){
						die('Error al insertar los puntos sin actualizar en la base de datos remota:<br> '.$sql.'<br>'. mysqli_error($dbremote));
					}
				
					//Establece los puntos de la base de datos local como actualizados
					$sql = "UPDATE point SET updated = 1";
					
					mysqli_query($dblocal, $sql);
				}
				////////////////////UPDATE-END////////////////////
				
				
				//Obtiene el último punto ingresado por el empleado
				$sql = "SELECT in_out FROM point WHERE employee_id = LPAD(".$senha.", 5, '0') ORDER by point_id DESC LIMIT 1";
				
				if (!$result = mysqli_query($dbremote, $sql)){
					die('Error al seleccionar el ultimo punto registrado en la base de datos remota:<br> ' . mysqli_error($dbremote));
				}
				
				//Si la consulta devolvio un resultado
				if ($row = mysqli_fetch_array($result)){
					
					//La ultima accion tomada
					$last_in_out = $row['in_out'];
					
					//Si la ultima accion es distinta de la ingresda
					if ($last_in_out != $in_out){
						
						//Registra el punto
						$sql = "INSERT point(employee_id, date_time, in_out, updated) ";
						$sql .= "VALUES(LPAD(".$senha.", 5, '0'), NOW(), ".$in_out.", 1)";
						
						if (!$result = mysqli_query($dbremote, $sql)){
							die('Error al insertar su punto en la base de datos remota:<br> ' . mysqli_error($dbremote));
						}
						
						mysqli_close($dblocal);
						mysqli_close($dbremote);
						header('Location: ponto.php');
					}
					else if ($in_out == 0){
						$trying = 'salida';
						$nottrying = 'entrada';
							
						echo 'Su ultimo registro indica una '.$trying.', no puede registrar otra '.$trying.' sin generar una '.$nottrying.' primero.';
						echo '<br><br>';
						echo 'Aguarde unos segundos mientras lo redireccionamos a la pagina anterior';
						mysqli_close($dblocal);
						mysqli_close($dbremote);
						header("Refresh: 5; url=ponto.php");
					}
					else{
						$trying = 'entrada';
						$nottrying = 'salida';
						echo 'Su ultimo registro indica una '.$trying.', no puede registrar otra '.$trying.' sin generar una '.$nottrying.' primero.';
						echo '<br><br>';
						echo 'Aguarde unos segundos mientras lo redireccionamos a la pagina anterior';
						mysqli_close($dblocal);
						mysqli_close($dbremote);
						header("Refresh: 5; url=ponto.php");
					}
				}
				else{
					if ($in_out == '0'){
						echo 'Es su primera vez en el sistema, primero debe generar una entrada antes de una salida';
						echo '<br><br>';
						echo 'Aguarde unos segundos mientras lo redireccionamos a la pagina anterior';
						mysqli_close($dblocal);
						mysqli_close($dbremote);
						header("Refresh: 5; url=ponto.php");
					}
					else{
						$sql = "INSERT point(employee_id, date_time, in_out, updated) ";
						$sql .= "VALUES(LPAD(".$senha.", 5, '0'), NOW(), ".$in_out.", 1)";

						if (!$result = mysqli_query($dbremote, $sql)){
							die('Error al insertar su punto en la base de datos remota:<br> ' . mysqli_error($dbremote));
						}
						
						mysqli_close($dblocal);
						mysqli_close($dbremote);
						header('Location: ponto.php');
					}
				}
			}	
			/////////////////////////////SIN CONEXION/////////////////////////////
			else{
				$sql = "INSERT point(employee_id, date_time, in_out, updated) ";
				$sql .= "VALUES(LPAD(".$senha.", 5, '0'), NOW(), ".$in_out.", 0)";

				if (!$result = mysqli_query($dblocal, $sql)){
					die('Error al insertar su punto en la base de datos local:<br> ' . mysqli_error($dblocal));
				}
				
				mysqli_close($dblocal);
				header('Location: ponto.php');
			}
		}
		else{
			
			//Obtiene el último punto ingresado por el empleado, como su horario de salida
			$sql = "SELECT P.date_time, P.in_out, E.tohour ";
			$sql .= "FROM point P ";
			$sql .= "left join employee E ON P.employee_id = E.employee_id ";
			$sql .= "WHERE 1 ";
			$sql .= "AND P.employee_id = LPAD(".$senha.", 5, '0') ";
			$sql .= "ORDER by P.date_time ";
			$sql.= "DESC LIMIT 1;";
			
			if (!$result = mysqli_query($dblocal, $sql)){
				die('Error al seleccionar el ultimo punto registrado en la base de datos remota:<br> ' . mysqli_error($dblocal));
			}
				
			//Si la consulta devolvio un resultado
			if ($row = mysqli_fetch_array($result)){
				
				//La ultima accion tomada
				$last_date_time = $row['date_time'];
				$last_in_out = $row['in_out'];
				$tohour = $row['tohour'];
				
				//Si la ultima acción corresponde a dias anteriores
				if(date('Y-m-d H:i', strtotime($last_date_time)) < date("Y-m-d H:i", strtotime("now - 10 hours"))){
					//Y su ultimo registro es una entrada
					if ($last_in_out = $row['in_out']){
						//marcar salida
						$sql = "INSERT point(employee_id, date_time, in_out, updated) ";		
						$sql .= "VALUES(LPAD(".$senha.", 5, '0'), '".date("Y-m-d ", strtotime($last_date_time)).date("H:i:s", strtotime($tohour))."', 0, 1)";
						
						if (!$result = mysqli_query($dblocal, $sql)){
							die('Error al insertar su punto en la base de datos local:<br> ' . mysqli_error($dblocal));
						}
						
						//Registra el punto
						$sql = "INSERT point(employee_id, date_time, in_out, updated) ";		
						$sql .= "VALUES(LPAD(".$senha.", 5, '0'), NOW(), 1, 1)";
					
						
						if (!$result = mysqli_query($dblocal, $sql)){
							die('Error al insertar su punto en la base de datos local:<br> ' . mysqli_error($dblocal));
						}
						
						mysqli_close($dblocal);
						header('Location: ponto.php');
						
					}
				}
				
				//Si la ultima accion es distinta de la ingresda
				if ($last_in_out != $in_out){
					
					//Registra el punto
					$sql = "INSERT point(employee_id, date_time, in_out, updated) ";		
					$sql .= "VALUES(LPAD(".$senha.", 5, '0'), NOW(), ".$in_out.", 1)";
				
					
					if (!$result = mysqli_query($dblocal, $sql)){
						die('Error al insertar su punto en la base de datos local:<br> ' . mysqli_error($dblocal));
					}
					mysqli_close($dblocal);
					header('Location: ponto.php');
					
					
				}
				else if ($in_out == 0){
					
					$trying = 'salida';
					$nottrying = 'entrada';
							
					echo 'Su ultimo registro indica una '.$trying.', no puede registrar otra '.$trying.' sin generar una '.$nottrying.' primero.';
					echo '<br><br>';
					echo 'Aguarde unos segundos mientras lo redireccionamos a la pagina anterior';
					mysqli_close($dblocal);
					header('Refresh: 5; URL=ponto.php');
				}
				else{
					$trying = 'entrada';
					$nottrying = 'saída';
					echo 'Seu ultimo registro indica uma '.$trying.', não pode registrar uma '.$trying.' sem gerar uma '.$nottrying.' primero.';
					echo '<br><br>';
					echo 'Aguarde unos segundos mientras lo redireccionamos a la pagina anterior<br>';
					mysqli_close($dblocal);
					header('Refresh: 5; URL=ponto.php');
				}
			}
			else{
				if ($in_out == '0'){
					echo 'Es su primera vez en el sistema, primero debe generar una entrada antes de una salida';
					echo '<br><br>';
					echo 'Aguarde un instante mientras lo redireccionamos a la pagina anterior';
					mysqli_close($dblocal);
					header('Refresh: 5; URL=ponto.php');
				}
				else{
					$sql = "INSERT point(employee_id, date_time, in_out, updated) ";
					$sql .= "VALUES(LPAD(".$senha.", 5, '0'), NOW(), 1, 1)";
					
					if (!$result = mysqli_query($dblocal, $sql)){
						die('Error al insertar su punto en la base de datos local ( as server):<br> ' . mysqli_error($dblocal));
					}
					mysqli_close($dblocal);
					header('Refresh: 5; URL=ponto.php');
				}
			}
		}

}

	if ($_POST['accion'] == 'sendPointMessage'){

		$employee_id = $_POST['employee_id'];
		$data = $_POST['date'];
		$message = $_POST['message'];
		
		$sql = "INSERT point_message(employee_id, data, message) VALUES(";
		$sql .= $employee_id.", ";
		$sql .= "'".$data."', ";
		$sql .= "'".$message."') ";
		
		$resultadoStringSQL = resultFromQuery($sql);
				
		header('Location: funcionarios.pontos.php');
	}


/*Procesos*/

	if ($_POST['accion'] == 'admitirPais') {

		$country = $_POST['country'];
		$state = $_POST['state'];
		$city = $_POST['city'];

		//Insert País//		
		$sql = "insert paises (nombre) values (";
		$sql .= "'".$country."') ";
		
		$resultadoStringSQL = resultFromQuery($sql);		
		$idcountry = mysql_insert_id();
		
		bitacoras($_SESSION["idusuarios"], 'Insertar País ID:'.$idcountry.". Nombre: ".$country);
		
		//Insert Estado//		
		$sql = "insert state (state, country_id) values (";
		$sql .= "'".$state."', ";
		$sql .= "'".$idcountry."') ";
		
		$resultadoStringSQL = resultFromQuery($sql);		
		$idstate = mysql_insert_id();
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Estado ID:'.$idstate.". Nombre: ".$state);
		
		//Insert Ciudad//		
		$sql = "insert city (city, state_id) values (";
		$sql .= "'".$city."', ";
		$sql .= "'".$idstate."') ";
		
		$resultadoStringSQL = resultFromQuery($sql);		
		$idcity = mysql_insert_id();
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Ciudad ID:'.$idcity.". Nombre: ".$city);
		
		//Retorna a la pagina desde donde fue llamado
		echo '<script languaje="javascript"> top.location=window.top.location.href</script>';
	}

	if ($_POST['accion'] == 'admitirEstado') {

		$country = $_POST['country'];
		$state = $_POST['state'];
		$city = $_POST['city'];

		//Insert Estado//		
		$sql = "insert state (state, country_id) values (";
		$sql .= "'".$state."', ";
		$sql .= "'".$country."') ";
		
		$resultadoStringSQL = resultFromQuery($sql);		
		$idstate = mysql_insert_id();
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Estado ID:'.$idstate.". Nombre: ".$state);
		
		//Insert Ciudad//		
		$sql = "insert city (city, state_id) values (";
		$sql .= "'".$city."', ";
		$sql .= "'".$idstate."') ";
		
		$resultadoStringSQL = resultFromQuery($sql);		
		$idcity = mysql_insert_id();
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Ciudad ID:'.$idcity.". Nombre: ".$city);
		
		//Retorna a la pagina desde donde fue llamado
		//echo '<script languaje="javascript"> self.close()</script>';
	}

	if ($_POST['accion'] == 'admitirCiudad') {

		$state = $_POST['state'];
		$city = $_POST['city'];

		//Insert Ciudad//		
		$sql = "insert city (city, state_id) values (";
		$sql .= "'".$city."', ";
		$sql .= "'".$state."') ";
		
		$resultadoStringSQL = resultFromQuery($sql);		
		$idcity = mysql_insert_id();
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Ciudad ID:'.$idcity.". Nombre: ".$city);
		
		//Retorna a la pagina desde donde fue llamado
		echo '<script languaje="javascript"> top.location=window.top.location.href</script>';
	}

/* Reportes */

	if ($_POST['accion'] == 'exportarReportes') {
		require_once("lib/excel.php"); 
		require_once("lib/excel-ext.php"); 
		switch ($_POST['reporte']) {
			case 'liquidacionOperador':
				echo '<a href="reportes.php">volver a la herramienta.</a>';
				echo '<script languaje="javascript"> self.location="toexcel.php"</script>';
				break;
			case 'nominaCompletaEmpleados':
				$sql = "SELECT * FROM  `_temp_liquidaciones_mp` ";
				break;

			case 'seguimientoResultados':
				$sql = "SELECT * FROM ddv2_empleados";
				break;

			case 'seguimientoEstados':
				$sql = "SELECT * FROM ddv2_empleados";
				break;
		}
	}

}

/* Limpiar sesion */

if (isset( $_GET['accion'])) {
	if ($_GET['accion'] == 'limpiarSesion') {
		if (isset($_SESSION["sesionDmasD"])) {
			$sesionDmasD = &$_SESSION["sesionDmasD"];
			$sesionDmasD->initialize();
		}
		echo '<script languaje="javascript"> self.location="index.php"</script>';
	}
}

if (!isset($_SESSION["idusuarios"]) && !strpos($_SERVER['HTTP_REFERER'], 'ponto.php')){
	echo '<script languaje="javascript"> self.location="login.php"</script>';
}

function updateQuery($table, $column, $value, $condition){
	
	$sqlQuery = 'UPDATE ';
	$sqlQuery .= $table.' ';
	$sqlQuery .= 'SET ';
	
	foreach ($column as $j => $val) {
		
		if (mb_substr($sqlQuery, -4) != 'SET ' && mb_substr($sqlQuery, -2) != ', '){
			$sqlQuery  .= ', ';
		}
		
		if (isset($value[$j]) && $value[$j] != ''){
			
			if (is_numeric($value[$j])){
				$sqlQuery .= $val.' = '.$value[$j];
			}
			elseif($value[$j] == 'NULL'){
				$sqlQuery .= $val.' = NULL';
			}
			else{
				$sqlQuery .= $val." = '".$value[$j]."'";
			}
		}
	}
	
	if (mb_substr($sqlQuery, -2) == ', '){
			$sqlQuery = substr($sqlQuery, 0, -2);
	}
	
	$sqlQuery .= ' WHERE 1 ';
	$sqlQuery .= 'AND '.$condition;
	
	return $sqlQuery;
}

function insertQuery($table, $column, $value){
	
	$sqlQuery = 'INSERT ';
	$sqlQuery .= $table.'(';
	
	foreach ($column as $j => $val) {

		if ($value[$j] != ''){
			if (isset($value[$j])){
				$sqlQuery .= $val.', ';
			}
			elseif($value[$j] == 'NULL' || $value[$j] == "'NULL'" || $value[$j] == NULL){
				$sqlQuery .= $val.', ';
			}
				
		}
	}
	if (mb_substr($sqlQuery, -2) == ', '){
			$sqlQuery = substr($sqlQuery, 0, -2);
	}
	
	$sqlQuery .= ') VALUES (';
	
	foreach ($column as $j => $val) {
		
		if (mb_substr($sqlQuery, -1) != '(' && mb_substr($sqlQuery, -2) != ', '){
			$sqlQuery  .= ', ';
		}
		
		if ($value[$j] != ''){
			if (is_numeric($value[$j]) || $value[$j] == 'current_timestamp')
			{
				$sqlQuery .= $value[$j];
			}
			elseif($value[$j] == 'NULL'){
				$sqlQuery .= $val.' = NULL';
			}
			else
			{
				$sqlQuery .= "'".$value[$j]."'";
			}
		}
	}
	if (mb_substr($sqlQuery, -2) == ', '){
			$sqlQuery = substr($sqlQuery, 0, -2);
	}
	
	$sqlQuery  .= ")";
	
	return $sqlQuery;
}

function getColumns($table){
	
	$resultado = resultFromQuery("SHOW COLUMNS FROM ".$table);
	if (!$resultado) {
		echo 'No se pudo ejecutar la consulta: ' . mysql_error();
		exit;
	}
	
	if (mysql_num_rows($resultado) > 0) {
		while ($fila = mysql_fetch_assoc($resultado)) {
			$column[] = $fila['Field'];
		}
	}
	
	return $column;
}

function checkConnection()
{
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

?> 
