<?php

function esLoginValido(&$usuario, $username, $clave){
	// Cargamos el usuario
//	$usuario = new Usuario;
	loadUsuario($username, $usuario);
	
	// Si el usuario no existe, devolvemos False
	if ($usuario->nombre == '') {
		return false;
	}	
	
	// Comparamos el usuario con la clave
	// si coinciden, devolvemos True, sino False
	return $usuario->clave == $clave;

}

function loadPermisosDeUsuario(&$usuario) {
	$administradores = array(1, 2, 99999994, 9999, 10015, 10016);
	if (in_array($usuario->id, $administradores)) {
		$permiso = &$usuario->permisos->nuevo();
		$permiso->nombre = 'administrador';
		$permiso->descripcion = 'Permisos de administracion sobre el Sistema D+D';
	}
	
}

function loadUsuarioDelSistema(&$sesionDmasD) {
	$usuario = new Usuario;
	loadUsuario('Sistema D+D',$usuario);
	$sesionDmasD->usuarios->add($usuario);
	$sesionDmasD->usuarioDelSistema = &$usuario;
}

function usuarioConIDEmpleadoFromDB($idEmpleado) {
	// Busco el usuario realacionado con el idEmpleado
	$sql = "SELECT u.nombre ";
	$sql .= "from ddv2_usuarios u, ddv2_relaciondeusuariosyempleados ree ";
	$sql .= "where u.idddv2_usuarios = ree.idddv2_usuarios ";
	$sql .= "";
	$sql .= "  and ree.idddv2_empleados = ".$idEmpleado;

  $result = resultFromQuery($sql);
  $row = siguienteResult($result);
  
	if ($row != false) {
		$usuario = new Usuario;
		$nombreUsuario = $row->nombre;
		loadUsuario($nombreUsuario, $usuario);
		return $usuario;
	} else {
		return null;
	}
	
}

function loadUsuario($nombreUsuario, &$usuario) {
	// Busca el usuario por nombre y llena la informacion de usuario
	// Devuelve si encontro o no al usuario

  // inicializamos el usuario
  $usuario->initialize();

  // Buscamos por nombre
  $sql = "SELECT u.idddv2_usuarios ID, clave PASSWD, nombre NOMBRE, ree.idddv2_empleados EMPLEADO ";
	$sql.= "FROM ddv2_usuarios u left join ddv2_relaciondeusuariosyempleados ree ";
	$sql.= " on ree.idddv2_usuarios = u.idddv2_usuarios ";
	$sql.= "WHERE upper(u.nombre) = upper(".dbStringFrom($nombreUsuario).")";
	
  $result = resultFromQuery($sql);
  $row = siguienteResult($result);
	
	// Si encontramos, le damos para adelante...
	if ($row != false) {
		// Info de usuario
		$usuario->nombre = $row->NOMBRE;
		$usuario->id = $row->ID;
		$usuario->clave = $row->PASSWD;
		if (!is_null($row->EMPLEADO)) {
			$usuario->empleado = empleadoFromDB($row->EMPLEADO);
		}
	}
	
	loadPermisosDeUsuario($usuario);

	return ($row != false);
  
}

function posicionFromDB($idPosiciones) {
  $sql = "SELECT * ";
	$sql.= "FROM ddv2_posiciones ";
	$sql.= "WHERE idddv2_posiciones = ".$idPosiciones;

  $result = resultFromQuery($sql);
  $row = siguienteResult($result);

	$posicion = new ItemGenerico;
	$posicion->id = $idPosiciones;
	
	if ($row != false) {
		$posicion->nombre = $row->nombre;
	} else {
		$posicion->nombre = 'Posicion ID: '.$idPosiciones.' no existente en el listado de posiciones';
	}
	
	return $posicion;
}

function areaFromDB($idAreas) {
  $sql = "SELECT * ";
	$sql.= "FROM ddv2_areas ";
	$sql.= "WHERE idddv2_areas = ".$idAreas;

  $result = resultFromQuery($sql);
  $row = siguienteResult($result);

	$area = new ItemGenerico;
	$area->id = $idAreas;
	
	if ($row != false) {
		$area->nombre = $row->nombre;
	} else {
		$area->nombre = 'Area ID: '.$idAreas.' no existente en el listado de posiciones';
	}
	
	return $area;
}

function plantaFromDB($idPlantas) {
  $sql = "SELECT * ";
	$sql.= "FROM ddv2_plantas ";
	$sql.= "WHERE idddv2_plantas = ".$idPlantas;

  $result = resultFromQuery($sql);
  $row = siguienteResult($result);

	$planta = new ItemGenerico;
	$planta->id = $idPlantas;
	
	if ($row != false) {
		$planta->nombre = $row->nombre;
	} else {
		$planta->nombre = 'Planta ID: '.$idPlantas.' no existente en el listado de posiciones';
	}
	
	return $planta;
}

