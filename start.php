<?php include "lib/sessionLib.php";
?> 
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
		<?php
		
		//Muestra opciones segun configuración de la terminal
		$configfile = parse_ini_file("./local-config.ini", true);
		$configfile = $configfile['config'];
		
		if (!$configfile['terminal_mode']){
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php?idlocales=0\">";
		}
		elseif ($configfile['terminal_mode']){
			if($configfile['terminal_name'] == "Centro") {
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php?idlocales=1\">";
			} 
			elseif ($configfile['terminal_name'] == "João Fernandez"){
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php?idlocales=2\">";
			}				
		}
		 ?>
		        
        <script src="js/jquery.min.js"></script>  
        <script src="js/matrix.login.js"></script> 
    </body>

</html>
