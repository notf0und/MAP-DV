<?php include "head.php"; 

$today = date("Y-m-d");

// Conservo datos del formulario
if ($_POST['accion'] == 'reporte'){

		$dataIN = $_POST['dataIN'];
		$dataOUT = $_POST['dataOUT'];
		$idoperadoresturisticos = $_POST['idoperadoresturisticos'];
		$idposadas = $_POST['idposadas'];

	}else{

		$dataIN = $today;
		$dataOUT = $today;

	}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="mediapension.informes.php" title="Media pension" class="tip-bottom">Reports</a>
		<a href="#" class="current">Liquidação</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
	
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Filtro</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="" method="post" class="form-horizontal">
					<input type="hidden" id="accion" name="accion" value="reporte" />
					<div class="control-group">
						<label class="control-label">Operador</label>
						<div class="controls">
							<?php
								$sqlQuery = " SELECT idoperadoresturisticos, nombre FROM operadoresturisticos";
								$resultado = resultFromQuery($sqlQuery);
								echo comboFromArray('idoperadoresturisticos', $resultado, $idoperadoresturisticos, '', '');
							?>								
						</div>
					</div>
					<!--
					<div class="control-group">
						<label class="control-label">Pousada</label>
						<div class="controls">
							<?php
								$sqlQuery = " SELECT idposadas, nombre FROM posadas";
								$resultado = resultFromQuery($sqlQuery);
								echo comboFromArray('idposadas', $resultado, $idposadas, '', '');
							?>								
						</div>
					</div>
					-->
					<div class="control-group">
						<label class="control-label">Data Desde</label>
						<div class="controls">
							<div data-date="" class="input-append date datepicker">
								<input id="dataIN" name="dataIN" type="text" value="<?php echo $dataIN;?>" date-format="yyyy-mm-dd" class="span11" >
								<span class="add-on"><i class="icon-th"></i></span> 
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Data Hasta</label>
						<div class="controls">
							<div  data-date="" class="input-append date datepicker">
								<input id="dataOUT" name="dataOUT" type="text" value="<?php echo $dataOUT;?>" date-format="yyyy-mm-dd" class="span11" >
								<span class="add-on"><i class="icon-th"></i></span> 
							</div>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success">Liquidacion</button>
					</div>
					</form>
				</div>
			</div>
		</div>
		
<?php if ($_POST['accion'] == 'reporte'){
		$dataIN = $_POST['dataIN'];
		$dataOUT = $_POST['dataOUT'];
		$idoperadoresturisticos = $_POST['idoperadoresturisticos'];

	liquidacionServiciosOperadores($idoperadoresturisticos, $dataIN, $dataOUT);	
//	echo '<script languaje="javascript"> self.location="mediapension.informes.liquidaciones.reporte.operadores.php"</script>';
		
?>	
	
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Liquidação</h5>
          </div>
          <div class="widget-content nopadding">
			<?php
				/*
					MP Media Pension.
					A Agencias.
					S Servicios.
					P Posadas.
				*/
				$sqlQuery = " SELECT MP.idmediapension, H.Titular 'Nome PAX', MP.qtdedepax 'Q', A.Nombre 'Agencia', P.Nombre 'Posada', MP.DataIN, MP.DataOUT, MP.numeroexterno, ";
				$sqlQuery .= " DATEDIFF(MP.DataOUT, MP.DataIN) 'N', (MP.qtdedepax*DATEDIFF(MP.DataOUT, MP.DataIN)) 'M', S.Nombre 'Servicio', ";
				$sqlQuery .= " DATEDIFF(MP.DataOUT, MP.DataIN)*(MP.qtdedepax*(SELECT precio FROM servicios_listasdeprecios WHERE idservicios = MP.idservicios AND idlistasdeprecios = P.idlistasdeprecios)) 'USD', ";
				$sqlQuery .= " (SELECT precio FROM servicios_listasdeprecios WHERE idservicios = MP.idservicios AND idlistasdeprecios = P.idlistasdeprecios) 'precio' ";
				$sqlQuery .= " FROM `mediapension` MP ";
				$sqlQuery .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
				$sqlQuery .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
				$sqlQuery .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
				$sqlQuery .= " LEFT JOIN servicios S ON MP.idservicios = S.idservicios ";
				$sqlQuery .= " WHERE 1 ";
				if ($idoperadoresturisticos!=0){
					$sqlQuery .= " AND MP.idoperadoresturisticos = ".$idoperadoresturisticos;
				}
				$sqlQuery .= " AND MP.DataIN >= '".$dataIN."'";
				$sqlQuery .= " AND MP.DataOUT <= '".$dataOUT."'";
				//echo tableFromResultGDA(resultFromQuery($sqlQuery), 'mediapension', false, false, 'posts.php', false);
			?>		  
          </div>
        </div>

		<form method="get" action="posts.php">
			<button class="btn btn-success" type="submit">Exportar a Excel</button>
		</form>      	

		</div>

<?php }?>	
		
	<hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>

<?php if ($_POST['accion'] == 'reporte'){?>
	<script languaje="javascript"> self.location="mediapension.informes.liquidaciones.operadores.reporte.php"</script>
<?php }?>
