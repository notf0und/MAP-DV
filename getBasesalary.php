<?php 
include_once 'lib/dbUtils.php';

$jobcategory=intval($_GET['jobcategory']);


$query="SELECT basesalary FROM basesalary WHERE jobcategory_id='$jobcategory'";

$result=resultFromQuery($query);

while ($row=mysql_fetch_array($result)) {
	$basesalary = $row['basesalary'];
}

echo $basesalary;
?>
