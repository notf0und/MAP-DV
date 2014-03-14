<?php

include "head.php"; 
//SELECT
$sqlQuery = "SELECT ";
$sqlQuery .= "PAY.payment_id AS id, ";
$sqlQuery .= "date(PAY.date) Data, ";
$sqlQuery .= "CONCAT(P.firstname, ' ', P.lastname) 'Beneficiario', ";
$sqlQuery .= "PT.type Tipo, ";
$sqlQuery .= "PM.method Modo, ";
$sqlQuery .= "CONCAT('$', PAY.ammount) Monto ";

//FROM
$sqlQuery .= "FROM payment PAY ";

//Union de payment con employee
$sqlQuery .= "LEFT JOIN employee E ON PAY.employee_id = E.employee_id ";

//Union de employee con profile
$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";

//Union de payment con paymenttype
$sqlQuery .= "LEFT JOIN paymenttype PT ON PAY.paymenttype_id = PT.paymenttype_id ";

//Union de payment con paymentmethod
$sqlQuery .= "LEFT JOIN paymentmethod PM ON PAY.paymentmethod_id = PM.paymentmethod_id ";

$sqlQuery .= "WHERE enabled = 1 ";

$tablapagamentos = tableFromResult(resultFromQuery($sqlQuery), 'payment', true, true, 'posts.php', true);
?>	

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="pagamentos.php" title="Pagamentos" class="tip-bottom">Pagamentos</a>
		<a href="#" class="current">Lista de Pagamentos</a>
	</div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
	<div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Lista de Pagamentos</h5>
          </div>
          <div class="widget-content nopadding">
			  <form id="paymentForm" name="paymentForm" action="posts.php" method="post">
				  <!--Tabla-->
				  <?php echo $tablapagamentos;?>
				  <!--Tabla-->
				</form> 
					  
          </div>
			<div id="myModal" class="modal hide">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button">×</button>
					<h3>Detalle</h3>
				</div>
				<div class="modal-body" id="modal-body">
					<p>Here is the text coming you can put also image if you want…</p>
				</div>
			</div>
        </div>
		<form method="get" action="pagamentos.novo.php">
			<button class="btn btn-success" type="submit">Novo...</button>
		</form>      	
	</div>
    <hr/>
  </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
