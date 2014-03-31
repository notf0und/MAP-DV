<?php
$url = 'http://davincimp.no-ip.info:8080/ponto.php';

$options['http'] = array(
    'method' => "HEAD",
    'ignore_errors' => 1,
);

$context = stream_context_create($options);

$body = file_get_contents($url, NULL, $context);

if (!$http_response_header){
	header('Location: http://localhost/public_html/ponto.php');
	exit();
}

$responses = parse_http_response_header($http_response_header);

$code = $responses[0]['status']['code']; // last status code

if ($code != 200){
	//Redirect to emergency point 
	header('Location: http://localhost/public_html/ponto.php');
	exit();
}
else{
	$local_config = parse_ini_file("local-config.ini", true);
	$localdbcfg = $local_config['local_database'];
	
	$dblocal = mysqli_connect($localdbcfg['dbhost'], $localdbcfg['user'], $localdbcfg['password'], $localdbcfg['dbname']);
	
	if (mysqli_connect_errno()){
		echo "Failed to connect to local MySQL: " . mysqli_connect_error();
	}
	
	$sql = "SELECT * FROM point WHERE updated = 0";

	if (!$result = mysqli_query($dblocal, $sql)){
		die('Error al verificar si existen actualizaciones en la base de datos:<br> ' . mysqli_error($dblocal));
	}
	
	if ($row = mysqli_fetch_array($result)){
		
		$remotedbcfg = $local_config['remote_database'];
		
		$dbremote = mysqli_connect($remotedbcfg['dbhost'], $remotedbcfg['user'], $remotedbcfg['password'], $remotedbcfg['dbname'], 3306);

		$sql = "SELECT * FROM point WHERE updated = 0";					

		if (!$result = mysqli_query($dblocal, $sql)){
			die('Error al verificar si existen actualizaciones en la base de datos:<br> ' . mysqli_error($dblocal));
		}

		//Prepara la cadena de inserción
		$sql = "INSERT point (employee_id, date_time, in_out, updated) ";
		$sql .= "VALUES ";
						
		//Todos los puntos sin actualizar
		while($point = mysqli_fetch_array($result)){
			
			if (mb_substr($sql, -1) != ' ' && mb_substr($sql, -2) != ', '){
				$sql  .= ', ';
			}
			
			$sql .= "(".$point['employee_id'].", ";
			$sql .= "'".$point['date_time']."', ";
			$sql .= $point['in_out'].", ";
			$sql .= "1)";
		}
					
		//Insert de puntos sin actualizar
		if (!mysqli_query($dbremote, $sql)){
			die('Error al insertar los puntos sin actualizar en la base de datos remota:<br> '.$sql.'<br>'. mysqli_error($dbremote));
		}
		
		mysqli_close($dbremote);
				
		//Establece los puntos de la base de datos local como actualizados
		$sql = "UPDATE point SET updated = 1";
					
		mysqli_query($dblocal, $sql);
	}
	
	mysqli_close($dblocal);

	//Redirect to online point
	header('Location: '.$url);
	exit();
}

/**
 * parse_http_response_header
 *
 * @param array $headers as in $http_response_header
 * @return array status and headers grouped by response, last first
 */
function parse_http_response_header(array $headers)
{
	
    $responses = array();
    $buffer = NULL;
    foreach ($headers as $header)
    {
        if ('HTTP/' === substr($header, 0, 5))
        {
            // add buffer on top of all responses
            if ($buffer) array_unshift($responses, $buffer);
            $buffer = array();

            list($version, $code, $phrase) = explode(' ', $header, 3) + array('', FALSE, '');

            $buffer['status'] = array(
                'line' => $header,
                'version' => $version,
                'code' => (int) $code,
                'phrase' => $phrase
            );
            $fields = &$buffer['fields'];
            $fields = array();
            continue;
        }
        list($name, $value) = explode(': ', $header, 2) + array('', '');
        // header-names are case insensitive
        $name = strtoupper($name);
        // values of multiple fields with the same name are normalized into
        // a comma separated list (HTTP/1.0+1.1)
        if (isset($fields[$name]))
        {
            $value = $fields[$name].','.$value;
        }
        $fields[$name] = $value;
    }
    unset($fields); // remove reference
    array_unshift($responses, $buffer);

    return $responses;
}
?>
