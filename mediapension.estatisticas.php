<?php include "head.php"; ?>

<!--main-container-part--> 
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb">
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Meia-pensão</a>
		<a href="#" class="current">Estatísticas</a>
	</div>
	<h1>Meia-pensão - Estatísticas</h1><hr>
  </div>
<!--End-breadcrumbs-->


<!--Chart-box-->    
    <div class="row-fluid">
      <div class="widget-box">
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
				<li class="bg_ly"><i class="icon-plus"></i> <strong>
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
    </div>
<!--End-Chart-box--> 


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