function plandecarreraFromDB($idEmpleado) {
  $sql = "SELECT * ";
	$sql.= "FROM ddv2_planesdecarrera ";
	$sql.= "WHERE idddv2_empleados = ".$idEmpleado;

  $result = resultFromQuery($sql);
  $row = siguienteResult($result);

	$plandecarrera = new ItemGenerico;
	
	if ($row != false) {
		$plandecarrera->id = $row->idddv2_planesdecarrera;
		$plandecarrera->nombre = '';
	} else {
		$plandecarrera->id = 'no ten';
		$plandecarrera->nombre = 'Plan de carrera ID: '.$idEmpleado.' no existente en el listado de posiciones';
	}
	
	return $plandecarrera;
}

function empleadoFromDB($idEmpleado) {

	$empleado = new Empleado;
	$empleado->initialize();
	$empleado->id = $idEmpleado;
	loadEmpleado($empleado);
	
	return $empleado;
}

function loadEmpleado(&$empleado) {
	// Busca el empleado por id y llena la informacion correspondiente
	// Devuelve si encontro o no al empleado

  // Buscamos por nombre
  $sql = "SELECT * ";
	$sql.= "FROM ddv2_empleados ";
	$sql.= "WHERE idddv2_empleados = ".$empleado->id;
	
  $result = resultFromQuery($sql);
  $row = siguienteResult($result);
	
	// Si encontramos, le damos para adelante...
	if ($row != false) {
		// Info de empleado
		$empleado->numeroDeLegajo = $row->numeroDeLegajo;
		$empleado->fechaDeIngreso = $row->fechaDeIngreso;
		$empleado->urlDeFoto = $row->urlDeFoto;
		$empleado->nombres = $row->nombres;
		$empleado->apellidos = $row->apellidos;
		$empleado->direccion = $row->direccion;
		$empleado->telefonoFijo = $row->telefonoFijo;
		$empleado->telefonoMovil = $row->telefonoMovil;
		$empleado->nombreDeConvenio = $row->nombreDeConvenio;
		$empleado->email = $row->email;
		$empleado->posicion = posicionFromDB($row->idddv2_posiciones);
		$empleado->planta = plantaFromDB($row->idddv2_plantas);
		$empleado->area = areaFromDB($row->idddv2_areas);
		$empleado->plandecarrera = plandecarreraFromDB($row->idddv2_empleados);
		$empleado->esEvaluador = tieneColaboradores($empleado);
		$empleado->esReviewer = tieneRevisiones($empleado);
	}
	
	$empleadoEncontrado = ($row != false);

	// Levantamos los grupos del empleado
	$sql = "SELECT g.* ";
	$sql .= "FROM ddv2_gruposdeempleados g, ddv2_relaciondegruposyempleados rge ";
	$sql .= "WHERE rge.idddv2_gruposdeempleados = g.idddv2_gruposDeEmpleados ";
	$sql .= "  and rge.idddv2_empleados = ".$empleado->id;
	
  $result = resultFromQuery($sql);
	
	while ($row = siguienteResult($result)) {
		$grupo = new ItemGenerico;
		$grupo->id = $row->idddv2_gruposdeempleados;
		$grupo->nombre = $row->nombre;
		$grupo->descripcion = $row->descripcion;
		$empleado->grupos->add($grupo);
	}
	
	return $empleadoEncontrado;
  
}

function guardarEmpleado(&$empleado) {
	// Busca el empleado por id y llena la informacion correspondiente
	// Devuelve si encontro o no al empleado

  // Buscamos por nombre
  $sql = "SELECT * ";
	$sql.= "FROM ddv2_empleados ";
	$sql.= "WHERE idddv2_empleados = ".$empleado->id;
	
  $result = resultFromQuery($sql);
  $row = siguienteResult($result);
	
	// Si encontramos, le damos para adelante...
	if ($row != false) {
		// Info de empleado
//	var $planta;
//	var $area;
		$empleado->numeroDeLegajo = $row->numeroDeLegajo;
		$empleado->fechaDeIngreso = $row->fechaDeIngreso;
		$empleado->urlDeFoto = $row->urlDeFoto;
		$empleado->nombres = $row->nombres;
		$empleado->apellidos = $row->apellidos;
		$empleado->direccion = $row->direccion;
		$empleado->telefonoFijo = $row->telefonoFijo;
		$empleado->telefonoMovil = $row->telefonoMovil;
		$empleado->nombreDeConvenio = $row->nombreDeConvenio;
		$empleado->email = $row->email;
		$empleado->posicion = posicionFromDB($row->idddv2_posiciones);
		$empleado->planta = plantaFromDB($row->idddv2_plantas);
		$empleado->area = areaFromDB($row->idddv2_areas);
	}

	return ($row != false);
  
}

