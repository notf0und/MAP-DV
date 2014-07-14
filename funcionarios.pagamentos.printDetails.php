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
$details = employeePaymentDetailsTable($employee_id, $month, $year, $salario);

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
