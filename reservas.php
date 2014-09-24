<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="#" class="current">Reservas</a>
	</div>
	<h1>Menu de opções</h1><hr>
  </div>
<!--End-breadcrumbs-->

  <div class="container-fluid">
	<!--Action boxes-->
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
		  
	  
		  <?php 
			if (isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 10)) {?>
				<li class="bg_lg span2"> <a href="reservas.mapa.php"> <i class="icon-list-ul"></i> Exibir o mapa</a> </li>
		  <?php }
			elseif(isset($_SESSION["idusuarios_tipos"]) && ($_SESSION["idusuarios_tipos"] == 11)){
				$sql = 'SELECT P.idposadas ';
				$sql .= 'FROM usuarios U ';
				
				$sql .= 'LEFT JOIN employee E ';
				$sql .= 'ON U.employee_id = E.employee_id ';
				
				$sql .= 'LEFT JOIN empresa B ';
				$sql .= 'ON E.idempresa = B.idempresa ';
				
				$sql .= 'LEFT JOIN posadas P ';
				$sql .= 'ON P.nombre LIKE B.nombre ';
				
				$sql .= 'WHERE idusuarios = ' . $_SESSION["idusuarios"];
				$result = resultFromQuery($sql);
				$row = siguienteResult($result);
				$idposadas = $row->idposadas;
			?>
				<li class="bg_lg span2"> <a href="reservas.mapa.php?pousada=<?=$idposadas?>"> <i class="icon-list-ul"></i> Exibir o mapa</a> </li>

		  <?php } ?>
		  

      </ul>
    </div>
	<!--End-Action boxes-->    

	

    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