function actualizarEmpleadosEnGrupo($idddv2_gruposdeempleados, $empleadosEnGrupo) {
	/* Borra todas las relaciones (empleados asiganados a ese grupo) */
	$sql = "DELETE  ";
	$sql .= "FROM ddv2_relaciondegruposyempleados ";
	$sql .= "  WHERE  idddv2_gruposDeEmpleados = ".$idddv2_gruposdeempleados;
	$result = resultFromQuery($sql);
	
	/* Inserto las nuevas relaciones (empleados asiganados a ese grupo) */
	$count = count($empleadosEnGrupo);
	for ($i = 0; $i < $count; $i++) {
		$sql = "INSERT INTO ddv2_relaciondegruposyempleados (idddv2_empleados, idddv2_gruposDeEmpleados) VALUES (";
		$sql .= "".$empleadosEnGrupo[$i].",";
		$sql .= "'".$idddv2_gruposdeempleados."') ";
		$result = resultFromQuery($sql);
	}
}

function enviarMailSimple($from, $to, $asunto, $mensaje) {
	mail($to, $asunto, $mensaje,'From: '.$from."\r\nContent-type: text/html\r\n");
}

function enviarMail($userName, $userPass, $fromAddress, $fromName, $toAddress, $toName, $asunto, $mensaje) {
	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

	$mail->IsSMTP(); // telling the class to use SMTP

	try {
	  $mail->Host       = "smtp.softguild.com.ar"; // SMTP server
	  $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
	  $mail->AddReplyTo('info@softguild.com.ar', 'Rosa Rosa');
	  $mail->AddAddress('spol55@gmail.com', 'Pablito');
	  $mail->SetFrom('info@softguild.com.ar', 'Rosa Rosa');
	  $mail->AddReplyTo('info@softguild.com.ar', 'Rosa Rosa');
	  $mail->Subject = $asunto;
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	  $mail->MsgHTML($mensaje);
	  $mail->Send();
	  echo "Message Sent OK</p>\n";
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}
}

function enviarGMail($userName, $userPass, $fromAddress, $fromName, $toAddress, $toName, $asunto, $mensaje) {
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPDebug  = 2;
	$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
	$mail->Username = $userName;
	$mail->Password = $userPass;
	$mail->From = $fromAddress;
	$mail->FromName = $fromName;
	$mail->Subject = $asunto;
	$mail->AltBody = $mensaje;
	$mail->MsgHTML($mensaje);
 //	$mail->AddAttachment("files/img03.jpg");
	$mail->AddAddress($toAddress, $toName);
	$mail->IsHTML(true);
	 
	if(!$mail->Send()) {
	  echo "Error: " . $mail->ErrorInfo;
	} else {
	  echo "Mensaje enviado correctamente";
	}
}

function evaluacionFromDB($idEvaluacion, &$sesionDmasD = null) {
  $sql = "SELECT * ";
	$sql.= "FROM ddv2_evaluaciones ";
	$sql.= "WHERE idddv2_evaluaciones = ".$idEvaluacion;

  $result = resultFromQuery($sql);
  $row = siguienteResult($result);

	$evaluacion = new EvaluacionDeEmpleado;
	$evaluacion->initialize();
	
	$evaluacion->id = $idEvaluacion;
	
	if ($row != false) {
		$evaluacion->evaluado = empleadoFromDB($row->evaluado);
		$evaluacion->evaluador = empleadoFromDB($row->evaluador);
		$evaluacion->reviewer = empleadoFromDB($row->reviewer);
		$evaluacion->fechaDeCreacion = $row->fechaDeCreacion;
		$evaluacion->cantidadDeObjetivosCuantitativosRequeridos = $row->cantidadDeObjetivosCuantitativosRequeridos;
		$evaluacion->cantidadDeObjetivosCualitativosRequeridos = $row->cantidadDeObjetivosCualitativosRequeridos;
	}
	
	loadEvaluacion($evaluacion);
	
	if (isset($sesionDmasD)) {
		$sesionDmasD->aplicarEstadosSobreItemsDeEvaluacion($evaluacion);
	}
	
	return $evaluacion;
}


