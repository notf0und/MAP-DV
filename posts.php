<?php 
	include "lib/sessionLib.php";
	//include "dBug.php";
	//new dBug($_POST);
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
		
		$idlocales = $_SESSION["idlocales"];

		$idadmision = admitirServicio($idmediapension, $qtdedepaxagora, $precio, $idlocales);
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
			$idlocales = $_SESSION["idlocales"];
			echo $idlocales;
			
			if ($qtdedepaxagora)
			{ 
			bitacoras($_SESSION["idusuarios"], 'Insertar $qtdedepaxagora: '.$qtdedepaxagora);
			//INSERT MEDIAPENSION ADMICION
			$datadiaria = date("Y-m-d");
			//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
			$precio = 0;
			$idadmision = admitirServicio($idmediapension, $qtdedepaxagora, $precio, $idlocales);
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

/* Area Contable */
	if ($_POST['accion'] == 'admitirEmpleado') {
			
		//INSERT ADDRESS
		//Parametros que se van a pasar
		$sql = "INSERT address(";
		$sql .= $_POST['city_id'] != '' ? 'city_id' : '';//Validar, debe ir obligatoriamente
		$sql .= $_POST['neightborhood'] != '' ? ', neightborhood' : '';
		$sql .= $_POST['address'] != '' ? ', address' : '';
		$sql .= $_POST['addressnumber'] != '' ? ', addressnumber' : '';
		$sql .= $_POST['addressfloor'] != '' ? ', addressfloor' : '';
		$sql .= $_POST['addressapartment'] != '' ? ', addressapartment' : '';
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
		$sql .= $_POST['birth_city_id'] != '' ? ', birth_city_id' : '';
		$sql .= $_POST['fathername'] != '' ? ', fathername' : '';
		$sql .= $_POST['mothername'] != '' ? ', mothername' : '';
		$sql .= $_POST['civilstatus'] != '' ? ', civilstatus' : '';
		$sql .= $_POST['marriedname'] != '' ? ', marriedname' : '';
		$sql .= $_POST['education_id'] != 0 ? ', education_id' : '';
		$sql .= $address_id != '' ? ', address_id' : '';
		$sql .= ") ";
		
		//Valores a pasar
		$sql .= "VALUES (";		
		$sql .= $_POST['profile_firstname'] != '' ? "'".$_POST['profile_firstname']."'" : '';
		$sql .= $_POST['profile_lastname'] != '' ? ', '."'".$_POST['profile_lastname']."'" : '';
		$sql .= $_POST['sex_id'] != 0 ? ', '.$_POST['sex_id'] : '';
		$sql .= $birthdate ? ', '."'".$birthdate."'" : '';
		$sql .= $_POST['birth_city_id'] != '' ? ', '.$_POST['birth_city_id'] : '';
		$sql .= $_POST['fathername'] != '' ? ', '."'".$_POST['fathername']."'" : '';
		$sql .= $_POST['mothername'] != '' ? ', '."'".$_POST['mothername']."'" : '';
		$sql .= $_POST['civilstatus'] != '' ? ', '.$_POST['civilstatus'] : '';
		$sql .= $_POST['marriedname'] != '' ? ', '."'".$_POST['marriedname']."'" : '';
		$sql .= $_POST['education_id'] != 0 ? ', '.$_POST['education_id'] : '';
		$sql .= $address_id != '' ? ', '.$address_id : '';
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
			$sql .= $_POST['cpfnumber'] != '' ? ', '.$_POST['cpfnumber'] : '';
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
			$sql .= $_POST['eleitorsection'] != '' ? ', '."'".$_POST['eleitornumber']."'" : '';
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
			$sql .= $_POST['idnumber'] != '' ? ', '.$_POST['idnumber'] : '';
			$sql .= $_POST['idexpeditor'] != '' ? ', '."'".$_POST['idexpeditor']."'" : '';
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
		
		//Parametros a pasar
		$sql = "INSERT employee(";
		$sql .= "profile_id";
		$sql .= $_POST['idempresa'] != '' ? ', idempresa' : '';
		$sql .= $_POST['jobcategory_id'] != '' ? ', jobcategory_id' : '';
		$sql .= $_POST['bonussalary'] != '' ? ', bonussalary' : '';
		$sql .= $admissiondate ? ', admission' : '';
		$sql .= $_POST['contractdate'] != '' ? ', contract' : '';
		$sql .= $_POST['transport'] != 0 ? ', transport' : '';
		$sql .= $_POST['traject'] != '' ? ', traject' : '';
		$sql .= $_POST['fromhour'] != '' ? ', fromhour' : '';
		$sql .= $_POST['tohour'] != '' ? ', tohour' : '';
		$sql .= $_POST['intervalhour'] != '' ? ', intervalhour' : '';
		$sql .= $_POST['experiencecontract'] != 0 ? ', experiencecontract' : '';
		$sql .= ") ";
		
		//Valores a pasar
		$sql .= "VALUES (";		
		$sql .= $profile_id;
		$sql .= $_POST['idempresa'] != '' ? ', '.$_POST['idempresa'] : '';
		$sql .= $_POST['jobcategory_id'] != '' ? ', '.$_POST['jobcategory_id'] : '';
		$sql .= $_POST['bonussalary'] != '' ? ', '."'".$_POST['bonussalary']."'" : '';
		$sql .= $admissiondate ? ', '."'".$admissiondate."'" : '';
		$sql .= $contractdate ? ', '."'".$contractdate."'" : '';
		$sql .= $_POST['transport'] != 0 ? ', '."'".$_POST['transportvalue']."'" : '';
		$sql .= $_POST['traject'] != '' ? ', '."'".$_POST['traject']."'" : '';
		$sql .= $_POST['fromhour'] != '' ? ', '."'".$_POST['fromhour']."'" : '';
		$sql .= $_POST['tohour'] != '' ? ', '."'".$_POST['tohour']."'" : '';
		$sql .= $_POST['intervalhour'] != '' ? ', '."'".$_POST['intervalhour']."'" : '';
		$sql .= $_POST['experiencecontract'] != 0 ? ', '."'".$_POST['experiencecontract']."'" : '';
		$sql .= ")";
		
		$resultado = resultFromQuery($sql);
		
		echo '<script languaje="javascript"> self.location="salarios.php"</script>';
	}

	if ($_POST['accion'] == 'admitJobcategory') {
		
		$name = $_POST['jobcategory_name'];
		$basesalary = $_POST['basesalary'];
		$valid_from = $_POST['valid_from'];
		
		//Insert Base Salary
		$sql = "INSERT basesalary (basesalary, valid_from, created) ";
		$sql .= "VALUES (";
		$sql .= "'".$basesalary."',";
		$sql .= "'".$valid_from."',";
		$sql .= "current_timestamp) ";
		$resultadoStringSQL = resultFromQuery($sql);		
		$basesalary_id = mysql_insert_id();
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Salario Base ID: '.$basesalary_id);
		
		//Insert Job Category
		$sql = "INSERT jobcategory (name, basesalary_id, created) ";
		$sql .= "VALUES (";
		$sql .= "'".$name."',";
		$sql .= "'".$basesalary_id."',";
		$sql .= "current_timestamp) ";
		$resultadoStringSQL = resultFromQuery($sql);		
		$jobcategory_id = mysql_insert_id();
		
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Categoria Laboral: '.$jobcategory_id);
	}
	
	if ($_POST['accion'] == 'admitPayment') {
		
		$employee_id = $_POST['employee_id'];
		$paymenttype_id = $_POST['paymenttype_id'];
		$paymentmethod_id = $_POST['paymentmethod_id'];
		$ammount = $_POST['ammount'];
		
		//Insert Base Salary
		$sql = "INSERT payment(employee_id, paymenttype_id, paymentmethod_id, ammount, date) ";
		$sql .= "VALUES (";
		$sql .= $employee_id.", ";
		$sql .= $paymenttype_id.", ";
		$sql .= $paymentmethod_id.", ";
		$sql .= "'".$ammount."', ";
		$sql .= "current_timestamp) ";
		$resultadoStringSQL = resultFromQuery($sql);		
		$payment_id = mysql_insert_id();
		
		bitacoras($_SESSION["idusuarios"], 'Insertar Pagamento: '.$payment_id);
		
		echo '<script languaje="javascript"> self.location="pagamentos.php"</script>';
	}

	if ($_POST['accion'] == 'employeeModify') {
		$_SESSION['employee'] = $_POST['id'];
		echo '<script languaje="javascript"> self.location="funcionarios.novo.php"</script>';
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

if (!isset($_SESSION["idusuarios"])){
	echo '<script languaje="javascript"> self.location="login.php"</script>';
}


?> 
