<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="administradores.php" title="Administradores" class="tip-bottom">Administradores</a>
		<a href="#" class="current">Serviços</a>
	</div>
	<h1>Serviços</h1><hr>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5>Serviços</h5>
			</div>
			<form id="administradoresServiceForm" name="administradoresServiceForm" action="posts.php" method="post">
				<input type="hidden" id="accion" name="accion" value="administradoresService" />

			<div class="widget-content nopadding">
				<?php
					$sqlQuery = "SELECT idservicios, nombre Nome, ComidasDiarias 'Refeições diárias' FROM servicios";
					echo tableFromResult(resultFromQuery($sqlQuery), 'administradoresService', false, true, 'posts.php', true);
				?>		  
			</div>
			</form>
        </div>
		<form method="get" action="administradores.servicios.novo.php">
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
