<?php

include "head.php";

$month = isset($_POST['month']) ? $_POST['month'] : date('m');
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

$comboMonth = comboDate('month', $month);
$comboYear = comboDate('year', $year);


$empresaTable = previsionTable($month, $year);

function previsionTable($mes, $ano){
	

	//SELECT
	$sqlQuery = "SELECT ";
	$sqlQuery .= "E.employee_id id, ";
	$sqlQuery .= "NULL '&nbsp', ";
	$sqlQuery .= "EMP.nombre Empresa ";
	

	//FROM
	$sqlQuery .= "FROM employee E ";

	//Union de employee con profile
	$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
	
	$sqlQuery .= "LEFT JOIN empresa EMP ON E.idempresa = EMP.idempresa ";


	//Condicion de empresa
	$sqlQuery .= "WHERE 1 ";
	$sqlQuery .= "AND E.decline IS NULL OR E.decline > CURDATE()";
	$sqlQuery .= "ORDER BY CONCAT(P.firstname, ' ', P.lastname)";


	$resultado = resultFromQuery($sqlQuery);

	//start table
	$table = '<TABLE class="table table-bordered data-table" name="previsao" id="previsao">';
	$table .= '<thead><tr>';
	$table .= '<TH>&nbsp</TH>';
	$table .= '<TH>Nome Completo</TH>';
	$table .= '<TH>Empresa</TH>';
	$table .= '<TH>Base</TH>';
	$table .= '<TH>Abono</TH>';
	$table .= '<TH>Insalubridade</TH>';
	$table .= '<TH>S. Fam.</TH>';
	$table .= '<TH>Faltas</TH>';
	$table .= '<TH>INSS</TH>';
	$table .= '<TH>Transporte</TH>';
	$table .= '<TH>Sindicato</TH>';
	$table .= '<TH>Alimentação</TH>';
	$table .= '<TH>Adelantos</TH>';
	$table .= '<TH>Saldo</TH>';


	$table .= '</tr></thead><tbody>';

	while ($row = siguienteResult($resultado)){
		
		$salario = calcularSalario($row->id, $mes, $ano);
		/*
		var_dump($salario);
		echo '<br>';
		*/
		
		$table .= '<TR>';
		$table .= '<TD></TD>';
		$table .= '<TD>'.$salario['employee']['fullname'].'</TD>';
		$table .= '<TD>'.$row->Empresa.'</TD>';
		$table .= '<TD>'.$salario["+"]['Salario Base'].'</TD>';
		
		//Abono
		$table .= '<TD>';
		isset($salario['+']['Abono']) ? $table .= $salario['+']['Abono'] : '';
		$table .= '</TD>';
		
		//Insalubridade
		$table .= '<TD>';
		isset($salario['+']['Insalubridade']) ? $table .= $salario['+']['Insalubridade'] : '';
		$table .= '</TD>';
		
		//Salario Familia
		$table .= '<TD>';
		isset($salario['+']['Salario Familia']) ? $table .= $salario['+']['Salario Familia'] : '';
		$table .= '</TD>';
		
		//Faltas
		$table .= '<TD>';
		isset($salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')']) ? $table .= array_sum($salario['-']['Faltas ('.$salario['employee']['Non Attendance'].')'][0]) : '';
		$table .= '</TD>';
		
		//INSS
		$table .= '<TD>';
		isset($salario['-']['INSS']) ? $table .= $salario['-']['INSS'] : '';
		$table .= '</TD>';
		
		//Transporte
		$table .= '<TD>';
		isset($salario['-']['Transporte']) ? $table .= $salario['-']['Transporte'] : '';
		$table .= '</TD>';
		
		//Sindicato
		$table .= '<TD>';
		isset($salario['-']['Sindicato']) ? $table .= $salario['-']['Sindicato'] : '';
		$table .= '</TD>';
		
		//Alimentação
		$table .= '<TD>';
		isset($salario['-']['Alimentação']) ? $table .= $salario['-']['Alimentação'] : '';
		$table .= '</TD>';
		
		//Adelantos
		$table .= '<TD>';
		isset($salario['adelantos']) ? $table .= $salario['adelantos'] : '';
		$table .= '</TD>';
		
		//Saldo
		$table .= '<TD>';
		isset($salario['Total']) ? $table .= '<a href="funcionarios.pagamentos.php?employee_id='.$row->id.'">'.$salario['Total'].'</a>' : '';
		$table .= '</TD>';

		
		
	}
	$table .= '</TR></tbody></TABLE>';
	
	return $table;
}

	
?>	

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="pagamentos.php" title="Pagamentos" class="tip-bottom">Pagamentos</a>
		<a href="#" class="current">Previsão</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid span2">
		<div id="no-print">	

		<form method="post" action="pagamentos.previsao.php">
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
		</form>
		</div>
		<?php echo ($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 4) ? '<h2><b><div id="totalSalarios">
			Previsão: R$</div></b></h2>' : '';?>

          <div class="widget-content nopadding">
			  <?php echo $empresaTable;?>
          </div>
    	
	</div>
    <hr/>
  </div>

</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
