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
		  
		<?php if (isset($_SESSION["idusuarios_tipos"]) && (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 2) || ($_SESSION["idusuarios_tipos"] == 3) || ($_SESSION["idusuarios_tipos"] == 4) || ($_SESSION["idusuarios_tipos"] == 8) || ($_SESSION["idusuarios_tipos"] == 6))) {?>
        <li class="bg_lb span2"> <a href="mediapension.php"> <i class="icon-glass"></i>Meia-pensão</a> </li>
		<?php } ?>
		
		<?php if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3 || ($_SESSION["idusuarios_tipos"] == 4) || $_SESSION["idusuarios_tipos"] == 6)) {?>
		<li class="bg_lb span2"> <a href="hoteleria.php"> <i class="icon-download-alt"></i>Hotelaria</a></li>
		<li class="bg_lb span2"> <a href="liquidaciones.php"> <i class="icon-list-ul"></i> Liquidações</a> </li>
		<li class="bg_lr span2"> <a href="vouchers.conflictivos.php"> <i class="icon-tags"></i> Vouchers em conflito</a> </li>
		<?php } ?>
		
		<?php if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 3)) {?>
        <li class="bg_lb span2"> <a href="administradores.php"> <i class="icon-list-ul"></i> Administradores</a> </li>
		<?php } ?>

		
      </ul>
    </div>
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
		  
		  <?php if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 10 || $_SESSION["idusuarios_tipos"] == 11)) {?>
			  <li class="bg_lg span2"> <a href="reservas.php"> <i class="icon-calendar"></i> Reservas</a> </li>
		  <?php }?>
		  
		<?php if (isset($_SESSION["idusuarios_tipos"]) && (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 4) || ($_SESSION["idusuarios_tipos"] == 5) || ($_SESSION["idusuarios_tipos"] == 8) || ($_SESSION["idusuarios_tipos"] == 9))) {?>
        <li class="bg_lo span2"> <a href="salarios.php"> <i class="icon-sitemap"></i> Área Contábil </a> </li>
  		<?php }?>
  		
  		<?php if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 1)) {?>
        <li class="bg_lg span2"> <a href="interactive.php"> <i class="icon-bar-chart"></i> Estatísticas</a> </li>
  		<?php }?>


      </ul>
    </div>

    <div class="quick-actions_homepage">
      <ul class="quick-actions">
		  
		  <?php if (isset($_SESSION["idusuarios_tipos"]) && $_SESSION["idusuarios_tipos"] == 1) {?>
			  <li class="bg_ly span2"> <a href="sigda.php"> <i class="icon-github"></i> Sistema</a> </li>
		  <?php }?>


      </ul>
    </div>

<!--End-Action boxes-->    


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
