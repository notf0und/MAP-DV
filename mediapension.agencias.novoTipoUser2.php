<?php 
include "head.php"; 
$idmediapension = -1;
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="mediapension.php" title="Media pension" class="tip-bottom">Media pension</a>
		<a href="#" title="Administradores" class="tip-bottom">Administradores</a>
		<a href="#" class="current">Agencias</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Nova Agencia</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="posts.php" id="form" name="form" class="form-horizontal" method="post">
						<input type="hidden" id="accion" name="accion" value="nuevaAgenciaTipoUser2" />
						<input type="hidden" id="idagencias" name="idagencias" value="-1" />
							<div class="control-group">
								<label class="control-label">Nome do agencia</label>
								<div class="controls">
									<input id="nomedoagencia" name="nomedoagencia" type="text" class="span11" placeholder="Nome do agencia" required="true" />
								</div>
							</div>
						<div class="form-actions">
							<input id="next" class="btn btn-primary" type="submit" value="Agregar" />
							<div id="status"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>
