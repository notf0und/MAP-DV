<?php

include "head.php"; 

$employee_id = $_GET['employee_id'];

$month = isset($_POST['month']) ? $_POST['month'] : date('m');
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

$comboMonth = comboDate('month', $month);
$comboYear = comboDate('year', $year);

$salario = isset($month) && isset($year) ? calcularSalario($employee_id, $month, $year) : calcularSalario($employee_id);

$table = salarioTable($salario);


if (isset($salario['employee']['Declined'])){
	$message = 'O funcionario deixou de trabalhar no día '.$salario['employee']['Decline Date'].'. Não se pode calcular o salario para esta data.';
}
elseif ($month == date('m') && $year == date('Y')){
	$message = 'Salario calculado en base a os '.$salario['employee']['Worked Days'].' dias a trabalhar no mes atual';
}
elseif ($salario['employee']['Worked Days'] <= 0){
	$message = 'O funcionario ainda não trabalhaba nesta data';
}
elseif ($salario['employee']['Non Attendance']){
	$message = 'Salario calculado en base a os '.($salario['employee']['Worked Days'] - $salario['employee']['Non Attendance'] - 1 ).' dias trabalhados no período '.$month.'/'.$year;
}
else{
	$message = 'Salario calculado en base a os '.($salario['employee']['Worked Days']).' dias trabalhados no período '.$month.'/'.$year;
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
    
    if ($salario['employee']['Worked Days'] > 0){
		
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
function comboDate($mode = false, $selected = false){
	
	$combo = '';
	
	if (isset($mode) && $mode == 'month'){
		
		for ($i=1; $i<=12; $i++){
			$combo .= '<option value="'.$i.'" ';
			$combo .= isset($selected) && $i==$selected ? 'selected' : '';
			$combo .= '>'.$i.'</option>';
			$combo .= "\r\n";
		}
	}	
	elseif (isset($mode) && $mode == 'year'){
		
		for ($i=2013; $i<=2014; $i++){
			$combo .= '<option value="'.$i.'" ';
			$combo .= isset($selected) && $i==$selected ? 'selected' : '';
			$combo .= '>'.$i.'</option>';
			$combo .= "\r\n";
		}
	}
	return $combo;
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
