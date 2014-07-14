<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];

//Lista con informacion detallada de los consumos
$sql = " SELECT ";
$sql .= "requestpayment_id, employee_id, payment_id, amountrequest, data, status, amountaproved, discountdate ";
$sql .= "FROM requestpayment ";
$sql .= "WHERE 1 ";
$sql .= "AND requestpayment_id = ".$_GET["requestpayment_id"];


$result = resultFromQuery($sql);
$row = siguienteResult($result);

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
</head>
<body>
<!--main-container-part-->
<form id="ValesForm" name="ValesForm" action="posts.php" method="post">
	<input type="hidden" id="accion" name="accion" value="AprovarVale" />
	<input type="hidden" id="requestpayment_id" name="requestpayment_id" value="<?php echo isset($_GET['requestpayment_id']) ? $_GET['requestpayment_id'] : ''?>" />
				
				<div class="control-group">
					<label class="control-label">Data de desconto: </label>
					<div data-date="" class="input-append date datepicker">
						<input id="discountdate" name="discountdate" type="text" value="<?php echo date('Y-m-d')?>" class="span11">
						<span class="add-on"><i class="icon-th"></i></span>
					</div>
				</div>

		
				<div class="control-group">
					<label class="control-label">Data de pagamento: </label>
					<div data-date="" class="input-append date datepicker">
						<input id="ate" name="data" type="text" value="<?php echo date('Y-m-d')?>" class="span11">
						<span class="add-on"><i class="icon-th"></i></span>
					</div>
				</div>
				<div class="control-group">
					
					<label class="control-label">Valor: </label>
					
					<div class="input-prepend"> <span class="add-on">R$</span>
					
					<input id="amountaproved" name="amountaproved" type="text" placeholder="Básico" class="span4 m-wrap" value="<?php echo $row->amountrequest?>">

				</div>


				

			</div>
			
				<button class="btn btn-primary" type="submit">Aprovar</button>
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