function loadEvaluacion(&$evaluacion) {
// items de la evaluacion
  $sql = "select gdi.idddv2_gruposdeitems, gdi.nombre as nombreDelGrupo, gdi.descripcion as descripcionDelGrupo, ide.*
					from ddv2_gruposDeItems gdi, ddv2_itemsDeEvaluacion ide, ddv2_relacionDeItemsYEvaluaciones rie
					where ide.idddv2_itemsdeevaluacion = rie.idddv2_itemsDeEvaluacion
					  and (ide.nombre like concat(gdi.nombreDeItems,'%') or ide.nombre = gdi.nombre)
					  and ide.fechaHoraDeBaja is null
					  and rie.idddv2_evaluaciones = ".$evaluacion->id."
					order by gdi.idddv2_gruposdeitems, ide.idddv2_itemsdeevaluacion";
	
  $result = resultFromQuery($sql);
	
	while ($row = siguienteResult($result)) {
// Datos del Item
		$item = new ItemDeEvaluacion;
		$item->initialize();

		$item->id = $row->idddv2_itemsdeevaluacion;
		$item->nombre = $row->nombre;
		$item->descripcion = $row->descripcion;
		
		loadItemDeEvaluacion($item);

// Datos del grupo de items
		
		$grupo = &$evaluacion->gruposDeItems->conID($row->idddv2_gruposdeitems);
		if (!isset($grupo)) {
			$grupo = new ListaGenerica;
			$grupo->id = $row->idddv2_gruposdeitems;
			$grupo->nombre = $row->nombreDelGrupo;
			$grupo->descripcion = $row->descripcionDelGrupo;
			$evaluacion->addGrupo($grupo);
		}

		if (strpos($item->nombre, ':') === false) {
			$evaluacion->addItem($item, $grupo);
		} elseif (strpos($item->nombre, ':accion') > 0) {
			$nombre = substr($item->nombre, 0, strpos($item->nombre, ':'));
			$itemPadre = &$grupo->conNombre($nombre);
			if (!is_null($itemPadre)) {
				$itemPadre->acciones->add($item);
			}
		}
		
	}

	return ($row != false);
  
}

function loadItemDeEvaluacion(&$item) {
	$sql = "select * ";
	$sql .= "from ddv2_datosextradeitems de ";
	$sql .= "where idddv2_itemsDeEvaluacion = ".$item->id;
	
  $result = resultFromQuery($sql);
	
	while ($row = siguienteResult($result)) {
		$datoExtra = new DatoExtraDeItem;
		
		$datoExtra->id = $row->idddv2_datosextradeitems;
		$datoExtra->nombre = $row->nombre;
		$datoExtra->descripcion = $row->descripcion;
		if (isset($row->valor)) {
			$datoExtra->valor = $row->valor;
		} else {
			$datoExtra->valor = $row->textoLargo;
		}
		$datoExtra->tipoDeDato = $row->idddv2_tiposDeDato;
		
		$item->datosExtra->add($datoExtra);
	}
	
	$sql = "select * ";
	$sql .= "from ddv2_valoraciondeitems ";
	$sql .= "where idddv2_itemsDeEvaluacion = ".$item->id;
	
  $result = resultFromQuery($sql);
	
	while ($row = siguienteResult($result)) {
		// Ponderacion
		if ($row->idddv2_tiposDeValoracion == 1) {
			$item->ponderacion = $row->valorNumerico;
		}
		
		// Evaluacion
		if ($row->idddv2_tiposDeValoracion == 2) {
			$item->evaluacion = $row->valorNumerico;
		}
		
		// Autoevaluacion
		if ($row->idddv2_tiposDeValoracion == 3) {
			$item->autoevaluacion = $row->valorNumerico;
		}

		// Estado de Item
		if ($row->idddv2_tiposDeValoracion == 4) {
			$item->estado = $row->valorAsociado;
		}
	}
	

}

function periodoDeEvaluacionFromDB($idPeriodo) {
	$sql  = "SELECT * ";
	$sql .= "FROM ddv2_periodosdeevaluacion ";
	$sql .= "WHERE idddv2_periodosdeevaluacion = ".$idPeriodo;
	
	$periodo = new PeriodoDeEvaluacion;
	$periodo->initialize();

	$periodo->id = $idPeriodo;

  $result = resultFromQuery($sql);
  $row = siguienteResult($result);
	
	if ($row != false) {
		$periodo->evaluacionPrototipo = evaluacionFromDB($row->evaluacionPrototipo);
		$periodo->fechaDeInicio = $row->fechaDeInicio;
		$periodo->fechaDeFin = $row->fechaDeFin;
		$periodo->descripcion = $row->descripcion;
	}
	
	loadPeriodoDeEvaluacion($periodo);
	
	return $periodo;
}

