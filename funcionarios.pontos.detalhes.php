<?php

include "head.php"; 

$accept = Array(1, 4, 9);
checkPermissions($accept);

function checkPermissions($accept){
	if(!in_array($_SESSION["idusuarios_tipos"], $accept)){
		echo 'NO Aceptado';
		exit();
	}
}

if(isset($_POST['accion']) && $_POST['accion'] == 'PointDelete'){

	$sql='DELETE FROM point WHERE point_id = '.key($_POST['deleteRow']['id']);
	$result = resultFromQuery($sql);
	
	bitacoras($_SESSION["idusuarios"], 'Apagado ponto: '.$_POST['deleteRow']['id']);
	header('Location: funcionarios.pontos.detalhes.php?employee_id='.$_POST['employee_id'].'&date='.$_POST['date']);
}

if (isset($_POST['id'])){
	$sql = "SELECT TIME_FORMAT(TIME(date_time), '%H:%i') hour, in_out FROM point where point_id = ".$_POST['id'];
	$result = resultFromQuery($sql);
	$row = siguienteResult($result);

	$point_id = $_POST['id'];
	$hour = $row->hour;
	$in_out = $row->in_out;
	echo $_POST['id'];
}

//Obtener nombre del funcionario
//SELECT
$sql = "SELECT ";
$sql .= "CONCAT(P.firstname, ' ', P.lastname) As Funcionario ";
$sql .= "FROM employee E ";
$sql .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
$sql .= "WHERE E.employee_id = ".$_GET['employee_id']." ";
$result = resultFromQuery($sql);
$row = siguienteResult($result);


$funcionario = $row->Funcionario;
$string = '<div class="widget-box">';
$string .= '<div class="widget-title"> <span class="icon"> <i class="icon-time"></i> </span>';
$string .= '<h5>'.$funcionario.' - '.date('d/m/Y', strtotime($_GET['date'])).'</h5>';
$string .= '</div>';
$string .= '<div class="widget-content nopadding">';
$string .= '<form id="PointForm" name="PointForm" action="posts.php" method="post">';
$string .= '<table class="table table-bordered table-striped">';
$string .= '<thead>';
$string .= '<tr>';
$string .= '<th>Hora</th>';
$string .= '<th>Evento</th>';
if($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 4){
	$string .= '<th>Modificar</th>';
	$string .= '<th>Apagar</th>';
}

$string .= '</tr>';
$string .= '</thead>';
$string .= '<tbody>';

/////////////////////////////////
$sql = "SELECT ";
$sql .= "entrada.employee_id, ";
$sql .= "DATE(entrada.date_time) data, ";
$sql .= "MIN(entrada.point_id) identrada, ";
$sql .= "MAX(salida.point_id) idsalida, ";
$sql .= "MIN(entrada.date_time) dtentrada, ";
$sql .= "MAX(salida.date_time) dtsalida, ";
$sql .= "TIME_FORMAT(TIME(MIN(entrada.date_time)), '%H:%i') entrada, ";
$sql .= "TIME_FORMAT(TIME(MAX(salida.date_time)), '%H:%i') salida ";
$sql .= "FROM point AS entrada ";
$sql .= "LEFT JOIN point AS salida ON entrada.employee_id = salida.employee_id ";
$sql .= "LEFT JOIN employee AS E ON entrada.employee_id = E.employee_id ";
$sql .= "WHERE 1 ";
$sql .= "AND entrada.employee_id = ".$_GET['employee_id']." ";

$sql .= "AND date(entrada.date_time) = '".$_GET['date']."' ";
$sql .= "AND entrada.date_time > DATE_ADD(date(entrada.date_time), interval HOUR(E.fromhour) - 7 hour) ";
$sql .= "AND salida.date_time < DATE_ADD(DATE_ADD(date(entrada.date_time), interval 1 day), interval HOUR(E.fromhour) - 7 hour);";
$result = resultFromQuery($sql);
$row = siguienteResult($result);

//////////////////////////////////
//SELECT
$sql = "SELECT PT.point_id, ";
$sql .= "PT.employee_id, ";
$sql .= "CONCAT(P.firstname, ' ', P.lastname) As Funcionario, ";
$sql .= "PT.date_time Hora, ";
$sql .= "PT.in_out Status ";
$sql .= "FROM point PT ";
$sql .= "LEFT JOIN employee E ON PT.employee_id = E.employee_id ";
$sql .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
$sql .= "WHERE PT.employee_id = ".$_GET['employee_id']." ";
$sql .= "AND PT.date_time >= '".$row->dtentrada."' ";

