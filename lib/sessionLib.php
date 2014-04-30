<?php

ini_set('display_errors',1);
error_reporting(E_ALL);
// Biblioteca de funciones
include "lib.php";
include "functions.php";

require_once 'php-github-api-master/vendor/autoload.php';

$client = new \Github\HttpClient\CachedHttpClient();
$client->setCache(
    // Built in one, or any cache implementing this interface:
    // Github\HttpClient\Cache\CacheInterface
    new \Github\HttpClient\Cache\FilesystemCache('/tmp/github-api-cache')
);

$client = new \Github\Client($client);

session_start();

if (isset($_SESSION["idusuarios"]) && $_SESSION["idusuarios"] == 13) {
	
	//Reporte de errores PHP
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	
	require_once('lib/FirePHPCore/FirePHP.class.php');
	ob_start();
	$firephp = FirePHP::getInstance(true);
	
	/* Example usage of firephp
	if (isset($_SESSION["idusuarios"]) && $_SESSION["idusuarios"] == 13) {
			 
		$firephp->log('Un mensaje plano');
		$firephp->info('Un mensaje de información');
		$firephp->warn('Una alerta');
		$firephp->error('Enviar un mensaje de error');
		
		$table   = array();
		$table[] = array('Titulo 1','Titulo 2', 'Titulo 3');
		$table[] = array('Col 1, fila 1','Col 2, fila 1','Col 3, fila 1');
		$table[] = array('Col 1, fila 2','Col 2, fila 2','Col 3, fila 2');
		$table[] = array('Col 1, fila 3','Col 2, fila 3','Col 3, fila 3');

		$firephp->table('Tabla', $table);  

		fb($table, 'Tabla', FirePHP::TABLE);
	}
	*/
}
else{
	error_reporting(0);
}



//error_reporting(E_ALL);
//ini_set('display_errors','OFF');
?>
