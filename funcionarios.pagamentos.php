<?php

include "head.php"; 

$employee_id = $_GET['employee_id'];

$month = isset($_POST['month']) ? $_POST['month'] : date('m');
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

$comboMonth = comboDate('month', $month);
$comboYear = comboDate('year', $year);

$salario = isset($month) && isset($year) ? calcularSalario($employee_id, $month, $year) : calcularSalario($employee_id);
$table = salarioTable($salario, $month, $year);
$details = comboDetails($employee_id, $month, $year, $salario);

$dSearch = date('Y-m', strtotime($year.'-'.$month));

//Si la fecha de admision y expulsion coinciden con el mes buscado
if (isset($salario['employee']['Admission']) && isset($salario['employee']['Decline Date']) && $dSearch == date('Y-m', strtotime($salario['employee']['Admission'])) && $dSearch == date('Y-m', strtotime($salario['employee']['Decline Date']))){
	$message = 'O funcionario trabalho desde '.$salario['employee']['Admission'].' até '.$salario['employee']['Decline Date'].'. Salario calculado com base a os '.$salario['employee']['Worked Days'].' días trabalhados';
}
//Si el mes buscado es anterior a la fecha de admision
elseif (isset($salario['employee']['Admission']) && $dSearch < date('Y-m', strtotime($salario['employee']['Admission']))){
	$message = 'O funcionario começou a trabalhar na data '.$salario['employee']['Admission'].'. Não se pode calcular o salario ';
}
elseif (isset($salario['employee']['Admission']) && $dSearch == date('Y-m', strtotime($salario['employee']['Admission']))){
	$message = 'O funcionario começou a trabalhar na data '.$salario['employee']['Admission'].'. Salario calculado com base a os '.$salario['employee']['Worked Days'].' días trabalhados';
}
elseif (isset($salario['employee']['Decline Date']) && $dSearch > date('Y-m', strtotime($salario['employee']['Decline Date']))){
	$message = 'O funcionario deixou de trabalhar na data '.$salario['employee']['Decline Date'].'. Não se pode calcular o salario';
}
elseif (isset($salario['employee']['Decline Date']) && $dSearch == date('Y-m', strtotime($salario['employee']['Decline Date']))){
	$message = 'O funcionario deixou de trabalhar na data '.$salario['employee']['Decline Date'].'. Salario calculado com base a os '.$salario['employee']['Worked Days'].' días trabalhados';
}

elseif ($salario['employee']['Worked Days'] > 0){
	$message = 'Salario calculado com base a os '.$salario['employee']['Worked Days'].' dias trabalhados';
}

setlocale(LC_ALL, 'pt_BR');

$message .= ' no mes de '.mb_convert_encoding(ucfirst(strftime("%B", strtotime($year.'-'.$month))), "UTF-8", "iso-8859-1").'.';

