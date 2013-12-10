<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];

if($_SESSION["NombreCompleto"]=="") {
	$_SESSION["login"] = 0;
	echo '<script languaje="javascript"> window.top.location="start.php"</script>';
}
?>
<head>
<title>DaVinci MAP</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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


<script type="text/javascript" src="lib/lib.js"></script>



</head>
<body>
	
<!--breadcrumbs-->
<div id="content">
    <div class="row-fluid">
        <div class="widget-box">
          <div class="widget-content nopadding">
			  
			<!--Start form-->
			<form action="posts.php" id="form-horizontal" name="form-horizontal" class="form-horizontal" method="post">
				<input type="hidden" id="accion" name="accion" value="admitirCiudad" />
				
			           
              <!--Start step 1-->
              <div id="form-wizard-1" class="step">
				
            
                <!--Full Name-->
                <div class="control-group">
				  <label class="control-label">Nome</label>
				  
				  	<!--Country-->
					<div class="controls">
						<?php
							$country=intval($_GET['country']);
							$sqlQuery = " SELECT idpaises, nombre FROM paises ";
							$sqlQuery .= "WHERE 1 ";
							$sqlQuery .= "AND idpaises = ".$country. ";";
							$resultado = resultFromQuery($sqlQuery);
							$row = mysql_fetch_object($resultado);
						?>
						<input type="hidden" id="country" name="country" value="<?php echo $row->idpaises?>" />
						<input type="text" disabled="" class="span4 m-wrap " value="<?php echo $row->nombre?>">
					</div>
                  
                  	<!--State-->
					<div class="controls">
						<?php
							$state=intval($_GET['state']);
							$sqlQuery = " SELECT state_id, state FROM state ";
							$sqlQuery .= "WHERE 1 ";
							$sqlQuery .= "AND state_id = ".$state. ";";
							$resultado = resultFromQuery($sqlQuery);
							$row = mysql_fetch_object($resultado);
						?>
						<input type="hidden" id="state" name="state" value="<?php echo $row->state_id?>" />
						<input type="text" disabled="" class="span4 m-wrap " value="<?php echo $row->state?>">
					</div>
                  
                  <div class="controls">
                    <input id="city" type="text" name="city" placeholder="Cidade" class="span4 m-wrap"/>
                  </div>
                  
                </div>
                
              <!--End step 1-->
              
              <div class="form-actions">
				  <button type="submit" class="btn btn-success">Salvar</button>
			  </div>
			  
            </form>
			<!--End form-->

      </div>
    </div>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>

