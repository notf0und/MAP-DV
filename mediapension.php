<?php include "head.php"; ?>

<!--main-container-part--> 
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb">
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="#" class="current">Meia-pensão</a>
	</div>
	<h1>Meia-pensão - Menu de opções</h1><hr>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
  		
  		<?php if (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 2) || ($_SESSION["idusuarios_tipos"] == 3)) {?>
        <li class="bg_lr span2"> <a href="mediapension.novo.php"> <i class="icon-plus"></i> Novo... </a> </li>
        <li class="bg_ly span2"> <a href="mediapension.lista.php"> <i class="icon-list-ul"></i> Veja a lista</a> </li>
        
        <?php if (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 3)) {?>
        <li class="bg_lb span2"> <a href="mediapension.vouchers.php"> <i class="icon-list-ul"></i> Vouchers</a> </li>
		<?php } ?>
        
        <li class="bg_lg span2"> <a href="mediapension.estatisticas.php"> <i class="icon-signal"></i> Estatísticas</a> </li>
  		<?php } ?>
      </ul>
    </div>
<!--End-Action boxes--> 


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
