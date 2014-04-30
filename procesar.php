<?php

include_once 'lib/sessionLib.php';

if (isset($_POST['employee_id']) && $_POST['employee_id'] != ''){
	
 
	$employee_id = $_POST['employee_id'];

	$sql = "SELECT CONCAT(P.firstname, ' ', P.lastname) Funcionario ";
	$sql .= 'FROM employee E ';
	$sql .= 'LEFT JOIN profile P on P.profile_id = E.profile_id ';
	$sql .= 'WHERE E.employee_id = '.$employee_id;

	$result = resultFromQuery($sql); 

	if ($fila = mysql_fetch_object($result)) {
		echo $fila->Funcionario;
	}
	else{
		echo 'O codigo ingressado não corresponde a nenhum funcionario';
	}
} 
 
?>
