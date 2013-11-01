<?php include "head.php"; ?>

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
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5>Vouchers</h5>
			</div>
			<form id="VouchersMPForm" name="VouchersMPForm" action="posts.php" method="post">
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = " SELECT MP.idmediapension id, H.titular 'Nome PAX', MP.numeroexterno 'Vouchar #', ";
					//$sqlQuery .= " MP.idmediapension 'MEDIAPENSION', MP.actualizado 'act', ";
					$sqlQuery .= " MP.qtdedepax 'Qtde de PAX', MP.dataIN, MP.dataOUT, ";
					$sqlQuery .= " P.nombre 'Posada', O.nombre 'Operador', A.nombre 'Agencia'  ";
					$sqlQuery .= " , RDP.nombre 'Responsable'  ";
					$sqlQuery .= " FROM `mediapension` MP ";
					$sqlQuery .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
					$sqlQuery .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
					$sqlQuery .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
					$sqlQuery .= " LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ";
					$sqlQuery .= " LEFT JOIN responsablesDePago RDP ON MP.idresponsablesDePago = RDP.idresponsablesDePago ";
					$sqlQuery .= " WHERE 1 ";
					$sqlQuery .= " AND MP.idliquidaciones = 0 ";
					$sqlQuery .= " AND MP.habilitado = 1 ";
					echo tableFromResult(resultFromQuery($sqlQuery), 'VouchersMP', true, true, 'posts.php', true);
				?>		  
			</div>
			</form>
        </div>
		<form method="post" action="posts.php">
			<input type="hidden" id="accion" name="accion" value="VouchersMPNew" />
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
