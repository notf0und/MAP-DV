<?php include "head.php"; ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb">
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
		<a href="user.php" title="Usuario" 
		class="tip-bottom">Usuario</a>
		<a href="#" class="current">Alterar senha</a>
	</div>
	<h1>Usuario - Alterar senha</h1>
  </div>
  

<!--End-breadcrumbs-->
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
	<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
	<h5>Usuario - Alterar senha</h5>
      </div>
      <div class="widget-content nopadding">
	
	<form action="posts.php" id="user-changepassword" name="user-changepassword" class="form-horizontal" method="post">
	  <input type="hidden" id="accion" name="accion" 
	  value="userChangePassword" />
	  <div id="form-wizard-1" class="step">
	    <div class="control-group">
	      <label class="control-label">Antiga senha</label>
	      <div class="controls">
		<input id="oldpassword" name="oldpassword" type="password" class="span11" 
		placeholder="Ingrese sua antiga senha" required="true" />
	      </div>
	    </div>
	    
	    <div class="control-group">
	      <label class="control-label">Nova senha</label>
	      <div class="controls">
		<input id="password" name="password" type="password" class="span11" 
		placeholder="Ingrese sua nova senha" required="true" />
	      </div>
	    </div>
	    
	    <div class="control-group">
	      <label class="control-label">Confirme a nova senha</label>
	      <div class="controls">
		<input id="password2" name="password2" type="password" class="span11" 
		placeholder="Repita sua nova senha"  required="true" />
	      </div>
	    </div>
	    
	    <div class="form-actions">
                  <input type="submit" value="Validate" class="btn btn-success">
	    </div>
	  </div>
	</form>
	
      </div>
    </div>
  </div>
</div>
<hr/>
</div>
</div>


</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
