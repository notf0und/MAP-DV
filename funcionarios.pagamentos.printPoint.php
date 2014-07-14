<?php

include "head.php"; 

$employee_id = $_GET['employee_id'];

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

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
	
	$table = "\n\t".'<table style = "width: 100%; border-collapse: collapse; padding: 0" border="1">';
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
				elseif($bolsa[$data]['Motive'] == 'Folga'){
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
				$table .= "\n\t\t\t\t".'<td><p align="center" style="color:'.$color.'">'.date('d', strtotime($data)).'</p></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center" style="color:'.$color.'">'.$bolsa[$data]['Motive'].'</p></td>';
				$table .= "\n\t\t\t\t".'<td><b><p align="center" style="color:'.$color.'">'.$bolsa[$data]['Motive'].'</p></b></td>';
				$table .= "\n\t\t\t\t".'<td><p align="center" style="color:'.$color.'">'.$bolsa[$data]['Motive'].'</p></td>';
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


$bolsa = bolsadehoras($employee_id, $month, $year);
$tblbolsa = bolsatotable($bolsa);

$sql = "SELECT CONCAT(P.firstname, ' ', P.lastname) Funcionario, ";
$sql .= 'C.name cname, ';
$sql .= 'C.cnpj cnpj, ';
$sql .= 'A.street street, ';
$sql .= 'A.number number, ';
$sql .= 'A.block block, ';
$sql .= 'A.batch batch, ';
$sql .= 'A.neightborhood neightborhood ';



$sql .= 'FROM employee E ';

$sql .= 'LEFT JOIN profile P ';
$sql .= 'ON E.profile_id = P.profile_id ';

$sql .= 'LEFT JOIN employee_corporate EC ';
$sql .= 'ON E.employee_id = EC.employee_id ';

$sql .= 'LEFT JOIN corporate C ';
$sql .= 'ON EC.corporate_id = C.corporate_id ';

$sql .= 'LEFT JOIN address A ';
$sql .= 'ON C.address_id = A.address_id ';


$sql .= 'WHERE 1 ';
$sql .= 'AND E.employee_id = '.$employee_id.' ';

$sql .= 'ORDER BY EC.employee_corporate_id DESC ';
$sql .= 'LIMIT 1;';
$result = resultFromQuery($sql);
$row = siguienteResult($result);

$funcionario = $row->Funcionario;
$cname = $row->cname;
$cnpj = $row->cnpj;
$street = $row->street;
$number = $row->number;
$block = $row->block;
$batch = $row->batch;
$batch = $row->batch;
$neightborhood = $row->neightborhood;


$firstline ='Razão Social: '.$cname;
$cnpj != '' ? $firstline .= " -- CNPJ: ".$cnpj : '';

$secondline = 'Endereço: '.$street;
$secondline .= $street != '' ? ($number != '' ?  ' Nº '.$number : ' S/N') : '';
$block != '' ? $secondline .= ' Quadra: '.$block: '';
$batch != '' ? $secondline .= ' Lote: '.$batch: '';
$neightborhood != '' ? $secondline .= ' - '.$neightborhood: '';



?>	

<!--main-container-part-->
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
		 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="funcionarios.lista.php" title="Lista de funcionarios" class="tip-bottom">Lista de funcionarios</a>
		<a href="funcionarios.pagamentos?employee_id=<?php echo $employee_id; ?>" title="Balance de Salario" class="tip-bottom">Balance de Salario</a>
		<a href="#" class="current">Registro de pontos</a>
		
    </div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		  <h5> <?php echo $firstline;?></h5>
		  <h5> <?php echo $secondline;?></h5>
		  <h5> <?php echo 'Funcionario: '.$funcionario.' - Período '.$month.'/'.$year?></h5>
		  
		  <div class="widget-box">


			  <?php echo $tblbolsa?>

        </div>
      </div>
    </div>
    <br>
    <p>Observações:________________________________________________________________________________________________________________________________________________________________________________________________</p>
    <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________</p>
  </div>
  <br>
<p align="center">_______________________________</p>
<h5><p align="center"><?php echo $funcionario?></p></h5>
</div>

<!--Footer-part-->
<?php include "footer.php"; ?>
