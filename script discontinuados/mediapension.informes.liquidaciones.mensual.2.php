<?php include "head.php"; 

$numero = cal_days_in_month(CAL_GREGORIAN, $_SESSION["visualizarMes"], $_SESSION["visualizarAno"]); 
$dataIN = $_SESSION["visualizarAno"]."-".$_SESSION["visualizarMes"]."-01";
$dataOUT = $_SESSION["visualizarAno"]."-".$_SESSION["visualizarMes"]."-".$numero;
$idposadas = $_GET['idposadas'];
liquidacionServiciosPosadas($idposadas, $dataIN, $dataOUT);	
		
?>	
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="#" class="current">Media pension</a>
	</div>
	<h1>Export Liquidacion</h1><hr>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <li class="bg_lg span1"> <a href="mediapension.informes.liquidaciones.mensual.php"> <i class="icon-share-alt"></i> Voltar </a> </li>
      </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
<script languaje="javascript"> self.location="mediapension.informes.liquidaciones.mensual.reporte.php"</script>
