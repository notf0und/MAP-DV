<?php 
include_once "head.php"; 

//$client->authenticate('notf0und', '******', 'Github\Client::AUTH_HTTP_TOKEN');

//if (!isset($_SESSION['closed-issues'])){
	$_SESSION['closed-issues'] = $client->api('issue')->all('notf0und', 'MAP-DV', array('state' => 'closed'));
	
//}
$issueslist = '<div class="widget-box">';
$issueslist .= '<div class="widget-title"> <span class="icon"><i class="icon-question-sign"></i></span>';
$issueslist .= '<h5>Questiões abertas</h5>';
$issueslist .= '</div>';
$issueslist .= '<div class="widget-content nopadding">';
$issueslist .= '<ul class="activity-list">';

for ($i = 0; $i < count($_SESSION['closed-issues']); $i++){
	
	$issueslist .= '<li><a onclick="form.submit();" href="sigda.issues.details.php?issue='.$_SESSION['closed-issues'][$i]['number'].'">';
	$issueslist .= '<i class="icon-github">';
	$issueslist .= '</i> <strong>';
	$issueslist .= $_SESSION['closed-issues'][$i]['title'];
	$issueslist .= ' </strong>';
	
	if ($_SESSION['closed-issues'][$i]['labels']){
		
		for ($j = 0; $j < count($_SESSION['closed-issues'][$i]['labels']); $j++){
		
			$issueslist .= '<span class="color" style="background-color: #';
			$issueslist .= $_SESSION['closed-issues'][$i]['labels'][$j]["color"];
			$issueslist .= '"> <font color="black">';
			
			if ($_SESSION['closed-issues'][$i]['labels'][$j]["name"] == 'bug'){
			$issueslist .= 'defeito';
			}
			elseif ($_SESSION['closed-issues'][$i]['labels'][$j]["name"] == 'enhancement'){
				$issueslist .= 'melhoria';
			}
			else{
				$issueslist .= $_SESSION['closed-issues'][$i]['labels'][$j]["name"];
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
			<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Início</a> 
			<a href="sigda.php" title="Sistema" class="tip-bottom">Sistema</a>
			<a href="sigda.issues.php" title="Questões" class="tip-bottom">Questões</a>
			<a href="#" class="current">Fechadas</a>
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
