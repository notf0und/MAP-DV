<?php

include "head.php"; 

//include "dBug.php";
//new dBug($_POST);


//SELECT
$employee_id = $_GET['employee_id'];

$sqlQuery = "SELECT ";
$sqlQuery .= "E.employee_id id, ";
$sqlQuery .= "BS.basesalary base, ";
$sqlQuery .= "E.bonussalary, ";
$sqlQuery .= "E.transport, ";
$sqlQuery .= "CONCAT(P.lastname, ', ', P.firstname) fullname ";

//FROM
$sqlQuery .= "FROM employee E ";

$sqlQuery .= "LEFT JOIN jobcategory JC ON E.jobcategory_id = JC.jobcategory_id ";

$sqlQuery .= "LEFT JOIN basesalary BS ON JC.basesalary_id = BS.basesalary_id ";

$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";

$sqlQuery .= "WHERE E.employee_id = ".$employee_id;


$resultado = resultFromQuery($sqlQuery);		
		
if ($row = siguienteResult($resultado)) {
	
	$basesalary = $row->base;
	$bonussalary = $row->bonussalary;
	$transport = $row->transport;
	$fullname = $row->fullname;
}



$transportdiscount = $basesalary*0.06;


//Pagos recibidos durante el ultimo mes
//SELECT
$sqlQuery = "SELECT ";
$sqlQuery .= "PAY.payment_id id, ";
$sqlQuery .= "PAY.ammount, ";
$sqlQuery .= "DATE_FORMAT(PAY.date, '%d/%m') date, ";
$sqlQuery .= "PT.type ";

//FROM
$sqlQuery .= "FROM payment PAY ";

$sqlQuery .= "LEFT JOIN paymenttype PT ON PAY.paymenttype_id = PT.paymenttype_id ";

$sqlQuery .= "WHERE PAY.employee_id = ".$_GET['employee_id'];



	
$resultado = resultFromQuery($sqlQuery);

while ($row = mysql_fetch_object($resultado)) {
	
	$stringHTML .= '<tr>';
	$stringHTML .= '<td class="taskDesc"><i class="icon-minus-sign"></i>'.$row->type. " ".$row->date.'</td>';
	$stringHTML .= '<td></td>';
	$stringHTML .= '<td class="taskStatus">'.$row->ammount.'</td>';
	$stringHTML .= '</tr>';
	
	$adelantos +=  $row->ammount;
	
}


$saldototal = $basesalary + $bonussalary - $adelantos - $transportdiscount;
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
            <h5>Balance de Salario: <?php echo $fullname;?></h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Descrição</th>
                  <th>Vencimientos</th>
                  <th>Descontos</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="taskDesc"><i class="icon-plus-sign"></i> Salario Base</td>
                  <td class="taskStatus"><?php echo $basesalary;?></td>
                  <td></td>
                </tr>
                
                <?php if (isset($bonussalary)) {?>
					<tr>
						<td class="taskDesc"><i class="icon-plus-sign"></i> Abono</td>
						<td class="taskStatus"><?php echo $bonussalary;?></td>
						<td></td>
					</tr>
				<?php } ?>
                
                
                <?php if (isset($transport)) {?>
					<tr>
						<td class="taskDesc"><i class="icon-minus-sign"></i> Vale Transporte</td>
						<td></td>
						<td class="taskStatus"><?php echo $transportdiscount;?></td>
					</tr>
				<?php } ?>
				
				
				<?php echo $stringHTML;?>
				
				
				<tr>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				
				<tr>
					<td class="taskDesc">TOTAL</td>
					<td class="taskStatus"> <b>R$<?php echo $saldototal;?></b></td>
					<td></td>
				</tr>
				
              </tbody>
            </table>
            
          </div>
        </div>
        
        <form method="get" action="pagamentos.novo.php">
			<button class="btn btn-success" type="submit">Adicionar Pagamento</button>
		</form>  
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
