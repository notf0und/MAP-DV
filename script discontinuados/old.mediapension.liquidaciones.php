<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="#" class="current">Liquidaciones</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5>Liquidaciones Pendientes</h5>
			</div>
			<form id="LiquidacionesForm" name="LiquidacionesForm" action="posts.php" method="post">
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = " SELECT L.idliquidaciones 'ID', L.fecha Fecha, DATA.nombre Responsable, ";
					$sqlQuery .= " L.importe 'USD', LE.nombre 'Estados' ";
					$sqlQuery .= " FROM `liquidaciones` L";
					$sqlQuery .= " LEFT JOIN operadoresturisticos DATA ON L.responsable = DATA.idoperadoresturisticos ";
					$sqlQuery .= " LEFT JOIN liquidaciones_estados LE ON L.idestados = LE.idestados ";
					$sqlQuery .= " WHERE 1 ";
					$sqlQuery .= " AND idliquidaciones > 0 ";
					$sqlQuery .= " ORDER BY L.idestados, L.fecha ";
					echo tableFromResultGDA(resultFromQuery($sqlQuery), 'Liquidaciones', true, true, 'posts.php', true);
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
if ($_GET['liquidacion']==1){
	echo '<script languaje="javascript"> self.location="mediapension.informes.liquidaciones.mensual.reporte.php"</script>';
};
?>