function loadPeriodoDeEvaluacion(&$periodo) {
	// Cargo las cosas extendidas del periodo... ????
	
}

function idPeriodoDeEvaluacionPara($idEmpleado) {
	$sql = "SELECT pe.idddv2_periodosdeevaluacion ";
	$sql .= "from ddv2_periodosdeevaluacion pe, ddv2_relaciondegruposyempleados rge ";
	$sql .= "where pe.idddv2_grupoDeEmpleados = rge.idddv2_gruposDeEmpleados ";
	$sql .= "  and now() between pe.fechaDeInicio and pe.fechaDeFin ";
	$sql .= "  and rge.idddv2_empleados = ".$idEmpleado;
	
	$result = resultFromQuery($sql);
	$row = siguienteResult($result);
	
	if ($row != false) {
		return $row->idddv2_periodosdeevaluacion;
	} else {
		$sql = "SELECT pe.idddv2_periodosdeevaluacion ";
		$sql .= "from ddv2_periodosdeevaluacion pe, ddv2_relaciondegruposyempleados rge ";
		$sql .= "where pe.idddv2_grupoDeEmpleados = rge.idddv2_gruposDeEmpleados ";
		$sql .= "  and pe.fechaDeInicio = (select max(fechaDeInicio) from ddv2_periodosdeevaluacion where idddv2_gruposDeEmpleados = rge.idddv2_gruposDeEmpleados) ";
		$sql .= "  and rge.idddv2_empleados = ".$idEmpleado;
		
		$result = resultFromQuery($sql);
		$row = siguienteResult($result);

		if ($row != false) {
			return $row->idddv2_periodosdeevaluacion;
		} else {
			return -1;
		}
		
	}
}

function periodoDeEvaluacionFromDBPara($idEmpleado) {
	$idPeriodo = idPeriodoDeEvaluacionPara($idEmpleado);
	if ($idPeriodo <> -1) {
		return periodoDeEvaluacionFromDB($idPeriodo);
	} else {
		return null;
	}
}

function idEvaluacionPara(&$sesionDmasD) {
	$sql = "SELECT e.idddv2_evaluaciones ";
	$sql .= "FROM ddv2_relaciondeperiodosyevaluaciones rpe, ddv2_evaluaciones e ";
	$sql .= "where rpe.idddv2_evaluaciones = e.idddv2_evaluaciones ";
	$sql .= "  and rpe.idddv2_periodosDeEvaluacion = ".$sesionDmasD->periodoDeEvaluacion->id;
	$sql .= "  and e.evaluado = ".$sesionDmasD->usuario->empleado->id;
	
	$result = resultFromQuery($sql);
	$row = siguienteResult($result);
	
	if ($row != false) {
		return $row->idddv2_evaluaciones;
	}
	
	
}

function loadEvaluacionDeSesion(&$sesionDmasD) {
	if (isset($sesionDmasD->periodoDeEvaluacion)) {
		$idEvaluacion = idEvaluacionPara($sesionDmasD);
		echo "idEvaluacion:".$idEvaluacion;
		if ($idEvaluacion <> '') {
			$sesionDmasD->evaluacionDeEmpleado = evaluacionFromDB($idEvaluacion, $sesionDmasD);
		}
	}
}

function loadEstadosDeItems(&$sesionDmasD) {
	$sql = "select vp.* ";
	$sql .= "from ddv2_valorespredefinidos vp, ddv2_tiposdedato td ";
	$sql .= "where vp.idddv2_tiposDeDato = td.idddv2_tiposdedato ";
	$sql .= "  and td.nombre like '%estado%' ";

  $result = resultFromQuery($sql);
	
	while ($row = siguienteResult($result)) {
		$estado = new EstadoDeItem;
		$estado->id = $row->idddv2_valoresPredefinidos;
		$estado->nombre = $row->nombre;
		$estado->descripcion = $row->descripcion;
		$estado->tipoDeDato = $row->idddv2_tiposDeDato;
		$sesionDmasD->estadosDeItems->add($estado);
	}
}

