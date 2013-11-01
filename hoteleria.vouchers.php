<?php include "head.php"; ?>

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
			<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
				<h5>Vouchers</h5>
			</div>
			<form id="VouchersHTLForm" name="VouchersHTLForm" action="posts.php" method="post">
			<div class="widget-content nopadding">
				<?php
					$sqlQuery = " SELECT HTL.idhoteleria id, H.titular 'Nome PAX', HTL.numeroexterno 'Vouchar #', ";
					$sqlQuery .= " HTL.qtdedepax 'Qtde de PAX', HTL.dataIN, HTL.dataOUT FROM `hoteleria` HTL ";
					$sqlQuery .= " LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes ";
					$sqlQuery .= " WHERE 1 ";
					$sqlQuery .= " AND HTL.idhoteleria > 0 ";
					echo tableFromResult(resultFromQuery($sqlQuery), 'VouchersHTL', false, true, 'posts.php', true);
				?>		  
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
