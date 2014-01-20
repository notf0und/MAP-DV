<?php 
include "head.php"; 

if (isset($_SESSION['idmediapension'])){
	
	if (isset($_SESSION['referer'])){
		$referer = $_SESSION['referer'];
	}
	else{
		$referer = $_SERVER['HTTP_REFERER'];
	}
	
	$idmediapension = $_SESSION['idmediapension'];
	$current = 'Edit';
	$sqlQuery = "SELECT MP.idhuespedes 'idhuespedes', H.idpaises 'idpaises', H.Titular 'nomedopax', MP.idservicios, ";
	$sqlQuery .= " MP.qtdedepax 'qtdedepax', MP.numeroexterno, MP.idoperadoresturisticos, ";
	$sqlQuery .= " MP.idagencias, MP.idposadas, MP.idresponsablesDePago, MP.DataIN, MP.DataOUT, MP.qtdedecomidas, MP.hoteleria ";
	$sqlQuery .= " FROM `mediapension` MP ";
	$sqlQuery .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
	$sqlQuery .= " WHERE 1 ";
	$sqlQuery .= " AND MP.idmediapension = ".$_SESSION["idmediapension"];
	$resultadoStringSQL = resultFromQuery($sqlQuery);		
	if ($row = siguienteResult($resultadoStringSQL)){
		$idhuespedes = $row->idhuespedes;
		$nomedopax = $row->nomedopax;
		$idpaises = $row->idpaises;
		$qtdedepax = $row->qtdedepax;
		$numeroexterno = $row->numeroexterno;
		$idoperadoresturisticos = $row->idoperadoresturisticos;
		$idagencias = $row->idagencias;
		$idservicios = $row->idservicios;	
		$idposadas = $row->idposadas;
		$idresponsablesDePago = $row->idresponsablesDePago;
		$DataIN = $row->DataIN;
		$DataOUT = $row->DataOUT;
		$qtdedecomidas = $row->qtdedecomidas;
		$hoteleria = $row->hoteleria;
	}
}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="mediapension.vouchers.php" title="Vouchers" class="tip-bottom">Vouchers</a>
		<a href="#" class="current">Edit...</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Edit Voucher</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="admitirMediapension" />
						<input type="hidden" id="idmediapension" name="idmediapension" value="<?php echo $idmediapension;?>" />
						<input type="hidden" id="idhuespedes" name="idhuespedes" value="<?php echo isset($idhuespedes) ? $idhuespedes : '';?>" />
						<input type="hidden" id="actualizado" name="actualizado" value="1" />
						<input type="hidden" id="referer" name="referer" value="<?php echo $referer;?>" />
						
						<div class="control-group">
							<label class="control-label">Nome do pax</label>
							<div class="controls">
								<input id="nomedopax" name="nomedopax" type="text" class="span11" placeholder="Nome do pax" required="true" value="<?php echo isset($nomedopax) ? $nomedopax : '';?>"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Pais</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idpaises, nombre FROM paises ";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idpaises', $resultado, isset($idpaises) ? $idpaises : '', '', '');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Qtde de Pax</label>
							<div class="controls">
								<input id="qtdedepax" name="qtdedepax" type="text" class="span2" required="true" min=1 max=99  value="<?php echo isset($qtdedepax) ? $qtdedepax : '';?>" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Voucher #</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on">#</span>
									<input id="numeroexterno" name="numeroexterno" type="text" class="span11" placeholder="numero do voucher" value="<?php echo isset($numeroexterno) ? $numeroexterno : '';?>" />
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Serviço</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idservicios, nombre FROM servicios ";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idservicios', $resultado, isset($idservicios) ? $idservicios : '', '', '', 'true');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Operador</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idoperadoresturisticos, nombre FROM operadoresturisticos";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idoperadoresturisticos', $resultado, isset($idoperadoresturisticos) ? $idoperadoresturisticos : '', '', '');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Pousada</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idposadas, nombre FROM posadas";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idposadas', $resultado, isset($idposadas) ? $idposadas : '', '', '');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Agencia</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idagencias, nombre FROM agencias";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idagencias', $resultado, isset($idagencias) ? $idagencias : '', '', '');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Responsable</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idresponsablesDePago, nombre FROM responsablesDePago";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idresponsablesDePago', $resultado, isset($idresponsablesDePago) ? $idresponsablesDePago : '', '', '');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Data IN</label>
							<div class="controls">
								<div data-date="" class="input-append date datepicker">
									<input id="dataIN" name="dataIN" type="text" class="span11" required="true" value="<?php echo isset($DataIN) ? $DataIN : '';?>" />
									<span class="add-on"><i class="icon-th"></i></span> 
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Data OUT</label>
							<div class="controls">
								<div  data-date="" class="input-append date datepicker">
									<input id="dataOUT" name="dataOUT" type="text" class="span11"  required="true" value="<?php echo isset($DataOUT) ? $DataOUT : '';?>" />
									<span class="add-on"><i class="icon-th"></i></span> 
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Qtde de Serviços</label>
							<div class="controls">
								<input id="qtdedecomidas" name="qtdedecomidas" type="text" class="span2"  required="true" value="<?php echo isset($qtdedecomidas) ? $qtdedecomidas : '';?>" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Hoteleria</label>
							<div class="controls">
								<SELECT required="true" ID="hoteleria" NAME="hoteleria" SIZE="1" onchange="" STYLE="">
									<OPTION STYLE="" VALUE="0" <?php if (isset($hoteleria) && $hoteleria == 0){echo 'SELECTED';}?>>NO</OPTION>
									<OPTION STYLE="" VALUE="1" <?php if (isset($hoteleria) && $hoteleria == 1){echo 'SELECTED';}?>>SI</OPTION>
								</SELECT>								
							</div>
						</div>
						<div class="control-group">
							<br><button class="btn btn-success" type="submit">Modificar</button>
							<br><br>
						</div>
						<div id="status"></div>
					</form>

				</div>

			</div>
			<!--
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="borrarMediapension" />
						<input type="hidden" id="idmediapension" name="idmediapension" value="<?php echo $idmediapension;?>" />
						<button class="btn btn-success" type="submit">Borrar Voucher</button>
					</form>
			-->
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
