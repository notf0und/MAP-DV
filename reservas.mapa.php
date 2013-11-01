<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> 
			<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
			<a href="reservas.php" title="Reservas" class="tip-bottom">Reservas</a>
			<a href="#" class="current">Mapa</a>
		</div>
	</div>
<!--End-breadcrumbs-->
<?php 

if (isset($_GET['idposadas'])){
	$_SESSION["idposadas"] = $_GET['idposadas'];
}
if (isset($_GET['mes'])){
	$_SESSION["visualizarMes"] = $_GET['mes'];
}
if (isset($_GET['ano'])){
	$_SESSION["visualizarAno"] = $_GET['ano'];
}

?>
	<div class="row-fluid">
		<div class="container-fluid">
			<div class="widget-box">
				<div class="widget-content nopadding">
					<form id="formBuscar" name="formBuscar" method="get">
						<div class="control-group span3">
							Posada
							<?php
								$sqlQuery = " SELECT idposadas, nombre FROM posadas WHERE idposadas<=4 ";
								$resultado = resultFromQuery($sqlQuery);
								echo comboFromArray('idposadas', $resultado, $_SESSION["idposadas"], '', '');
							?>
						</div>
						<div class="control-group span1">
							Mes
							<select id="mes" name="mes">
								<?php for ($i=1; $i<=12; $i++){?>
								<option value="<?php echo $i;?>" <?php if($i==$_SESSION["visualizarMes"]){echo 'selected';}?>><?php echo $i;?></option>
								<?php }?>
							</select>
						</div>
						<div class="control-group span2">
							Ano
							<select id="ano" name="ano">
								<?php for ($i=2013; $i<=2016; $i++){?>
								<option value="<?php echo $i;?>" <?php if($i==$_SESSION["visualizarAno"]){echo 'selected';}?>><?php echo $i;?></option>
								<?php }?>
							</select>
						</div>
						<div class="control-group span2"><br>
							<button class="btn btn-success" type="submit">Ver</button>
						</div>
					</form>				
				</div>
			</div>
		</div>
	</div>

<?php 
if (($_SESSION["idposadas"]>0) && ($_SESSION["visualizarMes"]>0) && ($_SESSION["visualizarAno"]>0)){
?>


	<div class="container-fluid">
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
					<h5>Mapa de reservas</h5><br>
				</div>
				<div class="widget-content nopadding">
				<?php
					$sqlQuery = "SELECT idhabitaciones, codigo, '".$_SESSION["idposadas"].",".$_SESSION["visualizarMes"].",".$_SESSION["visualizarAno"]."' calendario FROM `habitaciones` WHERE idposadas = ".$_SESSION["idposadas"]."";
					echo tableFromResultGDA(resultFromQuery($sqlQuery), 'Mapa', false, false, 'posts.php', false);
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
			<form method="post" action="posts.php">
				<input type="hidden" id="accion" name="accion" value="nuevaReserva" />
				<button class="btn btn-success" type="submit">Novo...</button>
			</form>      	
			<form id="ReservasForm" name="ReservasForm" action="posts.php" method="post">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
						<h5>Reservas del mes</h5><br>
					</div>
					<div class="widget-content nopadding">
					<?php
						$sqlQuery = "SELECT R.idreservas 'ID', H.Titular 'Nome PAX', S.Nombre 'Servicio', ";
						$sqlQuery .= " R.qtdedepax 'Qtde PAX', R.DataIN, R.DataOUT, ";
						$sqlQuery .= " ( ";
						$sqlQuery .= " 		SELECT concat(P.Nombre, ' - ', H.Codigo, ' - ', Count('id'), ' noite/s') descripcion FROM `reservas_admisiones` RA ";
						$sqlQuery .= " 		LEFT JOIN posadas P ON RA.idposadas = P.idposadas ";
						$sqlQuery .= " 		LEFT JOIN habitaciones H ON RA.idhabitaciones = H.idhabitaciones ";
						$sqlQuery .= " 		WHERE RA.idreservas = R.idreservas	";
						$sqlQuery .= " ) descripcion ";
						$sqlQuery .= " FROM `reservas` R";
						$sqlQuery .= " LEFT JOIN huespedes H ON R.idhuespedes = H.idhuespedes ";
						$sqlQuery .= " LEFT JOIN servicios S ON R.idservicios = S.idservicios ";
						$sqlQuery .= " WHERE 1 ";
						$sqlQuery .= " AND R.idposadas = ".$_SESSION["idposadas"];
						$sqlQuery .= " AND ".$_SESSION["visualizarMes"]." BETWEEN MONTH(R.DataIN) AND MONTH(R.DataOUT)";
						echo tableFromResultGDA(resultFromQuery($sqlQuery), 'Reservas', true, true, 'posts.php', false);
					?>		  
					</div>
				</div>
			</form>
		</div>
		<hr/>
	</div>
<?php 
}
?>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>