function personalizarObjetivosCualitativos(&$sesionDmasD) {
	// Revisamos los objetivosCualitativos que no corresponden al evaluado y los eliminamos
	$evaluacion = &$sesionDmasD->periodoDeEvaluacion->evaluacionPrototipo;
	if (isset($evaluacion)){
		$empleado = &$sesionDmasD->empleadoEnRevision;
		$objetivos = &$evaluacion->gruposDeItems->conNombre('objetivosCualitativos');
		// Leemos las asignaciones de objetivos por grupoDeEmpleados
		$sql = "SELECT * ";
		$sql .= "FROM ddv2_relaciongruposdeempleadosyobjetivos ";
		$sql .= "WHERE idddv2_itemsdeevaluacion in ( ";
		$sql .= $objetivos->asSQLIDString();
		$sql .= ") ";
		
		$result = resultFromQuery($sql);
	}
	
	while ($row = siguienteResult($result)) {
		$idGrupo = $row->idddv2_gruposDeEmpleados;
		$idObjetivo = $row->idddv2_itemsdeevaluacion;
		
		// si el item esta en un grupo que no contiene al empleado, lo elimino
		$grupo = &$empleado->grupos->conID($idGrupo);
		if (is_null($grupo)) {
			$objetivo = &$objetivos->conID($idObjetivo);
			$objetivos->remove($objetivo);
		}
	}
	
	
}


function loadMensajes(&$sesionDmasD) {
	$evaluacion = &$sesionDmasD->evaluacionEnRevision;
	
	$sql = "select m.*, u.nombre as usuarioOrigen, rie.idddv2_evaluaciones ";
	$sql .= "from ddv2_mensajes m ";
	$sql .= "left join ddv2_relaciondeitemsyevaluaciones rie ";
	$sql .= "  on rie.idddv2_itemsDeEvaluacion = m.idddv2_itemsDeEvaluacion ";
	$sql .= ", ddv2_usuarios u ";
	$sql .= "where u.idddv2_usuarios = m.idddv2_usuarios ";
	$sql .= "  and ( ";
	$sql .= "  			m.idddv2_usuarios_destinatario = ".$sesionDmasD->usuario->id." ";
	$sql .= "  	 or m.idddv2_usuarios = ".$sesionDmasD->usuario->id." ";
	if (isset($evaluacion)) {
		$sql .= "  	 or rie.idddv2_evaluaciones = ".$evaluacion->id." ";
	}
	$sql .= "  ) ";
	
  $result = resultFromQuery($sql);
  
  $sesionDmasD->limpiarMensajes();
	
	while ($row = siguienteResult($result)) {
		$mensaje = new Mensaje;
		$mensaje->id = $row->idddv2_mensajes;
		$mensaje->fechaHora = $row->fechaHora;
		$mensaje->fechaHoraDeLeido = $row->fechaHoraDeLeido;
		$mensaje->descripcion = $row->texto;
		if ($row->idddv2_usuarios_destinatario <> null) {
			$mensaje->destinatario = new Usuario;
			$mensaje->destinatario->id = $row->idddv2_usuarios_destinatario;
		}

		// Obtenemos el usuario originante del mensaje
		$idUsuario = $row->idddv2_usuarios;
		if ($sesionDmasD->usuarios->conID($idUsuario) == null) {
			$usuario = new Usuario;
			loadUsuario($row->usuarioOrigen, $usuario);
			$sesionDmasD->usuarios->add($usuario);
		}
		$mensaje->usuario = &$sesionDmasD->usuarios->conID($idUsuario);
		
		if (isset($row->idddv2_itemsDeEvaluacion)) {
			if ((isset($evaluacion)) && ($row->idddv2_evaluaciones == $evaluacion->id)) {
				$mensaje->evaluacion = &$evaluacion;
			} else {
				$mensaje->evaluacion = evaluacionFromDB($row->idddv2_evaluaciones);
			}
			$mensaje->item = &$mensaje->evaluacion->items->conID($row->idddv2_itemsDeEvaluacion);
			if (isset($mensaje->destinatario)) {
				// tiene destinatario, si es el usuario logueado, lo agrego a las notificaciones de la sesion
				if ($mensaje->destinatario->id == $sesionDmasD->usuario->id) {
					$mensaje->destinatario = &$sesionDmasD->usuario;
					$sesionDmasD->notificaciones->add($mensaje);
				}
			} else {
				// no tiene destinatario, es un comentario del item
				$mensaje->item->comentarios->add($mensaje);
			}
		} else {
			$sesionDmasD->mensajes->add($mensaje);
		}
	}
		
}

function guardarNotificaciones(&$notificaciones) {
	for ($i = 0; $i < $notificaciones->count(); $i++) {
		guardarMensaje($notificaciones->at($i));
	}
}

