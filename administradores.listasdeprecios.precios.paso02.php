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
		<a href="#" class="current">Preços | Paso 2 de 2</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span6">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
						<h5>Preços</h5>
					</div>
					<div class="widget-content nopadding">
						<form action="posts.php" id="form-wizard" name="form-wizard" class="form-horizontal" method="post">
						<div id="form-wizard-1" class="step">
							<h4>&nbsp;&nbsp;- Meia-pensão - </h4>
							<input type="hidden" id="accion" name="accion" value="admitirPrecios02" />
							<input type="hidden" id="idlistasdeprecios" name="idlistasdeprecios" value="<?php echo $idlistasdeprecios;?>" />
							<input type="hidden" id="iditem" name="iditem" value="<?php echo $iditem;?>" />

							<?php
							$sqlQueryServicios = " SELECT * FROM servicios WHERE idservicios > 0 and idservicios < 6";
							$resultadoServicios = mysql_query ($sqlQueryServicios);
							while ($lineaServicios=mysql_fetch_row($resultadoServicios)) {
								$precio = precioListasdeprecios('0_'.$lineaServicios[0], $idlistasdeprecios);
							?>
								<div class="control-group">
									<label class="control-label"><?php echo $lineaServicios[1] ?></label>
									<div class="controls">
										<input type="text" class="span6" placeholder="0.00" id="0_<?php echo $lineaServicios[0] ?>" name="0_<?php echo $lineaServicios[0] ?>" value="<?php echo $precio;?>" />
									</div>
								</div>
							<?php
							}
							?> 
						</div>
							<?php
							$i = 1;
							if ($idresponsablesDePago==1){
							$sqlQueryPosadas = " SELECT * FROM posadas WHERE idposadas > 0 and idposadas < 5";
							$resultadoPosadas = mysql_query ($sqlQueryPosadas);
							while ($lineaPosadas=mysql_fetch_row($resultadoPosadas)) {
								$i++;
							?>
						<div id="form-wizard-<?php echo $i;?>" class="step">
							<h4>&nbsp;&nbsp;- <?php echo $lineaPosadas[1] ?> - </h4>
							<?php
							$sqlQueryServicios = " SELECT * FROM servicios WHERE idservicios > 5";
							$resultadoServicios = mysql_query ($sqlQueryServicios);
							while ($lineaServicios=mysql_fetch_row($resultadoServicios)) {
								$precio = precioListasdeprecios($lineaPosadas[0].'_'.$lineaServicios[0], $idlistasdeprecios);
								
								$precios[$lineaServicios[1]] = $precio;
								
								if (strpos($lineaServicios[1], " C/Bebida")){
									
									$reference = rtrim($lineaServicios[1], " C/Bebida");
									
									$unit = 2;
									
									
									if (strpos($reference, "FAP")){						
										$unit *= 2;
									}
									
									if (strpos($reference, "DBL ") === 0){
										$unit *= 2;
									}
									elseif (strpos($reference, "TPL") === 0){
										$unit *= 3;
									}
									elseif (strpos($reference, "CPL") === 0){
										$unit *= 4;
									}
									
									
									$precio = $precios[$reference] + $unit;
									
									
									//var_dump($precios);
								}
								//echo $lineaPosadas[0].'_'.$lineaServicios[0].'!!!'. $idlistasdeprecios
							?>
								<div class="control-group">
									<label class="control-label"><?php echo $lineaServicios[1] ?></label>
									<div class="controls">
										<input type="text" class="span6" placeholder="0.00" id="<?php echo $lineaPosadas[0].'_'.$lineaServicios[0] ?>" name="<?php echo $lineaPosadas[0].'_'.$lineaServicios[0] ?>" value="<?php echo $precio;?>" />
									</div>
								</div>
							<?php
							}
							?> 
						</div>

						<?php
						}
						}
						?> 
						<div class="form-actions">
							<input id="back" class="btn btn-primary" type="reset" value="Back" />
							<input id="next" class="btn btn-primary" type="submit" value="Next" />
							<div id="status"></div>
						</div>
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
