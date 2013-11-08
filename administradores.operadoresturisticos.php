<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="administradores.php" title="Administradores" class="tip-bottom">Administradores</a>
		<a href="#" class="current">Operadores Turisticos</a>
	</div>
	<h1>Operadores Turisticos</h1><hr>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5>Operadores Turisticos</h5>
			</div>
			<form id="OperadoresturisticosForm" name="OperadoresturisticosForm" action="posts.php" method="post">
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = " SELECT idoperadoresturisticos, nombre, telefono  ";
					$sqlQuery .= " FROM `operadoresturisticos`  ";
					$sqlQuery .= " WHERE 1 ";
					echo tableFromResult(resultFromQuery($sqlQuery), 'Operadoresturisticos', true, true, 'posts.php', true);
				?>		  
			</div>
			</form>
        </div>
		<form method="post" action="posts.php">
			<input type="hidden" id="accion" name="accion" value="OperadoresturisticosNew" />
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
