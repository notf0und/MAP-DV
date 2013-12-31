<?php


include(getcwd().'/TestFolder/checkinternet.php'); // Incluye la clase CheckInternet()
include(getcwd().'/lib/dbUpdate.php');

$iStatus = new CheckInternet(); // Instancia un nuevo objeto de la clase CheckInternet()

$time = date("G:i:s");

if ($iStatus->status()){
	
	$ldb = parse_ini_file(getcwd()."/local-config.ini", true)['local_database'];
	
	//Lee configuracion local de local-config.ini para asignar la variable $idlocales
	$entry = $time.": Obtenção de informação do banco de dados local - ". __LINE__ ."\n";
	if ($ldb['dbname'] == 'DA_CN'){
		$idlocales = 1;
	}
	elseif ($ldb['dbname'] == 'DA_JF'){
		$idlocales = 2;
	}
	else{
		$idlocales = 0;
	}
	
	
	$sql = " SELECT * FROM locales where idlocales = ".$idlocales;
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
	$entry .= $time.": Obtenção de informação do banco de dados do server - ". __LINE__ ."\n";
	$sql = " SELECT * FROM locales where idlocales = 0 ";
	$resultado = resultFromQuery($sql);	

	if ($row = mysql_fetch_object($resultado))
	{
		
		$db_hostname_server = $row->db_hostname;
		$db_database_server = $row->db_database;
		$db_username_server = $row->db_username;
		$db_password_server = $row->db_password;
	}
	
	try
	{
		if (!function_exists("ssh2_connect")){
			throw new Exception('La funcion ssh2_connect no existe', 0);
		}
		else{
			
			$entry .= $time.": Intentando conectarse por ssh2 localmente - ". __LINE__ ."\n";
			
			if(!($con = ssh2_connect($db_hostname_local, 22)))
			{
				throw new Exception('Sem acceso ssh ao computador local', 0);
			}
			else{
				$entry .= $time.": Iniciando sesión -". __LINE__ ."\n";

				if(!ssh2_auth_password($con, $ssh2_local_user, $ssh2_local_password))
				{
					throw new Exception('Erro de autenticação ssh local', 0);
				}
				else 
				{
					// allright, we're in!				
					// Selecciono de la tabla MP con la columna actualizado = 0
					$entry .= $time.": Procurando vouchers sem atualizar - ". __LINE__ ."\n";
					
					$sql = " select * from ".$db_database_local.".mediapension MP ";
					$sql .= " inner join ".$db_database_local.".huespedes H on MP.idhuespedes = H.idhuespedes ";
					$sql .= " where MP.actualizado = 0; ";
					
					$resultadoMP = resultFromQuery($sql);
				
					while ($rowMP = mysql_fetch_object($resultadoMP))
					{
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
							$entry .= $time.": Insertando vouchers novos no servidor - ". __LINE__ ."\n";
							
							$sql = "insert ".$db_database_server.".huespedes (titular, idpaises) values (";
							$sql .= "'".$nomedopax."',";
							$sql .= "'".$idpaises."') ";
							$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
							$idhuespedes = mysql_insert_id();

							$entry .= $time.": Insertar Huesped: ID ".$idhuespedes. " - ". __LINE__ ."\n";

							//INSERT MEDIAPENSION
							$entry .= $time.": Insertando meia pensões novas no servidor - ". __LINE__ ."\n";

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

							$entry .= $time.": Insertar MP: ID ".$idmediapension. " - ". __LINE__ ."\n";
							
							//INSERT MEDIAPENSION ADMICION
							// consulto todas las admisiones locales y luego realizo un insert por cada admision referenciada con $idmediapension_local
							
							$entry .= $time.": Insertando admisiones ingresadas com vouchers novos no servidor - ". __LINE__ ."\n";
							
							$sql = " select * from ".$db_database_local.".mediapension_admisiones where idmediapension = ".$idmediapension_local;
							$resultado = resultFromQuery($sql);	

							while ($row_admision = mysql_fetch_object($resultado)) 
							{
								$idamision_local = $row_admision->id;
								$datadiaria = date("Y-m-d");
								//$precio = valordiaria($datadiaria, $idposadas, $idservicios);
								$precio = 0;
								$sql = "insert ".$db_database_server.".mediapension_admisiones (data, idmediapension, qtdedepax, tarifa, actualizado, idlocales) values (";
								$sql .= "'".$row_admision->data."',";
								$sql .= "".$idmediapension.",";
								$sql .= "".$row_admision->qtdedepax.",";
								$sql .= "".$precio.", 1, ";
								$sql .= "".$idlocales.") ";
								$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
								$idadmision = mysql_insert_id();

								//INSERT MEDIAPENSION TICKTS 
								$entry .= $time.": Insertando tickets novos no servidor - ". __LINE__ ."\n";
								
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
								
								$entry .= $time.": Estableciendo admisiones locales como atualizadas - ". __LINE__ ."\n";
								
								$sql = " update ".$db_database_local.".mediapension_admisiones set actualizado = 1 where idmediapension = ".$idmediapension_local;
								$resultado = resultFromQuery($sql);									
							}
						}
					
					$entry .= $time.": Estableciendo vouchers locales como atualizados - ". __LINE__ ."\n";
					
					$sql = " update ".$db_database_local.".mediapension set actualizado = 1 where idmediapension = ".$idmediapension_local;
					$resultado = resultFromQuery($sql);	
					}


					//INSERT ADMISIONES SOLAS
					$entry .= $time.": Procurando admisiones sem voucher. - ". __LINE__ ."\n";

					$sql = " select MPA.data, MPA.idmediapension, MPA.qtdedepax, H.titular ";
					$sql .= " from ".$db_database_local.".mediapension_admisiones MPA ";
					$sql .= " left join ".$db_database_local.".mediapension MP on MPA.idmediapension = MP.idmediapension ";
					$sql .= " left join ".$db_database_local.".huespedes H on MP.idhuespedes = H.idhuespedes ";
					$sql .= " where MPA.actualizado = 0";
					
					$resultado = resultFromQuery($sql);	
					
					$entry .= $time.": Insertando admisiones sem voucher no servidor - ". __LINE__ ."\n";

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
						
						$sql = "insert ".$db_database_server.".mediapension_admisiones (data, idmediapension, qtdedepax, tarifa, actualizado, idlocales) values (";
						$sql .= "'".$row_admision->data."',";
						$sql .= "".$row_admision->idmediapension.",";
						$sql .= "".$row_admision->qtdedepax.",";
						$sql .= "".$precio.", 1, ";
						$sql .= "".$idlocales.") ";
						$resultadoStringSQL = mysql_resultFromQuery($sql, $dbConnection_server);		
						$idadmision = mysql_insert_id();
						
						
					}
					$entry .= $time.": Estableciendo admisiones locales sem voucher como atualizadas - ". __LINE__ ."\n";
					
					$sql = " update ".$db_database_local.".mediapension_admisiones set actualizado = 1 where actualizado = 0";
					$resultado = resultFromQuery($sql);
					
					$entry .= $time.": Fin de actualización en server - ". __LINE__ ."\n";
				}
			}
			
			//SEGUNDA PARTE
			//Directorio y archivo donde se va a guardar en el localhost como en el server
			$updownpathfile = '/tmp/backup.sql.gz';
			
			// Obtengo la información sobre la base de datos del servidor
			$entry .= $time.": Leitura de configuraçao do servidor e do computador local - ". __LINE__ ."\n";

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
			$entry .= $time.": Leitura de configuraçao do localhost - ". __LINE__ ."\n";

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
			
			if (!function_exists("ssh2_connect")){
				throw new Exception('La funcion ssh2_connect no existe', 0);
			}
			else{
				
				$entry .= $time.": Intentando conectarse por ssh2 localmente - ". __LINE__ ."\n";
				
				if(!($con_remote = ssh2_connect($remote_hostname, 2206)))
				{
					throw new Exception('Sem acceso ssh ao servidor', 0);
				}
				else{
					// try to authenticate with username root, password secretpassword
					if(!ssh2_auth_password($con_remote, $ssh2_remote_user, $ssh2_remote_password)) 
					{
						throw new Exception('Falha ao autenticar no servidor por ssh', 0);
					} 
					else 
					{
						//Genera el backup en el servidor
						$entry .= $time.": Creando backup do banco de dados no servidor - ". __LINE__ ."\n";

						$sqldump = "mysqldump --opt  -u ".$db_remote_user." -p".$db_remote_password." ".$db_remote_database." agencias usuarios usuarios_tipos mediapension mediapension_admisiones mediapension_tickets huespedes operadoresturisticos posadas posadas_listasdeprecios responsablesDePago | gzip  > ".$updownpathfile;
					
						if (!(ssh2_exec($con_remote, $sqldump))) 
						{
							throw new Exception('Error al intentar crear backup no servidor', 0);
						}
						else					
						{
							//ssh2_scp_recv(Connection, Remote path, Local Path)
							$entry .= $time.": Baixando banco de dados do servidor - ". __LINE__ ."\n";

							if (!ssh2_scp_recv($con_remote, $updownpathfile, $updownpathfile))
							{
								throw new Exception('Não foi possível fazer o download do banco de dados do servidor', 0);
							}
							else
							{
								if(!($con_local = ssh2_connect($local_hostname, 22)))
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
										$entry .= $time.": Aplicando actualizações - ". __LINE__ ."\n";
										
										$sqlrestore = "gzip -dc < ".$updownpathfile." | mysql -u ".$db_local_user." -p".$db_local_password." ".$db_local_database."";
										
										$restore = ssh2_exec($con_local, $sqlrestore);
										var_dump($restore);
										$entry .= $time.": Fin de Actualizaçao - ". __LINE__ ."\n\n\n";
									}
								}
							}
						}
					}
				}
			}
		}
	}
	catch(Exception $e)
	{
		$entry .= "\n--------------------------\n";
		$entry .= "Time: ".$time."\n";
		$entry .= "Message: ".$e->getMessage()."\n";
		$entry .= "Code: ".$e->getCode()."\n";
		$entry .= "File: ".$e->getFile()."\n";
		$entry .= "Line: ".$e->getLine()."\n";
		$entry .= "Trace: ".$e->getTrace()."\n";
		$entry .= "Trace as String: ".$e->getTraceAsString()."\n";
		$entry .= "--------------------------\n";
	}
}
else{
	$entry = "Desconectado\n";
}

$file = getcwd()."/cron.update.txt";
$open = fopen($file,"a");

if ( $open ) {
	fwrite($open,$entry);
	fclose($open);
}

unset($iStatus);

?>
