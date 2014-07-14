<?php

include_once "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];

if(!isset($_SESSION["login"]) || $_SESSION["login"] == 0) {
	header('Location: start.php');
}

if(isset($_GET['pousada'])){
	$posada_id = $_GET['pousada'];
}
else{
	$posada_id = 1;
}

header('Content-Type: text/html; charset=utf-8');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br">

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
		window.location.replace("ponto.php");
	}
}

function myFunction(id) {
	$.ajaxSetup({async:false});  //execute synchronously
    $.ajax({
		type: "post", 
		url: 'getBooking.php',
		data: {
			booking_id: id
		},
		dataType: 'json',
		success: function(data){
			row =  data[0];

			$('[name=booking_id]').val(row['booking_id']);
			$("[name=room] option[value="+row['room_id'] +"]").attr("selected",true);
			$("[name=room] option[value="+row['room_id'] +"]").val(row['room_id']).change();
			
			
			
			$('[name=label]').val(row['label']);
			$("[name=color] option[value="+row['color'] +"]").attr("selected",true);
			$("[name=color] option[value="+row['color'] +"]").val(row['color']).change();
			
			
			$('[name=idhuespedes]').val(row['idhuespedes']);
			$('[name=name]').val(row['name']);
			$('[name=quantity]').val(row['quantity']);
			
			$("[name=country] option[value="+row['idpaises'] +"]").attr("selected",true);
			$("[name=country] option[value="+row['idpaises'] +"]").val(row['idpaises']).change();
			
			$('[name=email]').val(row['email']);
			$('[name=notes]').val(row['note']);
			
			$("[name=idservicios] option[value="+row['idservicios'] +"]").attr("selected",true);
			$("[name=idservicios] option[value="+row['idservicios'] +"]").val(row['idservicios']).change();

			$("[name=responsable] option[value="+row['idresponsablesDepago'] +"]").attr("selected",true);
			$("[name=responsable] option[value="+row['idresponsablesDepago'] +"]").val(row['idresponsablesDepago']).change();
			
			$("[name=operador] option[value="+row['idoperadoresturisticos'] +"]").attr("selected",true);
			$("[name=operador] option[value="+row['idoperadoresturisticos'] +"]").val(row['idoperadoresturisticos']).change();
			
			$('[name=price]').val(row['price']);
			$('[name=pay]').val(row['pay']);
			
			$("[name=currency] option[value="+row['currency'] +"]").attr("selected",true);
			$("[name=currency] option[value="+row['currency'] +"]").val(row['currency']).change();

			$('[name=valor]').text(row['data']);
			$('#periodSelected').val(row['data']);
			$('[name=accion]').val('bookingModify');

		}
	});
	
	$.ajaxSetup({async:true});  //return to default setting

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
	  
	  <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Mapa</span></a>
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




<?php
header('Content-Type: text/html; charset=utf-8');
require('lib/gantti.php'); 

$data = array();


	$sql = 'SELECT idposadas, nombre FROM posadas WHERE 1 AND idposadas = ' . $posada_id;
	$posadas = resultFromQuery($sql);
	
	if($row = siguienteResult($posadas)){
		$posada_id = $row->idposadas; 
		$nombre = $row->nombre; 
	}
	
	$sql = "SELECT room_id, label from room where 1 AND enabled = 1 AND idposadas = " . $posada_id;
	$room = resultFromQuery($sql);
	
	while($rroom = siguienteResult($room)){
		
		$sql = 'SELECT * FROM booking WHERE room_id = ' . $rroom->room_id;
		$booking = resultFromQuery($sql);
		if($rbooking = siguienteResult($booking)){
			mysql_data_seek($booking, 0);
			while($rbooking = siguienteResult($booking)){
			
				$data[$rroom->label][] = array(
				  'message' => $rbooking->label,
				  'booking_id' => $rbooking->booking_id,
				  'start' => $rbooking->dataIN, 
				  'end'   => $rbooking->dataOUT,
				  'class' => $rbooking->color != '' ? $rbooking->color : 'blue'
				);
			}
		}
		else{
			$data[] = array(
			  'label' => $rroom->label,
			  'start' => '1999-04-01', 
			  'end'   => '1999-04-02'
			);
		}
	}

date_default_timezone_set('UTC');
setlocale(LC_ALL, 'pt_BR');

$gantti = new Gantti($data, array(
  'title'      => $nombre,
  'cellwidth'  => 35,
  'cellheight' => 25,
  'today'      => true,
  'first'      => date('Y-m-d', strtotime("first day of last month")),
  'last'      => date('Y-m-d', strtotime('2014-08-08'))
));



	
?>	
<link rel="stylesheet" href="styles/css/gantti.css" />
<link rel="stylesheet" href="css/periodpicker.css" type="text/css" />
<link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />
<title>DatePicker - jQuery plugin</title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/datepicker.js"></script>
<script type="text/javascript" src="js/eye.js"></script>
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>
<!--main-container-part-->
<div id="content">
	<div id="content-header">
		<div id="breadcrumb">
			<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Inicio</a>
			<a href="reservas.php" title="Área Contable" class="tip-bottom">Reservas</a>
			<a href="#" class="current">Mapas</a>
		</div>
		<h1>Mapas de Reservas</h1>
	</div>
	
	<?php echo $gantti ?>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon"> <i class="icon-align-justify"></i> </span>
						<h5>Form Layout</h5>
					</div>
					    <div class="widget-content">
							<form class="form-horizontal" action="posts.php" method="post">
								<input type="hidden" id="accion" name="accion" value="bookingNew" />
								<input type="hidden" name="idposadas" id="idposadas" value="<?=$posada_id?>">
								<input type="hidden" name="idhuespedes" id="idhuespedes" value="">
								<input type="hidden" name="booking_id" id="booking_id">
								<input type="hidden" name="periodSelected" id="periodSelected">
					

								<!--Periodo-->
								<div class="control-group">
									<label class="control-label">Período:</label>
									<div class="controls controls-row">
										
										<div id="period" class="span2 m-wrap">
											<div id="periodField" name="periodField">
												<span id="valor" name="valor"  class="span2 m-wrap"></span>
												<a href="#"  class="span2 m-wrap">Select date range</a>
											</div>
											<div id="periodCalendar">
											</div>
										</div>
									</div>
								</div>
								<!--Quarto-->
								<div class="control-group">
									<label class="control-label">Quarto:</label>
									
									<div class="controls controls-row">
										
										<select name="room" class="span2 m-wrap">
											<?php
											
											$sql = 'SELECT room_id, label FROM room WHERE idposadas = ' . $posada_id;
											$room = resultFromQuery($sql);
											
											while($rroom = siguienteResult($room)){
											
											?>
											<option value="<?=$rroom->room_id?>"><?=$rroom->label?></option>
											<?php }?>

										</select>
										
										<input name="label" type="text" class="span6 m-wrap" placeholder="Etiqueta" />
										
										<select name="color" class="span2 m-wrap">
											<option class="blueText" value='blue'>Azul</option>
											<option class="greenText" value='green'>Verde</option>
											<option class="roseText" value='rose'>Rosa</option>
											<option class="brownText" value='brown'>Marron</option>
											<option class="orangeText" value='orange'>Naranja</option>
											<option class="redText" value='red'>Rojo</option>
											<option class="yellowText" value='yellow'>Amarillo</option>
											<option class="grayText" value='gray'>Gris</option>
											<option class="blackText" value='black'>Negro</option>
										</select>
									
									</div>
								</div>
								<!--Hospede-->
								<div class="control-group">
									<label class="control-label">Hospede:</label>
									
									<div class="controls controls-row">
										<input name="name" type="text" class="span6 m-wrap" placeholder="Nome completo" />
										<input name="quantity" type="number" class="span2 m-wrap" placeholder="Qtde" />
										
										<select name="country" class="span2 m-wrap">
											<option selected disabled>País</option>
											<?php
											
											$sql = 'SELECT idpaises, nombre FROM paises;';
											$result = resultFromQuery($sql);
											
											while($row = siguienteResult($result)){
											
											?>
											<option value="<?=$row->idpaises?>"><?=$row->nombre?></option>
											<?php }?>

										</select>
										
									</div>
									
									<div class="controls controls-row">
										<input name="email" type="email" class="span4 m-wrap" placeholder="email@example.com" />
										<textarea name="notes" class="span6 m-wrap" placeholder="Observações" ></textarea>
									</div>
								</div>
								

								
								<!--Serviço-->
								<div class="control-group">
									<label class="control-label">Serviço</label>
									<div class="controls controls-row">
										
										<select name="idservicios" class="span4 m-wrap">
											<?php
											
											$sql = 'SELECT idservicios, nombre FROM  servicios WHERE idservicios >= 6 ';
											$result = resultFromQuery($sql);
											
											while($row = siguienteResult($result)){
											
											?>
											<option value="<?=$row->idservicios?>"><?=$row->nombre?></option>
											<?php }?>

										</select>
										
										<select name="responsable" class="span3 m-wrap">
											<option value="1">Operador</option>
											<option value="5" selected>Reserva</option>
											<option value="4">Venta por balcon</option>


										</select>
										
										<select name="operador " class="span3 m-wrap">
											<option selected disabled>Operador</option>
											<?php
											
											$sql = 'SELECT idoperadoresturisticos, nombre FROM operadoresturisticos';
											$result = resultFromQuery($sql);
											
											while($row = siguienteResult($result)){
											
											?>
											<option value="<?=$row->idoperadoresturisticos?>"><?=$row->nombre?></option>
											<?php }?>

										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Tarifas:</label>
									<div class="controls controls-row">
										<input name="price" type="number" class="span2 m-wrap" placeholder="Cobrado" />
										<input name="pay" type="number" class="span2 m-wrap" placeholder="Pagado" />
										
										<select name="currency" class="span3 m-wrap">
											<?php
											
											$sql = 'SELECT idcurrency, name FROM currency';
											$result = resultFromQuery($sql);
											
											while($row = siguienteResult($result)){
											
											?>
												<option value="<?=$row->idcurrency?>"><?=$row->name?></option>
											<?php }?>

										</select>
										
									</div>
								</div>
							
							
								<div class="control-group">

								<div class="controls">
									<input id="next" class="btn btn-primary" type="submit" value="Salvar" />
								</div>
							</div>
			
							</form>
					    </div>
					</div>
				</div>
			  </div>
			</div>
			





</div>

<!--end-main-container-part-->
<!--Footer-part-->
<div id="no-print">
<div class="row-fluid">
  <div id="footer" class="span12"> 2013 &copy; Grupos Das Americas.</div>
</div>
</div>

<!--end-Footer-part-->
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/bootstrap-colorpicker.js"></script> 

<script src="js/jquery.toggle.buttons.html"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<script src="js/jquery.validate.js"></script> 
<script src="js/jquery.wizard.js"></script> 
<script src="js/masked.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.popover.js"></script>
<script src="js/matrix.wizard.js"></script>
<script src="js/matrix.tables.js"></script>
<script src="js/matrix.form_common.js"></script> 
<script src="js/matrix.form_validation.js"></script>
<script src="js/wysihtml5-0.3.0.js"></script> 
<script src="js/jquery.peity.min.js"></script> 
<script src="js/bootstrap-wysihtml5.js"></script> 

<script>
	$('.textarea_editor').wysihtml5();
</script>

</body>

</html>

