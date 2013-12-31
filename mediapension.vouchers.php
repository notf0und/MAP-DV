<?php include "head.php"; 

$sqlQuery = " SELECT MP.idmediapension id, H.titular 'Nome PAX', MP.numeroexterno '# Voucher', ";
$sqlQuery .= " MP.qtdedepax 'Qtde de PAX', MP.dataIN, MP.dataOUT, ";
$sqlQuery .= " P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia'  ";
$sqlQuery .= " , RDP.nombre 'Responsable', SS.nombre 'Serviço', NULL 'Detalles' ";
$sqlQuery .= " FROM `mediapension` MP ";
$sqlQuery .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
$sqlQuery .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
$sqlQuery .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
$sqlQuery .= " LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ";
$sqlQuery .= " LEFT JOIN responsablesDePago RDP ON MP.idresponsablesDePago = RDP.idresponsablesDePago ";
$sqlQuery .= " LEFT JOIN servicios SS ON MP.idservicios = SS.idservicios ";
$sqlQuery .= " WHERE 1 ";
$sqlQuery .= " AND MP.idliquidaciones = 0 ";
$sqlQuery .= " AND MP.habilitado = 1 ";

if (isset($_POST['desde']) || isset($_POST['ate'])){
	
	$title = (isset($_POST['desde']) && $_POST['desde'] != '') ? $_POST['desde'] : 'Todos ';
	$title .= (isset($_POST['ate']) && $_POST['ate'] != '') ? ' / '.$_POST['ate'] : ' / Todos';
	
	$sqlQuery .= (isset($_POST['desde']) && $_POST['desde'] != '') ? "AND MP.dataIN >= '".dateFormatMySQL($_POST['desde'])."' " : '';
	$sqlQuery .= (isset($_POST['ate']) && $_POST['ate'] != '') ? "AND MP.dataIN <= '".dateFormatMySQL($_POST['ate'])."' " : '';
	
	$tablaVouchers = tableFromResult(resultFromQuery($sqlQuery), 'VouchersMP', true, true, 'posts.php', true);
	
	
}
else{
	$title = "Ultimo mes";

	$sqlQuery .= "AND month(MP.dataIN) >= month(curdate()) ";
	$sqlQuery .= "AND month(MP.dataIN) <= month(curdate())";
	
	$tablaVouchers = tableFromResult(resultFromQuery($sqlQuery), 'VouchersMP', true, true, 'posts.php', true);
	

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
