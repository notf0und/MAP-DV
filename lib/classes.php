<?php
error_reporting(E_ALL);

class ItemGenerico {
	var $id;
	var $nombre;
	var $descripcion;
}

class ListaGenerica extends ItemGenerico {
  var $lista;
  
  function initializeLista() {
    $this->lista = Array();
  }
  
  function initialize() {
    $this->initializeLista();
  }
  
  function add(&$item, $repeat = true) {
		if (!$repeat) {
			if (!$this->includes($item)) {
		    $this->lista[] = $item;
			}
		} else {
	    $this->lista[] = $item;
		}
		
		return $this->count();

  }
  
  function remove(&$item) {
		for ($i = 0; $i < $this->count(); $i++) {
			if ($this->at($i) === $item) {
				$this->removeAt($i);
			}
		}
  }
  
  function removeAt($index) {
 	  $lista = Array();
 	  for ($i = 0; $i < $this->count(); $i++) {
 	  	  if ($i != $index) {
  	  	  $lista[] = $this->at($i);
 	  	  }
 	  }
 	  $this->lista = $lista;
  }
  
  function first() {
    return $this->lista[0];
  }
  
  function last() {
    return $this->lista[$this->count()-1];
  }
  
  function asArray() {
    $result = Array();
    for ($i = 0; $i < sizeOf($this->lista); $i++) {
      $result[] = $this->lista[$i]->descripcion;
    }
    return $result;
  }
  
  function asArrayComplejo() {
    $result = Array();
    $result["id"] = Array();
    for ($i = 0; $i < sizeOf($this->lista); $i++) {
      $item = $this->lista[$i];
      $result["id"][] = $item->id;
      $result[$item->id] = $item->descripcion;
    }
    return $result;
  }
  
  function count() {
    return sizeOf($this->lista);
  }
  
  function at($index) {
    return $this->lista[$index];
  }
  
  function conNombre($nombre) {
  	  for ($i = 0; $i < $this->count(); $i++) {
  	  	  $item = $this->at($i);
  	  	  if ($item->nombre == $nombre) {
  	  	  	  return $item;
  	  	  }
  	  }
  	  return null;
  }
  
  function conID($ID) {
  	  for ($i = 0; $i < $this->count(); $i++) {
  	  	  $item = $this->at($i);
  	  	  if ($item->id == $ID) {
  	  	  	  return $item;
  	  	  }
  	  }
  	  return null;
  }
  
  function includes(&$unItem) {
    // Devuelve true si unItem es parte de los items del receptor
    for ($i = 0; $i < $this->count(); $i++) {
      if ($this->lista[$i] === $unItem) {
        return true;
      }
    }
    return false;
  }
  
  function nuevo() {
  	  $item = new ItemGenerico;
  	  $this->add($item);
  	  return $item;
  }

	function copyAllFrom(&$lista, $repeat = true) {
    for ($i = 0; $i < $lista->count(); $i++) {
			$item = $lista->at($i);
			$this->add($item, $repeat);
    }
	}	

	function addAllFrom(&$lista, $repeat = true) {
    for ($i = 0; $i < $lista->count(); $i++) {
			$item = &$lista->at($i);
			$this->add($item, $repeat);
    }
	}	

	function copy($referenced = true) {
		$lista = new ListaGenerica;
		$lista->initialize();
		if ($referenced) {
			$lista->addAllFrom($this);
		} else {
			$lista->copyAllFrom($this);
		}
	}
	
	function isEmpty() {
		return $this->count() == 0;
	}
  
  function asHTMLString() {
    $result = $this->nombre." (".$this->count().") <BR>";
    for ($i = 0; $i < sizeOf($this->lista); $i++) {
    	$result .= "id: ".$this->lista[$i]->id."	| nombre: ".$this->lista[$i]->nombre."	| descripcion: ".$this->lista[$i]->descripcion."<BR>";
    }
    return $result;
  }
  
  function asSQLIDString() {
  	$result = '';
  	for ($i = 0; $i < $this->count(); $i++) {
  		$item = &$this->at($i);
  		$result .= $item->id;
  		if ($i < $this->count()-1) {
  			$result .= ', ';
  		}
  	}
  	return $result;
  }
  
}

class Usuario extends ItemGenerico {
  var $clave;
  var $eMail;
  var $permisos;
	var $empleado;
  
  function initializePermisos() {
		$this->permisos = new ListaGenerica;
		$this->permisos->initialize();
  }
	
  function initializeEmpleado() {
		$this->empleado = new Empleado;
		$this->empleado->initialize();
  }
	
	function initialize() {
    $this->id = 0;
    $this->nombre = '';
    $this->clave = '';
    $this->descripcion = '';
    $this->initializePermisos();
    $this->initializeEmpleado();
  }
  
  function nombreCompleto() {
  	if (isset($this->empleado->id)) {
  		return $this->empleado->nombreCompleto();
  	} else {
  		return $this->nombre;
  	}
  }
}

class ItemDeEvaluacion extends ItemGenerico {
	var $ponderacion;
	var $evaluacion;
	var $autoevaluacion;
	var $estado;
	var $subitems;
	var $acciones;
	var $datosExtra;
	var $comentarios;
	
	function initialize() {
		$this->acciones = new ListaGenerica;
		$this->acciones->initialize();
		$this->subitems = new ListaGenerica;
		$this->subitems->initialize();
		$this->datosExtra = new ListaGenerica;
		$this->datosExtra->initialize();
		$this->comentarios = new ListaGenerica;
		$this->comentarios->initialize();
		
	}
	
