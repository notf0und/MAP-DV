<?php

include "head.php"; 

isset($_GET['jobcategory_id']) ? $jobcategory_id = $_GET['jobcategory_id'] : '';

if (isset($jobcategory_id)){
	//GET jobcategory name
	//SELECT
	$sql = "SELECT name FROM jobcategory WHERE jobcategory_id = ".$jobcategory_id;
	
	$result = resultFromQuery($sql);
	
	$row = siguienteResult($result);
	
	$name = $row->name;	

	//SELECT
	$sql = "SELECT basesalary_id, CONCAT('R$ ', basesalary) 'Salario Base', valid_from 'Válido desde', created 'Creado', updated 'Atualizado' FROM basesalary WHERE jobcategory_id = ".$jobcategory_id;
	
	$result = resultFromQuery($sql);
	
	$tablabasesalary = tableFromResult($result, 'jobcategory', true, true, 'newJobcategory.php', false);
}
?>	

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="funcionarios.php" title="Pagamentos" class="tip-bottom">Funcionarios</a>
		<a href="funcionarios.categorias.lista.php" title="Pagamentos" class="tip-bottom">Categorías</a>
		<a href="#" class="current"><?php echo isset($name) ? $name : ''?></a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		

          <div class="widget-content nopadding">
			  <form id="jobcategoryForm" name="jobcategoryForm" action="funcionarios.categorias.nova.php" method="post">
				  <input type="hidden" id="accion" name="accion" value="admitJobcategory" />
				  
				  <input type="hidden" id="jobcategory_id" name="jobcategory_id" value="<?php echo isset($jobcategory_id) ? $jobcategory_id : ''?>"/>
				  
				  <!--Tabla-->
				  <div class="control-group">
					  <label class="control-label">Categoría</label>
					  <div class="controls">
						  <input required id="name" type="text" name="name" placeholder="Nome" class="span4 m-wrap" value="<?php echo isset($name) ? $name : ''?>"/>
						  <button class="btn btn-success" type="submit"> Salvar </button>
					  </div>
				  </div>
				  
				  
				  <!--Tabla-->
			  </form> 
					  
          </div>
        </div>
		
		
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Lista de salarios base</h5>
          </div>
          <div class="widget-content nopadding">
			  <form id="jobCategory" name="paymentForm" action="newJobcategory.php" method="post">
				  <!--Tabla-->
				  <?php echo $tablabasesalary;?>
				  <!--Tabla-->
				</form> 
					  
          </div>
			<div id="myModal" class="modal hide">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button">×</button>
					<h3>Detalle</h3>
				</div>
				<div class="modal-body" id="modal-body">
					<p>Here is the text coming you can put also image if you want…</p>
				</div>
			</div>
        </div>
		
		
		<form method="get" action="newBaseSalary.php">
			<input type="hidden" id="jobcategory_id" name="jobcategory_id" value="<?php echo isset($jobcategory_id) ? $jobcategory_id : ''?>"/>
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
