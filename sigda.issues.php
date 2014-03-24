<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="sigda.php" title="Sistema" class="tip-bottom">Sistema</a>
		<a href="#" class="current">Questões</a>
	</div>
	<h1>Questões</h1><hr>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
		<li class="bg_lr span2"> <a href="sigda.issues.new.php"> <i class="icon-plus"></i> Nova </a> </li>
        <li class="bg_lb span2"> <a href="sigda.open-issues.php"> <i class="icon-folder-open"></i> Abertas </a> </li>
        <li class="bg_lg span2"> <a href="sigda.closed-issues.php"> <i class="icon-folder-close"></i> Fechadas </a> </li>
      </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
