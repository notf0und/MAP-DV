<?php
  
  function dbConnect() {
    
    if (!isset($dbConnection)) {
      $dbConnection = &$_SESSION["dbConnection"];
    }

    if (!$dbConnection) {
		
		//Muestra opciones segun configuración de la terminal
		$ldb = parse_ini_file("/home/sistemas/Documents/dasamericas/www/local-config.ini", true)['local_database'];
		

    	$dbConnection = mysql_dbConnect($ldb['dbhost'], $ldb['dbname'], $ldb['user'], $ldb['password']);
    }
    
    return $dbConnection;
  };
  
  function mysql_dbConnect($host, $database, $user, $pass) {
//    $dbConn = ocilogon($user, $pass, $host);
    $dbConn = mysql_connect($host, $user, $pass);
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	mysql_select_db($database, $dbConn);
    
    if (!$dbConn) {
      die('Could not connect to database: ' . mysql_error());
    }
    return $dbConn;
  };
  
  function oci_dbConnect($host, $database, $user, $pass) {
//    $dbConn = ocilogon($user, $pass, $host);
    $dbConn = oci_connect($user, $pass, $host);
    
    if (!$dbConn) {
      die('Could not connect to database: ' . ocierror());
    }
    return $dbConn;
  };
  
  function resultFromQuery($sqlQuery, &$dbConn = '') {
    // Si no pasaron la dbConnection, usamos la de la session
    if ($dbConn == '') {
      $dbConn = &$_SESSION["dbConnection"];
    }
    
    $dbConn = dbConnect();
    
    mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
    
    return mysql_resultFromQuery($sqlQuery, $dbConn);
  };
  
  function oci_resultFromQuery($sqlQuery, $dbConn) {
    $result = ociparse($dbConn, $sqlQuery);
    if (!$result) {
      die('Error ejecutando el query: ' . $sqlQuery . ocierror());
    }

    if (!ociexecute($result)) {
      die('Error ejecutando el query: ' . $sqlQuery . ocierror());
    }
    return $result;
  };
  
  function mysql_resultFromQuery($sqlQuery, &$dbConn) {
    $result = mysql_query($sqlQuery, $dbConn);
    if (!$result) {
      die('Error ejecutando el query: ' . $sqlQuery . '<BR>' . mysql_error());
    }

    return $result;
  };
  
  function siguienteResult($result) {
    return mysql_siguienteResult($result);
  }
  
  function mysql_siguienteResult($result) {
    return mysql_fetch_object($result);
  }
  
  function oci_siguienteResult($result) {
    return ocifetch($result);
  }
  
  function celdaFromResult($result,$col) {
    return mysql_celdaFromResult($result,$col);
  }
  
  function mysql_celdaFromResult($result,$col) {
    return mysql_fetch_field($result, ($col));
  }
  
  function oci_celdaFromResult($result,$col) {
    return ociresult($result, ($col+1));
  }
  
  function dbRowCount($result) {
    return mysql_dbRowCount($result);
  }
  
  function mysql_dbRowCount($result) {
    return mysql_num_rows($result);
  }
  
  function oci_dbRowCount($result) {
    return ocirowcount($result);
  }
  
  function dbFieldCount($result) {
    return mysql_dbFieldCount($result);
  }
  
  function mysql_dbFieldCount($result) {
    return mysql_num_fields($result);
  }
  
  function oci_dbFieldCount($result) {
    return ocinumcols($result);
  }
  
  function dbFieldName($result, $col) {
    return mysql_dbFieldName($result, $col);
  }
  
  function mysql_dbFieldName($result, $col) {
    return mysql_field_name($result, $col);
  }
  
  function oci_dbFieldName($result, $col) {
    return oci_field_name($result, $col);
  }

  function dbFieldByName($result, $fieldName) {
    for ($i = 0; $i < dbFieldCount($result); $i++) {
      $celda = celdaFromResult($result, ($i));
      if ($celda->name == $fieldName) {
        return $celda;
      }
    }
  }
  
  function tableFromResult($result, $name = '', $deletableRows = false, $modifiableRows = false) {
    return mysql_tableFromResult($result, $name, $deletableRows, $modifiableRows);
  };

  function oci_tableFromResult($result) {
		$table = '<TABLE style="font-family:Tahoma" cellpadding=0 border=0>';
		if (siguienteResult($result)) {
			for ($i = 0; $i < dbFieldCount($result); $i++) {
				$table = $table . '<TH>' . dbFieldName($result,$i) . '</TH>';
			}
			do {
				$table = $table . '<TR>';
				for ($j = 0; $j < dbFieldCount($result); $j++) {
					$table = $table . '<TD>' . oci_celdaFromResult($result,$j) . '</TD>';        
				}
				$table = $table . '</TR>';
			} while (siguienteResult($result));
		}
		$table = $table . '</TABLE><BR>';
    return $table;
  };
  
  function mysql_tableFromResult($result, $name = '', $deletableRows = false, $modifiableRows = false) {
		$table = '<TABLE class="table table-bordered data-table" name="'.$name.'" id="'.$name.'">';
  	//if ($deletableRows || $modifiableRows) {
  	//	$table .= '<form name="'.$name.'Form" method="POST">';
  	//}
 		$table .= '<thead>';
 		$table .= '<tr>';
 		//Prepara las columnas a mostrar
		for ($i = 1; $i < dbFieldCount($result); $i++) {
			$colname = dbFieldName($result,$i);
			if ($colname != 'decline'){
				$table .= "\n\t".'<TH>' . dbFieldName($result,$i) . '</TH>';
			}
		}
		if ($deletableRows) {
			$table .= '<TH>Apagar</TH>';
		}
		if ($modifiableRows) {
			$table .= '<TH>Modificar</TH>';
		}
		$table .= '</tr>';
 		$table .= '</thead>';
 		$table .= '<tbody>';
 		
		while ($row = siguienteResult($result)) {
			
			$table .= "\n\t".'<TR>';
			
			for ($j = 1; $j < dbFieldCount($result); $j++) {
				$colname = dbFieldName($result, $j);
				switch($colname){
					case 'Detalles':
						$table .= '<TD>';
						$table .= '<a href="#myModal" data-toggle="modal" class="" onclick="document.getElementById(\'modal-body\').innerHTML=\'<object id=foo name=foo type=text/html width=530 height=350 data=mediapension.admisiones.php?idmediapension='.$row->id.'></object>\'">Ver</a>';
						$table .= '</TD>';
						break;
					case 'Nome Completo':
						if (isset($row->decline) &&  $row->decline != ''){
							$table .= '<TD><font color="red">' ;
							$table .= $row->$colname;
							$table .= '</font></TD>';							
						}
						else{
							$table .= '<TD><font>';
							$table .= $row->$colname;
							$table .= '</font></TD>';
						}
						break;
						
					case 'decline':
						break;
					case 'Pagamentos':
						$table .= '<TD>';
						$table .= '<a href="funcionarios.pagamentos.php?employee_id='.$row->id.'">';
						$table .= ($_SESSION["idusuarios_tipos"] == 1) || ($_SESSION["idusuarios_tipos"] == 4) ? calcularSalario($row->id, date('n'), date('Y'))['Total'] : 'Balance de salario';
						$table .= '</a>';
						$table .= '</TD>';
						break;					
					default:
						$table .= '<TD>' . $row->$colname . '</TD>';
						break;
					}
				}
			if ($deletableRows) {
				$colname = dbFieldName($result, 0);
				$id = $row->$colname;
				$table .= '<TD><input type="submit" class="btn" name="deleteRow['.$colname.']['.$id.']" onclick="javascript:deleteRowEvent('."'".$name."','".$colname."','".$id."'".');" value="Apagar"></TD>';

			}
			if ($modifiableRows) {
				$colname = dbFieldName($result, 0);
				$id = $row->$colname;
				$table .= '<TD><input type="button" class="btn" name="modifyRow" onclick="javascript:modifyRowEvent('."'".$name."','".$colname."','".$id."'".');" value="Editar"></TD>';
			}
			$table = $table . '</TR>';
		};
 		$table .= '</tbody>';
  	//if ($deletableRows || $modifiableRows) {
  	//	$table .= '</form>';
  	//}
		$table = $table . '</TABLE>';
    
    return $table;
  };

  function stringFromFlashQuery($fields,$tables,$where = '1=1') {
    $sql =   "SELECT ".$fields." " .
        "FROM ".$tables." " .
        "WHERE ".$where;
    $result = resultFromQuery($sql);
    $strResult = '';
    for ($i=0; $i<dbFieldCount($result); $i++) {
      siguienteResult($result);
      $strResult = $strResult . celdaFromResult($result,0);
      if ($i != dbFieldCount($result) - 1) {
        $strResult = $strResult . ', ';
      }
    }
    return $strResult;
  }
	
	function dbStringFrom($valor) {
		
		if (!isset($valor)) {
			return 'null';
		}
		if (is_string($valor)) {
			if ($valor != '') {
				return "'".str_replace("'", "''",$valor)."'";
			} else {
				return 'null';
			}
		}
		if (is_bool($valor)) {
			if ($valor) {
				return '1';
			} else {
				return '0';
			}
		}
		return strval($valor);
	}

mysql_query("SET NAMES 'UTF8'");
?>