function salarioTable($salario, $month, $year){
	
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
							
							
							
							if (strpos($i,'Faltas') !== false) {
								$HTML .= '<tr>';
								$HTML .= '<td class="taskDesc"><i class="icon-minus-sign"></i>';
								$HTML .='<a id="example" data-content="'.$k.'" data-placement="left" data-toggle="popover" class="taskStatus" data-original-title="'.$i.'">'.$i.'</a></td>';
								$HTML .= '<td></td>';
								$HTML .= '<td class="taskStatus">'.$subsubvalue.'</td>';
								$HTML .= '</tr>';
							}
							/*
							if ($k != ''){
								$HTML .='<a id="example" data-content="'.$k.'" data-placement="left" data-toggle="popover" class="taskStatus" data-original-title="'.$i.'">'.$i.'</a></td>';
							}
							else {
								$HTML .= $i.'</td>';
							}
							$HTML .= '<td></td>';
							$HTML .= '<td class="taskStatus">'.$subsubvalue.'</td>';
							$HTML .= '</tr>';
							*/
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
	
	/////////////////////////
	$sql = "SELECT  SUM(PAY.ammount) ammount,  PT.type type ";
	$sql .= "FROM payment PAY ";
	$sql .= "LEFT JOIN paymenttype PT ON PAY.paymenttype_id = PT.paymenttype_id ";
	$sql .= "WHERE PAY.enabled = 1 ";
	$sql .= "AND PAY.employee_id = ".$salario['employee']['employee_id']." ";
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

				$HTML .= '<tr>';
				$HTML .= '<td class="taskDesc"><i class="icon-minus-sign"></i>'.$type.'</td>';
				$HTML .= '<td></td>';
				$HTML .= '<td class="taskStatus">'.$ammount.'</td>';
				$HTML .= '</tr>';	
			}
			
		}
	}
	
	//////////////////////////
	
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
/**
 * Generate a combobox with a date list
 * 
 * Method to generate a selection box of a date (month, year or a list of available vouchers on mediapension table) 
 * 
 * @access public
 * @api
 *
 * @param string|bool $mode
 * @return array|bool On Success it returns an array(email,username,user_id,hash)
 * 						which could then be use to construct the confirmation URL and Email.
 * 						On Failure it returns false
*/
function horasTrabajadas($employee_id, $month, $year){

	$HTML = '<table class="table table-bordered">';
	$HTML .= '<thead>';
	$HTML .= '<tr>';
    $HTML .= '<th>Día</th>';
    $HTML .= '<th>Entrada</th>';
    $HTML .= '<th>Intervalo</th>';
    $HTML .= '<th>Saída</th>';
    $HTML .= '<th>Trabalhado</th>';
    $HTML .= '</tr>';
    $HTML .= '</thead>';
    $HTML .= '<tbody>';
    
    $sql = "SELECT entrada.employee_id, ";
    $sql .= "DATE(entrada.date_time) data, ";
    $sql .= "MIN(entrada.point_id) identrada, ";
    $sql .= "MAX(salida.point_id) idsalida, ";
    $sql .= "TIME_FORMAT(TIME(MIN(entrada.date_time)), '%H:%i') entrada,  ";
    $sql .= "TIME_FORMAT(TIME(MAX(salida.date_time)), '%H:%i') salida ";
    
    $sql .= "FROM point AS entrada ";
    
    $sql .= "LEFT JOIN point AS salida ";
    $sql .= "ON entrada.employee_id = salida.employee_id ";
    $sql .= "AND DATE(entrada.date_time) = DATE(salida.date_time) ";

    $sql .= "WHERE 1 ";
    $sql .= "AND DATE(entrada.date_time) = DATE(salida.date_time) ";
    $sql .= "AND entrada.employee_id = ".$employee_id." ";
    $sql .= "AND salida.in_out = 0 ";
    $sql .= "AND MONTH(entrada.date_time) = ".$month." ";
    $sql .= "AND YEAR(entrada.date_time) = ".$year." ";
    
    $sql .= "GROUP BY DATE(entrada.date_time)";
    
    $result = resultFromQuery($sql);
    
    /*
    $sql = "SELECT employee_id, ";
    $sql .= "DATE(date_time) AS date, ";
    $sql .= "SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS hours_worked ";
    $sql .= "FROM point ";
    $sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
    $sql .= "GROUP BY date(date_time)";
    */
    $hs = 0;
    while ($row = siguienteResult($result)){
		
		$identrada = $row->identrada;
		$idsalida = $row->idsalida;
		
		if (!isset($prev_data)){
			$prev_data = $row->data;
		}
			
		//Si hay mas de un dia de diferencia entre la ultima vez que marcó
		if (date('d', strtotime($row->data)) - date('d', strtotime($prev_data)) >= 2){
			//
			$dstart = date('d', strtotime($prev_data)) + 1;
			$dend = date('d', strtotime($row->data));
			
			for ($i = $dstart; $i < $dend; $i++) {
				
				if (strlen($i) < 2){
					$i = '0'.$i;
				}
				
				$sql = "SELECT DAYNAME(valid_from) clearance, ";
				$sql .= "DAYNAME('".date('Y-m-', strtotime($row->data)).$i."') todayname ";
				$sql .= "FROM clearance ";
				$sql .= "WHERE 1 ";
				$sql .= "AND employee_id = ".$employee_id." ";
				$sql .= "AND valid_from <= '".date('Y-m-', strtotime($row->data)).$i."' ";
				$sql .= "ORDER BY valid_from DESC ";
				$sql .= "LIMIT 1 ";
				
				$rclearance = resultFromQuery($sql);
				
				if ($rowclearance = siguienteResult($rclearance)){
					
					$text = '';
					
					if($rowclearance->clearance != $rowclearance->todayname){
						
						//buscar feriados
						$sql = "SELECT * ";
						$sql .= "FROM holiday ";
						$sql .= "WHERE 1 ";
						$sql .= "AND day = '".date('Y-m-', strtotime($row->data)).$i."'";
						$rholiday = resultFromQuery($sql);

						if ($rowholiday = siguienteResult($rholiday)){
							$motive = 'Feriado';
							$HTML .= '<tr>';
							$HTML .= '<td><p align="center">'.$i.'</p></td>';
						}
						else{
							$motive = 'Ausente';
							$HTML .= '<tr>';
							$HTML .= '<td><p align="center" style="color:red">'.$i.'</p></td>';
						}
					}
					else{
						$motive = 'Folga';
						
						$HTML .= '<tr>';
						$HTML .= '<td><p align="center">'.$i.'</p></td>';
						
						
					}
					
					
					
				}
				else{
					$motive = 'Ausente ou dia de folga sem registrar';
					$HTML .= '<tr>';
					$HTML .= '<td><p align="center" style="color:red">'.$i.'</p></td>';
				
				}
				
				$HTML .= '<td><p align="center">'.$motive.'</p></td>';
				$HTML .= '<td><p align="center">'.$motive.'</p></td>';
				$HTML .= '<td><p align="center">'.$motive.'</p></td>';

				$HTML .= '<td><p align="center">0:00</p></td>';
				$HTML .= '</tr>';
				
				
				
				
				

			}
			
			$sql = "SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'";
			resultFromQuery($sql);
			
			$sql = "SELECT SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS hours_worked ";
			$sql .= "FROM point ";
			$sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
			$sql .= "AND DATE(date_time) = '".$row->data."'";
		


			$resultado = resultFromQuery($sql);
			
			if($hsworked = siguienteResult($resultado)){
				
				if ($hsworked->hours_worked > 0){
					
					$data = date_format(date_create_from_format('Y-m-d', $row->data), 'd');
					
					$HTML .= '<tr>';
					$HTML .= '<td><p align="center">'.$data.'</p></td>';
					$HTML .= '<td><p align="center">'.$row->entrada.'</p></td>';
					
					
					$sql = "SELECT SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS intervalo ";
					$sql .= "FROM point ";
					$sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
					$sql .= "AND DATE(date_time) = '".$row->data."'";
					$sql .= "AND point_id > '".$identrada."'";
					$sql .= "AND point_id < '".$idsalida."'";
					
					$resultintervalo = resultFromQuery($sql);
			
			
					if($intervalo = siguienteResult($resultintervalo)){
						$HTML .= '<td><p align="center">'.intHourstoNormal($intervalo->intervalo).'</p></td>';
					}
					else{
						$HTML .= '<td></td>';
					}

					$HTML .= '<td><p align="center">'.$row->salida.'</p></td>';
					$HTML .= '<td><p align="center">'.intHourstoNormal($hsworked->hours_worked).'</p></td>';
					$HTML .= '</tr>';
					$hs += $hsworked->hours_worked;
				}
			}
			
			$prev_data = date('Y-m-d', strtotime($row->data));

			
			
		}
		else{
			$prev_data = $row->data;
			
			$sql = "SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'";
			resultFromQuery($sql);
			
			$sql = "SELECT SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS hours_worked ";
			$sql .= "FROM point ";
			$sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
			$sql .= "AND DATE(date_time) = '".$row->data."'";
		


			$resultado = resultFromQuery($sql);
			
			if($hsworked = siguienteResult($resultado)){
				
				if ($hsworked->hours_worked > 0){
					
					$data = date_format(date_create_from_format('Y-m-d', $row->data), 'd');
					
					$HTML .= '<tr>';
					$HTML .= '<td><p align="center">'.$data.'</p></td>';
					$HTML .= '<td><p align="center">'.$row->entrada.'</p></td>';
					
					
					$sql = "SELECT SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS intervalo ";
					$sql .= "FROM point ";
					$sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
					$sql .= "AND DATE(date_time) = '".$row->data."'";
					$sql .= "AND point_id > '".$identrada."'";
					$sql .= "AND point_id < '".$idsalida."'";
					
					$resultintervalo = resultFromQuery($sql);
			
			
					if($intervalo = siguienteResult($resultintervalo)){
						$HTML .= '<td><p align="center">'.intHourstoNormal($intervalo->intervalo).'</p></td>';
					}
					else{
						$HTML .= '<td></td>';
					}

					$HTML .= '<td><p align="center">'.$row->salida.'</p></td>';
					$HTML .= '<td><p align="center">'.intHourstoNormal($hsworked->hours_worked).'</p></td>';
					$HTML .= '</tr>';
					$hs += $hsworked->hours_worked;
				}
			}
		}		
	}
	
	$HTML .= '<tr>';
	$HTML .= '<td><strong><p align="center">TOTAL</p></strong></td>';
	$HTML .= '<td></td>';
	$HTML .= '<td></td>';
	$HTML .= '<td></td>';
	$HTML .= '<td><strong><p align="center">'.intHourstoNormal($hs).'</p></strong></td>';
	$HTML .= '</tr>';
	
	$HTML .= '</tbody>';
    $HTML .= '</table>';
	
	return $HTML;
	
	
	
}

