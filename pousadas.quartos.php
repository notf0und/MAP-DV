<?php 
include_once "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];


if(isset($_GET['pousada'])){
	$posada_id = $_GET['pousada'];
}
else{
	$posada_id = 1;
}


if(isset($_POST['id'])){
	$room_id = $_POST['id'];
	
	$sql = 'SELECT label FROM room WHERE room_id = ' . $room_id;
	$result = resultFromQuery($sql);
	$row = siguienteResult($result);
	$label = $row->label;
	
}

if(isset($_POST['accion']) && $_POST['accion'] == 'RoomDelete'){

	$sql='UPDATE room set enabled = 0 WHERE room_id = '.key($_POST['deleteRow']['id']);
	$result = resultFromQuery($sql);
	
	bitacoras($_SESSION["idusuarios"], 'Apagado quarto: '.$_POST['deleteRow']['id']);
	header('Location: pousadas.quartos.php?pousada='.$posada_id);
}



$sql = 'select idposadas, nombre from posadas where idposadas = ' . $posada_id;
$posadas = resultFromQuery($sql);
$row = siguienteResult($posadas);

$pnombre = $row->nombre;

$tablaRoom = tableRoom($posada_id);


function tableRoom($posada_id){
	
	$string = '<table class="table table-bordered table-striped">';
	

	$sql = "SELECT room_id, label FROM room WHERE 1 AND enabled = 1 AND idposadas = " . $posada_id;
	$result = resultFromQuery($sql);
	
	
	if(mysql_num_rows($result) > 0){
		
		$string .= '<thead>';
		$string .= '<tr>';
		$string .= '<th>Quarto</th>';
		if($_SESSION["idusuarios_tipos"] == 1){
			$string .= '<th>Modificar</th>';
			$string .= '<th>Apagar</th>';
		}

		$string .= '</tr>';
		$string .= '</thead>';
		
		$string .= '<tbody>';

		
		while ($row = siguienteResult($result)){
			$string .= '<tr>';
			$string .= '<td><center>' . $row->label . '</center></td>';
			
			
			if($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 10){
				$string .= '<td><center><input type="button" class="btn" name="modifyRow" onclick="javascript:modifyRowEvent('."'Room'".', '."'id'".', '."'".$row->room_id."'".');" value="Editar"></center></td>';
				$string .= '<td><center><input type="submit" class="btn" name="deleteRow[id][' . $row->room_id . ']" onclick="javascript:deleteRowEvent('."'Room'".', '."'id'".', '."'".$row->room_id."'".');" value="Apagar"></center></td>';
				$string .= '</tr>';
			}
		}
	}

	

	$string .= '</tbody>';
	$string .= '</table>';

	
	return $string;




}


























?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<head>
<title>DaVinci MAP</title>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" type="text/css" href="print.css" media="print" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/colorpicker.css" />

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
		window.location.replace("http://grupodasamericas.com/ponto.php");
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
	  
	  <li class="submenu"> <a href="#"><i class="icon icon-bookmark-empty"></i> <span>Mapa</span></a>
	  <ul>
	<?php if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 10)) {
		
		$sql = 'select idposadas, nombre from posadas where posada_interna = 1 and habilitado = 1;';
		$posadas = resultFromQuery($sql);
		
		while ($row = siguienteResult($posadas)) {?>
				<li><a href="reservas.mapa.php?pousada=<?=$row->idposadas?>"><?=$row->nombre?></a></li>
		<?php }
	
	}?>
	</ul>
	</li>
	
		<li class="submenu"> <a href="#"><i class="icon icon-cog"></i> <span>Configurações</span></a>
			<ul>
				<li><a href="pousadas.quartos.php?pousada=<?=$posada_id?>">Quartos</a></li>
			</ul>
		</li>
	
	
	

  </ul>
</div>
</div>
<!--sidebar-menu-->

<!--main-container-part--> 
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb">
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="reservas.php" title="Reservas" class="tip-bottom">Reservas</a>
		<a href="reservas.mapa.php" title="Mapas" class="tip-bottom">Mapas</a>
		<a href="#" class="current">Quartos</a>
	</div>
	<h1>Quartos - <?=$pnombre?></h1><hr>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
	  <div class="row-fluid">
		  
		  <div class="span6">
			  <!--Recent posts-->
			  <div class="widget-box">
				  <div class="widget-title"> <span class="icon"> <i class="icon-file"></i> </span>
					<h5>Adicionar/Editar Quarto</h5>
				  </div>
				  <div class="widget-content nopadding">
					  <form id="Room" name="Room"  action="posts.php" method="post" class="form-horizontal">
						  <input type="hidden" id="accion" name="accion" value="<?php echo isset($_POST['accion']) && $_POST['accion'] == 'RoomModify' ? 'RoomModify' : 'RoomNew'; ?>" />
						  <input type="hidden" id="idposadas" name="idposadas" value="<?php echo isset($posada_id) ? $posada_id : ''; ?>" />
						  <input type="hidden" id="room_id" name="room_id" value="<?php echo isset($room_id) ? $room_id : ''; ?>" />
						  <!--Work Hours-->
						  <div class="control-group">
							  <label class="control-label">Nome do Quarto: </label>
							  <div class="controls">
								  <input type="text" name="label" class="span4 mask text" value="<?php echo isset($label) ? $label : '' ?>"><br>
							  </div>
						  </div>
							  

						  <div class="form-actions">
							  <button type="submit" class="btn btn-success"><?php echo isset($_POST['id']) ? 'Salvar edição' : 'Salvar nuevo'; ?></button>
						  </div>
					  </form>
				  </div>
			  </div>
		  </div>

		  <div class="span6">
			  <form id="RoomForm" name="RoomForm"  action="#" method="post" class="form-horizontal">
				  <div class="widget-box">
					  <div class="widget-title"> <span class="icon"> <i class="icon-time"></i> </span>
						<h5>Quartos</h5>
					  </div>
					  <div class="widget-content nopadding">

							  <?php echo $tablaRoom?>

					  </div>
				  </div>
			  </form>
		  </div>
	  </div>
  </div>
</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
