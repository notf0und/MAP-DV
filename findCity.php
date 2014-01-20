<?php 
include_once 'lib/dbUtils.php';

$state=intval($_GET['state']);
$selected=intval($_GET['selected']);

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

$query="SELECT city_id, city FROM city WHERE state_id='$state'";
$result=resultFromQuery($query);

?>
<select name="city">
<option STYLE="display:none">Cidade</option><option value="0">Outra Cidade</option>
<?php while ($row=mysql_fetch_array($result)) { ?>
<option <?php 
if ($selected == $row['city_id']){
	echo "selected ";
}
?>value=<?php echo $row['city_id']?>><?php echo $row['city']?></option>
<?php } ?>
</select>
