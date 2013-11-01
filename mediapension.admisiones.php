<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];
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
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
<!--main-container-part-->
<div id="content">
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span6">
			<div class="widget-box">
			<?php
				$sqlQuery = " SELECT ";
				$sqlQuery .= " MPA.data 'Data de admision', ";
				$sqlQuery .= " L.nombre 'Resto', ";
				$sqlQuery .= " MPA.qtdedepax 'Pax do Serviço' "; 
				$sqlQuery .= " FROM mediapension_admisiones MPA ";
				$sqlQuery .= " INNER JOIN mediapension MP ON MPA.idmediapension = MP.idmediapension ";
				$sqlQuery .= " INNER JOIN locales L ON MP.idlocales = L.idlocales ";
				$sqlQuery .= " WHERE MP.idmediapension = ".$_GET["idmediapension"];
				echo tableFromResult(resultFromQuery($sqlQuery), 'mediapension', false, false, 'posts.php', true);
			?>			
				<b>Resumen:</b><br>
				Total de servicios: xx<br>
				DataIN :<br>
				DataOUT :<br>

			</div>
			
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
</body>
</HTML>
