<?php 
include "head.php"; 

if (isset($_GET['id'])){
	$idlistasdeprecios = $_GET['id'];
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
		
		// Selecciono tabla del resposable de Pago
		
		$sql = " SELECT idresponsablesDePago, nombre, tabla, plural ";
		$sql .= " FROM responsablesDePago "; 
		$sql .= " WHERE 1 "; 
		$sql .= " AND idresponsablesDePago = ".$idresponsablesDePago;
		$resultadoResponsables= resultFromQuery($sql);	

		if ($rowLine = siguienteResult($resultadoResponsables)) {
			$tabla = $rowLine->tabla;
			$nombreResponsable = $rowLine->nombre;
			$plural = $rowLine->plural;
		}				

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
		<a href="#" class="current">Precios | Paso 1 de 2</a>
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
					<input type="hidden" id="accion" name="accion" value="admitirPrecios01" />
					<input type="hidden" id="idlistasdeprecios" name="idlistasdeprecios" value="<?php echo $idlistasdeprecios;?>" />
					<div class="control-group">
						<label class="control-label">Responsable</label>
						<div class="controls">
							<select name="iditem" id="iditem">
							<?php
							$sqlQuery = " SELECT id".$tabla.", nombre FROM ".$tabla." where id".$tabla." > 0";
							$resultado = mysql_query ($sqlQuery);
							echo ("<option value=0>Todos/as los/as ".$plural."</option>\n");
							while ($linea=mysql_fetch_row($resultado)) {
							echo ("<option value=".$linea[0]);
							if ($linea[0]==$iditem){
								echo (" selected");
							};
							echo (">".$linea[1]."</option>\n");
							}
							?> 
							</select>							
						</div>
					</div>
					<div id="status"></div>
				</div>
			</div>
			<div class="class="form-actions">
				<button class="btn btn-success" type="submit">Paso 2 ></button>
			</div>
			</form>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
