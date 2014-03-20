<?php 
include_once "head.php"; 

$_SESSION['issues'] = $client->api('issue')->all('notf0und', 'MAP-DV', array('state' => 'open'));


$issueslist = '<div class="widget-box">';
$issueslist .= '<div class="widget-title"> <span class="icon"><i class="icon-question-sign"></i></span>';
$issueslist .= '<h5>Questiões abertas</h5>';
$issueslist .= '</div>';
$issueslist .= '<div class="widget-content nopadding">';
$issueslist .= '<ul class="activity-list">';

for ($i = 0; $i < count($_SESSION['issues']); $i++){
	
	$issueslist .= '<li><a onclick="form.submit();" href="sigda.issues.details.php?issue='.$_SESSION['issues'][$i]['number'].'">';
	$issueslist .= '<i class="icon-github">';
	$issueslist .= '</i> <strong>';
	$issueslist .= $_SESSION['issues'][$i]['title'];
	$issueslist .= ' </strong>';


	if ($_SESSION['issues'][$i]['labels']){
		
		
		for ($j = 0; $j < count($_SESSION['issues'][$i]['labels']); $j++){
		
			$issueslist .= '<span class="color" style="background-color: #';
			$issueslist .= $_SESSION['issues'][$i]['labels'][$j]["color"];
			$issueslist .= '"> <font color="black">';
			
			if ($_SESSION['issues'][$i]['labels'][$j]["name"] == 'bug'){
			$issueslist .= 'erro';
			}
			elseif ($_SESSION['issues'][$i]['labels'][$j]["name"] == 'enhancement'){
				$issueslist .= 'melhoria';
			}
			else{
				$issueslist .= $_SESSION['issues'][$i]['labels'][$j]["name"];
			}

			$issueslist .= '</font>  </span>';
		}
	}
	$issueslist .= '</a></li>';

}

$issueslist .= '</ul>';
$issueslist .= '</div>';
$issueslist .= '</div>';



?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> 
			<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
			<a href="sigda.php" title="Sistema" class="tip-bottom">Sistema</a>
			<a href="#" class="current">Questiões Abertas</a>
		</div>
	</div>
<!--End-breadcrumbs-->
	<br/>
	<div class="row-fluid">
		<div class="container-fluid">
			<h4>Questiões do Sistema</h4>
				<?php echo $issueslist ?>


			


		</div>
		
	</div>

</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
