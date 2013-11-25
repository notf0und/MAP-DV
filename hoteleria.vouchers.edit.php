<?php 
include "head.php"; 

if (isset($_SESSION['idhoteleria'])){
	$idhoteleria = $_SESSION['idhoteleria'];
	$current = 'Edit';
	$sqlQuery = "SELECT HTL.idhuespedes 'idhuespedes', H.idpaises 'idpaises', H.Titular 'nomedopax', HTL.idservicios, ";
	$sqlQuery .= " HTL.qtdedepax 'qtdedepax', HTL.numeroexterno, HTL.idoperadoresturisticos, ";
	$sqlQuery .= " HTL.idagencias, HTL.idposadas, HTL.idresponsablesDePago, HTL.DataIN, HTL.DataOUT, HTL.qtdedenoites ";
	$sqlQuery .= " FROM `hoteleria` HTL ";
	$sqlQuery .= " LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes ";
	$sqlQuery .= " WHERE 1 ";
	$sqlQuery .= " AND HTL.idhoteleria = ".$_SESSION["idhoteleria"];
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
		$qtdedenoites = $row->qtdedenoites;
	}
}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="hoteleria.php" title="Hoteleria" class="tip-bottom">Hoteleria</a>
		<a href="hoteleria.vouchers.php" title="Vouchers" class="tip-bottom">Vouchers</a>
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
						<input type="hidden" id="accion" name="accion" value="admitirHoteleria" />
						<input type="hidden" id="idhoteleria" name="idhoteleria" value="<?php echo $idhoteleria;?>" />
						<input type="hidden" id="idhuespedes" name="idhuespedes" value="<?php echo $idhuespedes;?>" />
						<div class="control-group">
							<label class="control-label">Nome do pax</label>
							<div class="controls">
								<input id="nomedopax" name="nomedopax" type="text" class="span11" placeholder="Nome do pax" required="true" value="<?php echo $nomedopax;?>"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Pais</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idpaises, nombre FROM paises ";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idpaises', $resultado, $idpaises, '', '');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Qtde de Pax</label>
							<div class="controls">
								<input id="qtdedepax" name="qtdedepax" type="text" class="span2" required="true" min=1 max=99  value="<?php echo $qtdedepax;?>" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Voucher #</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on">#</span>
									<input id="numeroexterno" name="numeroexterno" type="text" class="span11" placeholder="numero do voucher" value="<?php echo $numeroexterno;?>" />
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Serviço</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idservicios, nombre FROM servicios WHERE idservicios > 5 ";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idservicios', $resultado, $idservicios, '', '', 'true');
								?>								
							</div>
						</div>
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
						<div class="control-group">
							<label class="control-label">Agencia</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idagencias, nombre FROM agencias";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idagencias', $resultado, $idagencias, '', '');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Responsable</label>
							<div class="controls">
								<?php
									$sqlQuery = " SELECT idresponsablesDePago, nombre FROM responsablesDePago";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('idresponsablesDePago', $resultado, $idresponsablesDePago, '', '');
								?>								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Data IN</label>
							<div class="controls">
								<div data-date="" class="input-append date datepicker">
									<input id="dataIN" name="dataIN" type="text" class="span11" required="true" value="<?php echo $DataIN;?>" />
									<span class="add-on"><i class="icon-th"></i></span> 
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Data OUT</label>
							<div class="controls">
								<div  data-date="" class="input-append date datepicker">
									<input id="dataOUT" name="dataOUT" type="text" class="span11"  required="true" value="<?php echo $DataOUT;?>" />
									<span class="add-on"><i class="icon-th"></i></span> 
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Qtde de Serviços</label>
							<div class="controls">
								<input id="qtdedenoites" name="qtdedenoites" type="text" id="noites" class="span2"  required="true" value="<?php echo $qtdedenoites;?>" />
							</div>
						</div>
						<div class="control-group">
							<button class="btn btn-success" type="submit">Modificar</button>
						</div>
						<div id="status"></div>
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
