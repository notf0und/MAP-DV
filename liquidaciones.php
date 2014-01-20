<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="#" class="current">Liquidações</a>
	</div>
	<h1>Liquidações - Menu de opções</h1><hr>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <li class="bg_lr span2"> <a href="liquidaciones.mensual.php"> <i class="icon-plus"></i> Liq. Mensal. </a> </li>
        <!--
        <li class="bg_lr span2"> <a href="mediapension.informes.liquidaciones.operadores.php"> <i class="icon-plus"></i> Liquidações por operadores </a> </li>
        <li class="bg_lr span2"> <a href="mediapension.informes.liquidaciones.agencias.php"> <i class="icon-plus"></i> Liquidações por agencias </a> </li>
        <li class="bg_lr span2"> <a href="mediapension.informes.liquidaciones.posadas.php"> <i class="icon-plus"></i> Liquidações por posadas </a> </li>
        -->
        <li class="bg_lb span2"> <a href="liquidaciones.pendientes.php"> <i class="icon-share-alt"></i> Liq. Pendentes</a> </li>
        <li class="bg_lb span2"> <a href="liquidaciones.cerradas.php"> <i class="icon-share-alt"></i> Liq. Fechadas</a> </li>
      </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
