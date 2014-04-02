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
		<a href="administradores.listasdeprecios.php" title="Listas de precios" class="tip-bottom">Listas de preços</a>
		<a href="#" class="current">Preços | Paso 1.1 de 2</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Editar listas de preços</h5>
				</div>
				<div class="widget-content nopadding">
					<input type="hidden" id="accion" name="accion" value="admitirPrecios01-1" />
					<input type="hidden" id="idlistasdeprecios" name="idlistasdeprecios" value="<?php echo $idlistasdeprecios;?>" />
					<div class="control-group">
						<label class="control-label">Responsável</label>
						<div class="controls">
							<select name="iditem[]" id="iditem" multiple>
								
								
								
							<?php
							// Carga lista de los operadores, posadas, agencias o balcon
							$sqlQuery = " SELECT id".$tabla.", nombre FROM ".$tabla." where id".$tabla." > 0";
							$resultado = mysql_query ($sqlQuery);
							
							//Carga la lista de los elementos seleccionados
							$sql = 'SELECT idelement FROM  grupos_precios WHERE idlistasdeprecios = '.$idlistasdeprecios; 
							$rs = mysql_query ($sql);
							
							for ($i=0; $i < mysql_num_rows($rs); $i++){
								$l[] = mysql_fetch_row($rs);
							}

							while ($linea=mysql_fetch_row($resultado)) {
								$selected = 0;
								
								foreach($l as $v) {
									if ($v[0] == $linea[0]){
										$selected = 1;
									}
									elseif ($selected != 1){
										$selected = 0;
									}
								}
								
								if ($selected){
									echo ("<option value=".$linea[0].' selected');
								}
								else{
									echo ("<option value=".$linea[0]);
								}
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
