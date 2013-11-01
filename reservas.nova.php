<?php 
include "head.php"; 
if (isset($_SESSION['idreservas'])){
	$idreservas = $_SESSION['idreservas'];
	$current = 'Edit';
	$sqlQuery = "SELECT H.Titular 'nomedopax', R.idservicios, ";
	$sqlQuery .= " R.qtdedepax 'qtdedepax', R.numeroexterno, R.idoperadoresturisticos, ";
	$sqlQuery .= " R.idagencias, R.idposadas, R.DataIN, R.DataOUT, ";
	$sqlQuery .= " ( ";
	$sqlQuery .= " 		SELECT H.Codigo 'codigo' FROM `reservas_admisiones` RA ";
	$sqlQuery .= " 		LEFT JOIN habitaciones H ON RA.idhabitaciones = H.idhabitaciones ";
	$sqlQuery .= " 		WHERE RA.idreservas = R.idreservas	LIMIT 1";
	$sqlQuery .= " ) codigo ";
	$sqlQuery .= " FROM `reservas` R ";
	$sqlQuery .= " LEFT JOIN huespedes H ON R.idhuespedes = H.idhuespedes ";
	$sqlQuery .= " WHERE 1 ";
	$sqlQuery .= " AND R.idreservas = ".$_SESSION["idreservas"];
	$resultadoStringSQL = resultFromQuery($sqlQuery);		
	if ($row = siguienteResult($resultadoStringSQL)){
		$nomedopax = $row->nomedopax;
		$qtdedepax = $row->qtdedepax;
		$numeroexterno = $row->numeroexterno;
		$idoperadoresturisticos = $row->idoperadoresturisticos;
		$idagencias = $row->idagencias;
		$idservicios = $row->idservicios;	
		$idposadas = $row->idposadas;
		$DataIN = $row->DataIN;
		$DataOUT = $row->DataOUT;
		$codigo = $row->codigo;
	}
}else{
	$idreservas = -1;
	$current = 'Nova';
}
?>
<script lang="javascript">

