<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> 
			<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
			<a href="liquidaciones.php" title="Liquidaciones" class="tip-bottom">Liquidaciones</a>
			<a href="#" class="current">Liquidação Mensual</a>
		</div>
	</div>
<!--End-breadcrumbs-->
<?php 

if (isset($_GET['mes'])){
	$_SESSION["visualizarMes"] = $_GET['mes'];
}
if (isset($_GET['ano'])){
	$_SESSION["visualizarAno"] = $_GET['ano'];
}

?>
	<br/>
	<div class="row-fluid">
		<div class="container-fluid">
			<h4>Proceso mensual</h4>
			<div class="widget-box">
				<div class="widget-content nopadding">
					<form id="formBuscar" name="formBuscar" method="get">
						<input type="hidden" id="accion" name="accion" value="liquidacion" />
						<div class="control-group span1">
							Mes
							<select id="mes" name="mes">
								<?php for ($i=1; $i<=12; $i++){?>
								<option value="<?php echo $i;?>" <?php if($i==$_SESSION["visualizarMes"]){echo 'selected';}?>><?php echo $i;?></option>
								<?php }?>
							</select>
						</div>
						<div class="control-group span2">
							Ano
							<select id="ano" name="ano">
								<?php for ($i=2013; $i<=2016; $i++){?>
								<option value="<?php echo $i;?>" <?php if($i==$_SESSION["visualizarAno"]){echo 'selected';}?>><?php echo $i;?></option>
								<?php }?>
							</select>
						</div>
						<div class="control-group span2"><br>
							<button class="btn btn-success" type="submit">Ejecutar proceso.</button>
						</div>
					</form>				
				</div>
			</div>
			<div class="widget-box">
				Se realizara una liquidacion para cada responsable existente en las mediapensiones del mes seleccionado.
				<div class="widget-content nopadding">
					<div class="control-group span11">
					</div>
				</div>
			</div>
		</div>
	</div>
<?php 
if ($_GET['accion'] == 'liquidacion'){
?>
	<div class="row-fluid">
		<div class="container-fluid">
			<h4>Resumen de responsables encontrados:</h4>
<?php
	$sql = " SELECT idresponsablesDePago, nombre, tabla FROM responsablesDePago ";
	$resultadoResponsables= resultFromQuery($sql);	

	while ($rowLine = siguienteResult($resultadoResponsables)) {	
		$tabla = $rowLine->tabla;
		$nombre = $rowLine->nombre;
		$idresponsablesDePago = $rowLine->idresponsablesDePago;

// CONSULTO HOTELERIA Y MEDIAPENSION PARA HACER UN DISTINCT DE idoperadoresturisticos
				$sqlResponsable = ' ( ';
				$sqlResponsable .= ' select MP.id'.$tabla.' ID, O.nombre from mediapension MP  ';
				$sqlResponsable .= ' left join '.$tabla.' O  ';
				$sqlResponsable .= ' on MP.id'.$tabla.' = O.id'.$tabla.' ';
				$sqlResponsable .= ' WHERE 1 ';
				$sqlResponsable .= ' AND idresponsablesDePago = '.$idresponsablesDePago.' ';
				$sqlResponsable .= ' AND idliquidaciones = 0 ';
				$sqlResponsable .= ' AND habilitado = 1 ';
				//$sqlResponsable .= " AND ".$_SESSION["visualizarMes"]." BETWEEN MONTH(MP.DataIN) AND MONTH(MP.DataOUT)";
				$sqlResponsable .= " AND MONTH(MP.DataIN) <= ".$_SESSION["visualizarMes"]." ";
				$sqlResponsable .= ' )  ';
				$sqlResponsable .= ' UNION  ';
				$sqlResponsable .= ' ( ';
				$sqlResponsable .= ' select HTL.id'.$tabla.' ID, O.nombre  ';
				$sqlResponsable .= ' from hoteleria HTL  ';
				$sqlResponsable .= ' left join '.$tabla.' O  ';
				$sqlResponsable .= ' on HTL.id'.$tabla.' = O.id'.$tabla.' ';
				$sqlResponsable .= ' WHERE 1 ';
				$sqlResponsable .= ' AND idresponsablesDePago = '.$idresponsablesDePago.' ';
				$sqlResponsable .= ' AND idliquidaciones = 0 ';
				$sqlResponsable .= ' AND habilitado = 1 ';
				//$sqlResponsable .= " AND ".$_SESSION["visualizarMes"]." BETWEEN MONTH(HTL.DataIN) AND MONTH(HTL.DataOUT)";
				$sqlResponsable .= " AND MONTH(HTL.DataIN) <= ".$_SESSION["visualizarMes"]." ";
				$sqlResponsable .= ' )  ';
				$sqlResponsable .= ' ORDER BY ID; ';
				
//				echo $sqlResponsable;
/*		
				$sqlResponsable = ' SELECT DISTINCT(MP.idoperadoresturisticos), O.nombre ';
				$sqlResponsable .= ' FROM mediapension MP ';
				$sqlResponsable .= ' LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ';
				$sqlResponsable .= ' WHERE 1 ';
				$sqlResponsable .= ' AND idresponsablesDePago = 1 ';
				$sqlResponsable .= ' AND idliquidaciones = 0 ';
				$sqlResponsable .= " AND ".$_SESSION["visualizarMes"]." BETWEEN MONTH(MP.DataIN) AND MONTH(MP.DataOUT)";
				echo $sqlResponsable;
*/
				if ($resultadoResponsable = resultFromQuery($sqlResponsable)){	
					$titulo = $nombre;
?>
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
					<h5><?php echo $titulo; ?></h5><br>
				</div>
				<div class="widget-content nopadding">
					<table>
<?php
					while ($rowLineResponsable = siguienteResult($resultadoResponsable)) {	
?>
						<tr>
							<td width="200"><?php echo $rowLineResponsable->nombre; ?></td>
							<td><a href="liquidaciones.mensual.1.php?idresponsablesDePago=<?php echo $idresponsablesDePago;?>&id=<?php echo $rowLineResponsable->ID;?>">Ver liquidacion</a></td>
							<td></td>
						</tr>
<?php

					}
				}
?>
					</table>
				</div>
			</div>
<?php
	}
?>
		</div>
	</div>
<?php 
}
?>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
