<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br">

<?php

include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];

if(!isset($_SESSION["login"]) || $_SESSION["login"] == 0) {
	header('Location: start.php');
}

if (isset($_SESSION["idusuarios"]) && $_SESSION["idusuarios"] == 13) {
	
	//Reporte de errores PHP
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	
	require_once('lib/FirePHPCore/FirePHP.class.php');
	ob_start();
	$firephp = FirePHP::getInstance(true);
	
	/* Example usage of firephp
	if (isset($_SESSION["idusuarios"]) && $_SESSION["idusuarios"] == 13) {
			 
		$firephp->log('Un mensaje plano');
		$firephp->info('Un mensaje de información');
		$firephp->warn('Una alerta');
		$firephp->error('Enviar un mensaje de error');
		
		$table   = array();
		$table[] = array('Titulo 1','Titulo 2', 'Titulo 3');
		$table[] = array('Col 1, fila 1','Col 2, fila 1','Col 3, fila 1');
		$table[] = array('Col 1, fila 2','Col 2, fila 2','Col 3, fila 2');
		$table[] = array('Col 1, fila 3','Col 2, fila 3','Col 3, fila 3');

		$firephp->table('Tabla', $table);  

		fb($table, 'Tabla', FirePHP::TABLE);
	}
	*/
}

?>
<head>
<title>DaVinci MAP</title>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" type="text/css" href="print.css" media="print" />
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

<script type="text/javascript" src="lib/lib.js"></script>

<script type="text/javascript">
document.onkeydown = function(event) {
var key_press = String.fromCharCode(event.keyCode);
var key_code = event.keyCode;

if(key_press == "k") {
window.location.replace("http://davincimp.no-ip.info:8080/ponto.php");
}
}
</script>

<link rel="shortcut icon" href="favicon.ico?v=2" />
</head>

<body>
<div id="no-print">
<!--Header-part-->
<div id="header">
  <h1></h1>
</div>
<!--close-Header-part--> 


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Oi <?php echo isset($_SESSION["NombreCompleto"]) ? $_SESSION["NombreCompleto"] : ''; ?></span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="user.changepassword.php"><i class="icon-key"></i>Alterar senha</a></li>
        <li class="divider"></li>
        <li><a href="login.php"><i class="icon-share-alt"></i> Sair</a></li>
      </ul>
    </li>
    <li class=""><a title="" href="logOut.php"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<!--
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>
-->
<!--lose-top-serch-->
<!--sidebar-menu-->

<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
	<?php if (isset($_SESSION["idusuarios_tipos"]) && (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 2) || ($_SESSION["idusuarios_tipos"] == 3))) {?>
    <li class="submenu <?php if(substr_count($script_name, 'mediapension') > 0){echo 'active';}; ?>"> <a href="#"><i class="icon icon-th-list"></i> <span>Meia-pensão</span></a>
      <ul>
        <li><a href="mediapension.novo.php">Novo...</a></li>
        <li><a href="mediapension.lista.php">Veja a lista</a></li>
		<?php if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 2)) {?>
        <li><a href="mediapension.agencias.novoTipoUser2.php">Nueva Agencia</a></li>
		<?php }?>
		<?php if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 3)) {?>
        <li><a href="mediapension.vouchers.php">Vouchers</a></li>
        <li><a href="mediapension.estatisticas.php">Estatísticas</a></li>
        <li><a href="mediapension.administradores.php">Administradores</a></li>
		<?php }?>
      </ul>
    </li>
	<?php }?>

	<?php if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 10)) {?>
    <li class="submenu <?php if(substr_count($script_name, 'reservas') > 0){echo 'active';}; ?>"> <a href="#"><i class="icon icon-th-list"></i> <span>Reservas</span></a>
      <ul>
        <li><a href="posts.php">Nova...</a></li>
        <li><a href="reservas.mapa.php">Exibir o mapa</a></li>
      </ul>
    </li>
	<?php }?>
  </ul>
</div>
</div>
<!--sidebar-menu-->