$("#state2").jCombo("getPosadas.php");
$("#city2").jCombo("getHabitaciones.php?id=", { parent: "#state2" } );
</script>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="reservas.php" title="Reservas" class="tip-bottom">Reservas</a>
		<a href="#" class="current"><?php echo $current?>...</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Nova reserva</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form-reservas" name="form-reservas" class="form-horizontal" method="post">
						<fieldset>
						<input type="hidden" id="accion" name="accion" value="admitirReserva" />
						<input type="hidden" id="idreservas" name="idreservas" value="<?php echo $idreservas;?>" />
						<div id="form-reservas-1" class="step">
							<div class="control-group">
								<label class="control-label">Nome do pax</label>
								<div class="controls">
									<input id="nomedopax" name="nomedopax" type="text" class="span11" placeholder="Nome do pax" required="true" value="<?php echo $nomedopax?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Qtde de Pax</label>
								<div class="controls">
									<input id="qtdedepax" name="qtdedepax" type="text" class="span2" required="true" min=1 max=99 value="<?php echo $qtdedepax?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Voucher reserva #</label>
								<div class="controls">
									<div class="input-prepend"> <span class="add-on">#</span>
										<input id="numeroexterno" name="numeroexterno" type="text" class="span11" placeholder="numero do voucher" value="<?php echo $numeroexterno?>" />
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Serviço</label>
								<div class="controls">
									<?php
										$sqlQuery = " SELECT idservicios, nombre FROM  servicios ";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('idservicios', $resultado, $idservicios, '', '', 'true');
									?>								
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Voucher # (MAP/FAP)</label>
								<div class="controls">
									<div class="input-prepend"> <span class="add-on">#</span>
										<input id="numeroexternoMAP" name="numeroexternoMAP" type="text" class="span11" placeholder="numero do voucher" value="<?php echo $numeroexterno?>" />
									</div>
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
								<label class="control-label">Agencia</label>
								<div class="controls">
									<?php
										$sqlQuery = " SELECT idagencias, nombre FROM agencias";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('idagencias', $resultado, $idagencias, '', '');
									?>								
								</div>
							</div>
						</div>
						<div id="form-reservas-2" class="step">
							<div class="control-group">
								<label class="control-label">Pousada</label>
								<div class="controls">
									<label>
									<?php
										$sqlQuery = " SELECT idposadas, nombre FROM posadas";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('combo1', $resultado, $idposadas, '', '');
									?>
									</label>									
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Habitaciones</label>
								<div class="controls">
									<label>
									<select name="combo2">
										<option value="0">Escolha</option>
									</select>								
									</label>									
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Data IN</label>
								<div class="controls">
									<div data-date="" class="input-append date datepicker">
										<input id="dataIN" name="dataIN" type="text" data-date-format="dd-mm-yyyy" class="span11"  required="true" value="<?php echo $DataIN?> ">
										<span class="add-on"><i class="icon-th"></i></span> 
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Data OUT</label>
								<div class="controls">
									<div  data-date="" class="input-append date datepicker">
										<input id="dataOUT" name="dataOUT" type="text" data-date-format="dd-mm-yyyy" class="span11"  required="true" value="<?php echo $DataOUT?>" >
										<span class="add-on"><i class="icon-th"></i></span> 
									</div>
								</div>
							</div>
						</div>
						<div id="form-reservas-3" class="step">
							<div class="control-group">
								Aca vamos a poner un resumen antes de confirmar.<br><br>
								<label class="control-label">Confirmacion de reserva?</label>
							</div>
						</div>
						<div class="form-actions">
							<input id="back" class="btn btn-primary" type="reset" value="Back" />
							<input id="next" class="btn btn-primary" type="submit" value="Next" />
							<div id="status"></div>
						</div>
					</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
	<script type="text/javascript">
	$(document).ready(function(){//inicio o jQuery
		$("select[name='combo1']").change(function(){
		var idCombo1 = $(this).val();//pegando o value do option selecionado
		//alert(idCombo1);//apenas para debugar a variável
		
			$.getJSON(//esse método do jQuery, só envia GET
				'combos-dependentes-function.inc.php',//script server-side que deverá retornar um objeto jSON
				{idCombo1: idCombo1},//enviando a variável
 
				function(data){
				//alert(data);//apenas para debugar a variável
					
					var option = new Array();//resetando a variável
					
					resetaCombo('combo2');//resetando o combo
					$.each(data, function(i, obj){
						
						
						option[i] = document.createElement('option');//criando o option
						$( option[i] ).attr( {value : obj.id} );//colocando o value no option
						$( option[i] ).append( obj.nome );//colocando o 'label'
 
						$("select[name='combo2']").append( option[i] );//jogando um à um os options no próximo combo
				});
			});
		});
	});	
	
	/* função pronta para ser reaproveitada, caso queria adicionar mais combos dependentes */
	function resetaCombo( el )
	{
		$("select[name='"+el+"']").empty();//retira os elementos antigos
		var option = document.createElement('option');					
		$( option ).attr( {value : '0'} );
		$( option ).append( 'Escolha' );
		$("select[name='"+el+"']").append( option );
	}
	</script>	

<?php include "footer.php"; ?>
<!--
<script lang="javascript">

function returnNumberOfDaysBetweenTwoDates(input1, input2) {
    var date1 = new Date(input1.value);
    var date2 = new Date(input2.value);

    var minutes = 1000*60;
    var hours = minutes*60;
    var days = hours*24;

    var diff = Math.abs(date1.getTime() - date2.getTime());

    return round(diff / days);
}

function cantidadDeServicios(){
	//alert('asdasdasd');
	alert(datediff('12/08/1975', '12/08/2013', 'days'));
	var start = $('#dataIN').datepicker('getDate');
    var end   = $('#dataOUT').datepicker('getDate');
    var days   = (end - start)/1000/60/60/24;
    alert(days);	
}
</script>
-->