if($row->dtentrada <= $row->dtsalida){
	$sql .= "AND PT.date_time <= '".$row->dtsalida."' ";
}
else{
	$sql .= "AND PT.date_time - interval 1 day < '".$row->dtsalida."' ";
}
$sql .= "ORDER BY PT.date_time";

$result = resultFromQuery($sql);

while ($row = siguienteResult($result)){
	
	$pt_id = $row->point_id;
	$employee_id = $row->employee_id;
	$hora = $row->Hora;
	$status = $row->Status;
	$date = new DateTime($hora);
	
	$string .= '<tr>';
	$string .= '<td><center>'.$date->format('H:i').'</center></td>';
	
	if ($status == 1){
		$string .= "<td><center>Entrada</center></td>";
	}else{
		$string .= "<td><center>Saída</center></td>";
	}
	
	if($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 4){
		$string .= '<td><center><input type="button" class="btn" name="modifyRow" onclick="javascript:modifyRowEvent('."'Point'".', '."'id'".', '."'".$pt_id."'".');" value="Editar"></center></td>';
		$string .= '<td><center><input type="submit" class="btn" name="deleteRow[id]['.$pt_id.']" onclick="javascript:deleteRowEvent('."'Point'".', '."'id'".', '."'".$pt_id."'".');" value="Apagar"></center></td>';
		$string .= '</tr>';
	}
}

$string .= '</tbody>';
$string .= '</table>';
$string .= '</form>';
$string .= '</div>';
$string .= '</div>';
            
?>	

<!--main-container-part-->



<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="#" class="current">Pontos</a>
	</div>

    <h1>Registro de ponto de  <?php echo $funcionario.' no día '.date('d/m/Y', strtotime($_GET['date'])); ?></h1>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span6">
        <!--Recent posts-->
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-file"></i> </span>
            <h5>Adicionar/Editar registro</h5>
          </div>
        <div class="widget-content nopadding">
			<form id="Point" name="Point"  action="posts.php" method="post" class="form-horizontal">
			  <input type="hidden" id="accion" name="accion" value="<?php echo isset($_POST['accion']) && $_POST['accion'] == 'PointModify' ? 'PointModify' : 'PointNew'; ?>" />
			  <input type="hidden" id="date" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>" />
			  <input type="hidden" id="point_id" name="point_id" value="<?php echo isset($point_id) ? $point_id : ''; ?>" />
			  <input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($_GET['employee_id']) ? $_GET['employee_id'] : '';?>" />
			  <!--Work Hours-->
			  <div class="control-group">
				  <label class="control-label">Hora: </label>
				  <div class="controls">
					   <!--From-->
					   <input type="text" id="mask-fromhours" name="hour" placeholder="--:--" class="span4 mask text" value="<?php echo isset($hour) ? $hour : '' ?>"><br>
				  </div>
			  </div>
			  

            <div class="control-group">
              <label class="control-label">Evento: </label>
              <div class="controls ">
                <select name="event">
                  <option value="0" <?php echo isset($in_out) && $in_out == 0 ? 'selected' : '' ?>>Saída</option>
                  <option value="1" <?php echo isset($in_out) && $in_out == 1 ? 'selected' : '' ?>>Entrada</option>
                </select>
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-success"><?php echo isset($_POST['accion']) && $_POST['accion'] == 'PointModify' ? 'Salvar edição' : 'Salvar nuevo'; ?></button>
            </div>
          </form>
        </div>
      </div>
       </div>

        <!--To do list-->
        <div class="span6">

			  <form id="PointForm" name="PointForm"  action="#" method="post" class="form-horizontal">
				  <input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
				  <input type="hidden" id="date" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>" />
					<?php echo $string;?>
			  </form>


      </div>
    </div>
    <a href="funcionarios.pagamentos.printPoint.php?employee_id=<?php echo isset($employee_id) ? $employee_id : '';?>"><button class="btn btn-primary">Ver Ponto</button></a>
  </div>
</div>
<!--main-container-part-->





<!--Footer-part-->
<?php include "footer.php"; ?>