function guardarMensaje(&$mensaje) {
	if ($mensaje->id == null) {
		// es nuevo
		$mensaje->id = proximoID('ddv2_mensajes', true);
		$sql = "INSERT INTO ddv2_mensajes (idddv2_mensajes, idddv2_usuarios, idddv2_itemsDeEvaluacion, fechaHora, texto, idddv2_usuarios_destinatario) ";
		$sql .= "values (";
		$sql .= $mensaje->id.", ";
		$sql .= $mensaje->usuario->id.", ";
		if ($mensaje->item == null) {
			$sql .= "null, ";
		} else {
			$sql .= $mensaje->item->id.", ";
		}
		$sql .= "'".$mensaje->fechaHora."', ";
		$sql .= "'".$mensaje->descripcion."', ";
		if ($mensaje->destinatario == null) {
			$sql .= "null ";
		} else {
			$sql .= $mensaje->destinatario->id." ";
		}
		$sql .= ") ";
	} else {
		// ya existia
		$sql = "update ddv2_mensajes ";
		$sql .= "  set fechaHora = now(), ";
		$sql .= "  texto = ".$mensaje->descripcion." ";
		$sql .= "where idddv2_mensajes = ".$mensaje->id;
	}
	$result = resultFromQuery($sql);
	
}

function guardarAccion(&$sesionDmasD, &$evaluacion, &$objetivo, &$accion) {
	//guardarItemDeEvaluacion(&$sesionDmasD, &$evaluacion, &$accion, &$objetivo, true);
}

function guardarItemDeEvaluacion(&$sesionDmasD, &$evaluacion, &$item, &$itemDeReferencia = null, $esAccion = false) {
	if ($item->id == -1) {
		// es un item nuevo

		
		// Insert del item
		$item->id = proximoID('ddv2_itemsdeevaluacion', true);

		$sql  = "insert into ddv2_itemsdeevaluacion (idddv2_itemsdeevaluacion, nombre, descripcion) ";
		$sql .= "values ( ";
		$sql .= $item->id.", ";
		$sql .= "'".$item->nombre."', ";
		$sql .= "'".$item->descripcion."' ";
		$sql .= ") ";
		
		$result = resultFromQuery($sql);
		
		if (!$esAccion) {
			
			// Insert de la ponderacion
			$idValoracion = proximoID('ddv2_valoraciondeitems', true);
			
			$sql  = "insert into ddv2_valoraciondeitems (idddv2_valoracionDeItems, idddv2_itemsDeEvaluacion, idddv2_tiposDeValoracion, fechaHora, valorNumerico, valorAsociado) ";
			$sql .= "values ( ";
			$sql .= $idValoracion.", ";
			$sql .= $item->id.", ";
			$sql .= "1, ";
			$sql .= "now(), ";
			$sql .= $item->ponderacion.", ";
			$sql .= "null ";
			$sql .= ") ";
			
			$result = resultFromQuery($sql);
		}

		// Insert del estado
		$idPonderacion = proximoID('ddv2_valoraciondeitems', true);
		
		$sql  = "insert into ddv2_valoraciondeitems (idddv2_valoracionDeItems, idddv2_itemsDeEvaluacion, idddv2_tiposDeValoracion, fechaHora, valorNumerico, valorAsociado) ";
		$sql .= "values ( ";
		$sql .= $idPonderacion.", ";
		$sql .= $item->id.", ";
		$sql .= "4, ";
		$sql .= "now(), ";
		$sql .= "null, ";
		$sql .= $item->estado->id." ";
		$sql .= ") ";
		
		$result = resultFromQuery($sql);


		// Insert de relacion con evaluacion
		$sql  = "insert into ddv2_relaciondeitemsyevaluaciones (idddv2_evaluaciones, idddv2_itemsDeEvaluacion) ";
		$sql .= "values ( ";
		$sql .= $evaluacion->id.", ";
		$sql .= $item->id." ";
		$sql .= ") ";
		
		$result = resultFromQuery($sql);
		
		// Insert de realacion con el item padre (si no viene, va el grupo)
		if ($itemDeReferencia == null) {
			$grupo = &$evaluacion->grupoDeItem($item);
			$itemDeReferencia = &$evaluacion->items->conNombre($grupo->nombre);
		}
				
		$sql  = "insert into ddv2_relacionentreitems (idddv2_itemsdeevaluacion_padre, idddv2_itemsdeevaluacion_hijo ) ";
		$sql .= "values ( ";
		$sql .= $itemDeReferencia->id.", ";
		$sql .= $item->id." ";
		$sql .= ") ";

		$result = resultFromQuery($sql);
				
		
	} else {
		// es un item existente
		
		// update del item
		$sql  = "update ddv2_itemsdeevaluacion ";
		$sql .= "  set descripcion = '".$item->descripcion."', ";
		$sql .= "      nombre = '".$item->nombre."' ";
		$sql .= "where idddv2_itemsdeevaluacion = ".$item->id;

		$result = resultFromQuery($sql);
		
		if (!$esAccion) {
			// Update de los nombres de las acciones de este item
			for ($nroAccion = 0; $nroAccion < $item->acciones->count(); $nroAccion++) {
				$accion = &$item->acciones->at($nroAccion);
				$sql  = "update ddv2_itemsdeevaluacion ";
				$sql .= "  set nombre = '".$item->nombre.":accion".($nroAccion+1)."' ";
				$sql .= "where idddv2_itemsDeEvaluacion = ".$accion->id;
		
				$result = resultFromQuery($sql);
			}
			
			// Update de la ponderacion
			$sql  = "update ddv2_valoraciondeitems ";
			$sql .= "  set valorNumerico = ".$item->ponderacion." ";
			$sql .= "where idddv2_itemsDeEvaluacion = ".$item->id;
			$sql .= "  and idddv2_tiposDeValoracion = 1";
	
			$result = resultFromQuery($sql);
		}

		// Update del estado
		$sql  = "update ddv2_valoraciondeitems ";
		$sql .= "  set valorAsociado = ".$item->estado->id." ";
		$sql .= "where idddv2_itemsDeEvaluacion = ".$item->id;
		$sql .= "  and idddv2_tiposDeValoracion = 4";

		$result = resultFromQuery($sql);

	}
	
	if (!$esAccion) {
		// Si no es una accion, entonces agregamos datos extra
		
		// Insert/update de datos extra
		for ($i = 0; $i < $item->datosExtra->count(); $i++) {
			$datoExtra = &$item->datosExtra->at($i);
			
			if ($datoExtra->id == -1) {
				// es un datoExtra nuevo
				$datoExtra->id = proximoID('ddv2_datosextradeitems', true);
				
				$sql  = "insert into ddv2_datosextradeitems (idddv2_datosextradeitems, idddv2_itemsDeEvaluacion, idddv2_tiposDeDato, nombre, descripcion, valor, textoLargo) ";
				$sql .= "values ( ";
				$sql .= $datoExtra->id.", ";
				$sql .= $item->id.", ";
				$sql .= $datoExtra->tipoDeDato.", ";
				$sql .= "'".$datoExtra->nombre."', ";
				$sql .= "'".$datoExtra->descripcion."', ";
				if ($datoExtra->tipoDeDato == 3) {
					// va en textoLargo
					$sql .= "null, '".$datoExtra->valor."' ";
				} else {
					// va en valor
					$sql .= "'".$datoExtra->valor."', null ";
				}
				$sql .= ") ";
				
				$result = resultFromQuery($sql);
				
			} else {
				// es un datoExtra existente
				$sql  = "update ddv2_datosextradeitems ";
				$sql .= "  set descripcion = '".$datoExtra->descripcion."', ";
				$sql .= "      nombre = '".$datoExtra->nombre."', ";
				if ($datoExtra->tipoDeDato == 3) {
					// va en textoLargo
					$sql .= "    textoLargo = '".$datoExtra->valor."', valor = null ";
				} else {
					// va en valor
					$sql .= "    valor = '".$datoExtra->valor."', textoLargo = null ";
				}
				$sql .= "where idddv2_datosextradeitems = ".$datoExtra->id;
				
				$result = resultFromQuery($sql);
			}
		}
	}

}

