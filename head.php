<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br">

<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];

if($_SESSION["NombreCompleto"]=="") {
	$_SESSION["login"] = 0;
	echo '<script languaje="javascript"> self.location="start.php"</script>';
}

?>
<head>
<title>DaVinci MAP</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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



</head>
<body>

<!--Header-part-->
<div id="header">
  <h1><a href="dashboard.html">Matrix Admin</a></h1>
</div>
<!--close-Header-part--> 


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Oi <?php echo $_SESSION["NombreCompleto"]; ?></span><b class="caret"></b></a>
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
	<?php if (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 2) || ($_SESSION["idusuarios_tipos"] == 3)) {?>
    <li class="submenu <?php if(substr_count($script_name, 'mediapension') > 0){echo 'active';}; ?>"> <a href="#"><i class="icon icon-th-list"></i> <span>Media pensión</span></a>
      <ul>
        <li><a href="mediapension.novo.php">Novo...</a></li>
        <li><a href="mediapension.lista.php">Veja a lista</a></li>
		<?php if (($_SESSION["idusuarios_tipos"] == 2)) {?>
        <li><a href="mediapension.agencias.novoTipoUser2.php">Nueva Agencia</a></li>
		<?php }?>
		<?php if (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 3)) {?>
        <li><a href="mediapension.informes.php">Reports</a></li>
        <li><a href="mediapension.vouchers.php">Vouchers</a></li>
        <li><a href="mediapension.administradores.php">Administradores</a></li>
		<?php }?>
      </ul>
    </li>
	<?php }?>

	<?php if (($_SESSION["idusuarios_tipos"] == 10)) {?>
    <li class="submenu <?php if(substr_count($script_name, 'reservas') > 0){echo 'active';}; ?>"> <a href="#"><i class="icon icon-th-list"></i> <span>Reservas</span></a>
      <ul>
        <li><a href="posts.php">Nova...</a></li>
        <li><a href="reservas.mapa.php">Exibir o mapa</a></li>
      </ul>
    </li>
	<?php }?>
  </ul>
</div>
<!--sidebar-menu-->
