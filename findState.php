<?php 
include_once 'lib/dbUtils.php';

$country=intval($_GET['country']);
$selected=intval($_GET['selected']);

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

$query="SELECT state_id, state FROM state WHERE country_id='$country'";
$result=resultFromQuery($query);
?>
<select name="state" onchange="getCity(<?php echo $country?>,this.value)">
<option STYLE="display:none">Estado</option><option value="0">Outro Estado</option>
<?php while ($row=mysql_fetch_array($result)) { ?>
	
<option <?php 
if ($selected == $row['state_id']){
	echo 'selected="selected" ';
}

?>value=<?php echo $row['state_id']?>><?php echo $row['state']?></option>
<?php } ?>
</select>
