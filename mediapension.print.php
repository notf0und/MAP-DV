<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];
?>
<html lang="en">
<head>
<title>Matrix Admin</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/colorpicker.css" />
<link rel="stylesheet" href="css/datepicker.css" />
<link rel="stylesheet" href="css/uniform.css" />
<link rel="stylesheet" href="css/select2.css" />
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
<!--main-container-part-->
<div id="content">
  <div class="container-fluid">
	<div class="row-fluid">
		<div class="span6">
			<div class="widget-box">
			<?php
				$sqlQuery = "SELECT MPA.data, MPA.qtdedepax, MP.numeroexterno, H.Titular, P.Nombre 'Posada', O.Nombre 'Operador', A.Nombre 'Agencia', R.Nombre 'Responsable', S.Nombre 'Servicio', MP.mensajegarcon FROM mediapension_admisiones MPA ";
				$sqlQuery .= " LEFT JOIN mediapension MP ON MPA.idmediapension = MP.idmediapension ";
				$sqlQuery .= " LEFT JOIN huespedes H ON MP.idhuespedes = H.idhuespedes ";
				$sqlQuery .= " LEFT JOIN servicios S ON MP.idservicios = S.idservicios ";
				$sqlQuery .= " LEFT JOIN posadas P ON MP.idposadas = P.idposadas ";
				$sqlQuery .= " LEFT JOIN agencias A ON MP.idagencias = A.idagencias ";
				$sqlQuery .= " LEFT JOIN operadoresturisticos O ON MP.idoperadoresturisticos = O.idoperadoresturisticos ";
				$sqlQuery .= " LEFT JOIN responsablesDePago R ON MP.idresponsablesDePago = R.idresponsablesDePago ";
				$sqlQuery .= " WHERE MPA.id = ".$_GET["id"];
				$result = resultFromQuery($sqlQuery);
				if ($row = siguienteResult($result)) {
					$sqlticketid = " SELECT `idtickets` + 1 `idtickets` FROM `mediapension_tickets` WHERE `idlocales` = ".$_SESSION["idlocales"]." ORDER BY `idtickets` DESC LIMIT 1 ";
					$resultticketid = resultFromQuery($sqlticketid);
					$rowticketid = siguienteResult($resultticketid);
					$sql = "insert mediapension_tickets (idtickets,  idmediapension_admisiones, idlocales) values (";
					$sql .= "'".$rowticketid->idtickets."','".$_GET["id"]."','".$_SESSION["idlocales"]."') ";
					$resultadoStringSQL = resultFromQuery($sql);		
			?>
				<h1>RISTORANTE DA VINCI</h1>
				Data: <?php echo $rowticketid->idtickets;?></br>
				idtickets: <?php echo $row->data;?></br>
				Nome do Pax: <?php echo $row->Titular;?> X <?php echo $row->qtdedepax;?></br>
				Pousada: <?php echo $row->Posada;?></br>
				Operador: <?php echo $row->Operador;?></br>
				# de Voucher: <?php echo $row->numeroexterno;?></br></br>
				Mensaje garçon: <?php echo $row->mensajegarcon;?></br></br></br>

<?php
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
// log in at server1.example.com on port 22
if(!($con = ssh2_connect("localhost", 22))){
    echo "fail: unable to establish connection\n";
} else {
    // try to authenticate with username root, password secretpassword
    
    $localcfg = parse_ini_file("./local-config.ini", true);
	$localcfg = $localcfg['config'];   
    
    if(!ssh2_auth_password($con, $localcfg['terminal_user'], $localcfg['terminal_password'])) {
        echo "fail: unable to authenticate\n";
    } else {
        // allright, we're in!
        // echo "okay: logged in...\n";
        // execute a command
        if (!($stream = ssh2_exec($con, "echo ".$localcfg['terminal_titulo'].". > /dev/lp0" ))) {
            echo "fail: unable to execute command\n";
        } else {
			ssh2_exec($con, "echo ................................ > /dev/lp0");
			ssh2_exec($con, "echo ................................ > /dev/lp0");
			ssh2_exec($con, "echo Data: $row->data > /dev/lp0");
			ssh2_exec($con, "echo Ticket : $rowticketid->idtickets > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo Nome do Pax: $row->Titular > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo Qtde Pax: $row->qtdedepax > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo Pousada: $row->Posada > /dev/lp0");
			ssh2_exec($con, "echo Operador: $row->Operador > /dev/lp0");
			ssh2_exec($con, "echo Agencia: $row->Agencia > /dev/lp0");
			ssh2_exec($con, "echo Responsable: $row->Responsable > /dev/lp0");
			ssh2_exec($con, "echo Num. de Voucher: $row->numeroexterno > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo Servicio: $row->Servicio > /dev/lp0");
			ssh2_exec($con, "echo Mensaje gar".chr(231)."on: $row->mensajegarcon > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo Mesa: > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo Sequença: > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo Gar".chr(231)."on: > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo Firma: > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
			ssh2_exec($con, "echo  > /dev/lp0");
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
        }
    }
}
?>





			<?php
				}
			?>

			
				<button onclick="window.location.href='mediapension.lista.php'" class="btn btn-success">Cerrar</button>	
			</div>
		</div>
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
</body>
</HTML>