	function estadoDeCompletitudDeAcciones() {
		$cantidadDeAcciones = $this->acciones->count();
		$cantidadDeAccionesTerminadas = 0;
		for ($nroAccion = 0; $nroAccion < $cantidadDeAcciones; $nroAccion++) {
			$accion = &$this->acciones->at($nroAccion);
			if ($accion->estado->nombre == 'terminada') {
				$cantidadDeAccionesTerminadas++;
			}
		}

		if ($cantidadDeAcciones == $cantidadDeAccionesTerminadas) {
			return 2;
		} elseif ((0 < $cantidadDeAccionesTerminadas) && ($cantidadDeAccionesTerminadas < $cantidadDeAcciones)) {
			return 1;
		} else {
			return 0;
		}

	}
	
	function renombrarAcciones() {
		for ($nroAccion = 0; $nroAccion < $this->acciones->count(); $nroAccion++) {
			$accion = &$this->acciones->at($nroAccion);
			$accion->nombre = $this->nombre.':accion'.($nroAccion+1);
		}
	}
	
	function nombreParaProximaAccion() {
		return $this->nombre.':accion'.($this->acciones->count()+1);
	}
	
	function addAccion(&$accion) {
		$accion->nombre = $this->nombreParaProximaAccion();
		$this->acciones->add($accion);
	}
	
	function initializeDatosExtra($tipoDeObjetivo) {
		$datoExtra = new DatoExtraDeItem;
		$datoExtra->id = -1;
		$datoExtra->nombre = 'fechaPropuesta';
		$datoExtra->tipoDeDato = 4;
		$datoExtra->descripcion = 'Fecha propuesta';
		$datoExtra->valor = date('Y-m-d');
		$this->datosExtra->add($datoExtra);
		if ($tipoDeObjetivo == 'objetivoCuantitativo') {
			// Datos extra para objetivosCuantitativos
			$datoExtra = new DatoExtraDeItem;
			$datoExtra->id = -1;
			$datoExtra->nombre = 'objetivo';
			$datoExtra->tipoDeDato = 3;
			$datoExtra->descripcion = 'Objetivo';
			$this->datosExtra->add($datoExtra);
		}
		$datoExtra = new DatoExtraDeItem;
		$datoExtra->id = -1;
		$datoExtra->nombre = 'indicadores';
		$datoExtra->tipoDeDato = 3;
		$datoExtra->descripcion = 'Indicadores de desempeño';
		$this->datosExtra->add($datoExtra);
	}
}

class DatoExtraDeItem extends ItemGenerico {
	var $tipoDeDato;
	var $valor;
	
	function asHTML() {
		return $valor;
	}
}

class PeriodoDeEvaluacion extends ItemGenerico {
	var $grupoDeEmpleados;
	var $fechaDeInicio;
	var $fechaDeFin;
	var $evaluacionPrototipo;
	
	function initialize() {
		$this->grupoDeEmpleados = new ListaGenerica;
		$this->grupoDeEmpleados->initialize();
	}
	
	function cualitativosOptionMWSList($nombreActual = '') {
		$texto = '';
		$grupo = &$this->evaluacionPrototipo->gruposDeItems->conNombre('objetivosCualitativos');
		
		for ($i = 0; $i < $grupo->count(); $i++) {
			$item = &$grupo->at($i);
			$texto .= '<option value="'.$item->nombre.'"';

			if ($item->nombre == $nombreActual) {
				$texto .= ' selected ';
			}

			$texto .= '>'.$item->descripcion.'</option>';
		}
		return $texto;
	}
	
