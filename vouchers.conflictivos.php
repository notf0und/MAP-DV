<?php include "head.php"; 
//MP con dataIN >= dataOUT
$sql = " SELECT MP.idmediapension id, H.titular 'Nome PAX', MP.numeroexterno '# Voucher', ";
$sql .= " MP.qtdedepax 'Qtde de PAX', MP.dataIN, MP.dataOUT, ";
$sql .= " P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia'  ";
$sql .= " , RDP.nombre 'Responsable', SS.nombre 'Serviço', NULL 'Detalles' ";
$sql .= " FROM `mediapension` MP ";
$sql .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
$sql .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON MP.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON MP.idservicios = SS.idservicios ";
$sql .= " WHERE 1 ";
$sql .= " AND MP.idliquidaciones = 0 ";
$sql .= " AND MP.habilitado = 1 ";
$sql .= " AND MP.dataIN >= MP.dataOUT ";


if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$mpinmayoroigualout = tableFromResult(resultFromQuery($sql), 'VouchersMP', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$mpinmayoroigualout = tableFromResult(resultFromQuery($sql), 'VouchersMP', false, true, 'posts.php', true);
}

//MP sin responsable de pago
$sql = " SELECT MP.idmediapension id, H.titular 'Nome PAX', MP.numeroexterno '# Voucher', ";
$sql .= " MP.qtdedepax 'Qtde de PAX', MP.dataIN, MP.dataOUT, ";
$sql .= " P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia'  ";
$sql .= " , RDP.nombre 'Responsable', SS.nombre 'Serviço', NULL 'Detalles' ";
$sql .= " FROM `mediapension` MP ";
$sql .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
$sql .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON MP.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON MP.idservicios = SS.idservicios ";
$sql .= " WHERE MP.idresponsablesDePago = 0 ";
$sql .= " AND MP.idliquidaciones = 0 ";
$sql .= " AND MP.habilitado = 1 ";

if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$mpsinresponsable = tableFromResult(resultFromQuery($sql), 'VouchersMP', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$mpsinresponsable = tableFromResult(resultFromQuery($sql), 'VouchersMP', false, true, 'posts.php', true);
}

//MP con responsable de pago a medio asignar
$sql = " SELECT MP.idmediapension id, H.titular 'Nome PAX', MP.numeroexterno '# Voucher', ";
$sql .= " MP.qtdedepax 'Qtde de PAX', MP.dataIN, MP.dataOUT, ";
$sql .= " P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia'  ";
$sql .= " , RDP.nombre 'Responsable', SS.nombre 'Serviço', NULL 'Detalles' ";
$sql .= " FROM `mediapension` MP ";
$sql .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
$sql .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON MP.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON MP.idservicios = SS.idservicios ";
$sql .= " WHERE ((MP.idresponsablesDePago = 1 AND MP.idoperadoresturisticos  = 0) ";
$sql .= " OR (MP.idresponsablesDePago = 2 AND MP.idposadas  = 0) ";
$sql .= " OR (MP.idresponsablesDePago = 3 AND MP.idagencias  = 0) ";
$sql .= " OR (MP.idresponsablesDePago = 4 AND MP.idposadas  = 0)) ";
$sql .= " AND MP.idliquidaciones = 0 ";
$sql .= " AND MP.habilitado = 1 ";

if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$mpmedioresponsable = tableFromResult(resultFromQuery($sql), 'VouchersMP', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$mpmedioresponsable = tableFromResult(resultFromQuery($sql), 'VouchersMP', false, true, 'posts.php', true);
}

//MP Duplicados
$sql = "SELECT a.idmediapension id, a.idmediapension identificador, H.titular 'Nome PAX', a.numeroexterno '# Voucher', ";
$sql .= "a.qtdedepax 'Qtde de PAX', a.dataIN, a.dataOUT, ";
$sql .= "P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia', ";
$sql .= "RDP.nombre 'Responsable', SS.nombre 'Serviço', NULL 'Detalles' ";
$sql .= "FROM `mediapension` a ";

$sql .= " LEFT JOIN huespedes H ON a.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON a.idposadas = P.idposadas ";
$sql .= " LEFT JOIN agencias A ON a.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON a.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON a.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON a.idservicios = SS.idservicios ";

$sql .= "INNER JOIN mediapension b ON a.numeroexterno = b.numeroexterno ";
$sql .= "AND a.habilitado = b.habilitado ";
$sql .= "AND a.dataIN = b.dataIN ";
$sql .= "AND a.dataOUT = b.dataOUT ";
$sql .= "AND a.idposadas = b.idposadas ";
$sql .= "WHERE a.idmediapension <> b.idmediapension ";
$sql .= "AND a.numeroexterno <> '' ";
$sql .= "AND a.habilitado = 1 ";
$sql .= "ORDER BY a.numeroexterno ASC ";


