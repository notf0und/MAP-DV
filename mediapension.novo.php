<?php 
include "head.php"; 
$idlocales = $_SESSION["idlocales"];
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="#" class="current">Novo...</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Novo Voucher - Media Pension</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form-wizard" name="form-wizard" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="admitirMediapension" />
						<input type="hidden" id="idmediapension" name="idmediapension" value="<?php echo isset($idmediapension) ? $idmediapension : '';?>" />
						<div id="form-wizard-1" class="step">
							<div class="control-group">
								<label class="control-label">Nome do pax</label>
								<div class="controls">
									<input id="nomedopax" name="nomedopax" type="text" class="span11" placeholder="Nome do pax" required="true" />
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
									<input id="qtdedepax" name="qtdedepax" type="text" class="span4" required="true" min=1 max=99 />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Voucher #</label>
								<div class="controls">
									<div class="input-prepend"> <span class="add-on">#</span>
										<input id="numeroexterno" name="numeroexterno" type="text" class="span11" placeholder="numero do voucher"/>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Serviço</label>
								<div class="controls">
									<?php
										$sqlQuery = " SELECT idservicios, nombre FROM  servicios WHERE idservicios < 6 ";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('idservicios', $resultado, isset($idservicios) ? $idservicios : '', '', '', true);
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
										echo comboFromArray('idposadas', $resultado, isset($idposadas) ? $idposadas : '', '', '', false);
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
							<?php if ($_SESSION["idlocales"]==0){?>
							<div class="control-group">
								<label class="control-label">Hoteleria</label>
								<div class="controls">
									<SELECT required="true" ID="hoteleria" NAME="hoteleria" SIZE="1" onchange="" STYLE="">
										<OPTION STYLE="" VALUE="0" <?php !isset($hoteleria) ? 'SELECTED' : '';?>>NO</OPTION>
										<OPTION STYLE="" VALUE="1" <?php isset($hoteleria) ? 'SELECTED' : '';?>>SI</OPTION>
									</SELECT>								
								</div>
							</div>
							<?php }?>
						</div>
						<div id="form-wizard-2" name="form-wizard-2" class="step">
							<div class="control-group">
								<label class="control-label">Data IN</label>
								<div class="controls">
									<div data-date="" class="input-append date datepicker">
										<input id="dataIN" name="dataIN" type="text" value="" data-date-format="dd-mm-yyyy" class="span11"  required="true" >
										<span class="add-on"><i class="icon-th"></i></span> 
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Data OUT</label>
								<div class="controls">
									<div  data-date="" class="input-append date datepicker">
										<input id="dataOUT" name="dataOUT" type="text" value="" data-date-format="dd-mm-yyyy" class="span11"  required="true" >
										<span class="add-on"><i class="icon-th"></i></span> 
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Total de Serviços</label>
								<div class="controls">
									<input id="qtdedecomidas" name="qtdedecomidas" type="text" id="comidas" value="" class="span2"  required="true" />
								</div>
							</div>
						</div>
						<div id="form-wizard-3" class="step">
							<div class="control-group">
								<label class="control-label">Qtde Pax do Serviço</label>
								<div class="controls">
									<input id="qtdedepaxagora" name="qtdedepaxagora" type="text" class="span2" required="true" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Mensagem Interno</label>
								<div class="controls">
									<textarea id="mensajeinterno" name="mensajeinterno" class="span11" ></textarea>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Mensagem Garçon</label>
								<div class="controls">
									<textarea id="mensajegarcon" name="mensajegarcon" class="span11" ></textarea>
								</div>
							</div>
						</div>
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
