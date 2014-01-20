<?php 
include_once "head.php"; 

if (isset($_GET['mes'])){
	$_SESSION["visualizarMes"] = $_GET['mes'];
}
if (isset($_GET['ano'])){
	$_SESSION["visualizarAno"] = $_GET['ano'];
}

//ComboMes
$comboMes = isset($_SESSION["visualizarMes"]) ? comboDate('month',  $_SESSION["visualizarMes"]) : comboDate('month');

//ComboYear
$comboYear = isset($_SESSION["visualizarAno"]) ? comboDate('year',  $_SESSION["visualizarAno"]) : comboDate('year');

//Function comboDate
function comboDate($selection, $selected=false){
	$sqlQuery = 'SELECT '.$selection.'(min(data)) min, '.$selection.'(max(data)) max ';
	$sqlQuery .= 'FROM mediapension ';
	$sqlQuery .= 'WHERE habilitado = 1 ';
	$sqlQuery .= 'AND idliquidaciones = 0;';
	
	$result = resultFromQuery($sqlQuery);

	while ($row = siguienteResult($result)) {	
			$min = $row->min;
			$max = $row->max;
	}

	$combo = '';
	if ($min > $max){
		for ($i=$min; $i<=12; $i++){
			
			$combo .= '<option value="'.$i.'" ';
			$combo .= isset($selected) && $i==$selected ? 'selected' : '';
			$combo .= '>'.$i.'</option>';
			$combo .= "\r\n";
		}
		
		for ($i=1; $i<=$max; $i++){
			
			$combo .= '<option value="'.$i.'" ';
			$combo .= isset($selected) && $i==$selected ? 'selected' : '';
			$combo .= '>'.$i.'</option>';
			$combo .= "\r\n";
			
		}
		
		
	}
	else{
		
		for ($i=$min; $i<=$max; $i++){
			
			$combo .= '<option value="'.$i.'" ';
			$combo .= isset($selected) && $i==$selected ? 'selected' : '';
			$combo .= '>'.$i.'</option>';
			$combo .= "\r\n";
			
		}
	}
	return $combo;
}

/////////////////////////////////////////////////////////////////////

if (isset($_GET['accion']) && $_GET['accion'] == 'liquidacion'){
	
	$resumen = '<div class="row-fluid">';
	$resumen .= "\n\t".'<div class="container-fluid">';
	$resumen .= "\n\t\t".'<h4>Resumo de responsávels encontrados:</h4>';
			
	$sql = "SELECT idresponsablesDePago, nombre, tabla FROM responsablesDePago ";
	$resultadoResponsables= resultFromQuery($sql);	

	while ($rowLine = siguienteResult($resultadoResponsables)) {
				
		$tabla = $rowLine->tabla;
		$nombre = $rowLine->nombre;
		$idresponsablesDePago = $rowLine->idresponsablesDePago;

		// CONSULTO HOTELERIA Y MEDIAPENSION PARA HACER UN DISTINCT DE idoperadoresturisticos
		$sqlResponsable = '(';
		$sqlResponsable .= 'SELECT MP.id'.$tabla.' ID, O.nombre ';
		$sqlResponsable .= 'FROM mediapension MP  ';
		$sqlResponsable .= 'LEFT JOIN '.$tabla.' O ON MP.id'.$tabla.' = O.id'.$tabla.' ';
		$sqlResponsable .= 'WHERE 1 ';
		$sqlResponsable .= 'AND idresponsablesDePago = '.$idresponsablesDePago.' ';
		$sqlResponsable .= 'AND idliquidaciones = 0 ';
		$sqlResponsable .= 'AND habilitado = 1 ';
		$sqlResponsable .= 'AND MONTH(MP.DataIN) = '.$_SESSION["visualizarMes"].' ';
		$sqlResponsable .= 'AND YEAR(MP.DataIN) = '.$_SESSION["visualizarAno"].') ';
		$sqlResponsable .= 'UNION  ';
		$sqlResponsable .= '(';
		$sqlResponsable .= 'SELECT HTL.id'.$tabla.' ID, O.nombre ';
		$sqlResponsable .= 'FROM hoteleria HTL ';
		$sqlResponsable .= 'LEFT JOIN '.$tabla.' O ON HTL.id'.$tabla.' = O.id'.$tabla.' ';
		$sqlResponsable .= 'WHERE 1 ';
		$sqlResponsable .= 'AND idresponsablesDePago = '.$idresponsablesDePago.' ';
		$sqlResponsable .= 'AND idliquidaciones = 0 ';
		$sqlResponsable .= 'AND habilitado = 1 ';
		$sqlResponsable .= 'AND MONTH(HTL.DataIN) = '.$_SESSION["visualizarMes"].' ';
		$sqlResponsable .= 'AND YEAR(HTL.DataIN) = '.$_SESSION["visualizarAno"].') ';
		$sqlResponsable .= 'ORDER BY nombre;';
				
//		echo $sqlResponsable;
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
			
			$titulo = $nombre; // Operador - Posada - Agencia - Venta por Balcon
			
			$resumen .= "\n\t\t".'<div class="widget-box">';
			$resumen .= "\n\t\t\t".'<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>';
			$resumen .= "\n\t\t\t\t".'<h5>'.$titulo.'</h5><br>';
			$resumen .= "\n\t\t\t".'</div>';

			while ($rowLineResponsable = siguienteResult($resultadoResponsable)) {
				
				$resumen .= "\n\t\t\t".'<div class="widget-content nopadding">';
				$resumen .= "\n\t\t\t\t".'<table>';
				$resumen .= "\n\t\t\t\t\t".'<tr>';
				$resumen .= "\n\t\t\t\t\t\t".'<td width="200">'.$rowLineResponsable->nombre.'</td>';
				$resumen .= "\n\t\t\t\t\t\t".'<td><a href="liquidaciones.mensual.1.php?idresponsablesDePago='.$idresponsablesDePago.'&id='.$rowLineResponsable->ID.'">Ver liquidacion</a></td>';
				$resumen .= "\n\t\t\t\t\t".'</tr>';
				$resumen .= "\n\t\t\t\t".'</table>';
				$resumen .= "\n\t\t\t".'</div>';
			}
			
			$resumen .= "\n\t\t".'</div>';
		}
	}
	
	$resumen .= "\n\t".'</div>';
	$resumen .= "\n".'</div>';
}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> 
			<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
			<a href="liquidaciones.php" title="Liquidaciones" class="tip-bottom">Liquidações</a>
			<a href="#" class="current">Liquidação Mensal</a>
		</div>
	</div>
<!--End-breadcrumbs-->
	<br/>
	<div class="row-fluid">
		<div class="container-fluid">
			<h4>Liquidação Mensal</h4>
			<div class="widget-box">
				<div class="widget-content nopadding">
					<form id="formBuscar" name="formBuscar" method="get">
						<input type="hidden" id="accion" name="accion" value="liquidacion" />
						<div class="control-group span1">
							Mes
							<select id="mes" name="mes">
								<?php echo $comboMes; ?>
							</select>
						</div>
						<div class="control-group span2">
							Ano
							<select id="ano" name="ano">
								<?php echo $comboYear; ?>
							</select>
						</div>
						<div class="control-group span2"><br>
							<button class="btn btn-success" type="submit">Executar processo.</button>
						</div>
					</form>				
				</div>
			</div>
			<div class="widget-box">
				Uma liquidação será feita para cada um dos responsávels existentes no mês selecionado.
				<div class="widget-content nopadding">
					<div class="control-group span11">
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo isset($resumen) ? $resumen : '';?>

</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
