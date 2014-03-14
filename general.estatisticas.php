<?php include "head.php"; 
//MP 

if (isset($_POST['desde']) || isset($_POST['ate'])){
	
	$title = (isset($_POST['desde']) && $_POST['desde'] != '') ? $_POST['desde'] : date('Y-m-01');
	$title .= (isset($_POST['ate']) && $_POST['ate'] != '') ? ' / '.$_POST['ate'] : date('/Y-m-t');
	
	$sqlcondition = "AND (MP.dataIN BETWEEN ";
	$sqlcondition .= (isset($_POST['desde']) && $_POST['desde'] != '') ? "'".dateFormatMySQL($_POST['desde'])."' " : "DATE_FORMAT(NOW() ,'%Y-%m-01')";//* AND MP.dataIN >= '".dateFormatMySQL($_POST['desde'])."' " : '';
	$sqlcondition .= (isset($_POST['ate']) && $_POST['ate'] != '') ? "AND '".dateFormatMySQL($_POST['ate'])."') " : 'AND CURDATE())';

}
else{
	$title = "Ultimo mes";

	$sqlcondition = "AND month(MP.dataIN) = month(curdate()) ";
}

//H&D
$sql = "SELECT SUM(CASE MP.hoteleria WHEN 1 THEN 0 ELSE SLDP.precio END  * DATEDIFF(MP.dataOUT, MP.dataIN) * MP.qtdedepax) ";
$sql .= "FROM mediapension MP ";
$sql .= "LEFT JOIN servicios SS ON MP.idservicios = SS.idservicios ";
$sql .= "LEFT JOIN listasdeprecios LDP ON MP.dataIN BETWEEN LDP.VigenciaIN  AND LDP.VigenciaOUT ";
$sql .= "LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios ";
$sql .= "WHERE 1   AND LDP.idresponsablesDePago =  MP.idresponsablesdepago ";
$sql .= "AND SLDP.idservicios = MP.idservicios ";
$sql .= "AND MONTH(MP.dataIN) = MONTH(CURDATE()) ";
$sql .= "AND YEAR(MP.dataIN) = YEAR(CURDATE()) ";
$sql .= "AND idoperadoresturisticos = 2 ";
$sql .= "AND SLDP.idlistasdeprecios = 10 ";
$sql .= $sqlcondition;

$result = resultFromQuery($sql);

while ($row = mysql_fetch_row($result))
{
	$reportehyd = $row[0];
}

$sql = "SELECT SUM(CASE MP.hoteleria WHEN 1 THEN 0 ELSE SLDP.precio END  * DATEDIFF(MP.dataOUT, MP.dataIN) * MP.qtdedepax) ";
$sql .= "FROM mediapension MP ";
$sql .= "LEFT JOIN servicios SS ON MP.idservicios = SS.idservicios ";
$sql .= "LEFT JOIN listasdeprecios LDP ON MP.dataIN BETWEEN LDP.VigenciaIN  AND LDP.VigenciaOUT ";
$sql .= "LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios ";
$sql .= "WHERE 1   AND LDP.idresponsablesDePago =  MP.idresponsablesdepago ";
$sql .= "AND SLDP.idservicios = MP.idservicios ";
$sql .= "AND idoperadoresturisticos != 2 ";
$sql .= "AND SLDP.idlistasdeprecios = 3 ";
$sql .= $sqlcondition;

$result = resultFromQuery($sql);

while ($row = mysql_fetch_row($result))
{
	$reporteotros = $row[0];
}

//HOTELERIA

if (isset($_POST['desde']) || isset($_POST['ate'])){

	$sqlcondition = "AND (HTL.dataIN BETWEEN ";
	$sqlcondition .= (isset($_POST['desde']) && $_POST['desde'] != '') ? "'".dateFormatMySQL($_POST['desde'])."' " : "DATE_FORMAT(NOW() ,'%Y-%m-01') ";//* AND MP.dataIN >= '".dateFormatMySQL($_POST['desde'])."' " : '';
	$sqlcondition .= (isset($_POST['ate']) && $_POST['ate'] != '') ? "AND '".dateFormatMySQL($_POST['ate'])."')" : 'AND CURDATE())';

}
else{
	$sqlcondition = "AND month(HTL.dataIN) = month(curdate()) ";
}

//H&D
$sql = "SELECT SUM(DATEDIFF(HTL.dataOUT, HTL.dataIN) * SLDP.precio) ";
$sql .= "FROM hoteleria HTL ";
$sql .= "LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes  ";
$sql .= "LEFT JOIN listasdeprecios LDP ON HTL.dataIN BETWEEN LDP.VigenciaIN ";
$sql .= "AND LDP.VigenciaOUT  LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios  ";
$sql .= "WHERE 1  ";
$sql .= "AND LDP.idresponsablesDePago =  HTL.idresponsablesdepago ";
$sql .= "AND SLDP.idservicios = HTL.idservicios ";
$sql .= "AND HTL.dataIN BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT  ";
$sql .= "AND SLDP.idposadas_internas = HTL.idposadas ";
$sql .= "AND idoperadoresturisticos = 2  ";
$sql .= "AND SLDP.idlistasdeprecios = 10 ";
$sql .= $sqlcondition;

