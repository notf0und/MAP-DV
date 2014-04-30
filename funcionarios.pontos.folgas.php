<?php

include "head.php"; 

if(isset($_POST['accion']) && $_POST['accion'] == 'ClearanceModify'){
	$employee_id = $_POST['employee_id'];
	$clearance_id = $_POST['id'];
	$sql = 'SELECT * FROM clearance where clearance_id = '.$clearance_id;
	$result = resultFromQuery($sql);
	$row = siguienteResult($result);
	$valid_from = $row->valid_from;
	$permanent = $row->permanent;
}
elseif(isset($_GET['employee_id'])){
	$employee_id = $_GET['employee_id'];
}



$sql = "SET lc_time_names = 'pt_BR';";
resultFromQuery($sql);

$sql = "SELECT clearance_id id, CONCAT(UCASE(LEFT(dayname(valid_from), 1)), SUBSTRING(dayname(valid_from), 2)) Día, valid_from Desde ";
$sql .= 'FROM clearance ';
$sql .= 'WHERE 1 ';
$sql .= 'AND employee_id = '.$employee_id;

$tabla = tableFromResult(resultFromQuery($sql), 'Clearance', false, true, false, false); 


$sql = "SELECT CONCAT(P.firstname, ' ', P.lastname) Funcionario ";
$sql .= 'FROM employee E ';
$sql .= 'LEFT JOIN profile P ';
$sql .= 'ON E.profile_id = P.profile_id ';
$sql .= 'WHERE 1 ';
$sql .= 'AND E.employee_id = '.$employee_id;
$result = resultFromQuery($sql);
$row = siguienteResult($result);
$funcionario = $row->Funcionario;


?>	

<!--main-container-part-->
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="funcionarios.lista.php" title="Lista de Funcionarios" class="tip-bottom">Lista de Funcionarios</a>
		<a href="#" class="current">Dias de folga de <?php echo $funcionario; ?></a>
	</div>
    <h1>Días de folga de <?php echo $funcionario; ?></h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span6">
        <!--Recent posts-->
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-file"></i> </span>
            <h5>Adicionar/Editar día de folga</h5>
          </div>
        <div class="widget-content nopadding">
          <form id="Clear" name="Clear"  action="posts.php" method="post" class="form-horizontal">
			  <input type="hidden" id="accion" name="accion" value="<?php echo isset($_POST['accion']) && $_POST['accion'] == 'ClearanceModify' ? 'ClearanceModify' : 'ClearanceNew'; ?>" />
			  <input type="hidden" id="clearance_id" name="clearance_id" value="<?php echo isset($clearance_id) ? $clearance_id : ''; ?>" />
			  <input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
            <div class="control-group">
              <label class="control-label">Día de folga</label>
              <div class="controls">
                <div  data-date="<?php echo isset($valid_from) ? $valid_from : ''; ?>" class="input-append date datepicker">
                  <input name="valid_from" type="text" value="<?php echo isset($valid_from) ? $valid_from : ''; ?>"  data-date-format="yyyy-mm-dd" class="span11" >
                  <span class="add-on"><i class="icon-th"></i></span> </div>
              </div>
            </div>
            
            
            <div class="control-group">
              <label class="control-label">Modo</label>
              <div class="controls">
                <label>
                  <input type="radio" name="mode" value="permanent" <?php echo (isset($permanent) && $permanent == 1) || !isset($permanent) ? 'checked' : 'unchecked';?>/>
                  Permanente</label>
                <label>
                  <input type="radio" name="mode" value="temporal" <?php echo isset($permanent) && $permanent == 0 ? 'checked' : 'unchecked';?>/>
                  Só um día</label>
                <label>
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-success"><?php echo isset($_POST['accion']) && $_POST['accion'] == 'ClearanceModify' ? 'Salvar edição' : 'Salvar nuevo'; ?></button>
            </div>
          </form>
        </div>
      </div>
       </div>

        <!--To do list-->
        <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
            <h5>Registros de días de folga</h5>
          </div>
          <div class="widget-content nopadding">
			  
			  <form id="ClearanceForm" name="ClearanceForm"  action="#" method="post" class="form-horizontal">
				  <input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
			  
					<?php echo $tabla;?>
			  </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->





<!--Footer-part-->
<?php include "footer.php"; ?>
