<?php include "head.php";
$idlocales = $_SESSION["idlocales"];
 ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb">
		<a href="index.php" title="ir para Início" class="tip-bottom"><i class="icon-home"></i> Início</a>
		<a href="#" class="current">Actualização</a>
	</div>
	<h1>Actualização</h1><hr>
  </div>
<!--End-breadcrumbs-->

<!-- Progress bar holder -->
<div id="progress"></div>

<!-- Progress information -->
<div id="information"></div>

<?php
$total = 19;

updatesToServer($idlocales);
actualizacionDeDatosManual($idlocales);

function updatesToServer($idlocales){
	$total = 19;
	
	// Obtengo la información sobre la base de datos local
	updateBar(1, "Obtenção de informação do banco de dados local.");
	$sql = " SELECT * FROM locales where idlocales =  ".$idlocales;
	$resultado = resultFromQuery($sql);
	
	if ($row = mysql_fetch_object($resultado))
	{
		$db_hostname_local = $row->db_hostname;
		$db_database_local = $row->db_database;
		$db_username_local = $row->db_username;
		$db_password_local = $row->db_password;
		
		$ssh2_local_user = $row->ssh2_user;
		$ssh2_local_password = $row->ssh2_password;
	}
	
	$setting = " SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO; ";
	$resultado = resultFromQuery($setting);	
				
				
	// Obtengo la información sobre la base de datos del servidor
	updateBar(2, "Obtenção de informação do banco de dados remoto.");
	$sql = " SELECT * FROM locales where idlocales = 0 ";
	$resultado = resultFromQuery($sql);	

	if ($row = mysql_fetch_object($resultado))
	{
		
		$db_hostname_server = $row->db_hostname;
		$db_database_server = $row->db_database;
		$db_username_server = $row->db_username;
		$db_password_server = $row->db_password;
	}
			
	/*
	 * 
	 * Seleccion las tablas que tienen W = True
	 * Por cada table, subo los que no tienen actualizado = 1
	 * Dejo registro de la actualizacion.
	 * 
	 * */
	if ($idlocales>0)
	{
		if (!function_exists("ssh2_connect")) die("La funcion ssh2_connect no existe");
		
		// Se loguea en localhost en el puerto 22
		updateBar(3, "Intentando conectarse por ssh2 localmente.");
		if(!($con = ssh2_connect($db_hostname_local, 22)))
		{
			updateBar($total, "Sem acceso ssh ao computador local");
			exit();
		} 
		else
		{
			// try to authenticate with username root, password secretpassword
			updateBar(4, "Iniciando sesión.");
			if(!ssh2_auth_password($con, $ssh2_local_user, $ssh2_local_password))
			{
				updateBar($total, "Erro de autenticação ssh local");
				exit();
			}
			else 
			{
				// allright, we're in!				
				if ($idlocales == 2)
				{
					ssh2_exec($con, "echo Inicio actualizacion de vouchers > /dev/lp0");
					ssh2_exec($con, "echo ................................ > /dev/lp0");
					ssh2_exec($con, "echo  > /dev/lp0");
					ssh2_exec($con, "echo  > /dev/lp0");
				}
			
				// Selecciono de la tabla MP con la columna actualizado = 0
				updateBar(5, "Procurando vouchers sem atualizar.");
				$sql = " select * from ".$db_database_local.".mediapension MP ";
				$sql .= " inner join ".$db_database_local.".huespedes H on MP.idhuespedes = H.idhuespedes ";
				$sql .= " where MP.actualizado = 0; ";
				
				$resultadoMP = resultFromQuery($sql);
				if ($idlocales == 2)
				{
					ssh2_exec($con, "echo Lista de vouchers nuevos > /dev/lp0");
					ssh2_exec($con, "echo ........................ > /dev/lp0");
				}
				
				while ($rowMP = mysql_fetch_object($resultadoMP))
				{
					if ($idlocales == 2)
					{
						ssh2_exec($con, "echo  > /dev/lp0");
					}
					$idmediapension = $rowMP->idmediapension;
					$idmediapension_local = $rowMP->idmediapension;
					$data = $rowMP->data;
					$dataTime = date("H:i", $data); 
					$numeroexterno = $rowMP->numeroexterno;
					$idposadas = $rowMP->idposadas;
					$idoperadoresturisticos = $rowMP->idoperadoresturisticos;
					$idagencias = $rowMP->idagencias;
					$idresponsablesDePago = $rowMP->idresponsablesDePago;
					$idhuespedes = $rowMP->idhuespedes;
					$qtdedepax = $rowMP->qtdedepax;
					$dataIN = $rowMP->dataIN;
					$dataOUT = $rowMP->dataOUT;
					$qtdedecomidas = $rowMP->qtdedecomidas;
					$idservicios = $rowMP->idservicios;
					$mensajeinterno = $rowMP->mensajeinterno;
					$mensajegarcon = $rowMP->mensajegarcon;
					$idlocales = $rowMP->idlocales;
					$idliquidaciones = $rowMP->idliquidaciones;
					$actualizado = $rowMP->actualizado;
					$nomedopax = $rowMP->titular;
					$idpaises = $rowMP->idpaises;
					
					$dbConnection_server = mysql_dbConnect($db_hostname_server, $db_database_server, $db_username_server, $db_password_server);
					$select_db = mysql_select_db($db_database_server, $dbConnection_server);

				
					if ($idmediapension > -1)
					{
						//INSERT HUESPED
						updateBar(6, "Insertando hospedes novos no servidor.");
						$sql = "insert ".$db_database_server.".huespedes (titular, idpaises) values (";
						$sql .= "'".$nomedopax."',";
						$sql .= "'".$idpaises."') ";
						$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
						$idhuespedes = mysql_insert_id();

						bitacoras($_SESSION["idusuarios"], 'Insertar Huesped: ID '.$idhuespedes);

						//INSERT MEDIAPENSION
						updateBar(7, "Insertando meia pensões novas no servidor.");
						$sql = "insert ".$db_database_server.".mediapension (numeroexterno, idoperadoresturisticos, idposadas, idagencias, idresponsablesDePago, idhuespedes, qtdedepax, dataIN, dataOUT, qtdedecomidas, idservicios, idlocales, mensajeinterno, mensajegarcon, actualizado) values (";
						$sql .= "'".$numeroexterno."',";
						$sql .= "".$idoperadoresturisticos.",";
						$sql .= "".$idposadas.",";
						$sql .= "".$idagencias.",";
						$sql .= "".$idresponsablesDePago.",";
						$sql .= "".$idhuespedes.",";
						$sql .= "".$qtdedepax.",";
						$sql .= "'".$dataIN."',";
						$sql .= "'".$dataOUT."',";
						$sql .= "".$qtdedecomidas.",";
						$sql .= "".$idservicios.",";
						$sql .= "".$idlocales.",";
						$sql .= "'".$mensajeinterno."',";
						$sql .= "'".$mensajegarcon."',";
						$sql .= "'1') ";
						$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);
						
						//Obtengo el id de la ultima media pension insertada en el servidor
						$idmediapension = mysql_insert_id();
						bitacoras($_SESSION["idusuarios"], 'Insertar MP: ID '.$idmediapension);
						
						if ($idlocales == 2)
						{
							ssh2_exec($con, "echo ".$nomedopax." X".$qtdedepax." -".$dataTime."- > /dev/lp0");
						}

						//INSERT MEDIAPENSION ADMICION
						// consulto todas las admisiones locales y luego realizo un insert por cada admision referenciada con $idmediapension_local
						updateBar(8, "Insertando admisiones ingresadas com vouchers novos no servidor.");
						
						$sql = " select * from ".$db_database_local.".mediapension_admisiones where idmediapension = ".$idmediapension_local;
						$resultado = resultFromQuery($sql);	

						while ($row_admision = mysql_fetch_object($resultado)) 
						{
							$idamision_local = $row_admision->id;
							$datadiaria = date("Y-m-d");
							//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
							$precio = 0;
							$sql = "insert ".$db_database_server.".mediapension_admisiones (data, idmediapension, qtdedepax, tarifa, actualizado) values (";
							$sql .= "'".$row_admision->data."',";
							$sql .= "".$idmediapension.",";
							$sql .= "".$row_admision->qtdedepax.",";
							$sql .= "".$precio.", 1) ";
							$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
							$idadmision = mysql_insert_id();

							//INSERT MEDIAPENSION TICKTS 
							updateBar(9, "Insertando tickets novos no servidor.");
							$sql = " select * from ".$db_database_local.".mediapension_tickets where idmediapension_admisiones = ".$idamision_local;
							$resultado = resultFromQuery($sql);	

							while ($row_tickets = mysql_fetch_object($resultado)) 
							{
								$sql = "insert ".$db_database_server.".mediapension_tickets (idtickets, idmediapension_admisiones, idlocales, fecha, actualizado) values (";
								$sql .= "'".$row_tickets->idtickets."',";
								$sql .= "".$idadmision.",";
								$sql .= "".$row_tickets->idlocales.",";
								$sql .= "'".$row_tickets->fecha."',";
								$sql .= "1) ";
								$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
								$idtickets = mysql_insert_id();
							}
							updateBar(10, "Estableciendo admisiones locales como atualizadas.");
							$sql = " update ".$db_database_local.".mediapension_admisiones set actualizado = 1 where idmediapension = ".$idmediapension_local;
							$resultado = resultFromQuery($sql);									
						}
					}
				
				updateBar(11, "Estableciendo vouchers locales como atualizados.");
				$sql = " update ".$db_database_local.".mediapension set actualizado = 1 where idmediapension = ".$idmediapension_local;
				$resultado = resultFromQuery($sql);	
				}


				//INSERT ADMISIONES SOLAS
				updateBar(12, "Procurando admisiones sem voucher.");
				if ($idlocales == 2)
				{
					ssh2_exec($con, "echo  > /dev/lp0");
					ssh2_exec($con, "echo Lista de admisiones: > /dev/lp0");
					ssh2_exec($con, "echo ................... > /dev/lp0");
				}
				
				$sql = " select MPA.data, MPA.idmediapension, MPA.qtdedepax, H.titular ";
				$sql .= " from ".$db_database_local.".mediapension_admisiones MPA ";
				$sql .= " left join ".$db_database_local.".mediapension MP on MPA.idmediapension = MP.idmediapension ";
				$sql .= " left join ".$db_database_local.".huespedes H on MP.idhuespedes = H.idhuespedes ";
				$sql .= " where MPA.actualizado = 0";
				
				$resultado = resultFromQuery($sql);	
				
				updateBar(13, "Insertando admisiones sem voucher no servidor.");
				while ($row_admision = mysql_fetch_object($resultado)) 
				{				
					$dbConnection_server = mysql_dbConnect($db_hostname_server, $db_database_server, $db_username_server, $db_password_server);
					$select_db = mysql_select_db($db_database_server, $dbConnection_server);
					
					$datadiaria = date("Y-m-d");
					//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
					$precio = 0;
					$nomedopax = $row_admision->titular;
					$qtdedepax = $row_admision->qtdedepax;
					$data = $row_admision->data;
					$dataTime = date("H:i", $data); 
					
					if ($idlocales == 2)
					{
						ssh2_exec($con, "echo ".$nomedopax." X".$qtdedepax." -".$dataTime."- > /dev/lp0");
					}
					
					$sql = "insert ".$db_database_server.".mediapension_admisiones (data, idmediapension, qtdedepax, tarifa, actualizado) values (";
					$sql .= "'".$row_admision->data."',";
					$sql .= "".$row_admision->idmediapension.",";
					$sql .= "".$row_admision->qtdedepax.",";
					$sql .= "".$precio.", 1) ";
					$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
					$idadmision = mysql_insert_id();
					
					
				}
				
				updateBar(14, "Estableciendo admisiones locales sem voucher como atualizadas.");
				$sql = " update ".$db_database_local.".mediapension_admisiones set actualizado = 1 where actualizado = 0";
				$resultado = resultFromQuery($sql);

				
				if ($idlocales == 2)
				{
					ssh2_exec($con, "echo  > /dev/lp0");
					ssh2_exec($con, "echo  > /dev/lp0");
				}
			}
		}
	}
}