	function datosExtraDeCualitativos() {
		$grupo = &$this->evaluacionPrototipo->gruposDeItems->conNombre('objetivosCualitativos');
		$texto = 'var thetext1=new Array();';
		$texto .= "thetext1[0]='<font color=red>Debe seleccionar una competencia.</font>';";
		for ($i = 0; $i < $grupo->count(); $i++) {
			$item = &$grupo->at($i);
			$texto .= "thetext1[".($i+1)."]='";
			for ($d = 0; $d < $item->datosExtra->count(); $d++) {
				$datoExtra = &$item->datosExtra->at($d);
				$texto .= $datoExtra->descripcion.": ".$datoExtra->valor;
			}
			$texto .= "<br><br>';";
		}
/*
		$texto .= "thetext1[1]=' Definición: Desarrolla talentos individuales al brindar retroalimentación actual y crear planes de desarrollo individual para permitir que las personas alcancen su potencial entero<br><br> Comportamientos anexos: <br><li>Identifica y selecciona a la(s) persona(s) más calificada(s) para su(s) puesto(s)</li><li>Participa activamente para crear un desarrollo motivador y realista y planes de carrera para el personal</li><li>Fomenta el que la gente trabaje fuera de su zona de confort y crea las condiciones correctas para que demuestren nuevas habilidades y comportamientos</li><li>Brinda de manera abierta retroalimentación actual constructiva y balanceada respecto al desempeño</li><li>Funge como guía o mentor de confianza</li><br><br> Comportamientos anexos negativos (ejemplos): <br><li>Asigna puestos o roles con base en amistad o para devolver favores, sin determinar a la(s) persona(s) más calificada(s) para dicho puesto</li><li>No toma información sobre los procesos y herramientas de manejo de talentos de Pernod Ricard o ignora los procesos formales en marcha</li><li>No logra hacer del desarrollo una prioridad; no invierte tiempo en ayudar a crear planes de carrera para el personal</li><li>Permanece cómodo con el status quo en lugar de fomentar el que los otros trabajen fuera de su zona de confort</li><li>Incita a otros a tomar riesgos significativos sin ayudarlos a realizar su asesoría necesaria para planear el apoyo necesario</li><li>No le brinda a los equipos/individuos oportunidades, apoyo y/o recursos amplios</li><li>Retiene o no logra brindar retroalimentación actual del desempeño</li><li>Enfoca la retroalimentación del desempeño ya sea en lo negativo o lo positivo sin ofrecer una perspectiva balanceada</li><li>No actúa sobre la retroalimentación proporcionada por otros</li><br><br> Recoomendaciones: <br><li>Métricas relacionadas que deben considerarse:<li>Tiempo en el trabajo actual</li><li># de reportes directos que han expresado preocupaciones sobre las revisiones de desempeño (si el individuo es Gerente)</li><li># de reportes directos que reciben capacitación (si el individuo es Gerente)</li><li>Porcentaje de terminación de revisiones anuales de desempeño y desarrollo (si el individuo es Gerente)</li><li># de reportes directos con planes de desarrollo (si el individuo es Gerente)</li><li>Retroalimentación de clientes internos o externos</li></li><li>Es ambicioso al tomar su experiencia a otro nivel elevando los estándares. Si ya es considerado como el mejor en el equipo o departamento, busca ser el mejor en el negocio. Si ya es considerado el mejor en el negocio, buscar ser el mejor en el Grupo</li><li>Solicita reuniones con el Gerente</li><li>Lleva a cabo al menos una reunión formal por año con los reportes directos para hablar tranquilamente sobre las expectativas y logros respecto al desempeño y desarrollo</li><li>Le permite a los miembros del equipo que cometan errores y revisa la situación para ayudarlos a concentrarse en hacer lo correcto en la siguiente ocasión</li><li>Con base en la evaluación de las fortalezas y necesidades de desarrollo de los miembros del equipo, le ayuda a anticipar los retos que cada uno podría enfrentar en sus tareas y debate con ellos el posible camino a seguir</li><li>Brinda una evaluación oportuna y balanceada; recuerda enfatizar primero las fortalezas al discutir las necesidades de desarrollo y determina el mejor momento para darlo</li><li>Utiliza múltiples recursos de retroalimentación antes de dar conclusiones sobre el desempeño de los miembros del equipo</li><li>Incita a los miembros del equipo a ser propietarios de su propio desarrollo. Los dirige hacia los recursos de aprendizaje puestos a disposición por la compañía y da un seguimiento periódico para evaluar su progreso</li><br><br>';";
		$texto .= "thetext1[2]=' Definición: Crea y dirige equipos de alto desempeño al fomentar la colaboración y asegurar el cumplimiento de la visión compartida<br><br> Comportamientos anexos: <br><li>Inspira a los miembros del equipo al comunicar la visión al mismo</li><li>Crea una cultura de rendimiento de cuentas y propósito compartido entre los miembros del equipo</li><li>Adapta el estilo de dirección a diferentes situaciones y brinda calma y coherencia al equipo durante situaciones de estrés</li><li>Empodera a los miembros del equipo para tomar decisiones brindándoles orientación y apoyo cuando lo requieren</li><li>Evalúa la dinámica y desempeño del equipo, motiva comportamientos ejemplares</li><li>Entiende las fortalezas y debilidades de los miembros del equipo para facilitar el desempeño del mismo</li><li>Fomenta el trabajo en equipo y colaboración al promover la apertura y el diálogo</li><br><br> Comportamientos anexos negativos (ejemplos): <br><li>No logra comunicar un regla o propósito general del equipo para motivarlo</li><li>Evita tratar asuntos de desempeño del equipo</li><li>No comparte el poder</li><li>No logar empoderar a los miembros del equipo para tomar decisiones</li><li>Favorece a ciertos miembros del equipo en lugar de tomar decisiones dentro del contexto más amplio del equipo completo</li><li>No logra fomentar o esperar trabajo en equipo y colaboración</li><li>No recompensa la colaboración</li><li>Toma crédito por el éxito y culpa al equipo por las fallas</li><br><br> Recoomendaciones: <br><li>Métricas relacionadas que deben considerarse:<li>Terminación de los objetivos del equipo</li><li>Retroalimentación de toda encuesta de compromiso</li><li>Retroalimentación de clientes internos o externos</li></li><li>Muestra entusiasmo por la visión del equipo al comunicar lo importante que son las metas del equipo y vincula los esfuerzos de la gente con los objetivos generales</li><li>Identifica los comportamientos que considera que son críticos para el éxito del equipo y después dirige con el ejemplo</li><li>Establece una “identidad del equipo” y trabaja creando orgullo</li><li>Organiza eventos que fomentan el equipo para permitir que la gente se conozca en varios escenarios</li><li>Capitaliza oportunidades para comunicar con frecuencia las prioridades y responsabilidades al equipo</li><li>Se toma el tiempo para orientar a nuevos miembros del equipo en su entorno. Explica claramente las relaciones del equipo, qué esperar y quién es responsable por ello</li><li>Una vez que todos entienden la dirección y los roles del equipo, se enfoca en empoderar a los individuos dándoles autonomía en la toma de decisiones y respaldando las decisiones que toman</li><li>Fomenta la cooperación en lugar de la competencia entre diferentes unidades, marcas o regiones</li><li>Toma acciones visibles cuando un miembro del equipo muestra comportamientos individualistas</li><li>Se toma el tiempo para entender los “puntos de vista” de otros, aún cuando pueden diferir por completo</li><br><br>';";
		$texto .= "thetext1[3]=' Definición: Toma iniciativas, pasos audaces y riesgos calculados de forma proactiva para desarrollar el negocio al mismo tiempo que asume responsabilidad para las decisiones<br><br> Comportamientos anexos:<br><li>Continuamente genera nuevas ideas, métodos, productos y servicios</li><li>Demuestra energía y pasión al abordar asuntos desafiantes de negocios</li><li>Innova rápidamente para dar resultados cuando surge nueva información y cambia prioridades de forma rápida cuando es necesario</li><li>Reta al “status quo” al pensar fuera del cuadro y toma riesgos con bases</li><li>Muestra la capacidad de mantenerse en situaciones complejas</li><li>Mueve y convence a otros para hacer que las cosas sucedan</li><br><br> Comportamientos anexos negativos (ejemplos):<br><li>Rechaza ideas innovadoras presentadas por otros (por ejemplo, muestra falta de respeto hacia otras opiniones o puntos de vista)</li><li>Al tomar en cuenta opciones, con frecuencia se basa en métodos establecidos en lugar de explorar nuevos enfoques</li><li>No logra reconocer el valor de la innovación (por ejemplo, se enfoca en por qué la ideas “no funcionarán” o “tomarán demasiado tiempo”) </li><li>Rechaza las iniciativas creativas de otros (por ejemplo, no motiva a los equipos, no fomenta nuevos enfoques)</li><li>No actúa en situaciones complejas; participa mayormente en actividades que son ya familiares y que garantizan ser exitosas</li><li>Se enfoca en evitar errores en lugar de tomar riesgos informados</li><li>No logra tomar decisiones oportunas (por ejemplo, no actuará hasta que la información completa esté disponible, permite que situaciones ambiguas continúen)</li><li>Culpa a los demás cuando se enfrenta al fracaso</li><br><br> Recoomendaciones: <br><li>Métricas relacionadas que deben considerarse:	<li>Contribución a un Nuevo Producto/Desarrollo de Negocio o Campaña de Mercadotecnia</li>	<li>Compromiso de otros para apoyar una nueva idea o causa</li>	<li>Retroalimentación de clientes internos o externos</li></li><li>Inicia 2 o 3 sesiones dedicadas a crear una lluvia de ideas respecto a un asunto o pregunta en particular cuando surge una oportunidad estratégica </li><li>Durante el proceso creativo, suspende las declaraciones críticas que dicen “no funcionará” y por el contrario piensa en términos positivos como “puede funcionar porque…”</li><li>Genera tantas ideas como sea posible durante una sesión de lluvia de ideas; si no hay más ideas, toma un descanso y regresa después para redefinir el problema y verlo desde una perspectiva diferente</li><li>Recompensa a los empleados por sus buenas ideas al agradecerles y decirles a los demás sobre sus buenas ideas</li><li>Pide retroalimentación a colegas en los que confía sobre situaciones en las que hay tendencia a tener demasiadas opiniones o ser muy inflexible</li><li>Con frecuencia confronta nuevas ideas y tendencias; crea curiosidad intelectual al leer los periódicos y publicaciones para enterarse sobre eventos actuales o nuevos desarrollos de negocio</li><li>Ayuda a poner en marcha ideas y cuando se cometen errores se enfoca en la organización y aprende de ellos</li><li>Evalúa sus propias reacciones ante cambios pasados para evaluar lo que ocasionó la resistencia y cómo lo superó. Se reúne con otros para tratar miedos y preocupaciones</li><br><br>';";
		$texto .= "thetext1[4]=' Definición: Brinda resultados y empodera a los demás al establecer objetivos claros, proporcionar los recursos y la retroalimentación apropiada y asegurar enfocarse en alcanzar los resultados<br><br> Comportamientos anexos<br><li>Logra sus objetivos individuales en su propio alcance de trabajo al aplicar estándares profesionales de excelencia</li><li>Toma en cuenta las mejores prácticas y experiencias pasadas para lograr un trabajo de alta calidad</li><li>Muestra un sentido de urgencia para cumplir metas y toma acciones correctivas para asegurar resultados</li><li>Asigna tareas y responsabilidades para resultados de trabajo a los individuos más adecuados, cuando es necesario</li><li>Mantiene la calma y altos estándares de desempeño en ambientes desafiantes</li><li>Mide y da seguimiento a los resultados y procesos clave del negocio para evaluar el desempeño e identificar mejoras</li><br><br> Comportamientos anexos negativos (ejemplos): <br><li>No logra entender los puntos clave de su propio desempeño en el trabajo actual</li><li>Considera la dirección del desempeño como una actividad que se realiza una vez por año</li><li>No demuestra una actitud de servicio al cliente cuando interactúa con otros</li><li>Evita tratar aspectos de desempeño</li><li>Se basa en enfoques actuales u obvios en lugar de considerar las mejores prácticas o aprender de experiencias pasadas</li><li>Asigna tareas y responsabilidades sin considerar a quién sería más conveniente asignarlas</li><li>Muestra un sentido de estrés, pánico o frustración en momentos de desafío y puede perder en enfoque en los objetivos</li><br><br> Recoomendaciones: <br><li>Métricas relacionadas que deben considerarse:<li>Clasificación general</li><li>Contribución a los resultados del Grupo</li><li>Porcentaje de proyectos completados a tiempo y dentro del presupuesto</li><li>Porcentaje de revisión de desempeño y desarrollo anual alcanzado</li><li>Porcentaje de terminación de plan anual de capacitación</li><li>Retroalimentación de clientes internos o externos</li></li><li>Solicita retroalimentación frecuente del Gerente con relación a las expectativas de desempeño y brechas por cubrir</li><li>Es persistente cuando confronta contratiempos. Consulta a otros para obtener nuevos insights para abordar asuntos</li><li>Para maximizar el desarrollo personal, busca tareas demandantes que le permitirán sacar provecho de sus fortalezas y mejorar sus debilidades</li><li>Busca tareas en las que sea posible aprender de una persona con más experiencia o un experto reconocido para obtener conocimiento y experiencia específica</li><li>Controla el comportamiento al abstenerse de expresar pánico, llanto o mostrar temor. Mantiene la comunicación racional y objetiva y brinda dirección a otros si es necesario</li><li>Revisa las medidas de desempeño en marcha para el equipo. Considera los cambios que se han puesto en marcha en el negocio e identifica lo que debe revisarse, eliminarse o crearse.</li><br><br>';";
		$texto .= "thetext1[5]=' Definición: Define la visión del futuro al identificar oportunidades para crear valor o mejor en la dirección a largo plazo y comparte la visión de forma convincente para inspirar un cambio.<br><br> Comportamientos anexos:<br><li> Anticipa cambios en el ambiente interno o externo para crear una visión del futuro para el negocio</li><li> Crea y mantiene relaciones estratégicas (intra-Grupo, clientes, gobierno, socios, grupos de industrias, etc.)</li><li> Demuestra una comprensión de las conexiones entre las áreas del negocio e incorpora esta perspectiva en las decisiones</li><li> Evalúa el nivel de cambio requerido para alcanzar la visión y desarrolla planes para apoyar la transición</li><li> Traduce la visión de la organización en objetivos claros, específicos y alcanzables</li><li> Comunica las necesidades y beneficios de cambio del negocio para fomentar el compromiso</li><li> Identifica criterios para evaluar la alineación estratégica de los planes con base en los factores de éxito para el negocio que son susceptibles de ser medidos</li><br><br> Comportamientos anexos negativos (ejemplos)<br><li>Muestra poco entusiasmo por la visión de Pernod Ricard y no logra promover activamente la visión y objetivo de Pernod Ricard a los empleados (por ejemplo, no organiza reuniones para transmitir en cascada la información clave, se comunica sin convicción en eventos) </li><li>No traduce la visión de Pernod Ricard en un plan estratégico para el área de responsabilidad asignada</li><li>Con frecuencia da respuestas inadecuadas a las preguntas de los empleados con respecto a la visión y dirección de la organización y las conexiones entre las áreas del negocio (por ejemplo, no se identifica con las decisiones de las altas direcciones e incluso expresa puntos de vista en foros públicos que contradicen a aquéllos del gerente senior, no aclara los roles dentro de la organización o la importancia de cada empleado)</li><li>No comunica objetivos específicos para apoyar la ejecución de la estrategia y no muestra interés al medir el progreso en el camino para motivar y/o adaptar</li><br><br> Recoomendaciones: <br><li>Métricas relacionadas que deben considerarse:<li>Contribución a la rentabilidad del grupo</li><li>Participación en Planes de Negocio y presentación de los mismos</li><li>Visión del futuro claramente definida para el Departamento/Negocio/Área</li><li>Retroalimentación de clientes internos o externos</li></li><li>Comunica claramente los planes estratégicos y objetivos de soporte de la Región/Marca/Filial y explica cómo contribuye al plan estratégico de Pernod Ricard durante reuniones formales e informales</li><li>Proporciona a los empleados una copia de la visión y metas estratégicas de Pernod Ricard y se asegura de que su rol para alcanzarlas esté claramente explicado, especialmente en la Revisiones Anuales</li><li>Cuando se debaten las iniciativas o prioridades con los empleados, menciona cómo éstas están relacionadas con la misión, visión y valores de Pernod Ricard</li><li>Mantiene a los empleados informados sobre el progreso de la Región/Marca/Filial para alcanzar su misión y visión; incluye datos concretos (por ejemplo, cifras en euros, números de producción y ventas) como soporte a sus comentarios</li><li>Involucra a los miembros del equipo durante tiempos de cambio y los guía, aún más, cuando la decisión del cambio no es una decisión compartida</li><li>Crea iniciativas de cambio para que otros quieran ser parte del mismo. Les permite a los demás participar y les da la autoridad y control para tomar decisiones. Comparte el poder</li><li>Contribuye con pequeños triunfos al contar historias con frecuencia sobre el progreso hacia el progreso y visión de la organización</li><li>Comparte los estatutos de Pernod Ricard dentro de la Región/Marca/Filial y brinda ejemplos específicos para ilustrar sus diferentes componentes a los empleados</li><li>Dedica una parte significativa del tiempo de una reunión en componentes clave de la Región/Marca/Filial para obtener retroalimentación sobre nuevos productos/servicios, mejora de calidad, etc.</li><li>Identifica oportunidades estratégicas en el mercado y permite a un equipo de Talentos hacer cualquier recomendación necesaria, etc.</li><li>Fomenta que los empleados identifiquen las mejores y próximas prácticas para Pernod Ricard o la Región/Marca/Filial</li><br><br>';";    
		$texto .= "thetext1[6]=' Definición: Representa y comunica de manera entusiasta los valores clave de Pernod Ricard, apegándose a la ética y el fuerte compromiso con las iniciativas de CSR<br><br> Comportamientos anexos:<br><li>Mantiene altos estándares profesionales que están alineados con los valores, ética y los estatutos de la organización</li><li>Funge como rol modelo y traduce los valores en comportamientos comprensibles para otros</li><li>Promueve la ética al confrontar y tratar comportamientos inapropiados y no éticos</li><li>Demuestra un compromiso con las prioridades de Responsabilidad Social Corporativa (CSR) al fomentar iniciativas de Grupo y locales</li><li>Establece un ambiente mutuo de confianza al comunicarse de forma honesta, directa y transparente con colegas de todos los niveles</li><li>Celebra el éxito y reconoce la contribución de otros</li><br><br> Comportamientos anexos negativos (ejemplos): <br><li>Aplica estándares profesionales cuestionables que pueden no estar en línea con los valores, ética y estatutos de la organización</li><li>Ignora o no logra lidiar con los comportamientos no éticos de otros</li><li>No logra demostrar compromiso con las prioridades de Responsabilidad Social Corporativa (CSR)</li><li>Retiene información; comunica solo información parcial y evita la publicación completa</li><li>Conserva todos los créditos para sí mismo en lugar de reconocer las contribuciones del equipo</li><br><br> Recoomendaciones: <br><li>Métricas relacionadas que deben considerarse al evaluar esta competencia(si aplica):<li>Participación en las iniciativas de Responsabilidad Social Corporativa (CSR)</li><li>Retroalimentación de clientes internos o externos</li></li><li>Mantiene sus promesas después de hacerlas, sin importar qué tan significativas son</li><li>Lee los estatutos de Pernod Ricard y hace suyos sus valores y ética. Se las explica a otros durante reuniones formales e informales</li><li>Cuando se comete un error, corrige el error tan pronto como sea posible. Reporta el error para determinar si éste ha tenido un impacto en otros y dichas personas requieren ayuda para lidiar con las consecuencias</li><li>Se comunica directamente con alguien si dicha persona está haciendo algo que se considera inadecuado o no ético. Explica que se tomarán acciones si no detiene o rectifica la situación</li><li>Evita toda actividad éticamente cuestionable. Si no está seguro, busca asistencia de otros</li><li>Solicita al departamento de RH casos de estudio sobre toma de decisiones éticas y pide que sean presentados al equipo</li><li>Se fija como meta el reconocer y recompensar a alguien durante la próxima reunión de equipo</li><li>Participa en las iniciativas de CSR y propone nuevas iniciativas</li><br><br>';";
*/		
		return $texto;
	}
	
}

