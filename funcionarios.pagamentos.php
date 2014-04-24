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
	
function bolsadehoras($employee_id, $month, $year){
	
	////////////STEP - 1//////////////////
	$sql = "SELECT fromhour 'Entrada', ";
	$sql .= "intervalhour 'Descanso', ";
	$sql .= "tohour 'Salida', ";
	$sql .= "SEC_TO_TIME(SUM(TIME_TO_SEC(tohour) - TIME_TO_SEC(fromhour) - TIME_TO_SEC(intervalhour) ) ) AS 'Carga' ";
	$sql .= "FROM employee ";
	$sql .= "WHERE 1 ";
	$sql .= "AND employee_id = ".$employee_id.";";
	
	$result = resultFromQuery($sql);
	
	$row = siguienteResult($result);
		
	$code = "<code>";
	$code .= "Entrada: ".$row->Entrada."<br>";
	$code .= "Descanso: ".$row->Descanso."<br>";
	$code .= "Salida: ".$row->Salida."<br>";
	
	//Si la carga que devuelve es negativa, es porque el empleado trabaja en un dia y sale en el otro
	if ($row->Carga < 0){
		//consultar la carga de una forma diferente
		$sql = "select SEC_TO_TIME(SUM(TIME_TO_SEC('24:00') - TIME_TO_SEC(fromhour)) + SUM(TIME_TO_SEC(tohour) - TIME_TO_SEC('00:00') - TIME_TO_SEC(intervalhour))) as Carga ";
		$sql .= "FROM employee ";
		$sql .= "WHERE 1 ";
		$sql .= "AND employee_id = ".$employee_id.";";
		
		$result = resultFromQuery($sql);
		$row = siguienteResult($result);
		
		$carga = $row->Carga;
		$carganegativa = 1;
		
		$code .= "Carga negativa: ".$row->Carga."<br>";
		

	}
	else{
		$carga = $row->Carga;
		$code .= "Carga positiva: ".$row->Carga."<br>";
	}
	
	$carga = date('H:i', strtotime($carga));
	$point = daysforPoint($employee_id, $month, $year);
	$sum = false;
	
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

		// mysql_num_rows($result) me muestra la cantidad de dias que se ha registrado en el mes seleccionado
		if (mysql_num_rows($result)>0){
			
			for($i = date('j', strtotime($point['start'])); $i <= date('j', strtotime($point['end'])); $i++){
				
				$worked = employeeWorkedDay($employee_id, $year."-".$month."-".$i);
				
				if(isset($worked['Worked']) && $worked['Worked'] != 0){
					

					$sum = sumbolsa($carga, intHourstoNormal($worked['Worked']), $sum);

					
					$worked['Time'] = $sum->format('%r%H:%I');
					
					if(!$sum->invert){
						$worked['Time'] = '+'.$worked['Time'];
					}
				}

				
				if(!isset($worked['Motive'])){

					$return[$worked['Data']]["Entrada"] = $worked['Entrada'];
					$return[$worked['Data']]["Intervalo"] = $worked['Intervalo'];
					$return[$worked['Data']]["Salida"] = $worked['Salida'];
					$return[$worked['Data']]["Trabalhado"] = intHourstoNormal($worked['Worked']);
					$return[$worked['Data']]["Bolsa"] = $worked['Time'];

				}
				else{
					$return[$worked['Data']]['Motive'] = $worked['Motive'];
				}



			}
			
			
		}
		else{

			for($i = date('j', strtotime($point['start'])); $i <= date('j', strtotime($point['end'])); $i++){
				
				$worked = employeeWorkedDay($employee_id, $year."-".$month."-".$i);
				
				if(isset($worked['Motive'])){
				
					$return[$worked['Data']]['Motive'] = $worked['Motive'];
				}

			}
			//Si no hay un resultado para el mes seleccionado
			//Buscar si hay un registro en el punto del empleado en meses anteriores
			
			//Si hay, meter falta, folga, atestado o ausente hasta el ultimo dia del mes
		}
	
	$code .= "</code>";
	

	return $return;
}

