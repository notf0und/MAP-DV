<?php 
include "head.php"; 

$lock = '<form action="#" name="fLock" method="post" class="form-horizontal">';


$sqlQuery = "SELECT MP.idmediapension id, H.titular 'Nome PAX', MP.numeroexterno '# Voucher', ";

if ($_SESSION["idusuarios"] == 13){
	$sqlQuery .= "MP.data 'Ingresado', ";
}

//Botón Transferir
if ($_SESSION["idusuarios_tipos"] == 1){
	$bTransfer = '<button class="btn btn-success">Transferir</button>';
}

$sqlQuery .= "MP.qtdedepax 'Qtde de PAX', MP.dataIN, MP.dataOUT, ";
$sqlQuery .= " P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia',  ";
$sqlQuery .= " RDP.nombre 'Responsable', SS.nombre 'Serviço', NULL 'Detalles' ";
$sqlQuery .= " FROM `mediapension` MP ";
$sqlQuery .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
$sqlQuery .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
$sqlQuery .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
$sqlQuery .= " LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ";
$sqlQuery .= " LEFT JOIN responsablesDePago RDP ON MP.idresponsablesDePago = RDP.idresponsablesDePago ";
$sqlQuery .= " LEFT JOIN servicios SS ON MP.idservicios = SS.idservicios ";
$sqlQuery .= " WHERE 1 ";

if(isset($_POST['mode']) && $_POST['mode'] == 'lock'){
	$sqlQuery .= " AND MP.idliquidaciones <> 0 ";
	$lock .= '<input type="hidden" id="mode" name="mode" value="unlock"/>';
	$lock .= '<span class="label label-success" onclick="document.fLock.submit()">Ver vouchers sem liquidar</span>';
}
else{
	$sqlQuery .= " AND MP.idliquidaciones = 0 ";
	$lock .= '<input type="hidden" id="mode" name="mode" value="lock"/>';
	$lock .= '<span class="label label-info" onclick="document.fLock.submit()">Ver vouchers já liquidados</span>';
}
$lock .= '</form>';
	

$sqlQuery .= " AND MP.habilitado = 1 ";

if (isset($_POST['desde']) || isset($_POST['ate'])){
	
	$title = (isset($_POST['desde']) && $_POST['desde'] != '') ? $_POST['desde'] : 'Todos ';
	$title .= (isset($_POST['ate']) && $_POST['ate'] != '') ? ' / '.$_POST['ate'] : ' / Todos';
	
	$sqlQuery .= (isset($_POST['desde']) && $_POST['desde'] != '') ? "AND MP.dataIN >= '".dateFormatMySQL($_POST['desde'])."' " : '';
	$sqlQuery .= (isset($_POST['ate']) && $_POST['ate'] != '') ? "AND MP.dataIN <= '".dateFormatMySQL($_POST['ate'])."' " : '';
		
	$_SESSION['desde'] = $_POST['desde'];
	$_SESSION['ate'] = $_POST['ate'];
}
elseif (isset($_SESSION['desde']) || isset($_SESSION['ate'])){
	
	$title = (isset($_SESSION['desde']) && $_SESSION['desde'] != '') ? $_SESSION['desde'] : 'Todos ';
	$title .= (isset($_SESSION['ate']) && $_SESSION['ate'] != '') ? ' / '.$_SESSION['ate'] : ' / Todos';
	
	$sqlQuery .= (isset($_SESSION['desde']) && $_SESSION['desde'] != '') ? "AND MP.dataIN >= '".dateFormatMySQL($_SESSION['desde'])."' " : '';
	$sqlQuery .= (isset($_SESSION['ate']) && $_SESSION['ate'] != '') ? "AND MP.dataIN <= '".dateFormatMySQL($_SESSION['ate'])."' " : '';
		
	$_POST['desde'] = $_SESSION['desde'];
	$_POST['ate'] = $_SESSION['ate'];
}
else{
	$title = "Ultimo mes";

	$sqlQuery .= "AND month(MP.dataIN) >= month(curdate()) ";
	$sqlQuery .= "AND month(MP.dataIN) <= month(curdate())";
}


if(isset($_POST['mode']) && $_POST['mode'] == 'lock'){
	if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
		$tablaVouchers = tableFromResult(resultFromQuery($sqlQuery), 'VouchersMP', false, false);
	}
	elseif ($_SESSION["idusuarios_tipos"] == 6){
		$tablaVouchers = tableFromResult(resultFromQuery($sqlQuery), 'VouchersMP', false, false);
	}
}
else{
	if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
		$tablaVouchers = tableFromResult(resultFromQuery($sqlQuery), 'VouchersMP', true, true);
		$bTransfer = '<a data-dismiss="modal" class="btn btn-primary" href="#">Transferir</a>';
	}
	elseif ($_SESSION["idusuarios_tipos"] == 6){
		$tablaVouchers = tableFromResult(resultFromQuery($sqlQuery), 'VouchersMP', false, true);
	}
}




	

?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="#" class="current">Vouchers</a>
	</div>
  </div>

  
  <div class="container-fluid">
	  <div class="row-fluid">
		  <div class="span12">
			  <div class="widget-box">
				  <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
				      <h5>Data de pesquisa </h5>
				      <?php echo $lock ?>
				      
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
				  <div class="widget-title">
					  <span class="icon"> <i class="icon-align-justify"></i> </span>
					  <h5>Vouchers - <?php echo $title; ?></h5>
				  </div>
					  
				  <div class="widget-content nopadding">
					  <form id="VouchersMPForm" name="VouchersMPForm" action="posts.php" method="post">
							  <div class="control-group">
								  <?php echo isset($tablaVouchers) ? $tablaVouchers : ''; ?>
							  </div>

							  <!-- Start Modal -->
							  <div id="myModal" class="modal hide fade">
								  <div class="modal-header">
									  <button data-dismiss="modal" class="close" type="button">×</button>
									  <h3>Detalle</h3>
								  </div>
								  <div class="modal-body" id="modal-body">
									  <p></p>
								  </div>

							  </div>
						  </form>
						  
						  <form method="post" action="posts.php">
							  <input type="hidden" id="accion" name="accion" value="VouchersMPNew" />
							  <button class="btn btn-success" type="submit">Novo...</button>
						  </form> 
					  </div>
				  </div>
		  </div>
	  </div>
  </div>
</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