class EvaluacionDeEmpleado extends ItemGenerico {
	var $evaluado;
	var $evaluador;
	var $reviewer;
	var $fechaDeCreacion;
	var $cantidadDeObjetivosCuantitativosRequeridos;
	var $cantidadDeObjetivosCualitativosRequeridos;
	var $items;
	var $gruposDeItems;
	
	function initialize() {
		$this->nombre = 'Evaluacion nueva';
		$this->items = new ListaGenerica;
		$this->items->initialize();
		$this->gruposDeItems = new ListaGenerica;
		$this->gruposDeItems->initialize();
	}
	
	function asString() {
		$texto = "evaluacionDeEmpleado id[".$this->id."] de: ".$this->evaluado->nombreCompleto()." evaluador: ".$this->evaluador->nombreCompleto()." reviewer: ".$this->reviewer->nombreCompleto()."<BR>
		Objetivos requeridos:<BR>
		Cuantitativos : ".$this->cantidadDeObjetivosCuantitativosRequeridos." / 
		Cualitativos  : ".$this->cantidadDeObjetivosCualitativosRequeridos."<BR><BR>";
 	  for ($i = 0; $i < $this->gruposDeItems->count(); $i++) {
 	  	$grupo = $this->gruposDeItems->at($i);
			$texto .= $grupo->asHTMLString()."<BR>";
 	  }
 	  return $texto;
	}
	
