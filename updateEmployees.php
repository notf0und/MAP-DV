<?php
 error_reporting(E_ALL);
 ini_set('display_errors', 1);

include(getcwd().'/TestFolder/checkinternet.php'); // Incluye la clase CheckInternet()

$iStatus = new CheckInternet(); // Instancia un nuevo objeto de la clase CheckInternet()

if ($iStatus->status()){
	
	$updownpathfile = '/tmp/updateEmployees.sql.gz';
	
	$cfgfile = parse_ini_file(getcwd()."/local-config.ini", true);
	$ldb = $cfgfile['local_database'];
	$rdb = $cfgfile['remote_database'];
	
	if ($ldb['dbname'] == 'DA_CN'){
		$idlocales = 1;
	}
	elseif ($ldb['dbname'] == 'DA_JF'){
		$idlocales = 2;
	}
	else{
		$idlocales = 0;
	}
	
	//Check max employee id on local database
	$dbLocal = mysql_connect($ldb['dbhost'], $ldb['user'], $ldb['password']);
	mysql_select_db($ldb['dbname'], $dbLocal);
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	
	$sql = "SELECT * FROM locales where idlocales = ".$idlocales;	
	$result = mysql_query($sql, $dbLocal);
    if (!$result) {
      die('Error ejecutando el query: ' . $sql . '<BR>' . mysql_error());
    }
	
	if ($row = mysql_fetch_object($result))
	{
		$db_hostname_local = $row->db_hostname;
		$db_database_local = $row->db_database;
		$db_username_local = $row->db_username;
		$db_password_local = $row->db_password;
		
		$ssh2_local_user = $row->ssh2_user;
		$ssh2_local_password = $row->ssh2_password;
	}
	

	$sql = " SELECT * FROM employee ORDER BY employee_id DESC LIMIT 1";
	
	$result = mysql_query($sql, $dbLocal);
    if (!$result) {
      die('Error ejecutando el query: ' . $sql . '<BR>' . mysql_error());
    }
    
	
	$row = mysql_fetch_object($result);
	
	$maxlocalEmployee = $row->employee_id;
	echo "Max employee on local: " . $maxlocalEmployee . "\n";
	
	mysql_close($dbLocal);
	
	
	//Check max employee id on remote database
	$dbRemote = mysql_connect($rdb['dbhost'], $rdb['user'], $rdb['password']);
	mysql_select_db($rdb['dbname'], $dbRemote);
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	$sql = " SELECT * FROM locales where idlocales = 0 ";
	$result = mysql_query($sql, $dbRemote);
    if (!$result) {
      die('Error ejecutando el query: ' . $sql . '<BR>' . mysql_error());
    }

	if ($row = mysql_fetch_object($result))
	{
		$db_hostname_server = $row->db_hostname;
		$db_database_server = $row->db_database;
		$db_username_server = $row->db_username;
		$db_password_server = $row->db_password;
		
		$ssh2_remote_user = $row->ssh2_user;
		$ssh2_remote_password = $row->ssh2_password;
	}
	
	$sql = " SELECT * FROM employee ORDER BY employee_id DESC LIMIT 1";
	
	$result = mysql_query($sql, $dbRemote);
    if (!$result) {
      die('Error ejecutando el query: ' . $sql . '<BR>' . mysql_error());
    }
    
	
	$row = mysql_fetch_object($result);
	
	$maxremoteEmployee = $row->employee_id;
	echo "Max employee on remote: " . $maxremoteEmployee . "\n";
	
	mysql_close($dbRemote);
	
	//Start update process
	if ($maxlocalEmployee < $maxremoteEmployee){
		echo "Starting update...\n";
		
		
		try{
			if(!($con_remote = ssh2_connect($db_hostname_server, 2206))){
				throw new Exception('Sem acceso ssh ao servidor', 0);
			}
			else{
				// try to authenticate with username root, password secretpassword
				if(!ssh2_auth_password($con_remote, $ssh2_remote_user, $ssh2_remote_password)){
					throw new Exception('Falha ao autenticar no servidor por ssh', 0);
				}
				else {
					//Genera el backup en el servidor
					echo "Creando backup do banco de dados no servidor - ". __LINE__ ."\n";
					$sqldump = "mysqldump --opt  -u ".$db_username_server." -p".$db_password_server." ".$db_database_server." paises state city address jobcategory profile employee | gzip  > ".$updownpathfile;
					
					if (!(ssh2_exec($con_remote, $sqldump))){
						throw new Exception('Error al intentar crear backup no servidor', 0);
					}
					else{
						//ssh2_scp_recv(Connection, Remote path, Local Path)
						echo "Baixando banco de dados do servidor - ". __LINE__ ."\n";

						if (!ssh2_scp_recv($con_remote, $updownpathfile, $updownpathfile))
						{
									throw new Exception('Não foi possível fazer o download do banco de dados do servidor', 0);
								}
						else
						{
							if(!($con_local = ssh2_connect($db_hostname_local, 22))){
										throw new Exception('Não foi possível conectar por ssh localmente', 0);
									} 
							else{
								// try to authenticate with username root, password secretpassword
								if(!ssh2_auth_password($con_local, $ssh2_local_user, $ssh2_local_password)){
											throw new Exception('Falha ao autenticar localmente por ssh', 0);
										} 
								else{
									echo "Aplicando actualizações - ". __LINE__ ."\n";
									
									$sqlrestore = "gzip -dc < ".$updownpathfile." | mysql -u ".$db_username_local." -p".$db_password_local." ".$db_database_local."";
											
									$restore = ssh2_exec($con_local, $sqlrestore);
									var_dump($restore);
									echo "Fin de Actualizaçao - ". __LINE__ ."\n\n\n";
								}
							}
						}
					}
				}
			}
		}
		catch(Exception $e)
		{
			echo "\n--------------------------\n";
			echo "Message: ".$e->getMessage()."\n";
			echo "Code: ".$e->getCode()."\n";
			echo "File: ".$e->getFile()."\n";
			echo "Line: ".$e->getLine()."\n";
			echo "Trace: ".$e->getTrace()."\n";
			echo "Trace as String: ".$e->getTraceAsString()."\n";
			echo "--------------------------\n";
		}

	}
	//var_dump($row);

	unset($iStatus);
	exit;
}

?>
