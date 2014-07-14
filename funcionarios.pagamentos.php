<?php

include "head.php"; 

$employee_id = $_GET['employee_id'];

$month = isset($_POST['month']) ? $_POST['month'] : date('m');
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

$comboMonth = comboDate('month', $month);
$comboYear = comboDate('year', $year);

$salario = isset($month) && isset($year) ? calcularSalario($employee_id, $month, $year) : calcularSalario($employee_id);
$table = salarioTable($salario, $month, $year);
$details = employeePaymentDetailsTable($employee_id, $month, $year, $salario);

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
	$sql .= "MAX(entrada.point_id) identradaerror, ";
	$sql .= "MAX(salida.point_id) idsalida, ";
	$sql .= "TIME_FORMAT(TIME(MIN(entrada.date_time)), '%H:%i') entrada,  ";
	$sql .= "MAX(entrada.date_time) dtentrada,  ";
	$sql .= "MAX(entrada.date_time) dtentradaerror,  ";
	$sql .= "MAX(salida.date_time) dtsalida,  ";
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
	if ($row = siguienteResult($result)){
			
			for($i = date('j', strtotime($point['start'])); $i <= date('j', strtotime($point['end'])); $i++){

				$worked = employeeWorkedDay($employee_id, $year."-".$month."-".$i);
				$worked['Time'] = '--:--';
				//echo 'Worked: '.$worked['Worked'].'<br>';
				
				if(isset($worked['Worked']) && $worked['Worked'] != 0){
					
					$sum = sumbolsa($carga, intHourstoNormal($worked['Worked']), $sum);
					$dias = $sum->format('%r%d') * 24;
					$horas = $sum->format('%H') + $dias;
					$worked['Time'] = $horas.$sum->format(':%I');
					
					if(!$sum->invert){
						$worked['Time'] = '+'.$worked['Time'];
					}
					else{
						$worked['Time'] = '-'.$worked['Time'];
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

function sumbolsa($carga, $hsworked, $prevsum=false){
	//echo var_dump($carga).'<br>';
	//echo var_dump($hsworked).'<br>';
	//echo var_dump($prevsum).'<br><br>';
	
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
					$color = '#FF5050';				}
				elseif($bolsa[$data]['Motive'] == 'Feriado'){
					$color = '#009999';
				}
				elseif($bolsa[$data]['Motive'] == 'Folga Extra'){
					$color = '#66CCFF';
				}

				elseif($bolsa[$data]['Motive'] == 'Ausente ou día de folga sem registrar'){
					$color = '#CC99FF';
				}
				elseif($bolsa[$data]['Motive'] == 'Folga' || $bolsa[$data]['Motive'] == 'Folga Temporal'){
					$color = '#00CC99';
				}
				elseif($bolsa[$data]['Motive'] == 'Trabalhando'){
					$color = '';
				}
				elseif($bolsa[$data]['Motive'] == 'Ferias'){
					$color = '#71C6E2';
				}
				else{
					$color = '#CC0000';
				}
				
				if(isset($_SESSION["idusuarios_tipos"]) && (($_SESSION["idusuarios_tipos"] == 1))){
					$table .= "\n\t\t\t\t".'<td><a href=funcionarios.pontos.detalhes.php?employee_id='.$_GET['employee_id'].'&date='.$data.'><p align="center" style="color:'.$color.'">'.date('d', strtotime($data)).'</p></a></td>';
				}
				else{
					$table .= "\n\t\t\t\t".'<td><p align="center" style="color:'.$color.'">'.date('d', strtotime($data)).'</p></td>';
				}

				
				$table .= "\n\t\t\t\t".'<td><p align="center" style="color:'.$color.'">'.$bolsa[$data]['Motive'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><b><p align="center" style="color:'.$color.'">'.$bolsa[$data]['Motive'].'</p></b></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center" style="color:'.$color.'">'.$bolsa[$data]['Motive'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">--:--</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center">'.$last_bolsa.'</p></td>';
				
				
			
			}
			else{
				
				if (isset($_SESSION["idusuarios_tipos"]) && (($_SESSION["idusuarios_tipos"] == 1))){
					$table .= "\n\t\t\t\t".'<td><a href=funcionarios.pontos.detalhes.php?employee_id='.$_GET['employee_id'].'&date='.$data.'><p align="center">'.date('d', strtotime($data)).'</p></a></td>';
				}
				else{
					$table .= "\n\t\t\t\t".'<td><p align="center">'.date('d', strtotime($data)).'</p></td>';
				}

				
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


$bolsa = bolsadehoras($employee_id, $month, $year);
$tblbolsa = bolsatotable($bolsa);

?>	

<!--main-container-part-->




<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
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
			<div class="accordion" id="collapse-group">
				<div class="accordion-group widget-box">
					<div class="accordion-heading">
						<div class="widget-title"> 
							<a data-parent="#collapse-group" href="#collapseGThree" data-toggle="collapse"> 
								<span class="icon">
									<i class="icon-chevron-down"></i>
								</span>
								<h5>Detalhes</h5>
								<div style='text-align:right'>
									<a href="funcionarios.pagamentos.printDetails.php?employee_id=<?php echo $employee_id; ?>&month=<?php echo $month; ?>&year=<?php echo $year; ?>">
										<i class="icon-print"></i>
									</a>
								</div>
							</a>
						</div>
					</div>
					<div class="collapse accordion-body" id="collapseGThree">
						<?php echo isset($details) ? $details : ''; ?>
					</div>
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
					  <h5>Resumo de ponto</h5><div style='text-align:right'><a href="funcionarios.pagamentos.printPoint.php?employee_id=<?php echo $employee_id; ?>&month=<?php echo $month; ?>&year=<?php echo $year; ?>"><i class="icon-print"></i></a></div>
					  
				  </a>
			  </div>
		  </div>
          <div class="collapse accordion-body" id="collapseGOne">
			  <?php echo $tblbolsa;?> 
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
		<!--
		<form method="post" action="funcionarios.faltas.nova.php">
			<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
			<br>
			<button class="btn" type="submit" value="add">Adicionar Falta</button>
		</form>
		-->
		<form method="post" action="posts.php">
			<input type="hidden" id="accion" name="accion" value="employeeSyndicate" />
			
			<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />

			<button name='syndicate' class="btn btn-info icon-ok-circle" type="submit" value="add">Adicionar sindicato</button>
			<button name='syndicate' class="btn btn-danger icon-remove-sign" type="submit" value="remove">Apagar sindicato</button>
		</form>
		<a href="funcionarios.pontos.periodosemtrabalhar.php?employee_id=<?php echo isset($employee_id) ? $employee_id : '';?>"><button class="btn btn-primary">Periodo sem trabalhar</button></a>
		
		</div>
	</div>
  </div>

</div>



<!--end-main-container-part-->

<?php include "footer.php"; ?>