	function addGrupo(&$grupo) {
		$this->gruposDeItems->add($grupo);
	}

	function addItem(&$item, &$grupo = null) {
		$this->items->add($item);
		if (isset($grupo)) {
			if ($grupo->nombre <> $item->nombre) {
				$grupo->add($item);
			}
		}
	}
	
	function removeItem(&$item) {
		$grupo = &$this->grupoDeItem($item);
		if (isset($grupo)) {
			$grupo->remove($item);
		}
		$this->items->remove($item);
	}
	
	function objetivoCuantitativoNumero($numeroDeOrden) {
		return $this->gruposDeItems->conNombre('objetivosCuantitativos')->at($numeroDeOrden);
	}

	function objetivoCualitativoNumero($numeroDeOrden) {
		return $this->gruposDeItems->conNombre('objetivosCualitativos')->at($numeroDeOrden);
	}
	
	function itemVistoPorEvaluador(&$item) {
		return ($item->estado->nombre == 'aprobadoPorEvaluador') || ($item->estado->nombre == 'aprobadoPorReviewer') || ($item->estado->nombre == 'rechazadoPorReviewer');
	}
	
	function itemVistoPorReviewer(&$item) {
		return ($item->estado->nombre == 'aprobadoPorReviewer');
	}
	
	function itemModificable(&$item) {
		return $item->estado->nombre == 'abiertoParaModificacion';
	}
	
