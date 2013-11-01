<?php include "head.php"; 

$numero = cal_days_in_month(CAL_GREGORIAN, $_SESSION["visualizarMes"], $_SESSION["visualizarAno"]); 
$dataIN = '2012-01-01';//$_SESSION["visualizarAno"]."-".$_SESSION["visualizarMes"]."-01";
$dataOUT = $_SESSION["visualizarAno"]."-".$_SESSION["visualizarMes"]."-".$numero;

$idresponsablesDePago = $_GET['idresponsablesDePago'];
$id = $_GET['id'];

$sql = " SELECT idresponsablesDePago, nombre, tabla ";
$sql .= " FROM responsablesDePago "; 
$sql .= "WHERE 1 "; 
$sql .= " AND idresponsablesDePago = ".$idresponsablesDePago;
 
$resultadoResponsables= resultFromQuery($sql);	

if ($rowLine = siguienteResult($resultadoResponsables)) {	
	$tabla = $rowLine->tabla;
	$nombre = $rowLine->nombre;
}

liquidacionServicios($idresponsablesDePago, $id, $dataIN, $dataOUT);	

$sql = "SELECT * FROM `".$tabla."` WHERE `id".$tabla."` = ".$id;
$resultado = resultFromQuery($sql);
$row = siguienteResult($resultado);
?>	
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="liquidaciones.php" title="liquidaciones" class="tip-bottom"> Liquidaciones</a>
		<a href="#" class="current">Proceso de Liquidaciones Mensual</a>
	</div>
	<h1>Export Liquidacion | <?php echo $nombre;?></h1><hr>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5><?php echo $nombre;?> - Media Pension</h5>
			</div>
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = "SELECT * FROM _temp_liquidaciones_mp";
					echo tableFromResultGDA(resultFromQuery($sqlQuery), 'Reporte', false, false, 'posts.php', false);
				?>
			</div>
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
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = "SELECT * FROM _temp_liquidaciones_htl";
					echo tableFromResultGDA(resultFromQuery($sqlQuery), 'Reporte2', false, false, 'posts.php', false);
				?>
			</div>
        </div>
			<?php
			$sql = "SELECT SUM(`USD`) total FROM `_temp_liquidaciones_htl` WHERE 1";
			$resultadoTotal = resultFromQuery($sql);
			$rowTotal = siguienteResult($resultadoTotal);
			$total = $rowTotal->total;
			?>
			<h2>Total : US <?php echo $total;?></h2>
	</div>
    <hr/>
  </div>
<!--Action boxes-->
  <div class="container-fluid">
<!--End-Action boxes-->    

            <form id="liquidacionCrear" name="liquidacionCrear" method="post" class="form-vertical" action="posts.php">
				<input type="hidden" id="accion" name="accion" value="liquidacionCrear" />
				<input type="hidden" id="ID" name="ID" value="<?php echo $id;?>" />
				<input type="hidden" id="idresponsablesDepago" name="idresponsablesDepago" value="<?php echo $idresponsablesDePago;?>" />
				<input type="hidden" id="nombre" name="nombre" value="<?php echo $nombre;?>" />
				<div class="control-group">
					<label class="control-label">Nombre de liquidacion</label>
					<div class="controls">
						<input id="titulo" name="titulo" type="text" class="span5" placeholder="titulo" required="true" />
					</div>
				</div>
                <div class="form-actions">
                    <span class="pull-left"><a href="mediapension.liquidaciones.mensual.php" class="flip-link btn btn-info" id="to-recover">Voltar</a></span>
                    <span class="pull-right"><button class="btn btn-success" type="submit">Generar liquidacion</button></span>
                </div>				
            </form>

    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
