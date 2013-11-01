<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
	</div>
	<h1>Menu de opções</h1><hr>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
		<?php if (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 2) || ($_SESSION["idusuarios_tipos"] == 3)) {?>
        <li class="bg_lb span2"> <a href="mediapension.php"> <i class="icon-glass"></i> Mediapension </a> </li>
        <li class="bg_lb span2"> <a href="hoteleria.php"> <i class="icon-download-alt"></i> Hoteleria </a> </li>
		<?php } ?>
		<?php if ($_SESSION["idusuarios_tipos"] == 1) {?>
        <li class="bg_lb span2"> <a href="liquidaciones.php"> <i class="icon-list-ul"></i> Liquidaciones</a> </li>
        <li class="bg_lb span2"> <a href="administradores.php"> <i class="icon-list-ul"></i> Adminitradores</a> </li>
		<?php } ?>
      </ul>
    </div>
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
		<?php if ($_SESSION["idusuarios_tipos"] == 1) {?>
        <li class="bg_lg span2"> <a href="reservas.php"> <i class="icon-calendar"></i> Reservas</a> </li>
        <li class="bg_ly span2"> <a href="salarios.php"> <i class="icon-money"></i> Salarios </a> </li>
  		<?php }?>


      </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