if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$mpduplicado = tableFromResult(resultFromQuery($sql), 'VouchersMP', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$mpduplicado = tableFromResult(resultFromQuery($sql), 'VouchersMP', false, true, 'posts.php', true);
}

// HTL dataIN >= dataOUT
$sql = " SELECT HTL.idhoteleria id, H.titular 'Nome PAX', HTL.numeroexterno '# Voucher', ";
$sql .= "HTL.qtdedepax 'Qtde de PAX', HTL.dataIN, HTL.dataOUT, ";
$sql .= "P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia', ";
$sql .= "RDP.nombre 'Responsable', SS.nombre 'Serviço' ";

$sql .= "FROM `hoteleria` HTL ";

$sql .= " LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON HTL.idposadas = P.idposadas ";

$sql .= " LEFT JOIN agencias A ON HTL.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON HTL.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON HTL.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON HTL.idservicios = SS.idservicios ";

$sql .= " WHERE 1 ";
$sql .= " AND HTL.idhoteleria > 0 ";
$sql .= " AND HTL.habilitado = 1 ";
$sql .= " AND HTL.dataIN >= HTL.dataOUT ";

if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$htlinmayoroigualout = tableFromResult(resultFromQuery($sql), 'VouchersHTL', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$htlinmayoroigualout = tableFromResult(resultFromQuery($sql), 'VouchersHTL', false, true, 'posts.php', true);
}

$sql = " SELECT HTL.idhoteleria id, H.titular 'Nome PAX', HTL.numeroexterno '# Voucher', ";
$sql .= "HTL.qtdedepax 'Qtde de PAX', HTL.dataIN, HTL.dataOUT, ";
$sql .= "P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia', ";
$sql .= "RDP.nombre 'Responsable', SS.nombre 'Serviço' ";

$sql .= "FROM `hoteleria` HTL ";

$sql .= " LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON HTL.idposadas = P.idposadas ";

$sql .= " LEFT JOIN agencias A ON HTL.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON HTL.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON HTL.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON HTL.idservicios = SS.idservicios ";

$sql .= " WHERE 1 ";
$sql .= " AND HTL.idhoteleria > 0 ";
$sql .= " AND HTL.habilitado = 1 ";
$sql .= " AND HTL.idresponsablesDePago = 0 ";

if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$htlsinresponsable = tableFromResult(resultFromQuery($sql), 'VouchersHTL', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$htlsinresponsable = tableFromResult(resultFromQuery($sql), 'VouchersHTL', false, true, 'posts.php', true);
}

$sql = " SELECT HTL.idhoteleria id, H.titular 'Nome PAX', HTL.numeroexterno '# Voucher', ";
$sql .= "HTL.qtdedepax 'Qtde de PAX', HTL.dataIN, HTL.dataOUT, ";
$sql .= "P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia', ";
$sql .= "RDP.nombre 'Responsable', SS.nombre 'Serviço' ";

$sql .= "FROM `hoteleria` HTL ";

$sql .= " LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON HTL.idposadas = P.idposadas ";

$sql .= " LEFT JOIN agencias A ON HTL.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON HTL.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON HTL.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON HTL.idservicios = SS.idservicios ";

$sql .= " WHERE ((HTL.idresponsablesDePago = 1 AND HTL.idoperadoresturisticos  = 0) ";
$sql .= " OR (HTL.idresponsablesDePago = 2 AND HTL.idposadas  = 0) ";
$sql .= " OR (HTL.idresponsablesDePago = 3 AND HTL.idagencias  = 0) ";
$sql .= " OR (HTL.idresponsablesDePago = 4 AND HTL.idposadas  = 0)) ";

$sql .= " AND HTL.idhoteleria > 0 ";
$sql .= " AND HTL.habilitado = 1 ";

if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$htlmedioresponsable = tableFromResult(resultFromQuery($sql), 'VouchersHTL', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$htlmedioresponsable = tableFromResult(resultFromQuery($sql), 'VouchersHTL', false, true, 'posts.php', true);
}


//HTL Duplicados
$sql = "SELECT a.idhoteleria id, a.idhoteleria identificador, H.titular 'Nome PAX', a.numeroexterno '# Voucher', ";
$sql .= "a.qtdedepax 'Qtde de PAX', a.dataIN, a.dataOUT, ";
$sql .= "P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia', ";
$sql .= "RDP.nombre 'Responsable', SS.nombre 'Serviço' ";
$sql .= "FROM hoteleria a ";

