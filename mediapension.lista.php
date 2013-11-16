<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="#" class="current">Veja a lista</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Lista de Media pension - Previsão pra hoje: ≈
            <?php
            $sqlQuery = "SELECT SUM(qtdedepax) FROM mediapension WHERE dataIN<=DATE(NOW()) AND dataOUT>=DATE(NOW()); ";
            $sqlResult = resultFromQuery($sqlQuery);
              
            while ($row = mysql_fetch_row($sqlResult)){ 
				echo "$row[0]"; 
			} 

            ?>
            
            </h5>
          </div>
          <div class="widget-content nopadding">
			<?php
				$sqlQuery = "SELECT MP.idmediapension, H.Titular 'Nome PAX', MP.numeroexterno 'Voucher', S.Nombre 'Servicio', MP.qtdedepax Pax, qtdedecomidas 'Dias', (SELECT SUM(qtdedepax) FROM mediapension_admisiones WHERE idmediapension = MP.idmediapension) 'admisiones' FROM `mediapension` MP";
				$sqlQuery .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
				$sqlQuery .= " LEFT JOIN servicios S ON MP.idservicios = S.idservicios ";
				$sqlQuery .= " WHERE 1 ";
				$sqlQuery .= " AND habilitado = 1 ";
				$sqlQuery .= " AND CURRENT_DATE BETWEEN MP.DataIN AND MP.DataOUT";
				echo tableFromResultGDA(resultFromQuery($sqlQuery), 'mediapension', false, false, 'posts.php', true);
			?>		  
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
		<form method="get" action="mediapension.novo.php">
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
