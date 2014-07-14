<?php 
include_once "head.php"; 

//desde-ate
if (isset($_POST['desde']) || isset($_POST['ate'])){
	
	$_SESSION['desde'] = (isset($_POST['desde']) && $_POST['desde'] != '') ? dateFormatMySQL($_POST['desde']) : date('Y-m-01');
	$_SESSION['ate'] = (isset($_POST['ate']) && $_POST['ate'] != '') ? dateFormatMySQL($_POST['ate']) : date('Y-m-t');
	
	$title = $_SESSION['desde'].' / '.$_SESSION['ate'];	
	
}
else{
	$_SESSION['desde'] = date('Y-m-01');
	$_SESSION['ate'] = date('Y-m-t');
	
	$title = "Ultimo mes";
	
	
}

$sqlcondition = "AND (MP.dataIN BETWEEN ";
$sqlcondition .= "'".$_SESSION['desde']."' ";
$sqlcondition .= "AND '".$_SESSION["ate"]."') ";


if (isset($_POST['accion']) && $_POST['accion'] == 'liquidacion'){
	
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
		$sqlResponsable = 'SELECT MP.id'.$tabla.' ID, O.nombre ';
		$sqlResponsable .= 'FROM mediapension MP  ';
		$sqlResponsable .= 'LEFT JOIN '.$tabla.' O ON MP.id'.$tabla.' = O.id'.$tabla.' ';
		$sqlResponsable .= 'WHERE 1 ';
		$sqlResponsable .= 'AND idresponsablesDePago = '.$idresponsablesDePago.' ';
		$sqlResponsable .= 'AND idliquidaciones = 0 ';
		$sqlResponsable .= 'AND MP.habilitado = 1 ';
		//condition
		if (isset($_POST['desde']) || isset($_POST['ate'])){
	
			$title = (isset($_POST['desde']) && $_POST['desde'] != '') ? $_POST['desde'] : date('Y-m-01');
			$title .= (isset($_POST['ate']) && $_POST['ate'] != '') ? ' / '.$_POST['ate'] : date('/Y-m-t');
			
			$sqlcondition = "AND (MP.dataIN BETWEEN ";
			$sqlcondition .= (isset($_POST['desde']) && $_POST['desde'] != '') ? "'".dateFormatMySQL($_POST['desde'])."' " : "DATE_FORMAT(NOW() ,'%Y-%m-01')";//* AND MP.dataIN >= '".dateFormatMySQL($_POST['desde'])."' " : '';
			$sqlcondition .= (isset($_POST['ate']) && $_POST['ate'] != '') ? "AND '".dateFormatMySQL($_POST['ate'])."') " : "AND DATE_FORMAT(NOW() ,'%Y-%m-".date('t')."')) ";

		}
		else{
			$title = "Ultimo mes";

			$sqlcondition = "AND MONTH(MP.dataIN) = MONTH(curdate()) ";
			$sqlcondition = "AND YEAR(MP.dataIN) = YEAR(curdate()) ";
		}
	
		$sqlResponsable .= $sqlcondition;

		$sqlResponsable .= 'UNION  ';
		$sqlResponsable .= 'SELECT HTL.id'.$tabla.' ID, O.nombre ';
		$sqlResponsable .= 'FROM hoteleria HTL ';
		$sqlResponsable .= 'LEFT JOIN '.$tabla.' O ON HTL.id'.$tabla.' = O.id'.$tabla.' ';
		$sqlResponsable .= 'WHERE 1 ';
		$sqlResponsable .= 'AND idresponsablesDePago = '.$idresponsablesDePago.' ';
		$sqlResponsable .= 'AND idliquidaciones = 0 ';
		$sqlResponsable .= 'AND HTL.habilitado = 1 ';
		
		//condition
		if (isset($_POST['desde']) || isset($_POST['ate'])){
	
			$title = (isset($_POST['desde']) && $_POST['desde'] != '') ? $_POST['desde'] : date('Y-m-01');
			$title .= (isset($_POST['ate']) && $_POST['ate'] != '') ? ' / '.$_POST['ate'] : date('/Y-m-t');
			
			$sqlcondition = "AND (HTL.dataIN BETWEEN ";
			$sqlcondition .= (isset($_POST['desde']) && $_POST['desde'] != '') ? "'".dateFormatMySQL($_POST['desde'])."' " : "DATE_FORMAT(NOW() ,'%Y-%m-01')";//* AND MP.dataIN >= '".dateFormatMySQL($_POST['desde'])."' " : '';
			$sqlcondition .= (isset($_POST['ate']) && $_POST['ate'] != '') ? "AND '".dateFormatMySQL($_POST['ate'])."') " : "AND DATE_FORMAT(NOW() ,'%Y-%m-".date('t')."')) ";

		}
		else{
			$title = "Ultimo mes";

			$sqlcondition = "AND MONTH(HTL.dataIN) = MONTH(curdate()) ";
			$sqlcondition = "AND YEAR(HTL.dataIN) = YEAR(curdate()) ";
		}
		
		$sqlResponsable .= $sqlcondition;

		$sqlResponsable .= 'ORDER BY nombre;';
		
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
			
			<!--Data de pesquisa-->
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Data de pesquisa: <?php echo isset($title) ? $title : ''; ?></h5>
				</div>
				<div class="widget-content nopadding">
					<form method="post" id="formBuscar" name="formBuscar" class="form-horizontal">
						
						<input type="hidden" id="accion" name="accion" value="liquidacion" />
						
						<div class="control-group">
							<label class="control-label">Desde: </label>
							<div data-date="" class="input-append date datepicker">
								<input id="desde" name="desde" type="text" value="<?php echo (isset($_POST['desde']) && $_POST['desde'] != '') ? $_POST['desde'] : ''; ?>">
								<span class="add-on"><i class="icon-th"></i></span>
							</div>
						</div>
							  
						<div class="control-group">
							<label class="control-label">Até: </label>
							<div data-date="" class="input-append date datepicker">
								<input id="ate" name="ate" type="text" value="<?php echo (isset($_POST['ate']) && $_POST['ate'] != '') ? $_POST['ate'] : ''; ?>">
								<span class="add-on"><i class="icon-th"></i></span>
							</div>
						</div>

						<div class="form-actions" align="left">
							 <button type="submit" class="btn btn-success">Executar processo.</button>
						</div>
							  
					</form>
				</div>
			</div>
			
			<!--
			<div class="widget-box">
				<div class="widget-content nopadding">
					<form id="formBuscar" name="formBuscar" method="get">
						<input type="hidden" id="accion" name="accion" value="liquidacion" />
						<div class="control-group span1">
							Mes
							<select id="mes" name="mes">
								<?php //echo $comboMes; ?>
							</select>
						</div>
						<div class="control-group span2">
							Ano
							<select id="ano" name="ano">
								<?php //echo $comboYear; ?>
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
			-->
		</div>
		
	</div>
	<?php echo isset($resumen) ? $resumen : '';?>

</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
