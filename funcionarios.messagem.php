<?php 
include "head.php";

//
if (isset($_SESSION['payment_id'])){
	
	$payment_id = $_SESSION['payment_id'];
	unset($_SESSION['payment_id']);
	
	$sql = "SELECT employee_id, paymenttype_id, paymentmethod_id, ammount, date(date) date, details ";
	$sql .= "FROM payment ";
	$sql .= "WHERE payment_id = ".$payment_id;
	$result = resultFromQuery($sql);
	
	if ($row = siguienteResult($result)){
		$employee_id = $row->employee_id;
		$paymenttype_id = $row->paymenttype_id;
		$paymentmethod_id = $row->paymentmethod_id;
		$ammount = $row->ammount;
		$details = $row->details;
		$date = $row->date;
	}
}

//EMPLOYEE COMBO
$sqlQuery = "SELECT E.employee_id, ";
$sqlQuery .= "CONCAT(P.firstname, ' ', P.lastname) ";
$sqlQuery .= "FROM employee E ";
$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
$resultado = resultFromQuery($sqlQuery);
$comboemployee = comboFromArray('employee_id', $resultado, isset($employee_id) ? $employee_id : $_GET['employee_id'], '', '', false, 'span4 m-wrap');

?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="salarios.php" title="Pagamentos" class="tip-bottom">Funcionarios</a>
		<a href="#" class="current">Enviar messagem</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-envelope"></i> </span>
					<h5>Enviar messagem</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="sendPointMessage" />

						<!--EMPLOYEE COMBO-->
						<div class="control-group">
							<label class="control-label">Funcionario</label>
							<div class="controls">
								<?php echo $comboemployee; ?>								
							</div>
						</div>
						
						<!--DATE PICKER-->
						<div class="control-group">
							<label class="control-label">Data</label>
							<div class="controls">
								<div data-date="" class="input-append date datepicker">
									<input id="date" name="date" type="text" data-date-format="yyyy-mm-dd" placeholder="AAAA-MM-DD" value="<?php echo isset($date) ? $date : '' ;?>">
									<span class="add-on"><i class="icon-th"></i></span>
								</div>
							</div>
						</div>
						
						<!--DETAILS TEXTAREA-->
						<div class="control-group">
							<label class="control-label">Messagem</label>
							<div class="controls">
								<textarea id="message" name="message" class="span10"><?php echo isset($message) ? $message : '' ;?></textarea>
							</div>
						</div>

						<div class="control-group">
							<div align="center">
							<br><button class="btn btn-primary" type="submit">Enviar messagem</button></div>
							<br><br>
						</div>
						<div id="status"></div>
					</form>

				</div>

			</div>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
