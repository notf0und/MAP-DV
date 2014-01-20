<?php 
include "head.php";

//EMPLOYEE COMBO
if (isset($_POST['employee_id'])){
	$employee_id = $_POST['employee_id'];
}
$sqlQuery = "SELECT E.employee_id, ";
$sqlQuery .= "CONCAT(P.firstname, ' ', P.lastname) ";
$sqlQuery .= "FROM employee E ";
$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
$resultado = resultFromQuery($sqlQuery);
$comboemployee = comboFromArray('employee_id', $resultado, isset($employee_id) ? $employee_id : '', '', '', false, 'span4 m-wrap');

//PAYMENT TYPE COMBO
$sqlQuery = "SELECT paymenttype_id, ";
$sqlQuery .= "type ";
$sqlQuery .= "FROM paymenttype ";
$resultado = resultFromQuery($sqlQuery);
$combopaymenttype = comboFromArray('paymenttype_id', $resultado, isset($paymenttype_id) ? $paymenttype_id : '', '', '', false, 'span4 m-wrap');

//PAYMENT METHOD COMBO
$sqlQuery = "SELECT paymentmethod_id, ";
$sqlQuery .= "method ";
$sqlQuery .= "FROM paymentmethod ";
$resultado = resultFromQuery($sqlQuery);
$combopaymentmethod = comboFromArray('paymentmethod_id', $resultado, isset($paymentmethod_id) ? $paymentmethod_id : '', '', '', false, 'span4 m-wrap');
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="salarios.php" title="Pagamentos" class="tip-bottom">Pagamentos</a>
		<a href="#" class="current">Registrar Pagamento</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Registrar Pagamento</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="admitPayment" />

						<!--EMPLOYEE COMBO-->
						<div class="control-group">
							<label class="control-label">Funcionario</label>
							<div class="controls">
								<?php echo $comboemployee; ?>								
							</div>
						</div>
						
						<!--PAYMENT TYPE COMBO-->
						<div class="control-group">
							<label class="control-label">Tipo</label>
							<div class="controls">
								<?php echo $combopaymenttype; ?>
							</div>
						</div>
						
						<!--PAYMENT METHOD COMBO-->
						<div class="control-group">
							<label class="control-label">Método</label>
							<div class="controls">
								<?php echo $combopaymentmethod; ?>
							</div>
						</div>
						
						<!--AMMOUNT INPUT-->
						<div class="control-group">
							<label class="control-label">Monto</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on">$</span>
									<input id="ammount" name="ammount" type="text" class="span12 m-wrap"  required />
								</div>
							</div>
						</div>
						
						<!--DATE PICKER-->
						<div class="control-group">
							<div class="controls">
								<div data-date="" class="input-append date datepicker">
									<input id="date" name="date" type="text" data-date-format="yyyy-mm-dd" placeholder="AAAA-MM-DD" value="<?php echo isset($date) ? $date : '' ;?>">
									<span class="add-on"><i class="icon-th"></i></span>
								</div>
							</div>
						</div>
						
						<!--DETAILS TEXTAREA-->
						<div class="control-group">
							<label class="control-label">Detalhes</label>
							<div class="controls">
								<textarea id="details" name="details" class="span4"></textarea>
							</div>
						</div>
						

						<div class="control-group">
							<br><button class="btn btn-success" type="submit">Aceitar</button>
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
