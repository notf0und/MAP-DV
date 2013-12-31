<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];

//Lista con informacion detallada de los consumos
$sqlQuery = " SELECT ";
$sqlQuery .= " MPA.idmediapension, ";
$sqlQuery .= " DATE_FORMAT(MPA.data, '%d/%c - %H:%i') 'Data de admision', ";
$sqlQuery .= " MPA.qtdedepax 'Pessoas', ";
$sqlQuery .= " L.nombre 'Resto' ";
$sqlQuery .= " FROM mediapension_admisiones MPA ";
$sqlQuery .= " LEFT JOIN locales L ON MPA.idlocales = L.idlocales ";
$sqlQuery .= " WHERE MPA.idmediapension = ".$_GET["idmediapension"];
$sqlQuery .= " ORDER BY MPA.data";
$tablaconsumos = tableFromResult(resultFromQuery($sqlQuery), 'mediapension', false, false, 'posts.php', true);

//Total de servicios
$sqlQuery = " SELECT ";
$sqlQuery .= " MP.qtdedecomidas, SUM(MPA.qtdedepax), MP.dataIN, MP.dataOUT";			
$sqlQuery .= " FROM mediapension MP";
$sqlQuery .= " LEFT JOIN mediapension_admisiones MPA ON MP.idmediapension = MPA.idmediapension";
$sqlQuery .= " WHERE MP.idmediapension = ".$_GET["idmediapension"];
$sqlResult = resultFromQuery($sqlQuery);
          
while ($row = mysql_fetch_row($sqlResult)){
	$totalcomidas = "$row[0]";
	$serviciosconsumidos = "$row[1]";
	$datain = "$row[2]";
	$dataout =  "$row[3]";
	$serviciosrestantes = $totalcomidas - $serviciosconsumidos;
}

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
			<div class="widget-box">
				
				<?php echo $tablaconsumos; ?>
				<b>Resumen:</b><br>
				
				Total de servicios:<b><?php echo $totalcomidas; ?></b><br>
				Serviços Consumidos:<b>	<?php echo $serviciosconsumidos; ?></b><br>
				Serviços Restantes:<b>	<?php echo $serviciosrestantes; ?></b><br><br>
				
				DataIN :<b><?php echo $datain;?></b><br>
				DataOUT :<b><?php echo $dataout;?></b>
			</div>
			
</div>

<!--end-main-container-part-->
</body>
</HTML>
