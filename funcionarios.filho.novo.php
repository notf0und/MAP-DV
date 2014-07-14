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
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="#" class="current">Registrar filho</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Registrar Filho</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="admitEmployeeSon" />

						<!--EMPLOYEE COMBO-->
						<div class="control-group">
							<label class="control-label">Funcionario</label>
							<div class="controls">
								<?php echo $comboemployee; ?>								
							</div>
						</div>
						
						<!--SON INPUT-->
						<div class="control-group">
							<label class="control-label">Filho</label>
							<div class="controls">
								
								<input id="name" name="name" type="text" class="span4 m-wrap" placeholder="Nome completo" required /><br>
								
								<div data-date="" class="input-append date datepicker">
										<input id="birthdate" name="birthdate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de nascimento" value="<?php echo isset($birthdate) ? $birthdate : '' ;?>" required>
										<span class="add-on"><i class="icon-th"></i></span>
								</div>
								
								<li>
								<SELECT ID="is_alive" NAME="is_alive" SIZE="1" placeholder="Status" class="span4 m-wrap">
									  <OPTION VALUE="0">Vivo</OPTION>
									  <OPTION VALUE="1">Defunto</OPTION>
								</SELECT></li>
								
								
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
