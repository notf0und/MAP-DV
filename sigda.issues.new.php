<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="sigda.php" title="Sistema" class="tip-bottom"></i> Sistema</a>
		<a href="sigda.issues.php" title="Questões" class="tip-bottom">Questões</a>
		<a href="#" class="current">Nova</a>
	</div>

  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">

	  <div class="row-fluid">
		  <div class="widget-box">
			<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
			  <h5>Nova Questião</h5>
			</div>
			<div class="widget-content nopadding">
			  <form action="posts.php" method="post" class="form-horizontal">
				  <input type="hidden" id="accion" name="accion" value="newIssue" />
				<div class="control-group">
				  <label class="control-label">Título:</label>
				  <div class="controls">
					<input name='title' type="text" class="span11" placeholder="Título" />
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label">Messagem:</label>
				  <div class="controls">
					<textarea name='body' class="span11" placeholder="Deixe um comentario"></textarea>
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label">Etiqueta:</label>
				  <div class="controls">
					<select name="labels[]" multiple >
					  <option>erro</option>
					  <option>melhoria</option>
					  <option>duplicado</option>
					  <option>inválido</option>
					  <option>questão</option>
					  <option>não concertado</option>
					</select>
				  </div>
				</div>
				  <div align="right"><button class="btn btn-success" >Enviar</button></div>
				<hr>
			  </form>
			</div>
		  </div>

	  </div>
  </div>
</div>

<!--End-Action boxes-->    


<!--end-main-container-part-->
<?php include "footer.php"; ?>