	function objetivoCuantitativoNuevo(&$sesionDmasD) {
		$objetivo = new ItemDeEvaluacion;
		$objetivo->initialize();
		$objetivo->initializeDatosExtra('objetivoCuantitativo');
		$objetivo->estado = &$sesionDmasD->estadosDeItems->conNombre('abiertoParaModificacion');
		$objetivo->id = -1;
		$grupo = &$this->gruposDeItems->conNombre('objetivosCuantitativos');
		$objetivo->nombre = 'objetivoCuantitativo'.($grupo->count()+1);
		$this->addItem($objetivo, $grupo);
		return $objetivo;
	}

	function objetivoCualitativoNuevo(&$sesionDmasD) {
		$objetivo = new ItemDeEvaluacion;
		$objetivo->initialize();
		$objetivo->initializeDatosExtra('objetivoCualitativo');
		$objetivo->estado = $sesionDmasD->estadosDeItems->conNombre('abiertoParaModificacion');
		$objetivo->id = -1;
		$grupo = &$this->gruposDeItems->conNombre('objetivosCualitativos');
		$objetivo->nombre = 'objetivoCualitativo0';
		$this->addItem($objetivo, $grupo);
		/* Se crean las 3 acciones correspondientes a experienciaEnElPuesto, coach, cursosYLecturas */

		$experiencia = new ItemDeEvaluacion;
		$experiencia->id = -1;
		$objetivo->addAccion($experiencia);
		$experiencia->estado = $sesionDmasD->estadosDeItems->conID(1);
//		guardarAccion($sesionDmasD, $this, $objetivo, $experiencia);
		
		$coach = new ItemDeEvaluacion;
		$coach->id = -1;
		$objetivo->addAccion($coach);
		$coach->estado = $sesionDmasD->estadosDeItems->conID(1);
//		guardarAccion($sesionDmasD, $this, $objetivo, $coach);
		
		$cursos = new ItemDeEvaluacion;
		$cursos->id = -1;
		$objetivo->addAccion($cursos);
		$cursos->estado = $sesionDmasD->estadosDeItems->conID(1);
//		guardarAccion($sesionDmasD, $this, $objetivo, $cursos);
		
		return $objetivo;
	}

