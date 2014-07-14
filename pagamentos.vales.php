<?php

include "head.php";

if(isset($_GET['state'])){

	$sql = 'UPDATE requestpayment SET status = '.$_GET['state'].' WHERE requestpayment_id = '.$_GET['rpid'];
	$result = resultFromQuery($sql);
}

$sql = "SELECT requestpayment_id id, CONCAT(P.firstname, ' ', P.lastname) Funcionario, amountrequest Solicitado, status Estado, amountaproved Aprovado, last_update Atualizado ";
$sql .= 'FROM requestpayment RP ';

$sql .= 'LEFT JOIN employee E ON RP.employee_id = E.employee_id ';

$sql .= 'LEFT JOIN profile P ON E.profile_id = P.profile_id ';

$sql .= 'WHERE status < 2';


$result = resultFromQuery($sql);


function tableRequestPayment($result, $sql){
	$table = '';
	if($row = siguienteResult($result)){
		$result = resultFromQuery($sql);
		$table = '<table class="table table-striped table-bordered">';
		$table .= '<thead>';
		$table .= '<tr>';
		$table .= '<th>Atualizado</th>';
		$table .= '<th>Funcionario</th>';
		$table .= '<th>Solicitado</th>';
		$table .= '<th>Estado</th>';
		$table .= '<th>Aprovado</th>';
		$table .= '<th>Ações</th>';
		$table .= '</tr>';
		$table .= '</thead>';
		$table .= '<tbody>';

		while($row = siguienteResult($result)){
			
			$table .= '<tr>';
			$table .= '<td style="text-align:center">'.date('d/m', strtotime($row->Atualizado)).'</td>';
			$table .= '<td style="text-align:center">'.$row->Funcionario.'</td>';
			$table .= '<td style="text-align:center">R$ '.$row->Solicitado.'</td>';
			$table .= '<td class="taskStatus">';
			if($row->Estado == 0){
				$table .= '<span class="pending">Esperando revisão</span>';
			}
			elseif($row->Estado == 1){
				$table .= '<span class="done">Aprovado</span>';
			}
			
			$table .= '</td>';
			$table .= '<td style="text-align:center">'.(isset($row->Aprovado) ? 'R$ '.$row->Aprovado : '' ).'</td>';
			
			
			
			if($row->Estado == 0){
				$table .= '<td class="taskOptions"><a href="#Aprovar" data-toggle="modal" class="" onclick="document.getElementById(\'modal-body\').innerHTML=\'<object id=foo name=foo type=text/html width=530 height=350 data=pagamentos.vales.aprovar.php?requestpayment_id='.$row->id.'></object>\'"><i class="icon-ok"></i></a> <a href="pagamentos.vales.php?rpid='.$row->id.'&state=3" class="tip-top" data-original-title="Cancelar"><i class="icon-remove"></i></a></td>';
			}
			elseif($row->Estado == 1){
				$table .= '<td class="taskOptions"><a href="#Pagar" data-toggle="modal" class="" onclick="document.getElementById(\'pagar-body\').innerHTML=\'<object id=bar name=bar type=text/html width=530 height=350 data=pagamentos.vales.pagar.php?requestpayment_id='.$row->id.'></object>\'"><i class="icon-ok"></i></a> <a href="pagamentos.vales.php?rpid='.$row->id.'&state=3" class="tip-top" data-original-title="Cancelar"><i class="icon-remove"></i></a></td>';
				//$table .= '<td class="taskOptions"><a href="#myAlert" data-toggle="modal" class="tip-top" data-original-title="Pagar"><i class="icon-money"></i></a> <a href="pagamentos.vales.php?rpid='.$row->id.'&state=3" class="tip-top" data-original-title="Cancelar"><i class="icon-remove"></i></a></td>';
			}
			
			
			$table .= '</tr>';
		}
		
		$table .= '</tbody>';
		$table .= '</table>';
		
	}
	return $table;

}
$tableRequestPayment = tableRequestPayment($result, $sql);

	
?>	

<!--main-container-part-->
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Inicio</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="pagamentos.php" title="Pagamentos" class="tip-bottom">Pagamentos</a>
		<a href="#" class="current">Vales</a>
	</div>
    <h1>Vales solicitados e aprovados.</h1>
  </div>
  

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
		  
		  

        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>Vales</h5>
          </div>
          <div class="widget-content nopadding">
			  
			  
			  <?php echo $tableRequestPayment?>

				  <div id="Aprovar" class="modal hide">
					  <div class="modal-header">
						  <button data-dismiss="modal" class="close" type="button">×</button>
						  <h3>Aprovar solicitud de vale</h3>
					  </div>
					  <div class="modal-body" id="modal-body" name="body">
					</div>
					<div class="modal-footer">
						<a data-dismiss="modal" class="btn" href="#">Cancelar</a> 
					</div>
            </div>
				  <div id="Pagar" class="modal hide">
					  <div class="modal-header">
						  <button data-dismiss="modal" class="close" type="button">×</button>
						  <h3>Aprovar solicitud de vale</h3>
					  </div>
					  <div class="modal-body" id="pagar-body" name="body">
					</div>
					<div class="modal-footer">
						<a data-dismiss="modal" class="btn" href="#">Cancelar</a> 
					</div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
