<?php

include "head.php"; 

//SELECT
$sql = 'SELECT JC.jobcategory_id id, CONCAT("<A HREF=funcionarios.categorias.edit.php?jobcategory_id=", JC.jobcategory_id, ">", JC.name, "</A>") Nome ';
$sql .= "FROM jobcategory JC ";
$sql .= "WHERE JC.jobcategory_id > 0; ";

$result = resultFromQuery($sql);

echo $sql;

$tablacategorias = tableFromResult($result, 'jobcategory', false, false, 'posts.php', true);
?>	

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="funcionarios.php" title="Pagamentos" class="tip-bottom">Funcionarios</a>
		<a href="#" class="current">Categorías</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Lista de Categorías</h5>
          </div>
          <div class="widget-content nopadding">
			  <form id="jobcategoryForm" name="jobcategoryForm" action="posts.php" method="post">
				  <!--Tabla-->
				  <?php echo $tablacategorias;?>
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
		<form method="get" action="newJobcategory.php">
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
