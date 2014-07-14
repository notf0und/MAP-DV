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
	
	var key_press = String.fromCharCode(event.keyCode);
	var key_code = event.keyCode;

	if(key_press == "k") {
		window.location.replace('ponto.php');
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
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
<div class="container-fluid">
	<!--Start modal-->
		<!--End modal-->
			 <h2>Impossível registar seu ponto</h2>
			 <h4>Para resolver sua situação por favor comparecer ao escritiorio. Obrigado</h4>
			 <br>
			 <br>
			 <br>
			 <br>
			 <a href='ponto.php'><button class="btn btn-success">Voltar <h2>+</h2></button></a>
    
</div>


<!--End-Action boxes-->

		   


</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
