<?php

include_once "lib/sessionLib.php";

$ps = parse_ini_file("local-config.ini", true);
$ps = $ps['config'];
$tm = $ps['terminal_mode'];
$ps = $ps['point_station'];

$sql = "SELECT PT.point_id, ";
$sql .= "E.employee_id employee_id, ";
$sql .= "CONCAT(P.firstname, ' ', P.lastname) Funcionario, ";
$sql .= "PT.date_time Hora, ";
$sql .= "PT.in_out Status ";
$sql .= "FROM point PT ";
$sql .= "LEFT JOIN employee E ON PT.employee_id = E.employee_id ";
$sql .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
$sql .= "WHERE PT.date_time > date_sub(now(), interval 1 minute) ";
$sql .= "AND PT.date_time < now() + interval 1 minute ";
$sql .= "ORDER BY PT.point_id ";
$sql .= "DESC LIMIT 1";

$configfile = parse_ini_file("./local-config.ini", true);
$configfile = $configfile['config'];



$result = resultFromQuery($sql);
if ($row = siguienteResult($result)){
	
	$employee_id = $row->employee_id;
	$funcionario = $row->Funcionario;
	$hora = $row->Hora;
	$status = $row->Status;
	
	if ($status == 1){
		$status = 'Entrada';
	}else{
		$status = 'Saída';
	}
	
	$date = new DateTime($hora);
	
	$string = "<PRE>Marca: <b>".$status."</b>\r\nFuncionario: <b>".$funcionario."</b>\r\nRegistro: <b>".$date->format('H:i d/m/Y')."</b>";

	$sql = "SELECT point_message_id, message from point_message where date(data) = curdate() AND employee_id = ".$employee_id;

	$result = resultFromQuery($sql);
	$i = 1;
	while ($row = siguienteResult($result)){
		
		$message = $row->message;
		$string .= "\r\nMessagem ".$i++.": <b>".$message."</b>";
	}
	
	$string .= "</PRE>";

}
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
<link rel="stylesheet" href="css/datepicker.css" />
<link rel="stylesheet" href="css/uniform.css" />
<link rel="stylesheet" href="css/select2.css" />
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="lib/lib.js"></script>

<?php if ($ps || !$tm) {?>
<script type="text/javascript">	
document.onkeydown = function(event) {

	$("#senha").focusin(function(){
	    console.log('focusInput got the focus');
	});
	
	$("#senha").focusout(function(){
	    console.log('focusInput lost focus');
	    
	    jQuery.post("getEmployeeName.php", {
						employee_id:$("#senha").val(),
					}, function(data, textStatus){
							$('#res').html(data);
							$('#res').css('color','red');
					});
	    
	});
	
var key_press = String.fromCharCode(event.keyCode);
var key_code = event.keyCode;

if(key_press == "a" || key_press == "1"){
	
	
	$('#myModal').modal('show');
	if($('#myModal').hasClass('in') == false){
		$("#in_out").val("1");
	}
	
	$("#myModal").on('shown', function(event){
		$('#senha').focus();
	});
	
	$('#myModal').on('hidden.bs.modal', function () {
		// do something…
	});

} else if(key_press == "b" || key_press == "2") {
	$('#myModal').modal('show');
	if($('#myModal').hasClass('in') == false){
		$("#in_out").val("0");
	}
	
	$("#myModal").on('shown', function(event){
		$('#senha').focus();
	});
	
	$('#myModal').on('hidden.bs.modal', function () {
		// do something…
	});
} else if(key_press == "k") {
	window.location.replace('index.php');
} else if(key_code == "13") {
	event.preventDefault();
    var $this = $(event.target);
    var index = parseFloat($this.attr('data-index'));
    if (index < 3){
		$('[data-index="' + (index + 1).toString() + '"]').focus();
	} else if ($('#senha').val() == $('#senha2').val() && $('#senha').val() != ''){
		document.forms["inputform"].submit();
	} else if ($('#senha').val() == '' || $('#senha2').val() == ''){
		alert('Escrever um código de funcionario');
		$('[data-index="' + (index - 1).toString() + '"]').focus();
	} else {
		alert('Os códigos de funcionario tem que ser iguais');
		$('[data-index="' + (index - 1).toString() + '"]').focus();
	}
}
}
</script>
<?php }?>


</head>

<body>






<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
	</div>
	<h1>Ponto diario</h1><hr>
	<?php echo isset($string) ? $string : ''; ?>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
<div class="container-fluid">
	<!--Start modal-->
		<div id="myModal" class="modal hide fade">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">×</button>
				<h3>Registro de entrada/saída</h3>
				<span id="res"></span>
			</div>
			
			<form id='inputform' method="post" action="posts.php">

				<div class="modal-body" id="modal-body">
					<input type="hidden" id="accion" name="accion" value="registrarPonto" />
					<input type="hidden" id="in_out" name="in_out"/>
					<center><input  data-index="2" type="password" id='senha' name='senha' placeholder="Código de funcionario"/></center>
					<center><input  data-index="3" type="password" id='senha2' name='senha2' placeholder="Repetir código"/></center>
				</div>
				
				<div class="modal-footer">
					<a data-dismiss="modal" class="btn" href="#">Cancelar</a>
					<input id="myButton" class="btn btn-primary" type="submit" value="Salvar" />
				</div>
			
			</form>
			
		</div>
		<!--End modal-->
		 <?php if ($ps || !$tm) {?>
			 <div class="quick-actions_homepage">
				 <ul class="quick-actions">
					 <li class="bg_lg span5"> <a href="#"> <i class="icon-signin"></i> Marcar Entrada <h2>1</h2></a> </li>
					 <li class="bg_lr span5"> <a href="#"> <i class="icon-signout"></i> Marcar Saída <h2>2</h2></a> </li>
				 </ul>
			 </div>
			 
			 <div class="quick-actions_homepage">
				 <ul class="quick-actions">
					 <li class="bg_lb span5"> <a href="#"> <i class="icon-time"></i> Solicitar troca de horario <h2>3</h2></a> </li>
					 <li class="bg_ls span5"> <a href="#"> <i class="icon-calendar"></i> Solicitar troco de folga <h2>4</h2></a> </li>
				 </ul>
			 </div>
    
			 <button class="btn btn-success">Saír <h5>+</h5></button>
		 <?php }else{ ?>
			 <h2>Imposible registar seu ponto pela internet</h2>
			 <p>Se você esta recebendo esta messagem, por favor se comunicar com o <code>Departamento de Sistemas</code></p>

		 <?php } ?>
    
</div>


<!--End-Action boxes-->

		   


</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
