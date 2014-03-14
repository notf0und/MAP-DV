<?php

include "head.php";

$fDeclined = '<form action="#" name="fDeclined" method="post" class="form-horizontal">';

//SELECT
$sqlQuery = "SELECT ";
$sqlQuery .= "E.employee_id id, ";
$sqlQuery .= "NULL '&nbsp', ";
$sqlQuery .= "CONCAT(P.firstname, ' ', P.lastname) 'Nome Completo', ";
$sqlQuery .= "E.decline, ";

$sqlQuery .= "CONCAT(TIMESTAMPDIFF(YEAR, E.admission, COALESCE(E.decline, NOW())), 'A / ', TIMESTAMPDIFF(MONTH, E.admission, COALESCE(E.decline, NOW())) - (TIMESTAMPDIFF(YEAR, E.admission, COALESCE(E.decline, NOW())) * 12), 'M') as Antiguedad, ";

$sqlQuery .= "DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), P.birthdate)), '%Y')+0 AS Edad, ";
$sqlQuery .= 'CONCAT("<A HREF=funcionarios.categorias.lista.php>", JC.name, "</A>") Categoría, ';
$sqlQuery .= "EMP.nombre Empresa, ";
$sqlQuery .= "CONCAT(TIME_FORMAT(E.fromhour, '%H:%i'), ' - ', TIME_FORMAT(E.tohour, '%H:%i')) 'Horario', ";
$sqlQuery .= "CO.nombre País, ";
$sqlQuery .= "NULL Pagamentos ";

//FROM
$sqlQuery .= "FROM employee E ";

//Union de employee con profile
$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";

$sqlQuery .= "LEFT JOIN jobcategory JC ON E.jobcategory_id = JC.jobcategory_id ";

//Union de employee con empresa
$sqlQuery .= "LEFT JOIN empresa EMP ON E.idempresa = EMP.idempresa ";

//Union desde profile.birth_city_id hasta paises.idpaises para obtener la nacionalidad
$sqlQuery .= "LEFT JOIN city C ON P.birth_city_id = C.city_id ";
$sqlQuery .= "LEFT JOIN state S ON C.state_id = S.state_id ";
$sqlQuery .= "LEFT JOIN paises CO ON S.country_id = CO.idpaises ";

if(isset($_POST['mode']) && $_POST['mode'] == 'decline'){
	$sqlQuery .= "WHERE E.decline IS NOT NULL AND DATE(E.decline) < CURDATE()";
	$fDeclined .= '<input type="hidden" id="mode" name="mode" value="active"/>';
	$fDeclined .= '<span class="label label-success" onclick="document.fDeclined.submit()">Ativos</span>';
}
else{
	$sqlQuery .= "WHERE E.decline IS NULL OR DATE(E.decline) >= CURDATE()";
	$fDeclined .= '<input type="hidden" id="mode" name="mode" value="decline"/>';
	$fDeclined .= '<span class="label label-important" onclick="document.fDeclined.submit()">Inativos</span>';
}

$fDeclined .='</form>';


$resultado = resultFromQuery($sqlQuery);
$tablafuncionarios = tableFromResult($resultado, 'employee', false, true, 'posts.php', true);

$sql = "select * from employee where decline is null OR DATE(decline) >= CURDATE();";
$result = resultFromQuery($sql);
$totalactivos = mysql_num_rows($result);

$sql = "select * from employee where decline is not null AND DATE(decline) < CURDATE() ";
$result = resultFromQuery($sql);
$totalinactivos = mysql_num_rows($result);

$totalfuncionarios = $totalactivos + $totalinactivos;


//Aniversarios
$sql = "SELECT CONCAT(P.firstname, ' ', P.lastname) Name, P.birthdate aniversario ";
$sql .= "FROM employee E ";
$sql .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
$sql .= "WHERE E.decline IS NULL ";
$sql .= "AND DAYOFYEAR(P.birthdate) - DAYOFYEAR(CURDATE()) BETWEEN 0 AND 7 ";
$sql .= "OR DAYOFYEAR(P.birthdate) + 365 - DAYOFYEAR(CURDATE()) BETWEEN 0 AND 7 ";
$sql .= "ORDER BY MONTH(P.birthdate), DAY(P.birthdate)";
$result = resultFromQuery($sql);

while($row = siguienteResult($result)){
	if(date("d/m", strtotime($row->aniversario)) == date("d/m", strtotime('now'))){
		$data = '<strong>Hoje</strong>';
	}
	else{
		$data = date("d/m", strtotime($row->aniversario));
	}
	
	
	if(!isset($aniversario)){
		
		
		$aniversario = $row->Name.' ('.$data.')<br>';
	}
	else{
		$aniversario .= $row->Name.' ('.$data.')<br>';
	}
}

	

?>	

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="#" class="current">Lista de Funcionarios</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	  <div style='text-align:right'><a id="example" data-content="<?php echo isset($aniversario) ? $aniversario : ''; ?>" data-placement="left" data-toggle="popover" class="taskStatus" data-original-title="Aniversarios"><?php echo isset($aniversario) ? '<strong>Aniversario</strong>' : ''; ?></a></div>
	<div class="row-fluid">
		<?php echo ($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 4) ? '<h2><b><div id="totalSalarios">
			Previsão: R$</div></b></h2>' : '';?>
			
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Lista de Funcionarios - <?php echo $totalfuncionarios.' funcionarios registrados - <font color="green">'.$totalactivos.' Activos</font> - <font color="red">'.$totalinactivos.' Inactivos</font>';?> </h5>
			<?php echo $fDeclined ?>
          </div>
          <form id="employeeForm" name="employeeForm" action="posts.php" method="post">
          <div class="widget-content nopadding">
			  <?php echo $tablafuncionarios;?>
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
		</form>
        </div>
		<form method="get" action="funcionarios.novo.php">
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