$result = resultFromQuery($sql);

while ($row = mysql_fetch_row($result))
{
	$reportehydhoteleria = $row[0];
}

//OTROS
$sql = "SELECT SUM(DATEDIFF(HTL.dataOUT, HTL.dataIN) * SLDP.precio) ";
$sql .= "FROM hoteleria HTL ";
$sql .= "LEFT JOIN huespedes H ON HTL.idhuespedes = H.idhuespedes ";
$sql .= "LEFT JOIN listasdeprecios LDP ON HTL.dataIN BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT ";
$sql .= "LEFT JOIN servicios_listasdeprecios SLDP ON LDP.idlistasdeprecios = SLDP.idlistasdeprecios ";
$sql .= "WHERE 1  AND LDP.idresponsablesDePago =  HTL.idresponsablesdepago ";
$sql .= "AND SLDP.idservicios = HTL.idservicios ";
$sql .= "AND HTL.dataIN BETWEEN LDP.VigenciaIN AND LDP.VigenciaOUT "; 
$sql .= "AND SLDP.idposadas_internas = HTL.idposadas ";
$sql .= "AND idoperadoresturisticos != 2 ";
$sql .= "AND SLDP.idlistasdeprecios = 3 ";
$sql .= $sqlcondition;

$result = resultFromQuery($sql);

while ($row = mysql_fetch_row($result))
{
	$reporteotroshoteleria = $row[0];
}

//Salarios
//SELECT
$sql = "SELECT ";
$sql .= "employee_id id ";
//FROM
$sql .= "FROM employee E ";

$result = resultFromQuery($sql);

$totalsalarios = 0;

while ($row = mysql_fetch_row($result))
{
	$salario = calcularSalario($row[0], date('n'), date('Y'));
	$salario = $salario['Total'];
	
	$totalsalarios += $salario;
	
	unset($salario);
}

$totalmp = $reportehyd + $reporteotros;

$totalhtl = $reportehydhoteleria + $reporteotroshoteleria;

$totalhyd = $reportehyd + $reportehydhoteleria;

$totalotros = $reporteotros + $reporteotroshoteleria;

$total = $totalhyd + $totalotros;


$amount = '1';
$from_Currency = 'USD';
$to_Currency = 'BRL';
                  
$amount = urlencode($amount);
$from_Currency = urlencode($from_Currency);
$to_Currency = urlencode($to_Currency);

//https://www.google.com/finance/converter?a=10&from=USD&to=BRL
$get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency");
$get = explode("<span class=bld>",$get);
$get = explode("</span>",$get[1]);  
$converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
				  


?>

<!--main-container-part--> 
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb">
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="#" class="current">Estatísticas</a>
	</div>
	<h1>Estatísticas Gerales</h1><hr>
  </div>
<!--End-breadcrumbs-->


