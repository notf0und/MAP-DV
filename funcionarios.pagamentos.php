<?php

include "head.php"; 

//include "dBug.php";
//new dBug($_POST);


//SELECT


$employee_id = $_GET['employee_id'];

$salario = calcularSalario($employee_id);

$table = salarioTable($salario);

if ($salario['employee']['Worked Days'] != 0){
	$message = 'Salario calculado en base a os '.$salario['employee']['Worked Days'].' dias trabalhados no ultimo mes';
}
else{
	$message = 'Não se pode estimar a quantidade de dias trabalhados. Por favor ingrese a data de admisão ou contratação deste funcionario';
}
function calcularSalario($employee_id){
	
	$salario = array('employee' => array('employee_id' => $employee_id));
	
	//Informacion sobre el empleado y detalles sobre salario
	//SELECT
	$sqlQuery = "SELECT ";
	$sqlQuery .= "E.employee_id id, ";
	$sqlQuery .= "CONCAT(P.firstname, ' ', P.lastname) fullname, ";
	$sqlQuery .= "BS.basesalary base, ";
	$sqlQuery .= "E.bonussalary, ";
	$sqlQuery .= "E.unhealthy, ";
	$sqlQuery .= "count(H.profile_id) sons, ";
	$sqlQuery .= "E.transport, ";
	$sqlQuery .= "F.status food, ";
	
	//Cantidad de dias trabajados Minimo (desde la admision, contrato, dias transcurridos en el mes) o 0
	$sqlQuery .= "COALESCE(LEAST(COALESCE(DATEDIFF(NOW(), admission), DATEDIFF(NOW(), contract)), DATEDIFF(NOW(), DATE_FORMAT(NOW(), '%Y-%m-01'))), 0) as 'worked', ";
	$sqlQuery .= "DAY(LAST_DAY(NOW())) as 'days' ";


	

	//FROM
	$sqlQuery .= "FROM employee E ";

	$sqlQuery .= "LEFT JOIN jobcategory JC ON E.jobcategory_id = JC.jobcategory_id ";
	$sqlQuery .= "LEFT JOIN basesalary BS ON JC.basesalary_id = BS.basesalary_id ";
	$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
	$sqlQuery .= "LEFT JOIN son H ON P.profile_id = H.profile_id ";
	$sqlQuery .= "LEFT JOIN foodemployee F ON E.employee_id = F.employee_id ";
	
	//Select latest food
	$sqlQuery .= "AND F.created = (SELECT MAX(created) FROM foodemployee Z WHERE Z.employee_id = E.employee_id)";
	
	//WHERE
	$sqlQuery .= "WHERE E.employee_id = ".$salario['employee']['employee_id'].' ';
	
	//Execute query
	$resultado = resultFromQuery($sqlQuery);	
	
	if ($row = siguienteResult($resultado)) {
		
		//Nombre completo
		$salario['employee']['fullname'] = $row->fullname;
		
		//Salario base
		$salario['+']['Salario Base'] = round(($row->base * ($row->worked /$row->days)), 2);
		
		$salario['employee']['Worked Days'] = $row->worked;
		//$salario['employee']['Days in month'] = $row->days;
		
		//Salario abono
		$_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 4 ? $salario['+']['Abono'] = round(($row->bonussalary * ($row->worked /$row->days)), 2) : false;
		
		//Insalubridad
		$row->unhealthy != 0 ? $salario['+']['Insalubridade'] = 67.8 : '';
		
		//Cantidad de hijos
		$row->sons > 0 ? $salario['+']['Salario Familia'] = $row->sons * 23.35 : false;
		
		//INSS
		$salario['-']['INSS'] = round($salario['+']['Salario Base'] * 0.08, 2);
		
		//Descuentos por Transporte
		if ($row->transport != NULL && $row->transport != '0'){

			$salario['-']['Transporte'] = round((transportDiscount($salario) * ($row->worked /$row->days)), 2);
		}
		
		//Descuentos de sindicato
		$salario['-']['Sindicato'] = 10;
		
		//Desconto por alimentação
		$row->food != 0 ? $salario['-']['Alimentação'] = round((67.8 * ($row->worked /$row->days)), 2) : '';
	}
	
	//Pagos recibidos durante el ultimo mes
	
	//SELECT
	$sqlQuery = "SELECT ";
	$sqlQuery .= "PAY.payment_id id, ";
	$sqlQuery .= "PAY.ammount, ";
	$sqlQuery .= "DATE_FORMAT(PAY.date, '%d/%m') date, ";
	$sqlQuery .= "PT.type, ";
	$sqlQuery .= "PAY.details ";

	//FROM
	$sqlQuery .= "FROM payment PAY ";

	$sqlQuery .= "LEFT JOIN paymenttype PT ON PAY.paymenttype_id = PT.paymenttype_id ";

	$sqlQuery .= "WHERE PAY.enabled = 1 ";
	$sqlQuery .= "AND PAY.employee_id = ".$salario['employee']['employee_id'];

		
	$resultado = resultFromQuery($sqlQuery);
	
	while ($row = mysql_fetch_object($resultado)) {
			
		$salario['-'][$row->type. " ".$row->date][][$row->details] = $row->ammount;
		
		
		isset($salario['adelantos']) ? $salario['adelantos'] += $row->ammount : $salario['adelantos'] = $row->ammount;
	}
	
	$salario['Total'] = array_sum($salario['+']) - (isset($salario['-']) ? array_sum($salario['-']) : 0) - (isset($salario['adelantos']) ?$salario['adelantos'] : 0);
	
	return $salario;
}

