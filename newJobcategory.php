<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br">

 
<head>
<title>DaVinci MAP</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />z
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
			<form action="posts.php" id="jobcategory_validate" class="form-horizontal" method="post" >
				<input type="hidden" id="accion" name="accion" value="admitJobcategory" />
				
			           
              <!--Start step 1-->
              <div id="form-wizard-1" class="step">
				
            
                <!--Job category-->
                <div class="control-group">
                  <label class="control-label">Nova categoría</label>
                  <div class="controls">
					  
					  <!--Name-->  
					  <input required type="text" id="jobCategory_name" name="jobcategory_name"placeholder="Nome" class="span4 mask text"><br>
					  
					  <!--Basic Salary-->  
					  <input required type="text" id="basesalary" name="basesalary" placeholder="Salario Básico" class="span4 mask text"><br>
					  
					  <!--Valid from-->
					  <div data-date=""  class="input-append date datepicker">
						  <input required id="valid_from" name="valid_from" type="text" data-date-format="yyyy-mm-dd" placeholder="Valido desde" class="span12 mask text">
						  <span class="add-on"><i class="icon-th"></i></span>
					  </div>
						
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