	function ponderacionDisponiblePara(&$item) {
		$ponderacionRestante = 100;
		$grupo = &$this->grupoDeItem($item);
		for ($nroItem = 0; $nroItem < $grupo->count(); $nroItem++) {
			$otroItem = &$grupo->at($nroItem);
			$ponderacionRestante = $ponderacionRestante - $otroItem->ponderacion;
		}
		if (isset($item->ponderacion)) {
			$ponderacionRestante = $ponderacionRestante + $item->ponderacion;
		}
		return $ponderacionRestante;
	}

	function grupoDeItem(&$item) {
		for ($nroGrupo = 0; $nroGrupo < $this->gruposDeItems->count(); $nroGrupo++) {
			$grupo = &$this->gruposDeItems->at($nroGrupo);
			if ($grupo->includes($item)) {
				return $grupo;
			}
		}
	}

}

class EstadoDeItem extends ItemGenerico {
	var $tipoDeDato;
}

class Empleado extends ItemGenerico {
	var $numeroDeLegajo;
	var $fechaDeIngreso;
	var $urlDeFoto;
	var $nombres;
	var $apellidos;
	var $direccion;
	var $telefonoFijo;
	var $telefonoMovil;
	var $planta;
	var $area;
	var $posicion;
	var $plandecarrera;
	var $nombreDeConvenio;
	var $email;
	var $esEvaluador;
	var $esReviewer;
	var $grupos;
	
	function nombreCompleto() {
		return $this->apellidos.', '.$this->nombres;
	}
	
	function initialize() {
		$this->grupos = new ListaGenerica;
	}
	
}

class Mensaje extends ItemGenerico {
	var $fechaHora;
	var $fechaHoraDeLeido;
	var $usuario;
	var $item;
	var $evaluacion;
	var $destinatario;

	function textoCorto() {
		$texto = substr($this->descripcion,1,40);
		return $texto;
	}
	
	function leido() {
		return !is_null($this->fechaHoraDeLeido);
	}
}

class SesionDmasD {
	var $usuarios;
	var $usuario;
	var $usuarioDelSistema;
	var $empleadoEnRevision;
	var $periodoDeEvaluacion;
	var $evaluacionDeEmpleado;
	var $evaluacionEnRevision;
	var $notificaciones;
	var $mensajes;
	var $valoresPredefinidos;
	var $estadosDeItems;
	
	function initialize() {
		$this->usuario = new Usuario;
		$this->usuarios = new ListaGenerica;
		$this->usuarios->initialize();
		$this->notificaciones = new ListaGenerica;
		$this->notificaciones->initialize();
		$this->mensajes = new ListaGenerica;
		$this->mensajes->initialize();
		$this->valoresPredefinidos = new ListaGenerica;
		$this->valoresPredefinidos->initialize();
		$this->estadosDeItems = new ListaGenerica;
		$this->estadosDeItems->initialize();
		$this->empleadoEnRevision = null;
		$this->periodoDeEvaluacion = null;
		$this->evaluacionDeEmpleado = null;
		$this->usuarios->add($this->usuario);
	}
	
	function empleadoConID($idEmpleado) {
		// devuelvo el empleado que forma parte de la lista de usuarios
		// si no existe, lo busco y lo cargo
		// si sigue sin existir, devuelvo null
		for ($i = 0; $i < $this->usuarios->count(); $i++) {
			$usuario = &$this->usuarios->at($i);
			if (isset($usuario->empleado)) {
				$empleado = &$usuario->empleado;
				if ($empleado->id == $idEmpleado) {
					return $empleado;
				}
			}
		}
		$usuario = usuarioConIDEmpleadoFromDB($idEmpleado);
		if (isset($usuario)) {
			$this->usuarios->add($usuario);
			return $usuario->empleado;
		} else {
			return null;
		}
	}
	
