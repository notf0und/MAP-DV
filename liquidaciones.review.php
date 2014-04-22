<?php include "head.php"; 

//desde-ate
$desde =  isset($_SESSION['desde']) ? $_SESSION['desde'] : date('Y-m-01');
$ate = isset($_SESSION['ate']) ? $_SESSION['ate'] : date('Y-m-t');

$dataIN = $desde;
$dataOUT = $ate;
$idliquidaciones = $_GET['idliquidaciones'];

//desde-ate

//cantidad de dias en el mes del año especificado 
//$numero = cal_days_in_month(CAL_GREGORIAN, $_SESSION["visualizarMes"], $_SESSION["visualizarAno"]);

//$dataIN = $_SESSION["visualizarAno"]."-".$_SESSION["visualizarMes"]."-01";
//$dataOUT = $_SESSION["visualizarAno"]."-".$_SESSION["visualizarMes"]."-".$numero;
$sql = " SELECT L.idresponsablesDePago, L.responsable, RDP.tabla, RDP.nombre ";
$sql .= "FROM liquidaciones L ";

$sql .= "LEFT JOIN responsablesDePago RDP ";
$sql .= "ON L.idresponsablesDePago = RDP.idresponsablesDePago ";

$sql .= "WHERE idliquidaciones = ".$idliquidaciones;

$resultadoResponsables= resultFromQuery($sql);	

if ($rowLine = siguienteResult($resultadoResponsables)) {	
	$tabla = $rowLine->tabla;
	$nombre = $rowLine->nombre;
	$idresponsablesDePago = $rowLine->idresponsablesDePago;
	$id = $rowLine->responsable;
}

liquidacionServiciosReview($idresponsablesDePago, $id, $idliquidaciones);	

$sql = "SELECT * FROM `".$tabla."` WHERE `id".$tabla."` = ".$id;
$resultado = resultFromQuery($sql);
$row = siguienteResult($resultado);
$nomres = $row->nombre;
?>	
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="liquidaciones.php" title="Liquidações" class="tip-bottom">Liquidações</a>
		<a href="liquidaciones.pendientes.php" title="Liquidações Pendentes" class="tip-bottom">Liquidações Pendentes</a>
		<a href="#" class="current">Revisão liquidação <?php echo $nomres;?></a>
	</div>
	<h1>Export Liquidacion | <?php echo $nombre.' '.$nomres;?></h1><hr>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5><?php echo $nombre;?> - Media Pension</h5>
			</div>
			<form id="VouchersMPForm" name="VouchersMPForm" action="posts.php" method="post">
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = "SELECT idmediapension id, Titular 'Nome PAX', numeroexterno '# Voucher', Q 'Qtde de PAX', DataIN, DataOUT, Agencia, Posada, N, M, Servicio, USD, Tarifa, NULL 'Detalles' FROM _temp_liquidaciones_mp";
					echo tableFromResult(resultFromQuery($sqlQuery), 'VouchersMP', false, false, 'posts.php', true);

				?>
			</div>
			</form>
        </div>
			<?php
			$sql = "SELECT SUM(`USD`) total FROM `_temp_liquidaciones_mp` WHERE 1";
			$resultadoTotal = resultFromQuery($sql);
			$rowTotal = siguienteResult($resultadoTotal);
			$total = $rowTotal->total;
			?>
			<h2>Total : US <?php echo $total;?></h2>
	</div>
    <hr/>
	<div class="row-fluid">
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5><?php echo $nombre;?> - Hoteleria</h5>
			</div>
			<form id="VouchersHTLForm" name="VouchersHTLForm" action="posts.php" method="post">
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = "SELECT idhoteleria id, Titular 'Nome PAX', numeroexterno '# Voucher', Q 'Qtde de PAX', DataIN, DataOUT, Agencia, Posada, N, M, Servicio, USD, Tarifa FROM _temp_liquidaciones_htl";
					echo tableFromResult(resultFromQuery($sqlQuery), 'VouchersHTL', false, false, 'posts.php', true);
				?>
			</div>
			</form>
        </div>
			<?php
			$sql = "SELECT SUM(`USD`) total FROM `_temp_liquidaciones_htl` WHERE 1";
			$resultadoTotal = resultFromQuery($sql);
			$rowTotal = siguienteResult($resultadoTotal);
			$total = $rowTotal->total;
			?>
			<h2>Total : US <?php echo $total;?></h2>
	</div>
  </div>
  <a href="liquidaciones-xls.php?idresponsablesDepago=<?php echo $idresponsablesDePago.'&id='.$id?>"><button class="btn btn-success btn-mini">Generar archivo Excel</button></a>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
