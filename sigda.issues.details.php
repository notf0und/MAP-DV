<?php 
include_once "head.php"; 

for ($i = 0; $i < count($_SESSION['issues']); $i++){
	
	if ($_SESSION['issues'][$i]['number'] == $_GET['issue']){
		$issue = $_SESSION['issues'];
	}
	
}

for ($i = 0; $i < count($_SESSION['closed-issues']); $i++){
	
	if ($_SESSION['closed-issues'][$i]['number'] == $_GET['issue']){
		$issue = $_SESSION['closed-issues'];
	}
}


if (isset($issue) && $issue != ''){

	$issuedetails = '<div class="widget-box">';
	$issuedetails .= '<div class="widget-title bg_ly" data-toggle="collapse" href="#collapseG2"><span class="icon"><i class="icon-chevron-down"></i></span>';
	$issuedetails .= '<h5>Latest Posts</h5>';
	$issuedetails .= '</div>';
	$issuedetails .= '<div class="widget-content nopadding collapse in" id="collapseG2">';
	$issuedetails .= '<ul class="recent-posts">';

	for ($i = 0; $i < count($issue); $i++){
		
		if ($issue[$i]['number'] == $_GET['issue']){
			$title = $issue[$i]['title'];
			
			$issuedetails .= '<li>';
			$issuedetails .= '<div class="user-thumb"> <img width="40" height="40" alt="User" src="'.$issue[$i]['user']['avatar_url'].'"> </div>';
			$issuedetails .= '<div class="article-post"> <span class="user-info"> Por: '.$issue[$i]['user']['login'].' / Data: '.date('d-m-Y', strtotime($issue[$i]['created_at'])).' / Hora:'.date('H:i', strtotime($issue[$i]['created_at'])).' </span>';
			$issuedetails .= '<p>'.$issue[$i]['body'].'</p>';
			$issuedetails .= '</div>';
			$issuedetails .= '</li>';
			
			
			$comments = $client->api('issue')->comments()->all('notf0und', 'MAP-DV', $issue[$i]['number']);
			
			if ($comments){
				for ($i = 0; $i < count($comments); $i++){
					$issuedetails .= '<li>';
					$issuedetails .= '<div class="user-thumb"> <img width="40" height="40" alt="User" src="'.$comments[$i]['user']['avatar_url'].'"> </div>';
					$issuedetails .= '<div class="article-post"> <span class="user-info"> Por: '.$comments[$i]['user']['login'].' / Data: '.date('d-m-Y', strtotime($comments[$i]['created_at'])).' / Hora:'.date('H:i', strtotime($comments[$i]['created_at'])).' </span>';
					$issuedetails .= '<p>'.$comments[$i]['body'].'</p>';
					$issuedetails .= '</div>';
					$issuedetails .= '</li>';
				}
			}
		}
	}
	
	$issuedetails .= '</ul>';
	$issuedetails .= '</div>';
	$issuedetails .= '</div>';
}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> 
			<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
			<a href="sistema.php" title="Liquidaciones" class="tip-bottom">Sistema</a>
			<a href="sigda.issues.php" title="Questiões do Sistema" class="tip-bottom">Questiões</a>
			<a href="#" class="current">Detalhes</a>
		</div>
	</div>
<!--End-breadcrumbs-->
	<br/>
	<div class="row-fluid">
		<div class="container-fluid">
			<h4><?php echo $title ?></h4>
			<?php echo $issuedetails ?>
			
		</div>
		
	</div>

</div>
<!--end-main-container-part-->
<?php include "footer.php"; ?>