<!--Chart-box-->
<div class="container-fluid">    
    <div class="row-fluid">
		<div class="span12">

			<!--Data de pesquisa-->
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
						  <h5>Data de pesquisa: <?php echo isset($title) ? $title : ''; ?></h5>
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
				  
			<!--Reporte Media Pension-->
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-tags"></i> </span>
					<h5>Reporte Media Pensión</h5>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Responsable</th>
								<th>Monto</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td> H&D</td>
								<td><center>U$S <?php echo $reportehyd; ?></center></td>
							</tr>

							<tr>
								<td>Otros operadores</td>
								<td><center>U$S <?php echo $reporteotros; ?></center></td>
							</tr>
					
							<tr>
							  <td></td>
							  <td></td>
							</tr>
					
							 <tr>
							  <td>TOTAL</td>
							  <td><center><b><?php echo '<span class="badge badge-success">U$S '.$totalmp.'</span>'; ?></b></center></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>



			
	<!--End-Chart-box--> 


	  </div>

			<!--Reporte Hotelaria-->
			<div class="widget-box">
			  <div class="widget-title"> <span class="icon"> <i class="icon-home"></i> </span>
				<h5>Reporte Hotelaria</h5>
			  </div>
			  <div class="widget-content nopadding">
				<table class="table table-bordered table-striped">
				  <thead>
					<tr>
					  <th>Responsable</th>
					  <th>Monto</th>
					</tr>
				  </thead>
				  <tbody>
					  <tr>
					  <td> H&D </td>
					  <td><center>U$S <?php echo $reportehydhoteleria; ?></center></td>
					</tr>
					  <tr>
					  <td>Otros operadores</td>
					  <td><center>U$S <?php echo $reporteotroshoteleria; ?></center></td>
					</tr>
					  <tr>
					  <td></td>
					  <td></td>
					</tr>
					  <tr>
					  <td>TOTAL</td>
					  <td><center><b><?php echo '<span class="badge badge-success">U$S '.$totalhtl.'</span>'; ?></b></center></td>
					</tr>
				  </tbody>			  
				</table>
			  </div>          
			</div>

			<!--Reporte Total-->
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-globe"></i> </span>
					<h5>Reporte Total</h5>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Responsable</th>
								<th>Monto</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td> H&D </td>
								<td><center>U$S <?php echo $totalhyd; ?></center></td>
							</tr>
							<tr>
								<td>Otros operadores</td>
								<td><center>U$S <?php echo $totalotros; ?></center></td>
							</tr>
							<tr>
							  <td></td>
							  <td></td>
							</tr>
							<tr>
							  <td>TOTAL</td>
							  <td><center><b><?php echo '<span class="badge badge-success">U$S '.$total.'</span> <br><span class="badge badge-info"> R$ '.round($total*$converted_amount, 2).'</span>'; ?></b></center></td>
							</tr>
						</tbody>
					</table>
				</div>          
			</div>
			
			<!--Reporte Salarios-->
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-money"></i> </span>
					<h5>Reporte Salarios</h5>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Detalle</th>
								<th>Monto</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							  <td>TOTAL</td>
							  <td><center><b><?php echo '<span class="badge badge-success"> U$S '.round($totalsalarios/$converted_amount, 2).'</span><br><span class="badge badge-important">R$ '.$totalsalarios.'</span>'; ?></b></center></td>
							</tr>
						</tbody>
					</table>
				</div>          
			</div>

			<!--Previsão Ristorante-->
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-edit"></i> </span>
					<h5>Previsão Ristorante</h5>
				</div>
			<div class="widget-content" >
			  <div class="row-fluid">
				  <ul class="site-stats">
					
					<!-- Cantidad de personas que van a comer hoy en el restaurante -->
					<li class="bg_lg"><i class="icon-globe"></i> <strong>
					<?php
					$sqlQuery = "SELECT COALESCE(SUM(MP.qtdedepax * SS.ComidasDiarias), 0) ";
					$sqlQuery .= "FROM   mediapension MP ";
					$sqlQuery .= "LEFT JOIN servicios SS on MP.idservicios = SS.idservicios ";
					$sqlQuery .= "WHERE 1 ";
					$sqlQuery .= "AND MP.dataIN<=CURDATE() ";
					$sqlQuery .= "AND MP.dataOUT>=CURDATE() ";
					$sqlQuery .= "AND MP.habilitado = 1;";
					$sqlResult = resultFromQuery($sqlQuery);
					
					while ($row = mysql_fetch_row($sqlResult))
					{
						echo "$row[0]";
						$comeranentotal = $row[0];
					}
					?>
					</strong> <small>Pessoas vão comer hoje</small></li>
					
					<!-- Cantidad de personas que ya comieron -->
					<li class="bg_lb"><i class="icon-user"></i> <strong>
					<?php
					$sqlQuery = "SELECT COALESCE(SUM(MPA.qtdedepax), 0) ";
					$sqlQuery .= "FROM mediapension_admisiones MPA ";
					$sqlQuery .= "LEFT JOIN mediapension MP ON MPA.idmediapension = MP.idmediapension ";
					$sqlQuery .= "WHERE 1 ";
					$sqlQuery .= "AND date(MPA.data) = curdate() ";
					$sqlQuery .= "AND MP.habilitado = 1;";
					$sqlResult = resultFromQuery($sqlQuery);
					
					while ($row = mysql_fetch_row($sqlResult))
					{
						echo "$row[0]";
						$yacomieron = $row[0];
					}
					?>
					</strong> <small>Pessoas já comeram</small></li>
					
					<!-- Cantidad de personas que faltan por comer -->
					<li class="bg_ly"><i class="icon-group"></i> <strong>
					<?php
					
					$faltancomer = $comeranentotal - $yacomieron;
					echo "$faltancomer";

					?>
					</strong> <small>Faltam comer</small></li>
					
					<!-- Cantidad de vouchers ingresados hoy -->
					<li class="bg_lh"> <i class="icon-tag"></i> <strong>
					<?php
					$sqlQuery = "SELECT COUNT(*) FROM mediapension WHERE DATE(data) = CURDATE() AND habilitado = 1;";
					$sqlResult = resultFromQuery($sqlQuery);
					
					while ($row = mysql_fetch_row($sqlResult))
					{
						echo "$row[0]";
					}
					?>
					</strong> <small>Vouchers foram ingressados hoje</small></li></a>
					
				  </ul>
			  </div>
			</div>
		  </div>

			<!--Historial Ristorante-->
			<div class="row-fluid">
		  <div class="span12">
			<div class="widget-box">
			  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
				<h5>Historial Ristorante</h5>
			  </div>
			  <div class="widget-content">
				<div class="chart"></div>
			  </div>
			</div>
		  </div>
		</div>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<!--Footer-part-->
<?php include "footer.php"; ?>

<!--end-main-container-part-->

<!--

<div class="row-fluid">
  <div id="footer" class="span12"> 2013 © Grupos Das Americas. </div>
</div>
<!--
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.flot.min.js"></script> 
<script src="js/matrix.charts.js"></script> 

</body>
</html>

-->

