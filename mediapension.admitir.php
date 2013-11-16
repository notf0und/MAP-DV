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
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Voucher-info</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" method="post" class="form-horizontal">
					<input type="hidden" id="accion" name="accion" value="admitirServicio" />
					<input type="hidden" id="idmediapension" name="idmediapension" value="<?php echo $_GET['idmediapension'];?>" />
					<div class="control-group">
						<label class="control-label">Qtde Pax do Serviço</label>
						<div class="controls">
							<select class="input" name="qtdedepaxagora" id="qtdedepaxagora" class="span2"> 
								<option value="" selected> </option> 
								<?php 
								$sqlQuery = "SELECT qtdedepax FROM mediapension";
								$sqlQuery .= " WHERE idmediapension = ".$_GET["idmediapension"];
								$result = resultFromQuery($sqlQuery);
								if ($row = siguienteResult($result)) {
									for ($i = 1; $i <= $row->qtdedepax; $i++) {
										echo '<option value="'.$i.'">'.$i.'</option> ';
									}
								}
								?> 
							</select>
							
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success">Admitir</button>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
</body>
</HTML>