function actualizacionDeDatosManual($idlocales){
	
	$total = 19;
	
	//Directorio y archivo donde se va a guardar en el localhost como en el server
	$updownpathfile = '/tmp/backup.sql.gz';
	
	// Obtengo la información sobre la base de datos del servidor
	updateBar(15, 'Leitura de configuraçao do servidor');
	$sql = " SELECT * FROM locales where idlocales =  0";
	$resultado = resultFromQuery($sql);
				
	if ($row = mysql_fetch_object($resultado))
	{
		$remote_hostname = $row->db_hostname;
		
		$ssh2_remote_user = $row->ssh2_user;
		$ssh2_remote_password = $row->ssh2_password;
		
		$db_remote_user = $row->db_username;
		$db_remote_password = $row->db_password;
		$db_remote_database = $row->db_database;
	}
	
	// Obtengo la información sobre la base de datos local
	updateBar(16, 'Leitura de configuraçao do localhost');
	$sql = " SELECT * FROM locales where idlocales = ".$idlocales;
	$resultado = resultFromQuery($sql);
				
	if ($row = mysql_fetch_object($resultado))
	{
		$local_hostname = $row->db_hostname;
		
		$ssh2_local_user = $row->ssh2_user;
		$ssh2_local_password = $row->ssh2_password;
						
		$db_local_user = $row->db_username;
		$db_local_password = $row->db_password;
		$db_local_database = $row->db_database;
	}
	
	if (!function_exists("ssh2_connect")) die("La función ssh2_connect no esta disponible");
	
	// log in at $remote_hostname on port 2206
	if(!($con_remote = ssh2_connect($remote_hostname, 2206)))
	{
		updateBar($total, "Sem acceso ssh ao servidor");
		exit();
	}
	else
	{
		// try to authenticate with username root, password secretpassword
		if(!ssh2_auth_password($con_remote, $ssh2_remote_user, $ssh2_remote_password)) 
		{
			updateBar($total, "Falha ao autenticar no servidor por ssh.");
			exit();
		} 
		else 
		{
			//Genera el backup en el servidor
			updateBar(17, "Creando backup do banco de dados no servidor.");
			$sqldump = "mysqldump --opt  -u ".$db_remote_user." -p".$db_remote_password." ".$db_remote_database." | gzip  > ".$updownpathfile;
		
			if (!(ssh2_exec($con_remote, $sqldump))) 
			{
				updateBar($total, "Error al intentar crear backup no servidor.");
				exit();
			}
			else
			{
				//ssh2_scp_recv(Connection, Remote path, Local Path)
				updateBar(18, "Baixando banco de dados do servidor.");
				if (!ssh2_scp_recv($con_remote, $updownpathfile, $updownpathfile))
				{
					updateBar($total, "Não foi possível fazer o download do banco de dados do servidor.");
					exit();
				}
				else
				{
					if(!($con_local = ssh2_connect($local_hostname, 22)))
					{
						updateBar($total, "Não foi possível conectar por ssh localmente.");
						exit();
					} 
					else
					{
						// try to authenticate with username root, password secretpassword
						if(!ssh2_auth_password($con_local, $ssh2_local_user, $ssh2_local_password))
						{
							updateBar($total, "Falha ao autenticar localmente por ssh.");
							exit();
						} 
						else
						{

							updateBar(19, "Aplicando actualizações.");
							$sqlrestore = "gzip -dc < ".$updownpathfile." | mysql -u ".$db_local_user." -p".$db_local_password." ".$db_local_database."";
							
							$restore = ssh2_exec($con_local, $sqlrestore);
							var_dump($restore);
						}
					}
				}
			}
		}
	}
}

function updateBar($i, $message){
	$total = 19;
    // Calculate the percentation
    $percent = intval($i/$total * 100)."%";

    // Javascript for updating the progress bar and information
    echo '<script language="javascript">
    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
    document.getElementById("information").innerHTML="'.$message.'";
    </script>';


// This is for the buffer achieve the minimum size in order to flush data
    echo str_repeat(' ',1024*64);


// Send output to browser immediately
    flush();
}

// Tell user that the process is completed
updateBar($total, 'Proceso completado exitosamente');
?>
 


    <hr/>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
