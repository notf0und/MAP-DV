<!-- ==============================================
//  Created by PHP Dev Zone           			 ||
//	http://php-dev-zone.blogspot.com             ||
//  Contact for any Web Development Stuff        ||
//  Email: ketan32.patel@gmail.com     			 ||
//=============================================-->


<?php 
$country=intval($_GET['country']);
$selected=intval($_GET['selected']);
echo $selected;
$con = mysql_connect('localhost', 'root', 'password'); 
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('dasamericas');
$query="SELECT state_id, state FROM state WHERE country_id='$country'";
$result=mysql_query($query);
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