function daysforPoint($employee_id, $month, $year){

	//PointStart
	$sql = "SELECT admission ";
	$sql .= "FROM employee ";
	$sql .= "WHERE 1 ";
	$sql .= "AND employee_id = ".$employee_id." ";

	
	$result = resultFromQuery($sql);
	if($row = siguienteResult($result)){
		if($row->admission <= date('Y-m-01', strtotime($year.'-'.$month))){
			$point['start'] = $year.'-'.$month.'-01';
		}
		else{
			//Buscar el momento el que el empleado comenzó a trabajar
			$point['start'] = $row->admission;
		}
	}
	
	
	//pointEND
	$d1 = new DateTime($year.'-'.$month);
	$d2 = new DateTime(date('Y-m'));
		
	//Buscar si el empleado tiene fecha de despido en el mes seleccionado
	$sql = "SELECT decline ";
	$sql .= "FROM employee ";
	$sql .= "WHERE 1 ";
	$sql .= "AND employee_id = ".$employee_id." ";

	
	$result = resultFromQuery($sql);
	
	if ($row = siguienteResult($result)){
		if($row->decline && $row->decline < date("Y-m-d", strtotime($point['start']))){
			$point['start'] = $row->decline;
			$point['end'] = $row->decline;
		}
		elseif($row->decline && $row->decline <= date('Y-m-t', strtotime($year.'-'.$month))){
			if($d1 < $d2){
				$point['end'] = date("Y-m-t", strtotime($year.'-'.$month));
			}
			elseif ($d1 == $d2){
				if ($row->decline && date("Y-m-d", strtotime($row->decline)) > date("Y-m-d")){
					$point['end'] = date("Y-m-d");
				}
				else{
					$point['end'] = $row->decline;
				}
			}
			else{
				$point['end'] = '1231231';
				//si el mes es futuro, no deberia establecer un pointEnd
			}
			
		}
		else{
			if($d1 < $d2){
				$point['end'] = date("Y-m-t", strtotime($year.'-'.$month));
			}
			elseif ($d1 == $d2){
				$point['end'] = date("Y-m-d");
			}
			else{
				$point['end'] = '1231231';
				//si el mes es futuro, no deberia establecer un pointEnd
			}				
		}
		
	}

	return $point;
}

function employeeWorkedDay($employee_id, $date){
	
	$sql = "SELECT entrada.employee_id, ";
    $sql .= "DATE(entrada.date_time) data, ";
    $sql .= "MIN(entrada.point_id) identrada, ";
    $sql .= "MAX(salida.point_id) idsalida, "; 
    $sql .= "MIN(entrada.date_time) dtentrada, "; 
	$sql .= "MAX(salida.date_time) dtsalida, "; 
    $sql .= "TIME_FORMAT(TIME(MIN(entrada.date_time)), '%H:%i') entrada, ";  
    $sql .= "TIME_FORMAT(TIME(MAX(salida.date_time)), '%H:%i') salida ";       
    
    $sql .= "FROM point AS entrada ";   
    
    $sql .= "LEFT JOIN point AS salida ";
    $sql .= "ON entrada.employee_id = salida.employee_id ";  
    
    $sql .= "LEFT JOIN employee AS E ";
    $sql .= "ON entrada.employee_id = E.employee_id ";
    
    $sql .= "WHERE 1 ";
    $sql .= "AND entrada.employee_id = ".$employee_id." ";
    $sql .= "AND salida.in_out = 0 ";
    $sql .= "AND date(entrada.date_time) = '".$date."' ";
    $sql .= "AND entrada.date_time > DATE_ADD(date(entrada.date_time), interval HOUR(E.fromhour) - 2 hour) ";
    $sql .= "AND salida.date_time < DATE_ADD(DATE_ADD(date(entrada.date_time), interval 1 day), interval HOUR(E.fromhour) - 2  hour); ";
	
	$result = resultFromQuery($sql);
	
	if($row = siguienteResult($result)){
		$return['Data'] = $date;
		
		if ($row->data == NULL){
			
			//Buscar motivo de ausencia de punto
			$return['Worked'] = '0';
			$return['Motive'] = employeeClearance($employee_id, $date);
		}
		else{
			//Si la consulta devuelve informacion, es porque hay informacion relativa al punto

			
			/////////////////////////////
			$sql = "SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'";
			resultFromQuery($sql);
			
			$sql = "SELECT SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS hours_worked ";
			$sql .= "FROM point ";
			$sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
			$sql .= "AND date_time > '".$row->dtentrada."' ";
			$sql .= "AND date_time < '".$row->dtsalida."'";

			$result = resultFromQuery($sql);
			$intervalo = siguienteResult($result);
			
			$sql = "SELECT SUM(UNIX_TIMESTAMP(date_time)*(1-2*in_out))/3600 AS hours_worked ";
			$sql .= "FROM point ";
			$sql .= "WHERE 1 AND employee_id = ".$employee_id." ";
			$sql .= "AND date_time >= '".$row->dtentrada."' ";
			$sql .= "AND date_time <= '".$row->dtsalida."'";
			/////////////////////////////
			$result = resultFromQuery($sql);
			$hsworked = siguienteResult($result);
			
			$return['Entrada'] = $row->entrada;
			$return['Intervalo'] = intHourstoNormal($intervalo->hours_worked);
			$return['Salida'] = $row->salida;
			$return['Worked'] = $hsworked->hours_worked;
			
			
		}
		
		return $return;
	}
	else{
		return '<code>AQUI NO VA NADA</code>';
	}
}

