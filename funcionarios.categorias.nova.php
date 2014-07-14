<?php 
include "head.php"; 

if (isset($_POST['jobcategory_id'])){
	
	if (isset($_SESSION['referer'])){
		$referer = $_SESSION['referer'];
	}
	else{
		$referer = $_SERVER['HTTP_REFERER'];
	}
	
	$jobcategory_id = $_POST['jobcategory_id'];
	
	if (isset($_POST['basesalary_id'])){
		
		$basesalary_id = $_POST['basesalary_id'];
		
		$sql = 'SELECT basesalary, valid_from FROM basesalary WHERE basesalary_id = '.$basesalary_id;
	
		$result = resultFromQuery($sql);
				
		$row = siguienteResult($result);
		
		$basesalary =  $row->basesalary;
		
		$valid_from =  $row->valid_from;
		
	}
	
	
	$current = 'Editar';
	
	$sql = 'SELECT name FROM jobcategory WHERE jobcategory_id = '.$jobcategory_id;
	
	$result = resultFromQuery($sql);
			
	$row = siguienteResult($result);
	
	$name =  $row->name;
	
}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contábil</a>
		<a href="funcionarios.php" title="Pagamentos" class="tip-bottom">Funcionarios</a>
		<a href="funcionarios.categorias.lista.php" title="Pagamentos" class="tip-bottom">Categorías</a>
		<a href="funcionarios.categorias.edit.php?jobcategory_id=<?php echo isset($jobcategory_id) ? $jobcategory_id : ''?>" title="<?php echo isset($name) ? $name : ''?>" class="tip-bottom"><?php echo isset($name) ? $name : ''?></a>
		<a href="#" class="current"><?php echo isset($current) ? $current : ''?></a>
		
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Edit Voucher</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						
						<input type="hidden" id="accion" name="accion" value="admitirMediapension" />
						<input type="hidden" id="idmediapension" name="idmediapension" value="<?php echo $idmediapension;?>" />
						<input type="hidden" id="referer" name="referer" value="<?php echo $referer;?>" />
						
						<div class="control-group">
							<label class="control-label">Categoría</label>
							
							<div class="controls">
								<input id="name" name="name" type="text" class="span11" placeholder="Categoría" disabled value="<?php echo isset($name) ? $name : '';?>"/>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Salario Base</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on">R$</span>
								<input id="basesalary" name="basesalary" type="text" class="span11" placeholder="Salario Base" value="<?php echo isset($basesalary) ? $basesalary : '';?>"/>
								</div>
							</div>
						</div>
						
						
						<div class="control-group">
							<label class="control-label">Válido desde</label>
							<div class="controls">
								<div data-date="" class="input-append date datepicker">
									<input id="valid_from" name="valid_from" type="text" class="span11" required="true" value="<?php echo isset($valid_from) ? $valid_from : '';?>" />
									<span class="add-on"><i class="icon-th"></i></span> 
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<br><button class="btn btn-success" type="submit">Modificar</button>
							<br><br>
						</div>
						<div id="status"></div>
					</form>

				</div>

			</div>
			<!--
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="borrarMediapension" />
						<input type="hidden" id="idmediapension" name="idmediapension" value="<?php echo $idmediapension;?>" />
						<button class="btn btn-success" type="submit">Borrar Voucher</button>
					</form>
			-->
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