function transportDiscount($salario){
	
	$salarioneto = array_sum($salario['+']);

	if ($salarioneto <= 1000){
		$transportdiscount = 45.60;
	}
	elseif ($salarioneto > 1000 && $salarioneto <= 1200){
		$transportdiscount = 50;
	}
	else{
		$transportdiscount = 60;
	}
	
	//$transportdiscount = $salario['+']['Salario Base'] * 0.06;

	return $transportdiscount;
}

function salarioTable($salario){
	
	$HTML = '<table class="table table-striped table-bordered">';
	$HTML .= '<thead>';
	$HTML .= '<tr>';
    $HTML .= '<th>Descrição</th>';
    $HTML .= '<th>Vencimentos</th>';
    $HTML .= '<th>Descontos</th>';
    $HTML .= '</tr>';
    $HTML .= '</thead>';
    $HTML .= '<tbody>';
    
    foreach ($salario['+'] as $i => $value) {
		if ($value != ''){
			$HTML .= '<tr>';		
			$HTML .= '<td class="taskDesc"><i class="icon-plus-sign"></i>'.$i.'</td>';
			$HTML .= '<td class="taskStatus">'.$value.'</td>';
			$HTML .= '<td></td>';
			$HTML .= '</tr>';
		}
		
	}
	
	if (isset($salario['-'])){
	
		foreach ($salario['-'] as $i => $value) {
			if (gettype($salario['-'][$i]) == 'array'){
				foreach ($salario['-'][$i] as $j => $subvalue) {
					if (gettype($salario['-'][$i]) == 'array'){
						foreach ($salario['-'][$i][$j] as $k => $subsubvalue) {
							$HTML .= '<tr>';

							$HTML .= '<td class="taskDesc"><i class="icon-minus-sign"></i>';
							if ($k != ''){
								$HTML .='<a id="example" data-content="'.$k.'" data-placement="left" data-toggle="popover" class="taskStatus" data-original-title="'.$i.'">'.$i.'</a></td>';
							}
							else {
								$HTML .= $i.'</td>';
							}
							$HTML .= '<td></td>';
							$HTML .= '<td class="taskStatus">'.$subsubvalue.'</td>';
							$HTML .= '</tr>';
						}
					}
				}
			}
			else{
				$HTML .= '<tr>';
				$HTML .= '<td class="taskDesc"><i class="icon-minus-sign"></i>'.$i.'</td>';
				$HTML .= '<td></td>';
				$HTML .= '<td class="taskStatus">'.$value.'</td>';
				$HTML .= '</tr>';
			}
			
		}
	}
	
	$HTML .= '<tr>';
	$HTML .= '<td></td>';
	$HTML .= '<td></td>';
	$HTML .= '<td></td>';
	$HTML .= '</tr>';
	
	$HTML .= '<tr>';
	$HTML .= '<td class="taskDesc"><b>Salário Líquido</b></td>';
	$HTML .= '<td class="taskStatus"> <b>R$'.$salario['Total'].'</b></td>';
	$HTML .= '<td></td>';
	$HTML .= '</tr>';
	
	$HTML .= '</tbody>';
    $HTML .= '</table>';
	
	return $HTML;
	
	
	
}
?>	

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="funcionarios.lista.php" title="Lista de funcionarios" class="tip-bottom">Lista de funcionarios</a>
		<a href="#" class="current">Balance de Salario</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Balance de Salario: <?php echo $salario['employee']['fullname']?> </h5>
          </div>
          <div class="widget-content nopadding">
			  <?php echo $table; ?>
                     
          </div>
        </div>
        
        <div id="no-print">
			<?php echo isset($message) ? $message : '';?>
			
        <form method="post" action="pagamentos.novo.php">
			<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
			<button class="btn btn-success" type="submit">Adicionar Desconto</button>
		</form><br>
		
		<form method="post" action="funcionarios.filho.novo.php">
			<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
			<button class="btn btn-warning" type="submit">Registrar Filho</button>
		</form><br>
		
		<form method="post" action="posts.php">
			<input type="hidden" id="accion" name="accion" value="employeeFood" />
			
			<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />

			<button name='food' class="btn btn-info icon-ok-circle" type="submit" value="add">Adicionar alimentação</button>
			<button name='food' class="btn btn-danger icon-remove-sign" type="submit" value="remove">Apagar alimentação</button>
		</form>
		
		</div>
	</div>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
