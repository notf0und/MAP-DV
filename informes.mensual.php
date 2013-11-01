<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> 
			<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
			<a href="informes.php" title="Liquidaciones" class="tip-bottom">Informes</a>
			<a href="#" class="current">Informe Mensual</a>
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
				<div class="widget-content nopadding">
					<div class="control-group span11">
					</div>
				</div>
			</div>
		</div>
	</div>
<?php 
if ($_GET['accion'] == 'liquidacion'){
	$numero = cal_days_in_month(CAL_GREGORIAN, $_SESSION["visualizarMes"], $_SESSION["visualizarAno"]); 
	$dataIN = $_SESSION["visualizarAno"]."-".$_SESSION["visualizarMes"]."-01";
	$dataOUT = $_SESSION["visualizarAno"]."-".$_SESSION["visualizarMes"]."-".$numero;
?>
	<div class="row-fluid">
		<div class="container-fluid">
			<h4>Vouchers</h4>
			
<?php
	$sql = " SELECT idresponsablesDePago, nombre, tabla FROM responsablesDePago ";
	$resultadoResponsables= resultFromQuery($sql);	

	while ($rowLine = siguienteResult($resultadoResponsables)) {	
		$tabla = $rowLine->tabla;
		$nombre = $rowLine->nombre;
		$idresponsablesDePago = $rowLine->idresponsablesDePago;

// CONSULTO HOTELERIA Y MEDIAPENSION PARA HACER UN DISTINCT DE idoperadoresturisticos
				$sqlResponsable = ' ( ';
				$sqlResponsable .= ' select MP.id'.$tabla.' ID, MP.idresponsablesDePago idresponsablesDePago, O.nombre from mediapension MP  ';
				$sqlResponsable .= ' left join '.$tabla.' O  ';
				$sqlResponsable .= ' on MP.id'.$tabla.' = O.id'.$tabla.' ';
				$sqlResponsable .= ' WHERE 1 ';
				$sqlResponsable .= ' AND idresponsablesDePago = '.$idresponsablesDePago.' ';
				$sqlResponsable .= ' AND idliquidaciones = 0 ';
				$sqlResponsable .= " AND ".$_SESSION["visualizarMes"]." BETWEEN MONTH(MP.DataIN) AND MONTH(MP.DataOUT)";
				$sqlResponsable .= ' )  ';
				$sqlResponsable .= ' UNION  ';
				$sqlResponsable .= ' ( ';
				$sqlResponsable .= ' select HTL.id'.$tabla.' ID, HTL.idresponsablesDePago idresponsablesDePago, O.nombre  ';
				$sqlResponsable .= ' from hoteleria HTL  ';
				$sqlResponsable .= ' left join '.$tabla.' O  ';
				$sqlResponsable .= ' on HTL.id'.$tabla.' = O.id'.$tabla.' ';
				$sqlResponsable .= ' WHERE 1 ';
				$sqlResponsable .= ' AND idresponsablesDePago = '.$idresponsablesDePago.' ';
				$sqlResponsable .= ' AND idliquidaciones = 0 ';
				$sqlResponsable .= " AND ".$_SESSION["visualizarMes"]." BETWEEN MONTH(HTL.DataIN) AND MONTH(HTL.DataOUT)";
				$sqlResponsable .= ' )  ';
				$sqlResponsable .= ' ORDER BY ID; ';
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
					<TABLE class="table table-bordered data-table">
<?php
					while ($rowLineResponsable = siguienteResult($resultadoResponsable)) {	
						$id = $rowLineResponsable->ID;
						$idresponsablesDePago = $rowLineResponsable->idresponsablesDePago;
						liquidacionServicios($idresponsablesDePago, $id, $dataIN, $dataOUT);	


						//$sql = "SELECT SUM(`USD`) total FROM `_temp_liquidaciones_mp` WHERE 1";
						$sql = "select (SELECT SUM(`USD`) total FROM `_temp_liquidaciones_mp` WHERE 1) MP, (SELECT SUM(`USD`) total FROM `_temp_liquidaciones_htl` WHERE 1) HTL";
						$resultadoTotal = resultFromQuery($sql);
						$rowTotal = siguienteResult($resultadoTotal);
						$MP = $rowTotal->MP;
						$HTL = $rowTotal->HTL;
						$total = $MP + $HTL;
?>
						<tr>
							<td><?php echo $rowLineResponsable->nombre; ?></td>
							<td>MP: <?php echo $MP; ?></td>
							<td>HTL: <?php echo $HTL; ?></td>
							<td>Total: <?php echo $total; ?></td>
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
