<?php include "lib/sessionLib.php";?> 
<!DOCTYPE html>
<?php 
$_SESSION["login"] = 0;
?>
<html lang="en">
   
	<head>
        <title>Grupo Das Americas</title>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/matrix-login.css" />
        <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="favicon.ico?v=1" />
		<script type="text/javascript">
		document.onkeydown = function(event) {
		var key_press = String.fromCharCode(event.keyCode);
		var key_code = event.keyCode;

		if(key_press == "k") {
		window.location.replace("ponto.php");
		}
		}
		</script>

	</head>
    <body>
        <div id="loginbox">            
            <form id="loginform" name="loginform" method="post" class="form-vertical" action="posts.php">
				<input type="hidden" id="accion" name="accion" value="login" />
				<input type="hidden" id="idlocales" name="idlocales" value="<?php echo $_GET["idlocales"];?>" />
				 <div class="control-group normal_text"> <h3><img src="img/grande.png" alt="Logo" /></h3></div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lg"><i class="icon-user"></i></span><input id="username" name="username" type="text" placeholder="Nome de Utilizador" />
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i></span><input id="password" name="password" type="password" placeholder="Senha" />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Esqueceu sua senha?</a></span>
                    <span class="pull-right"><button class="btn btn-success" type="submit">Login</button></span>
                </div>
            </form>
            <form id="recoverform" action="#" class="form-vertical">
				<p class="normal_text">Digite seu endereço de e-mail abaixo e nós lhe enviaremos instruções de como recuperar uma senha.</p>
				
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="Endereço de email" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Voltar para o Login</a></span>
                    <span class="pull-right"><a class="btn btn-info"/>Recuperar</a></span>
                </div>
            </form>
        </div>
        
        <script src="js/jquery.min.js"></script>  
        <script src="js/matrix.login.js"></script> 
    </body>

</html>
