<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="liquidaciones.php" title="Liquidaciones" class="tip-bottom">Liquidações</a>
		<a href="#" class="current">Liquidações Pendentes</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5>Liquidações Pendentes</h5>
			</div>
			<form id="LiquidacionesForm" name="LiquidacionesForm" action="posts.php" method="post">
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = " SELECT L.idliquidaciones 'ID', L.fecha Fecha, DATA.nombre Responsable, ";
					$sqlQuery .= " L.titulo 'Titulo', L.importeMP 'USD MP', L.importeHTL 'USD HTL', L.importeMP+L.importeHTL 'USD Total', LE.nombre 'Estados' ";
					$sqlQuery .= " FROM `liquidaciones` L";
					$sqlQuery .= " LEFT JOIN operadoresturisticos DATA ON L.responsable = DATA.idoperadoresturisticos ";
					$sqlQuery .= " LEFT JOIN liquidaciones_estados LE ON L.idestados = LE.idestados ";
					$sqlQuery .= " WHERE 1 ";
					$sqlQuery .= " AND idliquidaciones > 0 ";
					$sqlQuery .= " AND L.idestados < 3 ";
					$sqlQuery .= " ORDER BY L.idestados, L.fecha ";
					echo tableFromResult(resultFromQuery($sqlQuery), 'Liquidaciones', true, true, 'posts.php', true);
				?>		  
			</div>
			</form>
        </div>
	</div>
    <hr/>
  </div>
</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
<?php
if (isset($_GET['liquidacion']) && $_GET['liquidacion']==1){
	echo '<script languaje="javascript"> self.location="liquidaciones-xls.php?filename='.$_GET['filename'].'&idresponsablesDepago='.$_GET['idresponsablesDepago'].'&id='.$_GET['id'].'"</script>';
};
?>