$sql .= " LEFT JOIN huespedes H ON a.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON a.idposadas = P.idposadas ";
$sql .= " LEFT JOIN agencias A ON a.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON a.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON a.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON a.idservicios = SS.idservicios ";

$sql .= "INNER JOIN hoteleria b ON a.numeroexterno = b.numeroexterno ";
$sql .= "AND a.habilitado = b.habilitado ";
$sql .= "AND a.dataIN = b.dataIN ";
$sql .= "AND a.dataOUT = b.dataOUT ";
$sql .= "AND a.idposadas = b.idposadas ";
$sql .= "WHERE a.idhoteleria <> b.idhoteleria ";
$sql .= "AND a.numeroexterno <> '' ";
$sql .= "AND a.habilitado = 1 ";
$sql .= "ORDER BY a.numeroexterno ASC ";


if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$htlduplicado = tableFromResult(resultFromQuery($sql), 'VouchersHTL', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$htlduplicado = tableFromResult(resultFromQuery($sql), 'VouchersHTL', false, true, 'posts.php', true);
}

//HTL con posada externa
$sql = " SELECT HTL.idhoteleria id, H.titular 'Nome PAX', HTL.numeroexterno '# Voucher', ";
$sql .= "HTL.qtdedepax 'Qtde de PAX', HTL.dataIN, HTL.dataOUT, ";
$sql .= "P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia', ";
$sql .= "RDP.nombre 'Responsable', SS.nombre 'Serviço' ";

$sql .= "FROM `hoteleria` HTL ";

$sql .= " LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes ";
$sql .= " LEFT JOIN posadas P ON HTL.idposadas = P.idposadas ";

$sql .= " LEFT JOIN agencias A ON HTL.idagencias = A.idagencias ";
$sql .= " LEFT JOIN operadoresturisticos O ON HTL.idoperadoresturisticos = O.idoperadoresturisticos ";
$sql .= " LEFT JOIN responsablesDePago RDP ON HTL.idresponsablesDePago = RDP.idresponsablesDePago ";
$sql .= " LEFT JOIN servicios SS ON HTL.idservicios = SS.idservicios ";

$sql .= " WHERE 1 ";
$sql .= " AND HTL.idhoteleria > 0 ";
$sql .= " AND HTL.habilitado = 1 ";
$sql .= " AND HTL.idposadas > 4 ";

if ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 ){
	$tablahoteleria = tableFromResult(resultFromQuery($sql), 'VouchersHTL', true, true, 'posts.php', true);
}
elseif ($_SESSION["idusuarios_tipos"] == 6){
	$tablahoteleria = tableFromResult(resultFromQuery($sql), 'VouchersHTL', false, true, 'posts.php', true);
}



