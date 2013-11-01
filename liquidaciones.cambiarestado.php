<?php 
include "head.php"; 

if (isset($_SESSION['idliquidaciones'])){
	$idliquidaciones = $_SESSION['idliquidaciones'];
	$current = 'Edit';
	$sqlQuery = " SELECT L.idliquidaciones, L.fecha Fecha, DATA.nombre Responsable, L.importeMP+L.importeHTL 'USDTOTAL', idestados, L.titulo 'titulo' ";
	$sqlQuery .= " FROM `liquidaciones` L";
	$sqlQuery .= " LEFT JOIN operadoresturisticos DATA ON L.responsable = DATA.idoperadoresturisticos ";
	$sqlQuery .= " WHERE 1 ";
	$sqlQuery .= " AND idliquidaciones = ".$_SESSION["idliquidaciones"];
	$resultadoStringSQL = resultFromQuery($sqlQuery);		
	if ($row = siguienteResult($resultadoStringSQL)){
		$idliquidaciones = $row->idliquidaciones;
		$fecha = $row->Fecha;
		$nombre = $row->Responsable;
		$importeTotal = $row->USDTOTAL;
		$idestados = $row->idestados;
		$titulo = $row->titulo;
	}
}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="mediapension.liquidaciones.php" title="Media pension" class="tip-bottom">Liquidaciones</a>
		<a href="#" class="current">Edit...</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Cambiar estadado - Liquidaciones</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="LiquidacionesCambiarEstado" />
						<input type="hidden" id="idliquidaciones" name="idliquidaciones" value="<?php echo $idliquidaciones;?>" />
						<div class="control-group">
							<label class="control-label">Responsable</label>
							<div class="controls">
								<?php echo $nombre;?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Fecha</label>
							<div class="controls">
								<?php echo $fecha;?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">USD</label>
							<div class="controls">
								<?php echo $importeTotal;?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Nombre de Liquidacion</label>
							<div class="controls">
								<input id="titulo" name="titulo" type="text" class="span11" placeholder="titulo" value="<?php echo $titulo;?>" required="true" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Estado</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idestados, nombre FROM liquidaciones_estados ";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idestados', $resultado, $idestados, '', '', 'true');
								?>								
							</div>
						</div>
						<div class="control-group">
							<button class="btn btn-success" type="submit">Modificar</button>
						</div>
						<div id="status"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
