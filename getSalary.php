<?php 

include_once "lib/sessionLib.php";

$employee_id = intval($_GET['employee_id']);

$salario = calcularSalario($employee_id, date('n'), date('Y'));
$salario = $salario['Total'];

echo $salario;
?>