?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="#" class="current">Vouchers em conflito</a>
	</div>
  </div>

  
  <div class="container-fluid">
	  <div class="row-fluid">
		  <div class="span12">
			  <div class="accordion" id="collapse-group">
				  
				  <!--MP con dataIN >= dataOUT-->
				  <div class="accordion-group widget-box">
					  <div class="accordion-heading">
						  <div class="widget-title">
							  <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($mpinmayoroigualout) <= 354 ? 'icon-ok': 'icon-tag';?>"></i></span>
							  <h5>Vouchers de MP con dataIN >= dataOUT</h5>
							  </a>
						  </div>
					  </div>
					  <div class="collapse in accordion-body" id="collapseGOne">
						  <div class="widget-content nopadding">
						  <form id="VouchersMPForm" name="VouchersMPForm" action="posts.php" method="post">
							  <?php echo $mpinmayoroigualout;?>
						  </form>
						  </div>
					  </div>
				  </div>
				  
				  <!--MP sin responsable de pago-->
				  <div class="accordion-group widget-box">
					<div class="accordion-heading">
					  <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($mpsinresponsable) <= 354 ? 'icon-ok': 'icon-tag';?>"></i></span>
						<h5>Vouchers de MP sin Responsable de pago</h5>
						</a> </div>
					</div>
					<div class="collapse accordion-body" id="collapseGTwo">
					  <div class="widget-content nopadding">
						  <form id="VouchersMPForm" name="VouchersMPForm" action="posts.php" method="post">
							  <?php echo $mpsinresponsable;?>
						  </form>
					</div>
					</div>
				  </div>
				  
				  <!--MP con responsable de pago a medio asignar-->				  
				  <div class="accordion-group widget-box">
					<div class="accordion-heading">
					  <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGThree" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($mpmedioresponsable) <= 354 ? 'icon-ok': 'icon-tag';?>"></i></span>
						<h5>Vouchers de MP con Responsable de pago a medio asignar</h5>
						</a> </div>
					</div>
					<div class="collapse accordion-body" id="collapseGThree">
					  <div class="widget-content nopadding">
						  <form id="VouchersMPForm" name="VouchersMPForm" action="posts.php" method="post">
							  <?php echo $mpmedioresponsable;?>
						  </form>
					</div>
					</div>
				  </div>
				  
				  
				   <!--MP duplicado-->				  
				  <div class="accordion-group widget-box">
					<div class="accordion-heading">
					  <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGFour" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($mpduplicado) <= 354 ? 'icon-ok': 'icon-tag';?>"></i></span>
						<h5>Vouchers de MP duplicados</h5>
						</a> </div>
					</div>
					<div class="collapse accordion-body" id="collapseGFour">
					  <div class="widget-content nopadding">
						  <form id="VouchersMPForm" name="VouchersMPForm" action="posts.php" method="post">
							  <?php echo $mpduplicado;?>
						  </form>
					</div>
					</div>
				  </div>
 
			  </div>
		  </div>
	  </div>
	  
	  <div class="row-fluid">
		  <div class="span12">
			  <div class="accordion" id="collapse-group">
				  
				  <!--HTL con dataIN >= dataOUT-->
				  <div class="accordion-group widget-box">
					<div class="accordion-heading">
					  <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGFive" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($htlinmayoroigualout) <= 354 ? 'icon-ok': 'icon-home';?>"></i></span>
						<h5>Vouchers de Hotelaria con dataIN >= dataOUT</h5>
						</a> </div>
					</div>
					<div class="collapse accordion-body" id="collapseGFive">
					  <div class="widget-content nopadding">
						  <form id="VouchersHTLForm" name="VouchersHTLForm" action="posts.php" method="post">
							  <?php echo $htlinmayoroigualout;?>
						  </form>
					</div>
					</div>
				  </div>
				  
				  <!--HTL Sin responsable de pago-->
				  <div class="accordion-group widget-box">
					<div class="accordion-heading">
					  <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGSix" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($htlsinresponsable) <= 354 ? 'icon-ok': 'icon-home';?>" ></i></span>
						<h5>Vouchers de Hotelaria sin Responsable de Pago</h5>
						</a> </div>
					</div>
					<div class="collapse accordion-body" id="collapseGSix">
					  <div class="widget-content nopadding">
						  <form id="VouchersHTLForm" name="VouchersHTLForm" action="posts.php" method="post">
							  <?php echo $htlsinresponsable;?>
						  </form>
					</div>
					</div>
				  </div>
			  
				  <!--HTL con responsable de pago a medio asignar-->
				  <div class="accordion-group widget-box">
					<div class="accordion-heading">
					  <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGSeven" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($htlmedioresponsable) <= 354 ? 'icon-ok': 'icon-home';?>"></i></span>
						<h5>Vouchers de Hotelaria con responsable de pago a medio asignar</h5>
						</a> </div>
					</div>
					<div class="collapse accordion-body" id="collapseGSeven">
					  <div class="widget-content nopadding">
						  <form id="VouchersHTLForm" name="VouchersHTLForm" action="posts.php" method="post">
							  <?php echo $htlmedioresponsable;?>
						  </form>
					</div>
					</div>
				  </div>
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  <!--HTL duplicados-->
				  <div class="accordion-group widget-box">
						<div class="accordion-heading">
						  <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGEight" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($htlduplicado) <= 354 ? 'icon-ok': 'icon-home';?>"></i></span>
							<h5>Vouchers de Hoteleria duplicados</h5>
							</a> </div>
						</div>
						<div class="collapse accordion-body" id="collapseGEight">
						  <div class="widget-content nopadding">
						  <form id="VouchersHTLForm" name="VouchersHTLForm" action="posts.php" method="post">
							  <?php echo $htlduplicado;?>
						  </form>
						</div>
						</div>
					  </div>
					  
					  
					  
					  
					  
					  
			    			  
				  <!--HTL con Posada externa-->
				  <div class="accordion-group widget-box">
						<div class="accordion-heading">
						  <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGNine" data-toggle="collapse"> <span class="icon"><i class="<?php echo strlen($tablahoteleria) <= 354 ? 'icon-ok': 'icon-home';?>"></i></span>
							<h5>Vouchers de Hoteleria con posada externa</h5>
							</a> </div>
						</div>
						<div class="collapse accordion-body" id="collapseGNine">
						  <div class="widget-content nopadding">
						  <form id="VouchersHTLForm" name="VouchersHTLForm" action="posts.php" method="post">
							  <?php echo $tablahoteleria;?>
						  </form>
						</div>
						</div>
					  </div>
				  
			  </div>
        
		  </div>
		  
		  
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
        
  </div>
</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
