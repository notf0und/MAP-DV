<?php 
	include "lib/sessionLib.php";
	$script_name = $_SERVER['SCRIPT_NAME'];

    $query = "SELECT idposadas, nombre FROM posadas"; 
    $result = resultFromQuery($query); 
    $items = array(); 

    if($result && mysql_num_rows($result)>0) { 
        while($row = mysql_fetch_array($result)) { 
            $items[$row[0]] = htmlentities($row[1]); 
        }         
    } 

    $response = isset($_GET['callback'])?$_GET['callback']."(".json_encode($items).")":json_encode($items); 
    echo $response;    
?>