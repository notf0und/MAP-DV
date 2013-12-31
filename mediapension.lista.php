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
            <h5>Lista de Media pension</h5>
          </div>
          <div class="widget-content nopadding">
			<?php
				$sqlQuery = "SELECT ";
				$sqlQuery .= "MP.idmediapension, ";
				$sqlQuery .= "H.Titular 'Nome PAX', ";
				$sqlQuery .= "MP.numeroexterno 'Voucher', ";
				$sqlQuery .= "S.Nombre 'Servicio', ";
				$sqlQuery .= "MP.qtdedepax 'Pax', ";
				
				// Solo para camila mostrar el nombre de la posada, para los demas es irrelevante
				if ($_SESSION["idusuarios_tipos"] == 2) {
					$sqlQuery .= "P.nombre 'Pousada', ";
				}
				
				
				$sqlQuery .= "qtdedecomidas 'Dias', ";
				$sqlQuery .= "(SELECT SUM(qtdedepax) FROM mediapension_admisiones WHERE idmediapension = MP.idmediapension) 'admisiones' ";
				$sqlQuery .= "FROM mediapension MP ";
				$sqlQuery .= "LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
				$sqlQuery .= "LEFT JOIN servicios S ON MP.idservicios = S.idservicios ";
				$sqlQuery .= "LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
				$sqlQuery .= "WHERE 1 ";
				$sqlQuery .= "AND habilitado = 1 ";
				$sqlQuery .= "AND CURRENT_DATE BETWEEN MP.DataIN AND MP.DataOUT ";
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
