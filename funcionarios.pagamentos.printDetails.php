<?php

include "head.php"; 

$employee_id = $_GET['employee_id'];

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');


$sql = "SELECT CONCAT(P.firstname, ' ', P.lastname) Funcionario ";
$sql .= 'FROM employee E ';
$sql .= 'LEFT JOIN profile P ';
$sql .= 'ON E.profile_id = P.profile_id ';
$sql .= 'WHERE 1 ';
$sql .= 'AND E.employee_id = '.$employee_id;
$result = resultFromQuery($sql);
$row = siguienteResult($result);

$funcionario = $row->Funcionario;


$salario = isset($month) && isset($year) ? calcularSalario($employee_id, $month, $year) : calcularSalario($employee_id);
$details = comboDetails($employee_id, $month, $year, $salario);

function comboDetails($employee_id, $month, $year, $salario){
	
	$element = '<table class="table table-striped table-bordered">';
	$element .= '<thead>';
	$element .= '<tr>';
    $element .= '<th>Descrição</th>';
    $element .= '<th>Vencimentos</th>';
    $element .= '<th>Descontos</th>';
    $element .= '</tr>';
    $element .= '</thead>';
    $element .= '<tbody>';
	
	
	
	
	if (isset($salario['-'])){
	
		foreach ($salario['-'] as $i => $value) {
			if (gettype($salario['-'][$i]) == 'array'){
				foreach ($salario['-'][$i] as $j => $subvalue) {
					if (gettype($salario['-'][$i]) == 'array'){
						foreach ($salario['-'][$i][$j] as $k => $subsubvalue) {

							
							if (strpos($i,'Faltas') === false) {
								$element .= '<tr>';
								$element .= '<td class="taskDesc"><i class="icon-minus-sign"></i>';
								if ($k != ''){
									$element .='<a id="example" data-content="'.$k.'" data-placement="right" data-toggle="popover" class="taskStatus" data-original-title="'.$i.'">'.$i.'</a></td>';
								}
								else {
									$element .= $i.'</td>';
								}
								$element .= '<td></td>';
								$element .= '<td class="taskStatus">'.$subsubvalue.'</td>';
								$element .= '</tr>';
							}
						}
					}
				}
			}
		}
	}
	
	$element .= '<tr>';
	$element .= '<td></td>';
	$element .= '<td></td>';
	$element .= '<td></td>';
	$element .= '</tr>';
	
	$element .= '</tbody>';
    $element .= '</table>';
	
	
	
	
	
	
	
	
	
	
	
	
	/*
	$sql = "SELECT  SUM(PAY.ammount) ammount,  PT.type type ";
	$sql .= "FROM payment PAY ";
	$sql .= "LEFT JOIN paymenttype PT ON PAY.paymenttype_id = PT.paymenttype_id ";
	$sql .= "WHERE PAY.enabled = 1 ";
	$sql .= "AND PAY.employee_id = ".$employee_id." ";
	$sql .= "AND month(PAY.date) = ".$month." ";
	$sql .= "AND year(PAY.date) = ".$year." ";
	$sql .= "GROUP BY type;";

	$result = resultFromQuery($sql);
	while ($row = siguienteResult($result)) {
		$details[] = array($row->type => $row->ammount);
	}
	$element = '';
	$sumammount = 0;
	if (isset($details)){
		foreach ($details as $detail) {
			
			foreach ($detail as $type => $ammount) {
				
				$ammount = round($ammount, 2);
				$sumammount += $ammount;
				
				list($whole, $decimal) = explode('.', $ammount);
				
				$element .= '<div class="new-update clearfix">';
				$element .= '<i class="icon-ok-sign"></i>';
				$element .= '<div class="update-done">';
				$element .= '<a title="" href="#">';
				$element .= '<strong>';
				$element .= $type;
				$element .= '</strong>';
				$element .= '</a>';
				$element .= '</div>';
				$element .= '<div class="update-date">';
				$element .= '<span class="update-day">';
				$element .= $whole;
				$element .= '</span>';
				$element .= $decimal;
				$element .= '</div>';
				$element .= '</div>';
				
			}
			
		}
		list($whole, $decimal) = explode('.', $sumammount);
		
		$element .= '<div class="new-update clearfix">';
		$element .= '<i class="icon-circle-arrow-right"></i>';
		$element .= '<div class="update-done">';
		$element .= '<a title="" href="#">';
		$element .= '<strong>';
		$element .= 'TOTAL';
		$element .= '</strong>';
		$element .= '</a>';
		$element .= '</div>';
		$element .= '<div class="update-date">';
		$element .= '<span class="update-day">';
		$element .= $whole;
		$element .= '</span>';
		$element .= $decimal;
		$element .= '</div>';
		$element .= '</div>';
	}*/
	return $element;
}


?>	

<!--main-container-part-->
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
		 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="funcionarios.lista.php" title="Lista de funcionarios" class="tip-bottom">Lista de funcionarios</a>
		<a href="funcionarios.pagamentos?employee_id=<?php echo $employee_id; ?>" title="Balance de Salario" class="tip-bottom">Balance de Salario</a>
		<a href="#" class="current">Registro de consumos</a>
		
    </div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>Registro de consumos de <?php echo $funcionario.' no período '.$month.'/'.$year?> </h5>
          </div>

			  <?php echo $details?>

        </div>
      </div>
    </div>
  </div>
<br><p align="center">_______________________________</p><br>
<p align="center">Asignatura</p>
</div>

<!--Footer-part-->
<?php include "footer.php"; ?>
