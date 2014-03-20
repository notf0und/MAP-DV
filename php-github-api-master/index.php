<?php

// This file is generated by Composer
require_once 'vendor/autoload.php';

$client = new \Github\Client();
//$repositories = $client->api('user')->repositories('ornicar');

//$issues = $client->api('issue')->all('notf0und', 'MAP-DV', array('state' => 'open'));

$comments = $client->api('issue')->comments()->all('notf0und', 'MAP-DV', 4);

for ($i = 0; $i <= count($issues); $i++){
	echo $issues[$i]["title"].'<br>';
	echo $issues[$i]["body"].'<br><br>';
}

var_dump($comments);
?>
