<?php 
include "head.php"; 

if (isset($_SESSION['idposadas'])){
	$idposadas = $_SESSION['idposadas'];
	$current = 'Edit';
	$sqlQuery = "SELECT * ";
	$sqlQuery .= " FROM `posadas`  ";
	$sqlQuery .= " WHERE 1 ";
	$sqlQuery .= " AND idposadas = ".$idposadas;
	$resultadoStringSQL = resultFromQuery($sqlQuery);
	if ($row = siguienteResult($resultadoStringSQL)){
		$idposadas = $row->idposadas;
		$nombre = $row->nombre;
		$telefono = $row->telefono;
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
		<a href="administradores.posadas.php" title="Posadas" class="tip-bottom">Posadas</a>
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
					<h5>Edit Posada</h5>
				</div>
				<div class="widget-content nopadding">
					<input type="hidden" id="accion" name="accion" value="admitirPosadas" />
					<input type="hidden" id="idposadas" name="idposadas" value="<?php echo $idposadas;?>" />
					<div class="control-group">
						<label class="control-label">Nombre</label>
						<div class="controls">
							<input id="nombre" name="nombre" type="text" class="span11" placeholder="nombre" required="true" value="<?php echo $nombre;?>"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Telefono</label>
						<div class="controls">
							<input id="telefono" name="telefono" type="text" class="span11" placeholder="telefono" value="<?php echo $telefono;?>"/>
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
