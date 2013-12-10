<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];
?>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />


<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
	
<!--breadcrumbs-->
<div id="content">
    <div class="row-fluid">
        <div class="widget-box">
          <div class="widget-content nopadding">
			  
			<!--Start form-->
			<form action="posts.php" id="form-horizontal" name="form-horizontal" class="form-horizontal" method="post">
				<input type="hidden" id="accion" name="accion" value="admitirPais" />
				
			           
              <!--Start step 1-->
              <div id="form-wizard-1" class="step">
				
            
                <!--Full Name-->
                <div class="control-group">
				  <label class="control-label">Nome</label>
				  
				  <div class="controls">
                    <input id="country" type="text" name="country" placeholder="País" class="span4 m-wrap"/>
                  </div>
                  
                  <div class="controls">
                    <input id="state" type="text" name="state" placeholder="Estado" class="span4 m-wrap"/>
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

