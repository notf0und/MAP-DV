<?php include "head.php"; 
//MP H&D
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
$sql .= "AND SLDP.idlistasdeprecios = 10;";

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
$sql .= "AND MONTH(MP.dataIN) = MONTH(CURDATE()) ";
$sql .= "AND YEAR(MP.dataIN) = YEAR(CURDATE()) ";
$sql .= "AND idoperadoresturisticos != 2 ";
$sql .= "AND SLDP.idlistasdeprecios = 3;";
$result = resultFromQuery($sql);

while ($row = mysql_fetch_row($result))
{
	$reporteotros = $row[0];
}

//HOTELERIA
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
$sql .= "AND MONTH(HTL.dataIN) = MONTH(CURDATE()) ";
$sql .= "AND YEAR(HTL.dataIN) = YEAR(CURDATE()) ";
$sql .= "AND SLDP.idlistasdeprecios = 10 ";


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
$sql .= "AND MONTH(HTL.dataIN) = MONTH(CURDATE()) ";
$sql .= "AND YEAR(HTL.dataIN) = YEAR(CURDATE()) ";
$sql .= "AND SLDP.idlistasdeprecios = 3;";

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

	$totalsalarios += calcularSalario($row[0], date('n'), date('Y'))['Total'];
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
    <div class="row-fluid">
		
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
						  <td>TOTAL&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
						  <td><center><b><?php echo '<span class="badge badge-success">U$S '.$totalmp.'</span>'; ?></b></center></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>



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
                  <td> H&D</td>
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
						  <td>TOTAL&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
						  <td><center><b><?php echo '<span class="badge badge-success"> U$S '.round($totalsalarios/$converted_amount, 2).'</span><br><span class="badge badge-important">R$ '.$totalsalarios.'</span>'; ?></b></center></td>
						</tr>
					</tbody>
				</table>
			</div>          
        </div>
		
		

</div>   
    
		
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
							<td> H&D</td>
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
						  <td>TOTAL&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
						  <td><center><b><?php echo '<span class="badge badge-success">U$S '.$total.'</span> <br><span class="badge badge-info"> R$ '.round($total*$converted_amount, 2).'</span>'; ?></b></center></td>
						</tr>
					</tbody>
				</table>
			</div>          
        </div>
		
		

</div>   
    
<!--End-Chart-box--> 


  </div>
</div>

<!--end-main-container-part-->

<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2013 © Grupos Das Americas. </div>
</div>
<!--end-Footer-part-->
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.flot.min.js"></script> 
<script src="js/matrix.charts.js"></script> 

</body>
</html>