function borrarItemDeEvaluacion(&$sesionDmasD, &$evaluacion, &$item) {
	// Para borrarlo le ponemos fechaHoraDeBaja, esto hace que las consultas no lo vean
	
	$sql = "update ddv2_itemsdeevaluacion ";
	$sql .= "  set fechaHoraDeBaja = now() ";
	$sql .= "where idddv2_itemsdeevaluacion = ".$item->id;

	$result = resultFromQuery($sql);
	
	for ($i = 0; $i < $item->acciones->count(); $i++) {
		$accion = &$item->acciones->at($i);
		$sql = "update ddv2_itemsdeevaluacion ";
		$sql .= "  set fechaHoraDeBaja = now() ";
		$sql .= "where idddv2_itemsdeevaluacion = ".$accion->id;
	
		$result = resultFromQuery($sql);
	}
	
	$evaluacion->removeItem($item);

}

function tieneColaboradores(&$empleado) {
	$sql = "select count(1) as cantidad
					from ddv2_evaluaciones
					where evaluador = ".$empleado->id;
	$result = resultFromQuery($sql);
	$row = siguienteResult($result);
	
	if ($row != false) {
		return $row->cantidad > 0;
	}
}

function tieneRevisiones(&$empleado) {
	$sql = "select count(1) as cantidad
					from ddv2_evaluaciones
					where reviewer = ".$empleado->id;
	$result = resultFromQuery($sql);
	$row = siguienteResult($result);
	
	if ($row != false) {
		return $row->cantidad > 0;
	}
}
