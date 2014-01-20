<?php include "head.php"; 

$sqlQuery = " SELECT HTL.idhoteleria id, H.titular 'Nome PAX', HTL.numeroexterno '# Voucher', ";
$sqlQuery .= "HTL.qtdedepax 'Qtde de PAX', HTL.dataIN, HTL.dataOUT, ";
$sqlQuery .= "P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia', ";
$sqlQuery .= "RDP.nombre 'Responsable', SS.nombre 'Serviço' ";

$sqlQuery .= "FROM `hoteleria` HTL ";

$sqlQuery .= " LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes ";
$sqlQuery .= " LEFT JOIN posadas P ON HTL.idposadas = P.idposadas ";

$sqlQuery .= " LEFT JOIN agencias A ON HTL.idagencias = A.idagencias ";
$sqlQuery .= " LEFT JOIN operadoresturisticos O ON HTL.idoperadoresturisticos = O.idoperadoresturisticos ";
$sqlQuery .= " LEFT JOIN responsablesDePago RDP ON HTL.idresponsablesDePago = RDP.idresponsablesDePago ";
$sqlQuery .= " LEFT JOIN servicios SS ON HTL.idservicios = SS.idservicios ";

$sqlQuery .= " WHERE 1 ";
$sqlQuery .= " AND HTL.idhoteleria > 0 ";
$sqlQuery .= " AND HTL.habilitado = 1 ";


if (isset($_POST['desde']) || isset($_POST['ate'])){
	
	$title = (isset($_POST['desde']) && $_POST['desde'] != '') ? $_POST['desde'] : 'Todos ';
	$title .= (isset($_POST['ate']) && $_POST['ate'] != '') ? ' / '.$_POST['ate'] : ' / Todos';
	
	$sqlQuery .= (isset($_POST['desde']) && $_POST['desde'] != '') ? "AND HTL.dataIN >= '".dateFormatMySQL($_POST['desde'])."' " : '';
	$sqlQuery .= (isset($_POST['ate']) && $_POST['ate'] != '') ? "AND HTL.dataIN <= '".dateFormatMySQL($_POST['ate'])."' " : '';
	
	$_SESSION['desde'] = $_POST['desde'];
	$_SESSION['ate'] = $_POST['ate'];
}
elseif (isset($_SESSION['desde']) || isset($_SESSION['ate'])){
	
	$title = (isset($_SESSION['desde']) && $_SESSION['desde'] != '') ? $_SESSION['desde'] : 'Todos ';
	$title .= (isset($_SESSION['ate']) && $_SESSION['ate'] != '') ? ' / '.$_SESSION['ate'] : ' / Todos';
	
	$sqlQuery .= (isset($_SESSION['desde']) && $_SESSION['desde'] != '') ? "AND HTL.dataIN >= '".dateFormatMySQL($_SESSION['desde'])."' " : '';
	$sqlQuery .= (isset($_SESSION['ate']) && $_SESSION['ate'] != '') ? "AND HTL.dataIN <= '".dateFormatMySQL($_SESSION['ate'])."' " : '';
	
	$_POST['desde'] = $_SESSION['desde'];
	$_POST['ate'] = $_SESSION['ate'];
}
else{
	$title = "Ultimo mes";

	$sqlQuery .= "AND month(HTL.dataIN) >= month(curdate()) ";
	$sqlQuery .= "AND month(HTL.dataIN) <= month(curdate())";
}

if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$tablaHotelaria = tableFromResult(resultFromQuery($sqlQuery), 'VouchersHTL', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$tablaHotelaria = tableFromResult(resultFromQuery($sqlQuery), 'VouchersHTL', false, true, 'posts.php', true);
}


?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="hoteleria.php" title="Hoteleria" class="tip-bottom">Hoteleria</a>
		<a href="#" class="current">Vouchers</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		
		<div class="widget-box">
		  <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
		      <h5>Data de pesquisa</h5>
		  </div>
		
		  <div class="widget-content nopadding">
			  <form action="#" method="post" class="form-horizontal">
				  <div class="control-group">
					  <label class="control-label">Desde: </label>
					  <div data-date="" class="input-append date datepicker">
						  <input id="desde" name="desde" type="text" value="<?php echo (isset($_POST['desde']) && $_POST['desde'] != '') ? $_POST['desde'] : ''; ?>">
						  <span class="add-on"><i class="icon-th"></i></span>
					  </div>
				  </div>
						  
				  <div class="control-group">
					  <label class="control-label">Até: </label>
					  <div data-date="" class="input-append date datepicker">
						  <input id="ate" name="ate" type="text" value="<?php echo (isset($_POST['ate']) && $_POST['ate'] != '') ? $_POST['ate'] : ''; ?>">
						  <span class="add-on"><i class="icon-th"></i></span>
					  </div>
				  </div>

						  <div class="form-actions" align="left">
							  <button type="submit" class="btn btn-success">Pesquisar</button>
						  </div>
						  
					  </form>
				  </div>
			  </div>
		
		
		
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5>Vouchers - <?php echo $title; ?></h5>
			</div>
			<form id="VouchersHTLForm" name="VouchersHTLForm" action="posts.php" method="post">
			<div class="widget-content nopadding">
				<?php echo isset($tablaHotelaria) ? $tablaHotelaria : ''; ?>	  
			</div>
			</form>
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
		<form method="post" action="posts.php">
			<input type="hidden" id="accion" name="accion" value="VouchersHTLNew" />
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
