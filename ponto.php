<?php


include_once "lib/sessionLib.php";

$inifile = parse_ini_file("local-config.ini", true);
$configfile = $inifile['config'];
$tm = $configfile['terminal_mode'];
$ps = $configfile['point_station'];

$sql = "SELECT PT.point_id, ";
$sql .= "E.employee_id employee_id, ";
$sql .= "E.locked locked, ";
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

if ($ps){
	if (checkConnection()){
		$db = $inifile['remote_database'];
		
	}
	else{
		$db = $inifile['local_database'];		
	}
}
else{
	$db = $inifile['local_database'];
}


//Conexión a la DB
$database = mysqli_connect($db['dbhost'], $db['user'], $db['password'], $db['dbname'], 3306);
		
if (mysqli_connect_errno()){
	echo "Failed to connect to remote MySQL: " . mysqli_connect_error();

}
// Set charset
if (!$database->set_charset("utf8")) {
    echo("Error loading character set utf8: ".$mysqli->error);
}
				
if (!$result = mysqli_query($database, $sql)){
	die('Error al verificar si existen actualizaciones en la base de datos:<br> ' . mysqli_error($database));
}

if ($row = mysqli_fetch_array($result)){
	
	$employee_id = $row['employee_id'];
	$funcionario = $row['Funcionario'];
	$hora = $row['Hora'];
	$status = $row['Status'];
	$locked = $row['locked'];
	
	if ($locked == 0){
	
		if ($status == 1){
			$status = 'Entrada';
		}else{
			$status = 'Saída';
		}
		
		$date = new DateTime($hora);
		
		$string = "<PRE>Marca: <b>".$status."</b>\r\nFuncionario: <b>".$funcionario."</b>\r\nRegistro: <b>".$date->format('H:i d/m/Y')."</b>";

		$sql = "SELECT point_message_id, message from point_message where date(data) = curdate() AND employee_id = ".$employee_id;

		if (!$result = mysqli_query($database, $sql)){
			die('Error al verificar mensajes en la base de datos:<br> ' . mysqli_error($database));
			mysqli_close($database);
		}
		
		$i = 1;
		while ($row = mysqli_fetch_array($result)){
			
			$message = $row['message'];
			$string .= "\r\nMessagem ".$i++.": <b>".$message."</b>";
		}
		
		//Solicitud de vales	
		$sql = "SELECT amountrequest, ";
		$sql .= "data, ";
		$sql .= "status, ";
		$sql .= "amountaproved ";
		$sql .= "FROM requestpayment ";
		$sql .= "WHERE 1 ";
		$sql .= "AND status = 1 ";
		$sql .= "AND employee_id = ".$employee_id;
		
		if (!$result = mysqli_query($database, $sql)){
			mysqli_close($database);
			die('Error al verificar solicitud de vales en la base de datos:<br> ' . mysqli_error($database));
		}
		
		while ($row = mysqli_fetch_array($result)){

			$message = 'Sua solicitacão de vale foi aprovada por um '.floor(($row['amountaproved'] * 100)/$row['amountrequest']).'% do valor requerido. Pode passar a cobrar a partir do dia '.date('d/m/Y', strtotime($row['data']));
			$string .= "\r\nMessagem ".$i++.": <b>".$message."</b>";
		}
		
		$string .= "</PRE>";
	}
	else{
		
		$string = "<PRE>Seu Ponto não foi registrado a causa de um bloqueio. Para resolver sua situaçao por favor comparecer ao escritorio. Obrigado</PRE>";
		
	}
	

}
mysqli_close($database);
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