	function usuarioDelEmpleado(&$empleado) {
		for ($i = 0; $i < $this->usuarios->count(); $i++) {
			$usuario = $this->usuarios->at($i);
			if ($usuario->empleado->id == $empleado->id) {
				return $usuario;
			}
		}
		$usuario = usuarioConIDEmpleadoFromDB($empleado->id);
		$usuario->empleado = $empleado;
		return $usuario;
	}
	
	function hayNotificacionesNuevas() {
		for ($i = 0; $i < $this->notificaciones->count(); $i++) {
			$notificacion = &$this->notificaciones->at($i);
			if (!$notificacion->leido()) {
				return true;
			}
		}
		return false;
	}
	
	function notificacionesMWSList() {
	$texto = '<ul class="mws-notifications">';
	for ($i = 0; $i < $this->notificaciones->count(); $i++) {
		$notificacion = $this->notificaciones->at($i);
		if ($notificacion->leido()) {
			$leido = 'read';
		} else {
			$leido = 'unread';
		}
		$texto .= '<li class="'.$leido.'"><a href="#"><span class="message">';
		$texto .= $notificacion->descripcion;
		$texto .= '</span><span class="time"></span></a></li>';
	}
	$texto .= '</ul>';
	return $texto;
	}
	
	function limpiarMensajes() {
		$this->notificaciones->initialize();
		if (isset($this->evaluacionEnRevision)) {
			for ($i = 0; $i < $this->evaluacionEnRevision->items->count(); $i++) {
				$item = &$this->evaluacionEnRevision->items->at($i);
				$item->comentarios->initialize();
			}
		}
	}

	function aplicarEstadosSobreItemsDeEvaluacion(&$evaluacion) {
		// Recorremos los items de la evaluacion
		for ($nroDeItem = 0; $nroDeItem < $evaluacion->items->count(); $nroDeItem++) {
			$item = &$evaluacion->items->at($nroDeItem);
			if (isset($item->estado)) {
				$item->estado = $this->estadosDeItems->conID($item->estado);
			}
			// Recorremos las acciones del item
			for ($nroAccion = 0; $nroAccion < $item->acciones->count(); $nroAccion++) {
				$accion = &$item->acciones->at($nroAccion);
				if (isset($accion->estado)) {
					$accion->estado = $this->estadosDeItems->conID($accion->estado);
				}
			}
			
		}
	}
	
	function estadosOptionMWSList($tipoDeDato, $idEstadoSeleccionado = -1) {
		$texto = '';
		for ($i = 0; $i < $this->estadosDeItems->count(); $i++) {
			$estado = &$this->estadosDeItems->at($i);
			if ($estado->tipoDeDato == $tipoDeDato) {
				$texto .= '<option value="'.$estado->id.'"';
				if ($estado->id == $idEstadoSeleccionado) {
					$texto .= ' selected ';
				}
				$texto .= '>'.$estado->descripcion.'</option>';
			}
		}
		return $texto;
	}
	
	function notificacionesParaMensaje(&$mensaje) {
		$notificaciones = new ListaGenerica;
		$notificaciones->initialize();

		if (($mensaje->usuario->id <> 0) && (isset($mensaje->item))) {
			// es un comentario sobre un item ($item <> null && $usuario <> 0)
			// se notifica a todos los involucrados en la evaluacion, excepto quien hizo el comentario
			$texto = $mensaje->usuario->nombreCompleto()." ha creado un nuevo comentario para el item ".$mensaje->item->descripcion;
			$usuario = &$this->usuarioDelSistema;
			$fechaHora = date('Y-m-d h:i:s');
			$notificacion1 = new Mensaje;
			$notificacion2 = new Mensaje;
			$notificacion1->usuario = &$usuario;
			$notificacion2->usuario = &$usuario;
			$notificacion1->descripcion = $texto;
			$notificacion2->descripcion = $texto;
			$notificacion1->item = &$mensaje->item;
			$notificacion2->item = &$mensaje->item;
			$notificacion1->fechaHora = $fechaHora;
			$notificacion2->fechaHora = $fechaHora;
			if ($mensaje->usuario->empleado->id == $mensaje->evaluacion->evaluado->id) {
				$notificacion1->destinatario = $this->usuarioDelEmpleado($mensaje->evaluacion->evaluador);
				$notificacion2->destinatario = $this->usuarioDelEmpleado($mensaje->evaluacion->reviewer);
			}
			if ($mensaje->usuario->empleado->id == $mensaje->evaluacion->evaluador->id) {
				$notificacion1->destinatario = $this->usuarioDelEmpleado($mensaje->evaluacion->evaluado);
				$notificacion2->destinatario = $this->usuarioDelEmpleado($mensaje->evaluacion->reviewer);
			}
			if ($mensaje->usuario->empleado->id == $mensaje->evaluacion->reviewer->id) {
				$notificacion1->destinatario = $this->usuarioDelEmpleado($mensaje->evaluacion->evaluado);
				$notificacion2->destinatario = $this->usuarioDelEmpleado($mensaje->evaluacion->evaluador);
			}
			$notificaciones->add($notificacion1);
			$notificaciones->add($notificacion2);
		}
		return $notificaciones;
	}

}


?>
