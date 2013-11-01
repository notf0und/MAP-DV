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

	</head>
    <body>
		<h1>Local</h1>
<a href="login.php?idlocales=0"> <i class="icon-plus"></i> Escritorio </a> <br><br>
<a href="login.php?idlocales=1"> <i class="icon-plus"></i> Centro </a> <br><br>
<a href="login.php?idlocales=2"> <i class="icon-plus"></i> Joao Fernandes </a> 
        
        <script src="js/jquery.min.js"></script>  
        <script src="js/matrix.login.js"></script> 
    </body>

</html>