function intHourstoNormal($int){
	$int = abs($int);
	$num_hours = $int; //some float
	$hours = floor($num_hours);
	$mins = round(($num_hours - $hours) * 60);
	
	if ($mins < 10){
		$mins = '0'.$mins;
	}
	
	return $hours.":".$mins;
	
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
  <form method="post" action="funcionarios.pagamentos.php?employee_id=<?php echo $employee_id ?>">
	  <div class="container-fluid">
		<div class="row-fluid">
			<div id="no-print">
				<div class="control-group span1">
					Mes
					<select id="month" name="month">
						<? echo isset($comboMonth) ? $comboMonth : ''; ?>
					</select>
				</div>
			  
				<div class="control-group span2">
					Ano
					<select id="year" name="year">
						<? echo isset($comboYear) ? $comboYear : ''; ?>
					</select>
				</div>
						
				<div class="control-group span2"><br>
					<button class="btn btn-success" type="submit">Ver</button>
				</div>
			</div>
		</div>
	  </div>
  </form>
  
  
  
  
  
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Funcionario: <?php echo $salario['employee']['fullname']?> </h5>
          </div>
          
          <div class="widget-content nopadding">
			  <?php echo $table; ?>
                     
          </div>
        </div>
        <div id="no-print">
		<div class="widget-box">
			<div class="widget-title bg_lo" data-toggle="collapse" href="#collapseG3">
				<span class="icon">
					<i class="icon-chevron-down"></i>
				</span>
				<h5>Detalhes</h5>
			</div>
			<div class="widget-content nopadding updates collapse" id="collapseG3">
				<?php echo isset($details) ? $details : ''; ?>
			</div>
		</div>
		
		  <div class="accordion" id="collapse-group">
	  <div class="accordion-group widget-box">
		  <div class="accordion-heading">
			  <div class="widget-title"> 
				  <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse"> 
					  <span class="icon">
						  <i class="icon-chevron-down">
						  </i>
					  </span>
					  <h5>Resumo de ponto</h5>
				  </a>
			  </div>
		  </div>
          <div class="collapse accordion-body" id="collapseGOne">
			  <?php echo horasTrabajadas($employee_id, $month, $year);?> 
          </div>
	  </div>
  </div>
</div>
                    
        <?php echo isset($message) ? $message : '';?>
        
        <div id="no-print">			
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
		
		<form method="post" action="funcionarios.faltas.nova.php">
			<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
			<br>
			<button class="btn" type="submit" value="add">Adicionar Falta</button>
		</form>
		
		<form method="post" action="posts.php">
			<input type="hidden" id="accion" name="accion" value="employeeSyndicate" />
			
			<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />

			<button name='syndicate' class="btn btn-info icon-ok-circle" type="submit" value="add">Adicionar sindicato</button>
			<button name='syndicate' class="btn btn-danger icon-remove-sign" type="submit" value="remove">Apagar sindicato</button>
		</form>
		
		</div>
	</div>
  </div>
  
  
  
  
  
  
  
  
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
