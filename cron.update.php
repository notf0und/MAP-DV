<?php

include_once('/home/centro/Documents/dasamericas/www/TestFolder/checkinternet.php'); // Incluye la clase CheckInternet()


$iStatus = new CheckInternet(); // Instancia un nuevo objeto de la clase CheckInternet()

$time = date("G:i:s");

if ($iStatus->status()){
	
	//Load configuration file
	$cfgfile = parse_ini_file("/home/centro/Documents/dasamericas/www/local-config.ini", true);
	$ldb = $cfgfile['local_database'];
	$rdb = $cfgfile['remote_database'];

	// Create Connection to local database
	$dbLocal = new mysqli($ldb['dbhost'], $ldb['user'], $ldb['password'], $ldb['dbname']);

	if ($dbLocal->connect_error) {
		die('Error al conectar con la base de datos local (' . $dbLocal->connect_errno . ') ' . $dbLocal->connect_error);
	}

	// Create Connection to remote database
	$dbRemote = new mysqli($rdb['dbhost'], $rdb['user'], $rdb['password'], $rdb['dbname']);

	if ($dbRemote->connect_error) {
		die('Error al conectar con la base de datos remota (' . $dbRemote->connect_errno . ') ' . $dbRemote->connect_error);
	}
	
	$sql = "SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO;";
	$resultado = $dbLocal->query($sql);
	
	//Set $idlocales (0-Cualquiera, 1-DA_CN, 2-DA_JF)
	if ($ldb['dbname'] == 'DA_CN'){
		$idlocales = 1;
	}
	elseif ($ldb['dbname'] == 'DA_JF'){
		$idlocales = 2;
	}
	else{
		$idlocales = 0;
	}
	
	
	//Get Local SSH config
	$sql = " SELECT * FROM locales where idlocales = ".$idlocales;
	$resultado = $dbLocal->query($sql);

	if ($row = $resultado->fetch_assoc())
	{
		$ssh2_local_user = $row['ssh2_user'];
		$ssh2_local_password = $row['ssh2_password'];
		$ssh2_local_port = 22;
	}

	
	//Get Remote SSH config
	$sql = " SELECT * FROM locales where idlocales = 0 ";
	$resultado = $dbLocal->query($sql);

	if ($row = $resultado->fetch_assoc())
	{
		
		$ssh2_remote_user = $row['ssh2_user'];
		$ssh2_remote_password = $row['ssh2_password'];
		$ssh2_remote_port = 2206;
	}
	
	// Selecciono de la tabla MP con la columna actualizado = 0
	echo "Procurando vouchers sem atualizar - ". __LINE__ ."\n";
	
	$sql = " select * from mediapension MP ";
	$sql .= " inner join huespedes H on MP.idhuespedes = H.idhuespedes ";
	$sql .= " where MP.actualizado = 0; ";

	$resultadoMP = $dbLocal->query($sql);
				
	while ($rowMP = $resultadoMP->fetch_assoc())
	{
		$idmediapension = $rowMP['idmediapension'];
		$idmediapension_local = $rowMP['idmediapension'];
		$data = $rowMP['data'];
		$dataTime = date("H:i", strtotime($data)); 
		$numeroexterno = $rowMP['numeroexterno'];
		$idposadas = $rowMP['idposadas'];
		$idoperadoresturisticos = $rowMP['idoperadoresturisticos'];
		$idagencias = $rowMP['idagencias'];
		$idresponsablesDePago = $rowMP['idresponsablesDePago'];
		$idhuespedes = $rowMP['idhuespedes'];
		$qtdedepax = $rowMP['qtdedepax'];
		$dataIN = $rowMP['dataIN'];
		$dataOUT = $rowMP['dataOUT'];
		$qtdedecomidas = $rowMP['qtdedecomidas'];
		$idservicios = $rowMP['idservicios'];
		$mensajeinterno = $rowMP['mensajeinterno'];
		$mensajegarcon = $rowMP['mensajegarcon'];
		$idlocales = $rowMP['idlocales'];
		$idliquidaciones = $rowMP['idliquidaciones'];
		$actualizado = $rowMP['actualizado'];
		$nomedopax = $rowMP['titular'];
		$idpaises = $rowMP['idpaises'];
				
		if ($idmediapension > -1)
		{
			//INSERT HUESPED
			echo "Insertando vouchers novos no servidor - ". __LINE__ ."\n";
			
			$sql = "insert huespedes (titular, idpaises) values (";
			$sql .= "'".$nomedopax."',";
			$sql .= "'".$idpaises."') ";
			$dbRemote->query($sql);

			$idhuespedes = $dbRemote->insert_id;

			echo "Insertado Huesped: ID ".$idhuespedes. " - ". __LINE__ ."\n";

			//INSERT MEDIAPENSION
			echo "Insertando meia pensões novas no servidor - ". __LINE__ ."\n";

			$sql = "insert mediapension (numeroexterno, idoperadoresturisticos, idposadas, idagencias, idresponsablesDePago, idhuespedes, qtdedepax, dataIN, dataOUT, qtdedecomidas, idservicios, idlocales, mensajeinterno, mensajegarcon, actualizado) values (";
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
			
			//INSERT + UPDATE MP
			if($dbRemote->query($sql) === false)
			{
				trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbRemote->error, E_USER_ERROR);
			}
			else
			{
				$insertedMP = $dbRemote->insert_id;
				echo "Inserted MP " . $insertedMP . " on remote server". __LINE__ ."\n";

				$sql = "UPDATE mediapension SET actualizado = 1 WHERE idmediapension = " . $rowMP['idmediapension'];

				
				//UPDATING MP LOCAL
				if($dbLocal->query($sql) === false)
				{
					trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbLocal->error, E_USER_ERROR);
				} 
				else 
				{
					echo "Updated MP " . $rowMP['idmediapension'] . " on local server". __LINE__ ."\n";
				}


				//INSERT MPA
				// consulto todas las admisiones locales y luego realizo un insert por cada admision referenciada con $idmediapension_local
								
				echo "Insertando admisiones ingresadas com vouchers novos no servidor - ". __LINE__ ."\n";
								
				$sql = " select * from mediapension_admisiones where idmediapension = " . $rowMP['idmediapension'];
								
				$resultadoMPA = $dbLocal->query($sql);
					
				while ($row_admision = $resultadoMPA->fetch_assoc()) 
				{
					$precio = 0;
					$sql = "insert mediapension_admisiones (data, idmediapension, qtdedepax, tarifa, actualizado, idlocales) values (";
					$sql .= "'".$row_admision['data']."',";
					$sql .= "".$insertedMP.",";
					$sql .= "".$row_admision['qtdedepax'].",";
					$sql .= "".$precio.", 1, ";
					$sql .= "".$idlocales.") ";
									
					//INSERT + UPDATE MPA
					if($dbRemote->query($sql) === false)
					{
						trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbRemote->error, E_USER_ERROR);
					}
					else
					{
						$insertedMPA = $dbRemote->insert_id;
						echo "Inserted MPA " . $insertedMPA . " on remote server". __LINE__ ."\n";

						$sql = "UPDATE mediapension_admisiones SET actualizado = 1 WHERE id = ".$row_admision['id'];							
					
						if($dbLocal->query($sql) === false)
						{
							trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbLocal->error, E_USER_ERROR);
						}
						else
						{
							echo "Updated MPA " . $row_admision['id'] . " on local server - ". __LINE__ ."\n";
						}

						//INSERT MEDIAPENSION TICKTS 
						echo "Insertando tickets ingresados com vouchers novos no servidor - ". __LINE__ ."\n";
										
						$sql = " SELECT * FROM mediapension_tickets WHERE idmediapension_admisiones = ". $row_admision['id'] . ' AND actualizado = 0';
										
						$resultadoTickets = $dbLocal->query($sql);
									
						if ($row_tickets = $resultadoTickets->fetch_assoc())
						{
						
							$sql = "INSERT mediapension_tickets (idtickets, idmediapension_admisiones, idlocales, fecha, actualizado) values (";
							$sql .= "'".$row_tickets['idtickets']."',";
							$sql .= "".$insertedMPA.",";
							$sql .= "".$row_tickets['idlocales'].",";
							$sql .= "'".$row_tickets['fecha']."',";
							$sql .= "1) ";


							//INSERT + UPDATE MPT	
							if($dbRemote->query($sql) === false) 
							{
								trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbRemote->error, E_USER_ERROR);
							} 
							else 
							{
								echo "Inserted MPT " . $dbRemote->insert_id . " on remote server". __LINE__ ."\n";
								
								$sql = " UPDATE mediapension_tickets SET actualizado = 1 WHERE idtickets = ".$row_tickets['idtickets'];
							
								if($dbLocal->query($sql) === false)
								{
									trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbLocal->error, E_USER_ERROR);
								}
								else
								{
									echo "Updated MPT" . $row_tickets['idtickets'] . __LINE__ ."\n";
								}
								
							}
						}
					}							
				}
			}
		}
	}


	//INSERT ADMISIONES SOLAS
	echo "Procurando admisiones sem voucher. - ". __LINE__ ."\n";

	$sql = " SELECT * ";
	$sql .= "FROM mediapension_admisiones MPA ";
	$sql .= "WHERE MPA.actualizado = 0";
			
	$resultadoMPA = $dbLocal->query($sql);	
					
	while ($row_admision = $resultadoMPA->fetch_assoc()) 
	{	

		$precio = 0;

		$sql = "INSERT mediapension_admisiones (data, idmediapension, qtdedepax, tarifa, actualizado, idlocales) VALUES (";
		$sql .= "'".$row_admision['data']."',";
		$sql .= "".$row_admision['idmediapension'].",";
		$sql .= "".$row_admision['qtdedepax'].",";
		$sql .= "".$precio.", 1, ";
		$sql .= "".$idlocales.") ";

		//INSERT MPA
		echo "Insertando admisiones sem voucher no servidor. - ". __LINE__ ."\n";
		if($dbRemote->query($sql) === false)
		{
			trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbRemote->error, E_USER_ERROR);
		}
		else
		{
			$insertedMPA = $dbRemote->insert_id;
			echo "Inserted MPA " . $insertedMPA . " on remote server". __LINE__ ."\n";

			$sql = "UPDATE mediapension_admisiones SET actualizado = 1 WHERE id = ".$row_admision['id'];							
			
			//UPDATE LOCAL MPA	
			if($dbLocal->query($sql) === false)
			{
				trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbLocal->error, E_USER_ERROR);
			}
			else
			{
				echo "Updated MPA " . $row_admision['id'] . " on local server - ". __LINE__ ."\n";
			}

			//INSERT MPT 
			echo "Insertando tickets sem voucher no servidor - ". __LINE__ ."\n";

			$sql = "SELECT * FROM mediapension_tickets WHERE idmediapension_admisiones = ". $row_admision['id'] . ' AND actualizado = 0';
									
			$resultadoTickets = $dbLocal->query($sql);
								
			if ($row_tickets = $resultadoTickets->fetch_assoc())
			{
					
				$sql = "INSERT mediapension_tickets (idtickets, idmediapension_admisiones, idlocales, fecha, actualizado) values (";
				$sql .= "'".$row_tickets['idtickets']."',";
				$sql .= "".$insertedMPA.",";
				$sql .= "".$row_tickets['idlocales'].",";
				$sql .= "'".$row_tickets['fecha']."',";
				$sql .= "1) ";


				//INSERT + UPDATE MPT	
				if($dbRemote->query($sql) === false) 
				{
					trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbRemote->error, E_USER_ERROR);
				} 
				else 
				{
					echo "Inserted MPT " . $dbRemote->insert_id . " on remote server". __LINE__ ."\n";
					
					$sql = " UPDATE mediapension_tickets SET actualizado = 1 WHERE idtickets = ".$row_tickets['idtickets'];
						
					if($dbLocal->query($sql) === false)
					{
						trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $dbLocal->error, E_USER_ERROR);
					}
					else
					{
						echo "Updated MPT" . $row_tickets['idtickets'] . __LINE__ ."\n";
					}
							
				}
			}
		}


		/////////////////////////
		
	}
					
	echo "Fin de actualización de db en server - ". __LINE__ ."\n";
	
	
	//SEGUNDA PARTE
	$updownpathfile = '/tmp/backup.sql.gz';
			

	if (!function_exists("ssh2_connect")){
		throw new Exception('La funcion ssh2_connect no existe', 0);
	}
	else{
		echo "Intentando conectarse por ssh2 localmente - ". __LINE__ ."\n";
		
		if(!($con_remote = ssh2_connect($rdb['dbhost'], $ssh2_remote_port)))
		{
			throw new Exception('Sem acceso ssh ao servidor', 0);
		}
		else
		{
			// try to authenticate with username root, password secretpassword
			if(!ssh2_auth_password($con_remote, $ssh2_remote_user, $ssh2_remote_password)) 
			{
				throw new Exception('Falha ao autenticar no servidor por ssh', 0);
			} 
			else 
			{
				//Genera el backup en el servidor
				echo "Creando backup do banco de dados no servidor - ". __LINE__ ."\n";
				
				$sqldump = "mysqldump --opt  -u ". $rdb['user'] . " -p".$rdb['password']." ".$rdb['dbname']." agencias usuarios usuarios_tipos mediapension mediapension_admisiones mediapension_tickets huespedes operadoresturisticos posadas posadas_listasdeprecios responsablesDePago | gzip  > ".$updownpathfile;
				
				if (!(ssh2_exec($con_remote, $sqldump))) 
				{
					throw new Exception('Error al intentar crear backup no servidor', 0);
				}
				else
				{
					//ssh2_scp_recv(Connection, Remote path, Local Path)
					echo "Baixando banco de dados do servidor - ". __LINE__ ."\n";
					
					if (!ssh2_scp_recv($con_remote, $updownpathfile, $updownpathfile))
					{
						throw new Exception('Não foi possível fazer o download do banco de dados do servidor', 0);
					}
					else
					{
						//Conectarse por ssh local
						if(!($con_local = ssh2_connect($ldb['dbhost'], $ssh2_local_port)))
						{
							throw new Exception('Não foi possível conectar por ssh localmente', 0);
						} 
						else
						{
							// try to authenticate with username root, password secretpassword
							if(!ssh2_auth_password($con_local, $ssh2_local_user, $ssh2_local_password))
							{
								throw new Exception('Falha ao autenticar localmente por ssh', 0);
							} 
							else
							{
								echo "Aplicando actualizações - ". __LINE__ ."\n";
								
								$sqlrestore = "gzip -dc < ".$updownpathfile." | mysql -u ".$ldb['user']." -p".$ldb['password']." ".$ldb['dbname']."";
								
								$restore = ssh2_exec($con_local, $sqlrestore);
								var_dump($restore);
								echo "Fim de Actualizaçao - ". __LINE__ ."\n\n\n";
							}
							}
						}
					}
			}
		}
	}
}
else{
	echo "Disconnected\n";
}

$dbLocal->close();
$dbRemote->close();
unset($iStatus);

?>