function employeeClearance($employee_id, $date){
	
	$sql = "SELECT DAYNAME(valid_from) clearance, ";
	$sql .= "DAYNAME('".$date."') todayname ";
	$sql .= "FROM clearance ";
	$sql .= "WHERE 1 ";
	$sql .= "AND employee_id = ".$employee_id." ";
	$sql .= "AND valid_from <= '".$date."' ";
	$sql .= "ORDER BY valid_from DESC ";
	$sql .= "LIMIT 1 ";
	
	$rclearance = resultFromQuery($sql);
	
	if ($rowclearance = siguienteResult($rclearance)){
			
		if($rowclearance->clearance != $rowclearance->todayname){
			//buscar feriados
			$sql = "SELECT * ";
			$sql .= "FROM holiday ";
			$sql .= "WHERE 1 ";
			$sql .= "AND day = '".$date."'";
			$rholiday = resultFromQuery($sql);
			
			if ($rowholiday = siguienteResult($rholiday)){
				$motive = 'Feriado';
			}
			else{
				$motive = 'Ausente';
			}
		}
		else{
			$motive = 'Folga';
		}
	}
	else{
		$motive = 'Ausente ou día de folga sem registrar';
	}
	return $motive;
}

function sumbolsa($carga, $hsworked, $prevsum=false){
		
	$start = DateTime::createFromFormat('H:i', $carga);

	$end   = DateTime::createFromFormat('H:i', $hsworked);
	if(isset($prevsum) && $prevsum != false){
		$start->sub($prevsum);
	}
	$diff = $start->diff($end);
	return $diff;
	
}

function bolsatotable($bolsa){
	$last_bolsa = '00:00';
	
	$table = "\n\t".'<table class="table table-bordered">';
	$table .= "\n\t\t".'<thead>';
	$table .= "\n\t\t\t".'<tr>';
	$table .= "\n\t\t\t\t".'<th>Día</th>';
	$table .= "\n\t\t\t\t".'<th>Entrada</th>';
	$table .= "\n\t\t\t\t".'<th>Intervalo</th>';
	$table .= "\n\t\t\t\t".'<th>Saída</th>';
	$table .= "\n\t\t\t\t".'<th>Trabalhado</th>';
	$table .= "\n\t\t\t\t".'<th>Bolsa de horas</th>';
	$table .= "\n\t\t\t".'</tr>';
	$table .= "\n\t\t".'</thead>';
	$table .= "\n\t\t".'<tbody>';
	


	if(count($bolsa) > 0){
		
		foreach($bolsa as $data=>$details) {
			
			$table .= "\n\t\t\t".'<tr>';
			
			
			if(isset($bolsa[$data]['Motive'])){
				if($bolsa[$data]['Motive'] == 'Ausente'){
					$table .= "\n\t\t\t\t".'<td><p align="center" style="color:red">'.date('d', strtotime($data)).'</p></td>';
				}
				elseif($bolsa[$data]['Motive'] == 'Feriado'){
					$table .= "\n\t\t\t\t".'<td><p align="center" style="color:blue">'.date('d', strtotime($data)).'</p></td>';
				}
				elseif($bolsa[$data]['Motive'] == 'Ausente ou día de folga sem registrar'){
					$table .= "\n\t\t\t\t".'<td><p align="center" style="color:orange">'.date('d', strtotime($data)).'</p></td>';
				}
				else{
					$table .= "\n\t\t\t\t".'<td><p align="center" style="color:green">'.date('d', strtotime($data)).'</p></td>';
				}
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$bolsa[$data]['Motive'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$bolsa[$data]['Motive'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$bolsa[$data]['Motive'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">--:--</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$last_bolsa.'</p></td>';
				
				
			
			}
			else{
				$table .= "\n\t\t\t\t".'<td><p align="center">'.date('d', strtotime($data)).'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$bolsa[$data]['Entrada'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$bolsa[$data]['Intervalo'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$bolsa[$data]['Salida'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$bolsa[$data]['Trabalhado'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$bolsa[$data]['Bolsa'].'</p></td>';
				$last_bolsa = $bolsa[$data]['Bolsa'];
			}
				

			$table .= "\n\t\t\t".'</tr>';

		}
	}
	$table .= "\n\t\t".'</tbody>';
	$table .= "\n\t".'</table>';
	return $table;
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
			  <?php 
				$bolsa = bolsadehoras($employee_id, $month, $year);
				$tblbolsa = bolsatotable($bolsa);
				echo $tblbolsa;
			  
			  
			  
			  //echo horasTrabajadas($employee_id, $month, $year);?> 
          </div>
	  </div>	  
  </div>
</div>
                    
        <?php echo isset($message) ? $message : '';?>
        
        <div id="no-print">	
			<?php if (isset($_SESSION["idusuarios_tipos"]) && (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 4) || ($_SESSION["idusuarios_tipos"] == 5) || ($_SESSION["idusuarios_tipos"] == 8))) {?>
        <form method="post" action="pagamentos.novo.php">
			<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
			<button class="btn btn-success" type="submit">Adicionar Desconto</button>
		</form><br>
		<?php }?>
		
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
