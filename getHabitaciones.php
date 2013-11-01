<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];
     
    // Tomamos los parametros de Array
    $posadasid = !empty($_GET['id']) 
              ?intval($_GET['id']):0; 
     
    // Si no es seleccionada ninguna ciudad, tomamos data por defecto     
    $query = "SELECT idhabitaciones, codigo FROM habitaciones"; 
     
    //  Sino, concatenamos la consulta con el id de la ciudad
    if($posadasid>0) $query.=" WHERE idhabitaciones = '$posadasid'";  
    else $query.=" "; 
     
    //  Obtenemos los resultados
    $result = mysql_query($query); 
    $items = array(); 
    if($result && mysql_num_rows($result)>0) { 
        while($row = mysql_fetch_array($result)) { 
            $items[$row[0]] = htmlentities($row[1]); 
        }         
    } 
    mysql_close(); 
    $response = isset($_GET['callback'])?$_GET['callback']."(".json_encode($items).")":json_encode($items); 
    $cache->finish($response);  
?>