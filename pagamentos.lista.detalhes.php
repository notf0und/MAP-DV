<?php

include "head.php"; 

$employee_id = $_GET['employee_id'];

$month = isset($_POST['month']) ? $_POST['month'] : date('m');
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

$comboMonth = comboDate('month', $month);
$comboYear = comboDate('year', $year);

	$sql = "SELECT  SUM(PAY.ammount) ammount,  PT.type type ";
	$sql .= "FROM payment PAY ";
	$sql .= "LEFT JOIN paymenttype PT ON PAY.paymenttype_id = PT.paymenttype_id ";
	$sql .= "WHERE PAY.enabled = 1 ";
	$sql .= "AND PAY.employee_id = ".$employee_id ." ";
	$sql .= "AND month(PAY.date) = ".$month." ";
	$sql .= "AND year(PAY.date) = ".$year." ";
	$sql .= "GROUP BY type;";

	$result = resultFromQuery($sql);
	while ($row = siguienteResult($result)) {
		$details[] = array($row->type => $row->ammount);
	}
	
	$HTML = '<table class="table table-striped table-bordered">';
	$HTML .= '<thead>';
	$HTML .= '<tr>';
    $HTML .= '<th>Descrição</th>';
    $HTML .= '<th>Monto</th>';
    $HTML .= '</tr>';
    $HTML .= '</thead>';
    $HTML .= '<tbody>';
    
	$element = '';
	$sumammount = 0;
	if (isset($details)){
		foreach ($details as $detail) {
			
			foreach ($detail as $type => $ammount) {
				
				$ammount = round($ammount, 2);
				
				$sumammount += $ammount;
				
				$HTML .= '<tr>';
				$HTML .= '<td class="taskDesc"><i class="icon-minus-sign"></i>'.$type.'</td>';
				$HTML .= '<td class="taskStatus">'.$ammount.'</td>';
				$HTML .= '</tr>';

			}
			
		}
	}
	
	$HTML .= '<tr>';
	$HTML .= '<td></td>';
	$HTML .= '<td></td>';
	$HTML .= '</tr>';
	
	$HTML .= '<tr>';
	$HTML .= '<td>TOTAL</td>';
	$HTML .= '<td><center>'.$sumammount.'</center></td>';
	$HTML .= '</tr>';
	
	$HTML .= '</tbody>';
    $HTML .= '</table>';
	




?>	

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="pagamentos.php" title="Pagamentos" class="tip-bottom">Pagamentos</a>
		<a href="pagamentos.lista.php" title="Lista de Pagamentos" class="tip-bottom">Lista de Pagamentos</a>
		<a href="#" class="current">Detalhes</a>

	</div>
  </div>
<!--End-breadcrumbs-->

 <form method="post" action="pagamentos.lista.detalhes.php?employee_id=<?php echo $employee_id ?>">
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
            <h5>Lista de Pagamentos</h5>
          </div>
          <div class="widget-content nopadding">
			  <form id="paymentForm" name="paymentForm" action="posts.php" method="post">
				  <!--Tabla-->
				  <?php echo $HTML;?>
				  <!--Tabla-->
				</form> 
					  
          </div>
			<div id="myModal" class="modal hide">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button">×</button>
					<h3>Detalle</h3>
				</div>
				<div class="modal-body" id="modal-body">
					<p>Here is the text coming you can put also image if you want…</p>
				</div>
			</div>
        </div>
		<form method="get" action="pagamentos.novo.php">
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
