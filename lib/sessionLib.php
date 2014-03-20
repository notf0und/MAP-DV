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

//error_reporting(E_ALL);
//ini_set('display_errors','OFF');
?>
