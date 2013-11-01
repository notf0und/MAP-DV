<?php 
include "head.php"; 

if (isset($_SESSION['idlistasdeprecios'])){
	$idlistasdeprecios = $_SESSION['idlistasdeprecios'];
	$current = 'Edit';
	$sqlQuery = "SELECT * ";
	$sqlQuery .= " FROM `listasdeprecios`  ";
	$sqlQuery .= " WHERE 1 ";
	$sqlQuery .= " AND idlistasdeprecios = ".$idlistasdeprecios;
	$resultadoStringSQL = resultFromQuery($sqlQuery);
	if ($row = siguienteResult($resultadoStringSQL)){
		$idlistasdeprecios = $row->idlistasdeprecios;
		$nombre = $row->nombre;
		$VigenciaIN = $row->VigenciaIN;
		$VigenciaOUT = $row->VigenciaOUT;
		$idresponsablesDePago = $row->idresponsablesDePago;
		$iditem = $row->iditem;
	}
}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="administradores.php" title="Administradores" class="tip-bottom">Administradores</a>
		<a href="administradores.listasdeprecios.php" title="Listas de precios" class="tip-bottom">Listas de precios</a>
		<a href="#" class="current">Edit...</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Edit Lista de precios</h5>
				</div>
				<div class="widget-content nopadding">
					<input type="hidden" id="accion" name="accion" value="admitirListasdeprecios" />
					<input type="hidden" id="idlistasdeprecios" name="idlistasdeprecios" value="<?php echo $idlistasdeprecios;?>" />
					<div class="control-group">
						<label class="control-label">Nombre</label>
						<div class="controls">
							<input id="nombre" name="nombre" type="text" class="span11" placeholder="nombre" required="true" value="<?php echo $nombre;?>"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Vigencia IN</label>
						<div class="controls">
							<div data-date="" class="input-append date datepicker">
								<input id="VigenciaIN" name="VigenciaIN" type="text" class="span11" required="true" value="<?php echo $VigenciaIN;?>" />
								<span class="add-on"><i class="icon-th"></i></span> 
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Vigencia OUT</label>
						<div class="controls">
							<div  data-date="" class="input-append date datepicker">
								<input id="VigenciaOUT" name="VigenciaOUT" type="text" class="span11"  required="true" value="<?php echo $VigenciaOUT;?>" />
								<span class="add-on"><i class="icon-th"></i></span> 
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Responsables</label>
						<div class="controls">
							<?php
								$sqlQuery = " SELECT idresponsablesDePago, plural FROM responsablesDePago";
								$resultado = resultFromQuery($sqlQuery);
								echo comboFromArray('idresponsablesDePago', $resultado, $idresponsablesDePago, '', '');
							?>								
						</div>
					</div>
					<div id="status"></div>
				</div>
			</div>
			<div class="class="form-actions">
				<button class="btn btn-success" type="submit">Modificar</button>
			</div>
			</form>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
