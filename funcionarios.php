<?php include "head.php"; ?>

<!--main-container-part--> 
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="#" class="current">Funcionarios</a>
	</div>
	<h1>Funcionarios - Menú de Opções</h1><hr>
  </div>
  
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
  		
  		<?php if (($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 4) || ($_SESSION["idusuarios_tipos"] == 5) || ($_SESSION["idusuarios_tipos"] == 8) || ($_SESSION["idusuarios_tipos"] == 9)) {?>
        <li class="bg_lr span2"> <a href="funcionarios.novo.php"> <i class="icon-plus"></i> Registrar </a> </li>
        <li class="bg_ly span2"> <a href="funcionarios.lista.php"> <i class="icon-list-alt"></i> Veja a lista </a> </li>
        <li class="bg_lb span2"> <a href="funcionarios.pontos.php"> <i class="icon-check"></i> Pontos </a> </li>
        <li class="bg_lg span3"> <a href="funcionarios.empresa.php"> <i class="icon-check"></i> Funcionario/Empresa </a> </li>
  		<?php } ?>
  		  		
      </ul>
    </div>
<!--End-Action boxes--> 


    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