<?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MAP-DV') !== false) {?>
<script type="text/javascript">	
var showingModal = 0;
document.onkeydown = function(event) {
	
	$("#senha").focusout(function(){
	    
	    jQuery.post("getEmployeeName.php", {
						employee_id:$("#senha").val(),
					}, function(data, textStatus){
							$('#res').html(data);
							$('#res').css('color','red');
					});
	    
	});
	
	$("#senharequest").focusout(function(){
	    
	    jQuery.post("getEmployeeName.php", {
						employee_id:$("#senharequest").val(),
					}, function(data, textStatus){
							$('#NFuncionario').html(data);
							$('#NFuncionario').css('color','red');
					});
	    
	});
	
	var key_press = String.fromCharCode(event.keyCode);
	var key_code = event.keyCode;

	if((key_press == "a" || key_press == "1") && showingModal == 0){
		

		$('#myModal').modal('show');
		showingModal = 1;

		
		if($('#myModal').hasClass('in') == false){
			$("#in_out").val("1");
		}
		
		$("#myModal").on('shown', function(event){
			$('#senha').focus();
		});
		
		$('#myModal').on('hidden.bs.modal', function () {
			showingModal = 0;
		});

	} else if((key_press == "b" || key_press == "2") && showingModal == 0) {
		$('#myModal').modal('show');
		showingModal = 1;
		if($('#myModal').hasClass('in') == false){
			$("#in_out").val("0");
		}
		
		$("#myModal").on('shown', function(event){
			$('#senha').focus();
		});
		
		$('#myModal').on('hidden.bs.modal', function () {
			showingModal = 0;
		});
	} else if((key_press == "c" || key_press == "3") && showingModal == 0) {

		$('#RequestPaymentModal').modal('show');
		showingModal = 2;

		if($('#RequestPaymentModal').hasClass('in') == false){
			$("#in_out").val("0");
		}
		
		$("#RequestPaymentModal").on('shown', function(event){
			$('#senharequest').focus();
		});
		
		$('#RequestPaymentModal').on('hidden.bs.modal', function () {
			showingModal = 0;
		});
	} else if(key_press == "k") {
		window.location.replace('index.php');
	} else if(key_code == "13" && showingModal == 1) {
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
	} else if(key_code == "13" && showingModal == 2) {
		event.preventDefault();
		var $this = $(event.target);
		var index = parseFloat($this.attr('data-index'));
		if (index < 6){
			$('[data-index="' + (index + 1).toString() + '"]').focus();
		} else if ($('#senharequest').val() == $('#senha2request').val() && $('#senharequest').val() != ''){
			document.forms["RequestPaymentForm"].submit();
		} else if ($('#senharequest').val() == '' || $('#senha2request').val() == ''){
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
		
		<div id="RequestPaymentModal" class="modal hide fade">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">×</button>
				<h3>Solicitar Vale</h3>
				<span id="NFuncionario"></span>
			</div>
			
			<form id='RequestPaymentForm' method="post" action="posts.php">

				<div class="modal-body" id="modal-body">
					<input type="hidden" id="accion" name="accion" value="RequestPayment" />
					<center><input  data-index="4" type="password" id='senharequest' name='senharequest' placeholder="Código de funcionario"/></center>
					<center><input  data-index="5" type="password" id='senha2request' name='senha2request' placeholder="Repetir código"/></center>
					<center><input  data-index="6" type="text" id='ammount' name='ammount' placeholder="Monto do Vale"/></center>
				</div>
				
				<div class="modal-footer">
					<a data-dismiss="modal" class="btn" href="#">Cancelar</a>
					<input id="sendRequest" class="btn btn-primary" type="submit" value="Salvar" />
				</div>
			
			</form>
			
		</div>
		
		<!--End modal-->
		 <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MAP-DV') !== false) {?>
			 <div class="quick-actions_homepage">
				 <ul class="quick-actions">
					 <li class="bg_lg span5"> <a href="#"> <i class="icon-signin"></i> Marcar Entrada <h2>1</h2></a> </li>
					 <li class="bg_lr span5"> <a href="#"> <i class="icon-signout"></i> Marcar Saída <h2>2</h2></a> </li>
				 </ul>
			 </div>
			 
			 <div class="quick-actions_homepage">
				 <ul class="quick-actions">
					 <li class="bg_lb span5"> <a href="#"> <i class="icon-money"></i> Solicitar Vale <h2>3</h2></a> </li>
					 <li class="bg_ls span5"> <a href="#"> <i class="icon-calendar"></i> Solicitar troco de folga <h2>4</h2></a> </li>
				 </ul>
			 </div>
    
			 <button class="btn btn-success">Saír <h5>+</h5></button>
		 <?php }else{ ?>
			 <h2>Imposible registar seu ponto pela internet</h2>
			 <p>Se você esta recebendo esta messagem, por favor se comunicar com o <code>Escritorio (22) 2623-7098</code></p>

		 <?php } ?>
    
</div>


<!--End-Action boxes-->

		   


</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
