<?php 
include "lib/sessionLib.php";

//Lista con informacion detallada de los consumos
$sql = " SELECT ";
$sql .= "RP.requestpayment_id, RP.employee_id, RP.payment_id, RP.amountrequest, RP.data, RP.status, RP.amountaproved, RP.discountdate, RP.created, CONCAT(P.firstname, ' ', P.lastname) employee ";
$sql .= "FROM requestpayment RP ";
$sql .= "LEFT JOIN employee E ";
$sql .= "ON RP.employee_id = E.employee_id ";
$sql .= "LEFT JOIN profile P ";
$sql .= "ON E.profile_id = P.profile_id ";


$sql .= "WHERE 1 ";
$sql .= "AND requestpayment_id = ".$_GET["requestpayment_id"];


$result = resultFromQuery($sql);
$row = siguienteResult($result);

setlocale(LC_TIME, 'pt_BR');
$placedate = mb_convert_encoding(ucfirst(strftime("%A %d ")).'de '.ucfirst(strftime("%B").' de '.strftime("%Y")), "UTF-8", "iso-8859-1");

$requestpayment_id = $_GET["requestpayment_id"];
$amountaproved = $row->amountaproved;
$employee_id = $row->employee_id;
$employee = $row->employee;
$created = $row->created;
$discountdate = $row->discountdate;

$discountmonth = mb_convert_encoding(ucfirst(strftime("%B", strtotime($discountdate))), "UTF-8", "iso-8859-1");

?>
<html lang="en">
<head>
<title>Matrix Admin</title>

  
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/colorpicker.css" />
<link rel="stylesheet" href="css/datepicker.css" />
<link rel="stylesheet" href="css/uniform.css" />
<link rel="stylesheet" href="css/select2.css" />

<link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<style type="text/css">
	.alignleft {
		font-size:220%;
		float: left;
		text-align:left;
		width:50%;
	}
	.alignright {
			float: left;
		text-align:right;
		width:50%;
	}
</style>
</head>
<body>
<!--main-container-part-->
<form id="ValesForm" name="ValesForm" action="posts.php" method="post">
	<input type="hidden" id="accion" name="accion" value="PagarVale" />
	<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : ''?>" />
	<input type="hidden" id="requestpayment_id" name="requestpayment_id" value="<?php echo isset($requestpayment_id) ? $requestpayment_id : ''?>" />
	<input type="hidden" id="amountaproved" name="amountaproved" value="<?php echo isset($amountaproved) ? $amountaproved : ''?>" />
	<input type="hidden" id="discountdate" name="discountdate" value="<?php echo isset($discountdate) ? $discountdate : ''?>" />
	<input type="hidden" id="created" name="created" value="<?php echo isset($created) ? $created : ''?>" />
	
				<hr>
				<p class="alignleft">Vale</p>
				<p class="alignright"><?php echo $placedate?></p>
				
				<div style="clear: both;"></div>
				<hr>
				<p>Vale N°: <?php echo $requestpayment_id?></p>
				<p>Nome: <?php echo $employee?></p>
				<p>Valor: R$ <?php echo $amountaproved?></p>
				<p>Solicitud de vale gerada: <?php echo isset($created) ? date('H:i:s - d/m/Y', strtotime($created)) : ''?></p>
				<p>Pagamento a ser descontado do salario do mes de <?php echo isset($discountmonth) ? $discountmonth : ''?></p>
				<br>
				<p align="center">__________</p>
				<p align="center">Assinatura</p>
				
				<button class="btn btn-primary" onClick="window.print()">Pagar e imprimir</button>
				



			</form>
		
		


<!--end-Footer-part--> 
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/bootstrap-colorpicker.js"></script> 
<script src="js/bootstrap-datepicker.js"></script> 
<script src="js/jquery.toggle.buttons.html"></script> 
<script src="js/masked.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.form_common.js"></script> 
<script src="js/wysihtml5-0.3.0.js"></script> 
<script src="js/jquery.peity.min.js"></script> 
<script src="js/bootstrap-wysihtml5.js"></script> 
<script>
	$('.textarea_editor').wysihtml5();
</script>
</body>
</html>
