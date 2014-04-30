<?php

include "head.php"; 

$brisas = getPontos(1);
$colinas = getPontos(2);
$dasamericas = getPontos(3);
$dvcentro = getPontos(4);
$dvjoao = getPontos(5);
$paradise = getPontos(6);

function getPontos($idempresa){

	//SELECT
	$sql = "SELECT point_id, ";
	$sql .= "temp.employee_id, ";
	$sql .= "CONCAT(P.firstname, ' ', P.lastname) Funcionario, ";
	$sql .= "date_time Hora, ";
	$sql .= "in_out Status ";
	$sql .= " FROM ";
	$sql .= "(SELECT  point_id, employee_id, date_time, in_out ";
	$sql .= "FROM point ";
	$sql .= "WHERE date(date_time) = curdate() ";
	$sql .= "AND date_time < now() + interval 1 minute ";
	$sql .= "ORDER BY TIME(date_time) DESC) as temp ";
	$sql .= "LEFT JOIN employee E ON temp.employee_id = E.employee_id ";
	$sql .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
	$sql .= "WHERE idempresa = ".$idempresa." ";
	$sql .= "GROUP BY employee_id ";
	$sql .= "ORDER BY date_time DESC;";

	$result = resultFromQuery($sql);
	$string = '';
	while ($row = siguienteResult($result)){
		
		$employee_id = $row->employee_id;
		$funcionario = $row->Funcionario;
		$hora = $row->Hora;
		$status = $row->Status;
		$date = new DateTime($hora);
		
		if ($status == 1){
			$string .= "<div class='alert alert-success'> <i class='icon-chevron-right'></i> ";
		}else{
			$string .= "<div class='alert alert-info'> <i class='icon-chevron-left'></i> ";
		}
		
		$string .= "<strong>".$date->format('H:i')."</strong> ";
		$string .= $funcionario." <a href='funcionarios.messagem.php?employee_id=".$employee_id."' class='tip-top' data-original-title='Enviar messagem'><i class='icon-envelope'></i></a></div>";
	}
	$string .= "<h3>Tarde ou ausente</h3><hr>";


	//SELECT
	$sql = "SELECT employee_id, CONCAT(P.firstname, ' ', P.lastname) Funcionario, fromhour, TIME(NOW()) hora FROM employee E LEFT JOIN profile P ON E.profile_id = P.profile_id WHERE E.decline IS NULL AND E.idempresa = ".$idempresa;
	$result = resultFromQuery($sql);

	$tarde ='';
	$ausente ='';
	$disconnect = '';

	while ($row = siguienteResult($result)){
		
		$employee_id = $row->employee_id;
		$funcionario = $row->Funcionario;
		$fromhour = $row->fromhour;
		$hora = $row->hora;
		
		$sql = "SELECT TIME(date_time) ingreso from point where date(date_time) = curdate() AND in_out = 1 AND employee_id = ".$employee_id." LIMIT 1";
		$rentrada = resultFromQuery($sql);
		
		$sql = "select dayname(valid_from) clearance, DAYNAME(CURDATE()) today from clearance where employee_id = ".$employee_id." AND permanent = 1 AND valid_from <= CURDATE() ORDER BY valid_from DESC LIMIT 1;";
		$folga = resultFromQuery($sql);
		
		$defolga = siguienteResult($folga);
		
		if ($fila = siguienteResult($rentrada)){
			$ingreso = $fila->ingreso;
			
			if($ingreso > $fromhour){
				
				$fromhour = new DateTime($fromhour);
				$ingreso = new DateTime($ingreso);
				$demora = $ingreso->diff($fromhour, true);
				
				if($demora->h || $demora->i && ($demora->h > 0 || $demora->i > 15)){
					$sReturn = '';

					if($demora->h){
						$sReturn .= $demora->h . ':';
					}
					else{
						$sReturn .= '0:';
					}
					
					if($demora->i){
						if ($demora->i < 10){
							$sReturn .= '0'.$demora->i;
						}else{
							$sReturn .= $demora->i;
						}
					}
					else{
						$sReturn .= $demora->i;
					}
					
					$tarde .= "<div class='alert alert-warning'> <i class='icon-time'></i> ";
					$tarde .= "<strong>".$sReturn."</strong> ";
					$tarde .= $funcionario." <a href='funcionarios.messagem.php?employee_id=".$employee_id."' class='tip-top' data-original-title='Enviar messagem'><i class='icon-envelope'></i></a></div>";

				}
			}				
		}
		elseif(isset($defolga->clearance) && $defolga->clearance == $defolga->today){
			
			$disconnect .= "<div class='alert alert-grey'> <i class='icon-unlock'></i> ";
			$disconnect .= "<strong>Folga</strong> ";
			$disconnect .= $funcionario." <a href='funcionarios.messagem.php?employee_id=".$employee_id."' class='tip-top' data-original-title='Enviar messagem'><i class='icon-envelope'></i></a></div>";
		
		}
		
		elseif($fromhour < $hora){
			
			$ausente .= "<div class='alert alert-error'> <i class='icon-ban-circle'></i> ";
			$ausente .= "<strong>Ausente: </strong> ";
			$ausente .= $funcionario." <a href='funcionarios.messagem.php?employee_id=".$employee_id."' class='tip-top' data-original-title='Enviar messagem'><i class='icon-envelope'></i></a></div>";
		}
		elseif ($fromhour > $hora){
			$disconnect .= "<div class='alert alert-grey'> <i class='icon-unlock'></i> ";
			$disconnect .= "<strong>".date('H:i',strtotime($fromhour))." </strong> ";
			$disconnect .= $funcionario." <a href='funcionarios.messagem.php?employee_id=".$employee_id."' class='tip-top' data-original-title='Enviar messagem'><i class='icon-envelope'></i></a></div>";
		}

	}

	$string .= $tarde;
	$string .= $ausente;
	$string .= "<h3>Fora do horario de trabalho</h3><hr>";
	$string .= $disconnect;
	return $string;
}


?>	

<!--main-container-part-->



<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="#" class="current">Pontos</a>
	</div>
  </div>
  
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		
		<div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#tab1">Brisas de Buzios</a></li>
              <li><a data-toggle="tab" href="#tab2">Buzios Colinas</a></li>
              <li><a data-toggle="tab" href="#tab3">Das Americas</a></li>
              <li><a data-toggle="tab" href="#tab4">New Paradise</a></li>
              <li><a data-toggle="tab" href="#tab5">Da Vinci Centro</a></li>
              <li><a data-toggle="tab" href="#tab6">Da Vinci João Fernandez</a></li>
              <li><a data-toggle="tab" href="#tab7">Geral</a></li>
            </ul>
          </div>
          <div class="widget-content tab-content">
            <div id="tab1" class="tab-pane active">
				<?php echo $brisas;?>
            </div>
            <div id="tab2" class="tab-pane">
				<?php echo $colinas;?>
			</div>
            <div id="tab3" class="tab-pane">
				<?php echo $dasamericas;?>
            </div>
            <div id="tab4" class="tab-pane">
				<?php echo $paradise;?>
            </div>
            <div id="tab5" class="tab-pane">
				<?php echo $dvcentro;?>
            </div>
            <div id="tab6" class="tab-pane">
				<?php echo $dvjoao;?>
            </div>
            <div id="tab7" class="tab-pane">
            </div>
        </div>
		
 	
	</div>
    </div>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
