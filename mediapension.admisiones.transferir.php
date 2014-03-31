<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];

if (isset($_POST['idmediapension'])){
	
	$idmediapension = $_POST['idmediapension'];

	$sql = "SELECT * FROM mediapension where idmediapension = ".$idmediapension;

	$result = resultFromQuery($sql);
	
	if ($row = siguienteResult($result)){
	
		$sql = "SELECT MP.*, H.Titular ";
		$sql .= "FROM mediapension MP ";
		$sql .= "LEFT JOIN huespedes H ON H.idhuespedes = MP.idhuespedes ";
		$sql .= "WHERE dataIN = '".$row->dataIN."' ";
		$sql .= "AND dataOUT = '".$row->dataOUT."' ";
		$sql .= "AND idliquidaciones = 0 ";
		$sql .= "AND idmediapension <> ".$row->idmediapension;
		
		$vouchers = resultFromQuery($sql);
		
		$select = '<label class="control-label">Destino: </label>';
		$select .= '<div class="controls span4">';
		$select .= '<select name="To">';
		
		
		
		while ($rvouchers = siguienteResult($vouchers)){
			
			$select .= '<option value="'.$rvouchers->idmediapension.'">';
			$select .= $rvouchers->idmediapension.' - '.$rvouchers->Titular;
			$select .= '</option>';
		}
		
		$select .= '</select>';
		$select .= '</div>';
	}

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
		<form id="AdmisionesMPForm" name="AdmisionesMPForm" action="posts.php" method="post">
			<input type="hidden" id="accion" name="accion" value="AdmisionesMPTransfer" />
			<input type="hidden" id="From" name="From" value="<?php echo isset($_POST['idmediapension']) ? $_POST['idmediapension'] : '' ?>" />

			<div class="widget-box">
				  <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
				      <h5>Transferencia de admisões </h5>				      
				  </div>
				  <div class="widget-content nopadding">
					  <div class="control-group">
						  <?php echo isset($select) ? $select : '' ?>
					  </div>
					  <div class="form-actions" align="left">
						  <button type="submit" class="btn btn-success">Pesquisar</button>
					  </div>
				  </div>
			</div>
		</form>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
