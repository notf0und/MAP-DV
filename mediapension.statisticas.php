<?php include "head.php"; ?>

<!--main-container-part--> 
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb">
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="#" class="current">Statísticas</a>
	</div>
	<h1>Meia-pensão - Statísticas</h1><hr>
  </div>
<!--End-breadcrumbs-->


<!--Chart-box-->    
    <div class="row-fluid">
      <div class="widget-box">
        <div class="widget-content" >
          <div class="row-fluid">
              <ul class="site-stats">
				
				<!-- Cantidad de personas que van a comer hoy en el restaurante -->
				<li class="bg_lg span2"><i class="icon-globe"></i> <strong>
				<?php
				$sqlQuery = "SELECT COALESCE(SUM(MP.qtdedepax * SS.ComidasDiarias), 0) ";
				$sqlQuery .= "FROM   mediapension MP ";
				$sqlQuery .= "LEFT JOIN servicios SS on MP.idservicios = SS.idservicios ";
				$sqlQuery .= "LEFT JOIN mediapension_admisiones MPA on  MP.idmediapension = MPA.idmediapension ";
				$sqlQuery .= "WHERE MP.dataIN<=CURDATE() AND MP.dataOUT>=CURDATE() AND habilitado = 1;";
				$sqlResult = resultFromQuery($sqlQuery);
				
				while ($row = mysql_fetch_row($sqlResult))
				{
					echo "$row[0]";
					$comeranentotal = $row[0];
				}
				?>
				</strong> <small>Pessoas vão a comer hoje</small></li>
				
                <!-- Cantidad de personas que ya comieron -->
                <li class="bg_lb span2"><i class="icon-user"></i> <strong>
				<?php
				$sqlQuery = "SELECT COALESCE(SUM(qtdedepax), 0) FROM mediapension_admisiones where date(data) = curdate();";
				$sqlResult = resultFromQuery($sqlQuery);
				
				while ($row = mysql_fetch_row($sqlResult))
				{
					echo "$row[0]";
					$yacomieron = $row[0];
				}
				?>
                </strong> <small>Pessoas já comeram</small></li>
                
				<!-- Cantidad de personas que faltan por comer -->
				<li class="bg_ly span2"><i class="icon-plus"></i> <strong>
				<?php
				
				$faltancomer = $comeranentotal - $yacomieron;
				echo "$faltancomer";

				?>
				</strong> <small>Faltan por comer</small></li>
				
				<!-- Cantidad de vouchers ingresados hoy -->
                <li class="bg_lh span3"> <i class="icon-tag"></i> <strong>
				<?php
				$sqlQuery = "SELECT COUNT(*) FROM mediapension WHERE DATE(data) = CURDATE() AND habilitado = 1;";
				$sqlResult = resultFromQuery($sqlQuery);
				
				while ($row = mysql_fetch_row($sqlResult))
				{
					echo "$row[0]";
				}
				?>
                </strong> <small>Vouchers foram admitidos hoje</small></li></a>
                
              </ul>
          </div>
        </div>
      </div>
    </div>
<!--End-Chart-box--> 


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
