<!-- ==============================================
//  Created by PHP Dev Zone           			 ||
//	http://php-dev-zone.blogspot.com             ||
//  Contact for any Web Development Stuff        ||
//  Email: ketan32.patel@gmail.com     			 ||
//=============================================-->

<?php 
$state=intval($_GET['state']);
$selected=intval($_GET['selected']);
$con = mysql_connect('localhost', 'root', 'password'); 
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('dasamericas');
$query="SELECT city_id, city FROM city WHERE state_id='$state'";
$result=mysql_query($query);

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
