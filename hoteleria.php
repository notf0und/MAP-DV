<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb">
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="#" class="current">Hoteleria</a>
	</div>
	<h1>Hoteleria - Menu de opções</h1><hr>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
  		<?php if (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 2) || ($_SESSION["idusuarios_tipos"] == 3)  || ($_SESSION["idusuarios_tipos"] == 6)) {?>
<!--
        <li class="bg_lr span2"> <a href="hoteleria.novo.php"> <i class="icon-plus"></i> Novo... </a> </li>
-->
			<?php if (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 3)  || ($_SESSION["idusuarios_tipos"] == 6)) {?>
        <li class="bg_lb span2"> <a href="hoteleria.vouchers.php"> <i class="icon-list-ul"></i> Vouchers</a> </li>
			<?php } ?>
  		<?php } ?>
      </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
