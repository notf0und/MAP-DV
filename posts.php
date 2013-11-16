<?php 
	include "lib/sessionLib.php";
	include "dBug.php";
	new dBug($_POST);
	//var_dump($_POST);
	//die($_POST);

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
			$Sesion = parse_ini_file("./local-config.ini", true)[config];
			
			$_SESSION["idlocales_PRN_USER"] = $Sesion[terminal_user];
			$_SESSION["idlocales_PRN_PASS"] = $Sesion[terminal_password];
			$_SESSION["idlocales_PRN_TITULO"] = $Sesion[terminal_titulo];

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
		posadasCancelar($_POST['idposadas']);
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
				new dBug($resultadoStringSQL);

				while ($row = mysql_fetch_object($resultadoStringSQL)) {
					echo $row->idposadas;
					echo $row->nombre;
					echo "<br>";
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
						for($i=6;$i<15;$i++){
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
				for($i=1;$i<6;$i++){
					$precio = $_POST['0_'.$i];
					$idservicios = $i;
					$idposadas_internas = 0;
					$resultado = guardarPrecio($idlistasdeprecios, $idservicios, $precio, $idposadas_internas);
					echo ' resultado = :'.$resultado.' - <br>';
					echo ' servicio:'.$i.' - posada: 0 - precio:'.$precio.' <br>';
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
			echo '<script languaje="javascript"> self.location="hoteleria.vouchers.php"</script>';
			
		} else {
		
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

		
			echo '<script languaje="javascript"> self.location="hoteleria.vouchers.php"</script>';
		}
		
	}

	if ($_POST['accion'] == 'VouchersHTLNew') {
		$_SESSION['idhoteleria'] = -1;
		echo '<script languaje="javascript"> self.location="hoteleria.vouchers.edit.php"</script>';
	}

	if ($_POST['accion'] == 'VouchersHTLModify') {
		$_SESSION['idhoteleria'] = $_POST['id'];
		echo '<script languaje="javascript"> self.location="hoteleria.vouchers.edit.php"</script>';
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

		$idadmision = admitirServicio($idmediapension, $qtdedepaxagora, $precio);
		bitacoras($_SESSION["idusuarios"], 'Admitir servicio MP: ID '.$idmediapension);
		echo '<script languaje="javascript"> top.location="mediapension.print.php?id='.$idadmision.'"</script>';


	}

	if ($_POST['accion'] == 'admitirMediapension') {

		$idmediapension = $_POST['idmediapension'];
		$numeroexterno = $_POST['numeroexterno'];//ok
		$nomedopax = $_POST['nomedopax'];///WTF
		$idpaises = $_POST['idpaises'];//ok
		$idoperadoresturisticos = $_POST['idoperadoresturisticos'];//ok
		$idposadas = $_POST['idposadas'];//ok
		$idagencias = $_POST['idagencias'];//ok
		$idhuespedes = $_POST['idhuespedes'];//ok
		$idresponsablesDePago = $_POST['idresponsablesDePago'];//ok
		$qtdedepax = $_POST['qtdedepax'];//ok
		$qtdedepaxagora = $_POST['qtdedepaxagora'];///WTF
		$dataIN = $_POST['dataIN'];//ok
		$dataOUT = $_POST['dataOUT'];//ok
		$qtdedecomidas = $_POST['qtdedecomidas'];//ok
		$idservicios = $_POST['idservicios'];//ok
		$mensajeinterno = $_POST['mensajeinterno'];//ok//1
		$mensajegarcon = $_POST['mensajegarcon'];//ok//1
		$idlocales = $_SESSION["idlocales"];//ok
		
		//Set default values for comboboxes
		if ($idoperadoresturisticos === '') $idoperadoresturisticos = '0';
		if ($idposadas === '') $idposadas = '0';
		if ($idagencias === '') $idagencias = '0';
		if ($idresponsablesDePago === '') $idresponsablesDePago = '0';
		
		if ($idlocales==0){
			$actualizado = 1;
			$hoteleria = $_POST['hoteleria'];
		}else{
			$actualizado = 0;
			$hoteleria = 0;
		}

		if ($idmediapension > -1) {

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
			echo $_SESSION["idlocales"];
			
			if ($qtdedepaxagora)
			{ 
			bitacoras($_SESSION["idusuarios"], 'Insertar $qtdedepaxagora: '.$qtdedepaxagora);
			//INSERT MEDIAPENSION ADMICION
			$datadiaria = date("Y-m-d");
			//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
			$precio = 0;
			$idadmision = admitirServicio($idmediapension, $qtdedepaxagora, $precio);
			}
			
			if ($_SESSION["idlocales"]>0){
				echo '<script languaje="javascript"> self.location="mediapension.print.php?id='.$idadmision.'"</script>';
			}else{
				
				echo '<script languaje="javascript"> self.location="mediapension.vouchers.php"</script>';
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

	if ($_POST['accion'] == 'VouchersMPNew') {
		$_SESSION['idmediapension'] = -1;
		echo '<script languaje="javascript"> self.location="mediapension.vouchers.edit.php"</script>';
	}

	if ($_POST['accion'] == 'VouchersMPModify') {
		$_SESSION['idmediapension'] = $_POST['id'];
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
		} else {
		
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

if (!isset($_SESSION["idusuarios"])){
	echo '<script languaje="javascript"> self.location="login.php"</script>';
}


?> 
