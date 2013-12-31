<?php 
include_once 'lib/dbUtils.php';

$jobcategory=intval($_GET['jobcategory']);


$query="SELECT basesalary_id FROM jobcategory WHERE jobcategory_id='$jobcategory'";

$result=resultFromQuery($query);

while ($row=mysql_fetch_array($result)) {
	$basesalary_id = $row['basesalary_id'];
}

$query="SELECT basesalary FROM basesalary WHERE basesalary_id='$basesalary_id'";

$result=resultFromQuery($query);

while ($row=mysql_fetch_array($result)) {
	echo $basesalary_id = $row['basesalary'];
}

?>
