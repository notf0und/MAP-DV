<?php 
include "head.php";

//
if (isset($_REQUEST['employee_id'])){
	$employee_id = $_REQUEST['employee_id'];
}


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
$comboemployee = comboFromArray('employee_id', $resultado, isset($employee_id) ? $employee_id : '', '', '', false, 'span4 m-wrap');

//PAYMENT TYPE COMBO
$sqlQuery = "SELECT paymenttype_id, ";
$sqlQuery .= "type ";
$sqlQuery .= "FROM paymenttype ";
$resultado = resultFromQuery($sqlQuery);
$combopaymenttype = comboFromArray('paymenttype_id', $resultado, isset($paymenttype_id) ? $paymenttype_id : '', 'getAmmount(this.value)', '', false, 'span4 m-wrap');

//PAYMENT METHOD COMBO
$sqlQuery = "SELECT paymentmethod_id, ";
$sqlQuery .= "method ";
$sqlQuery .= "FROM paymentmethod ";
$resultado = resultFromQuery($sqlQuery);
$combopaymentmethod = comboFromArray('paymentmethod_id', $resultado, isset($paymentmethod_id) ? $paymentmethod_id : '', '', '', false, 'span4 m-wrap');
?>

<!--main-container-part-->
<script language="javascript" type="text/javascript">
	
	var rowNum = 0;
	function addRow(frm) {
		rowNum ++;
		//var row = '<p id="rowNum'+rowNum+'">Item quantity: <input type="text" name="qty[]" size="4" value="'+frm.add_qty.value+'"> Item name: <input type="text" name="name[]" value="'+frm.add_name.value+'"> <input type="button" value="Remove" onclick="removeRow('+rowNum+');"></p>';
		var row = '<div class="control-group"><label class="control-label">Tipo</label><div class="controls"><SELECT ID="paymenttype_id" NAME="paymenttype_id" SIZE="1" onchange="getAmmount(this.value)" STYLE="" CLASS="span4 m-wrap"><OPTION STYLE="display:none" VALUE="0"></OPTION><OPTION VALUE="1">Adiantamento de salario</OPTION><OPTION VALUE="2">Saldo de Salario</OPTION><OPTION VALUE="3">Consumos</OPTION><OPTION VALUE="4">Aluguel</OPTION><OPTION VALUE="5">Luz</OPTION><OPTION VALUE="6">Agua</OPTION><OPTION VALUE="7">Erro</OPTION></SELECT></div></div>';
		row += '<div class="control-group"><label class="control-label">Método</label><div class="controls"><SELECT ID="paymentmethod_id" NAME="paymentmethod_id" SIZE="1" onchange="" STYLE="" CLASS="span4 m-wrap"><OPTION STYLE="display:none" VALUE="0"></OPTION><OPTION VALUE="1">Dinheiro</OPTION><OPTION VALUE="2">Cheque</OPTION></SELECT></div></div>';
		row += '<div class="control-group"><label class="control-label">Monto</label><div class="controls"><div class="input-prepend"> <span class="add-on">$</span><input id="ammount" name="ammount" type="text" class="span12 m-wrap" required value=""/></div></div></div>';
		row += '<div class="control-group"><div class="controls"><div data-date="" class="input-append date datepicker"><input id="date" name="date" type="text" data-date-format="yyyy-mm-dd" placeholder="AAAA-MM-DD" value=""><span class="add-on"><i class="icon-th"></i></span></div></div></div>';
		row += '<div class="control-group"><label class="control-label">Detalhes</label><div class="controls"><textarea id="details" name="details" class="span4"></textarea></div></div>';
		jQuery('#itemRows').append(row);
		frm.add_qty.value = '';
		frm.add_name.value = '';
	}

	function removeRow(rnum) {
		jQuery('#rowNum'+rnum).remove();
	}

	
		
	function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e){
			try{
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		return xmlhttp;
	}
	
	
	function getAmmount(selected) {
		if (selected == 2){
			
			var strURL="getSalary.php?employee_id="+ $('#employee_id').val();
			var req = getXMLHTTP();
				
			if (req) {
				req.onreadystatechange = function() {
					if (req.readyState == 4) {
						// only if "OK"
						if (req.status == 200) {
							document.getElementById('ammount').value	= req.responseText;
						} 
						else {
							alert("Problem while using XMLHTTP:\n" + req.statusText);
						}
					}				
				}			
				req.open("GET", strURL, true);
				req.send(null);
			}
		}
	}
	
</script>
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
						<div id="itemRows">
						<input type="hidden" id="accion" name="accion" value="admitPayment" />
						<input type="hidden" id="payment_id" name="payment_id" value="<?php echo isset($payment_id) ? $payment_id : '' ;?>" />

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
									<input id="ammount" name="ammount" type="text" class="span12 m-wrap" required value="<?php echo isset($ammount) ? $ammount : '' ?>"/>
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
								<textarea id="details" name="details" class="span4"><?php echo isset($details) ? $details : '' ;?></textarea>
							</div>
						</div>
						

						<div class="control-group">
							<br><button class="btn btn-success" type="submit" name="accept" value="exit">Aceitar</button>
							<br><button class="btn btn-success" type="submit" name="accept" value="continue">Adicionar mais um</button>
							<br><br>
						</div>
						<div id="status"></div>
						
						
						
						

						</div>
						
					</form>

				</div>
				<!--<input onclick="addRow(this.form);" type="button" value="Add row" />-->

			</div>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
