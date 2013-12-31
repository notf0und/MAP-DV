<?php 
include "head.php";
$idlocales = $_SESSION["idlocales"];

if (isset($_SESSION['employee'])){
	$edit = true;
	$employee_id = $_SESSION['employee'];
	
	//SELECT
	$sqlQuery = "SELECT ";
	$sqlQuery .= "E.employee_id id, ";
	$sqlQuery .= "P.profile_id profile_id, ";
	$sqlQuery .= "P.firstname firstname, ";
	$sqlQuery .= "P.lastname, ";
	$sqlQuery .= "P.sex_id, ";
	$sqlQuery .= "P.birthdate, "; 
	$sqlQuery .= "PAIS.idpaises, ";
	$sqlQuery .= "S.state_id, ";
	$sqlQuery .= "P.birth_city_id, ";
	$sqlQuery .= "P.fathername, ";
	$sqlQuery .= "P.mothername, ";
	$sqlQuery .= "P.civilstatus, ";
	$sqlQuery .= "P.marriedname, ";
	$sqlQuery .= "P.education_id, ";
	$sqlQuery .= "A.address_id address_id, ";
	$sqlQuery .= "A.city_id, ";
	$sqlQuery .= "A.neightborhood, ";
	$sqlQuery .= "A.street, ";
	$sqlQuery .= "A.number, ";
	$sqlQuery .= "A.floor, ";
	$sqlQuery .= "A.apartment, ";
	$sqlQuery .= "PN.number phone1, ";
	
	//AQUI VA LA DOCUMENTACION
	$sqlQuery .= "WC.workingcard_id workingcard_id, ";
	$sqlQuery .= "WC.number carteiranumber, ";
	$sqlQuery .= "WC.serie carteiraserie, ";
	$sqlQuery .= "WC.expedition_date carteiradate, ";
	$sqlQuery .= "CPFC.cpfcard_id cpfcard_id, ";
	$sqlQuery .= "LPAD(CPFC.number, 14, 0) 'cpfnumber', ";
	$sqlQuery .= "VC.votingcard_id votingcard_id, ";
	$sqlQuery .= "RPAD(VC.number, 14, 0) eleitornumber, ";
	$sqlQuery .= "VC.zone eleitorzone, ";
	$sqlQuery .= "VC.section eleitorsection, ";
	$sqlQuery .= "VC.emissiondate eleitordate, ";
	$sqlQuery .= "RGC.rgcard_id rgcard_id, ";
	$sqlQuery .= "RGC.number idnumber, ";
	$sqlQuery .= "RGC.expeditor idexpeditor, ";
	$sqlQuery .= "RGC.date iddate, ";
	$sqlQuery .= "PISC.piscard_id piscard_id, ";
	$sqlQuery .= "PISC.number pisnumber, ";
	$sqlQuery .= "PISC.bank pisbanknumber, ";
	$sqlQuery .= "PISC.expeditiondate pisdate, ";
	$sqlQuery .= "MC.militarycard_id militarycard_id, ";
	$sqlQuery .= "MC.number milcertnumber, ";
	$sqlQuery .= "MC.serie milcertserie, ";
	$sqlQuery .= "MC.category milcertcategory, ";
	$sqlQuery .= "HC.habilitationcard_id habilitationcard_id, ";
	$sqlQuery .= "HC.number habilitationnumber, ";
	$sqlQuery .= "HC.category habilitationcategory, ";
	$sqlQuery .= "HC.expedition habilitationdate, ";
	$sqlQuery .= "HC.valid habilitationvaliddate, ";

	$sqlQuery .= "E.idempresa, ";
	$sqlQuery .= "E.jobcategory_id, ";
	$sqlQuery .= "E.admission, ";
	$sqlQuery .= "E.contract, ";
	$sqlQuery .= "BS.basesalary, ";
	$sqlQuery .= "E.bonussalary, ";
	$sqlQuery .= "E.fromhour, ";
	$sqlQuery .= "E.tohour, ";
	$sqlQuery .= "E.intervalhour, ";
	$sqlQuery .= "E.traject, ";
	$sqlQuery .= "E.transport, ";
	$sqlQuery .= "E.experiencecontract, ";
	$sqlQuery .= "E.unhealthy ";
	
	//FROM
	$sqlQuery .= "FROM employee E ";
	
	//Union de employee con jobcategory
	$sqlQuery .= "LEFT JOIN jobcategory JC ON E.jobcategory_id = JC.jobcategory_id ";
	
	//Union de jobcategory con base salary
	$sqlQuery .= "LEFT JOIN basesalary BS ON JC.basesalary_id = BS.basesalary_id ";

	//Union de employee con profile
	$sqlQuery .= "LEFT JOIN profile P ON E.profile_id = P.profile_id ";
	
	//Union de perfil con ciudad
	$sqlQuery .= "LEFT JOIN city C ON P.birth_city_id = C.city_id ";
	
	//Union de ciudad con estado
	$sqlQuery .= "LEFT JOIN state S ON C.state_id = S.state_id ";
	
	//Union de estado con pais
	$sqlQuery .= "LEFT JOIN paises PAIS ON S.country_id = PAIS.idpaises ";
	
	//Union de perfil con dirección
	$sqlQuery .= "LEFT JOIN address A ON P.address_id = A.address_id ";
	
	//Union de perfil con perfil_teléfono
	$sqlQuery .= "LEFT JOIN profile_phone PP ON P.profile_id = PP.profile_id ";
	
	//Union de perfil_telefono con telefono
	$sqlQuery .= "LEFT JOIN phone_number PN ON PP.phone_number_id = PN.phone_number_id ";
	
	//Union de perfil con workingcard (Carteira de trabalho)
	$sqlQuery .= "LEFT JOIN workingcard WC ON P.profile_id = WC.profile_id ";
	
	//Union de perfil con cpfcard (CPF)
	$sqlQuery .= "LEFT JOIN cpfcard CPFC ON P.profile_id = CPFC.profile_id ";
	
	//Union de perfil con votingcard (Titulo Eleitor)
	$sqlQuery .= "LEFT JOIN votingcard VC ON P.profile_id = VC.profile_id ";
	
	//Union de perfil con rgcard (Registro de identidade civil)
	$sqlQuery .= "LEFT JOIN rgcard RGC ON P.profile_id = RGC.profile_id ";
	
	//Union de perfil con piscard (Programa de integração social)
	$sqlQuery .= "LEFT JOIN piscard PISC ON P.profile_id = PISC.profile_id ";
	
	//Union de perfil con militarycard (Certificado Militar)
	$sqlQuery .= "LEFT JOIN militarycard MC ON P.profile_id = MC.profile_id ";
	
	//Union de perfil con habilitationcard (Carteira de habilitação)
	$sqlQuery .= "LEFT JOIN habilitationcard HC ON P.profile_id = HC.profile_id ";
	
	//AQUI VA LA DOCUMENTACION
	
	
	//WHERE
	$sqlQuery .= "WHERE E.employee_id = ".$employee_id;
	
	$resultadoStringSQL = resultFromQuery($sqlQuery);

	if ($row = siguienteResult($resultadoStringSQL)){
		$profile_id = $row->profile_id;
		$firstname = $row->firstname;
		$lastname = $row->lastname;
		$sex_id = $row->sex_id;
		$birthdate = $row->birthdate;
		$idpaises = $row->idpaises;
		$state_id = $row->state_id;
		$birth_city_id = $row->birth_city_id;
		$fathername = $row->fathername;
		$mothername = $row->mothername;
		$civilstatus = $row->civilstatus;
		$education_id = $row->education_id;
		$address_id = $row->address_id;
		$city_id = $row->city_id;
		$neightborhood = $row->neightborhood;
		$street = $row->street;
		$number = $row->number;
		$floor = $row->floor;
		$apartment = $row->apartment;
		$phone1 = $row->phone1;
		//PHONE 2
		$workingcard_id = $row->workingcard_id;
		$carteiranumber = $row->carteiranumber;
		$carteiraserie = $row->carteiraserie;
		$carteiradate = $row->carteiradate;
		$cpfcard_id = $row->cpfcard_id;
		$cpfnumber = $row->cpfnumber;
		echo $cpfnumber;
		$votingcard_id = $row->votingcard_id;
		$eleitornumber = $row->eleitornumber;
		$eleitorzone = $row->eleitorzone;
		$eleitorsection = $row->eleitorsection;
		$eleitordate = $row->eleitordate;
		$rgcard_id = $row->rgcard_id;
		$idnumber = $row->idnumber;
		$idexpeditor = $row->idexpeditor;
		$iddate = $row->iddate;
		$piscard_id = $row->piscard_id;
		$pisnumber = $row->pisnumber;
		$pisbanknumber = $row->pisbanknumber;
		$pisdate = $row->pisdate;
		$militarycard_id = $row->militarycard_id;
		$milcertnumber = $row->milcertnumber;
		$milcertserie = $row->milcertserie;
		$milcertcategory = $row->milcertcategory;
		$habilitationcard_id = $row->habilitationcard_id;
		$habilitationnumber = $row->habilitationnumber;
		$habilitationcategory = $row->habilitationcategory;
		$habilitationdate = $row->habilitationdate;
		$habilitationvaliddate = $row->habilitationvaliddate;
		$idempresa = $row->idempresa;
		$jobcategory_id = $row->jobcategory_id;
		$admission = $row->admission;
		$contract = $row->contract;
		$basesalary = $row->basesalary;
		$bonussalary = $row->bonussalary;
		$fromhour = $row->fromhour;
		$tohour = $row->tohour;
		$intervalhour = $row->intervalhour;
		$traject = $row->traject;
		$transport = $row->transport;
		$experiencecontract = $row->experiencecontract;
		$unhealthy = $row->unhealthy;
	}
	unset($_SESSION['employee']);
}

?>
<meta http-equiv="Cache-control" content="no-cache">
<meta http-equiv="Expires" content="-1">
<script language="javascript" type="text/javascript">
	
	function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e){
			try{
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		return xmlhttp;
	}
	
	function getState(countryId) {
		
		//Si se selecciona como pais a "Otro"
		if (countryId == 7){
				document.getElementById('modal-body').innerHTML='<object id=foo name=foo type=text/html width=530 height=350 data=newCountry.php></object>';
				$('#myModal').modal('show');
				
				$('#myModal').on('hidden.bs.modal', function () {
					  // do something…
					   location.reload();
				})
		}
		else{
			var strURL="findState.php?country="+countryId<?php 
				if (isset($state_id)){
					echo '+"&selected='.$state_id.'"';
				}
				?>;
			var req = getXMLHTTP();
		
			if (req) {			
				req.onreadystatechange = function() {
					if (req.readyState == 4) {
						// only if "OK"
						if (req.status == 200) {						
							document.getElementById('state_id').innerHTML=req.responseText;						
						} 
						else {
							alert("Problem while using XMLHTTP:\n" + req.statusText);
						}
					}				
				}			
				req.open("GET", strURL, true);
				req.send(null);
			}		
		}
	}
	
	function getCity(stateId) {
			
		if (stateId == 0){
			var e = document.getElementById("idpaises");
			var str = e.options[e.selectedIndex].value;
			
			document.getElementById('modal-body').innerHTML='<object id=foo name=foo type=text/html width=530 height=350 data=newState.php?country='+str+'></object>';
			
			$('#myModal').modal('show');
			
			$('#myModal').on('hidden.bs.modal', function () {
				 // do something…
				 var strURL="findState.php?country="+str;
				 var req = getXMLHTTP();
				 
				 if (req) {
					 req.onreadystatechange = function() {
						 if (req.readyState == 4) {
							 // only if "OK"
							if (req.status == 200) {
								document.getElementById('state_id').innerHTML=req.responseText;						
							} else {
								alert("Problem while using XMLHTTP:\n" + req.statusText);
							}
						}				
					}			
				req.open("GET", strURL, true);
				req.send(null);
				}
			})
		}
		
		var strURL="findCity.php?state="+stateId<?php 
				if (isset($birth_city_id)){
					echo '+"&selected='.$birth_city_id.'"';
					
				}
				?>;
		
		
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('birth_city_id').innerHTML=req.responseText;					
					} else {
						alert("Problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
		
				
	}
	
	function setCity(cityId) {
		
		if (cityId == 0){

			var e = document.getElementById("idpaises");
			var country = e.options[e.selectedIndex].value;
			
			var e = document.getElementById("state_id");
			var state = e.options[e.selectedIndex].value;

			document.getElementById('modal-body').innerHTML='<object id=foo name=foo type=text/html width=530 height=350 data=newCity.php?country='+country+'&state='+state+'></object>';
				$('#myModal').modal('show');
				
				$('#myModal').on('hidden.bs.modal', function () {
					  // do something…
					   location.reload();
				})
		}
	}
	
	function getBasesalary(jobcategory_id) {
		
		if (jobcategory_id == 0){
			document.getElementById('modal-body').innerHTML='<object id=foo name=foo type=text/html width=650 height=350 data=newJobcategory.php></object>'
			
			$('#myModal').modal('show');
			
			
		}
		else{
			
			var strURL="getBasesalary.php?jobcategory="+jobcategory_id;
			var req = getXMLHTTP();
			
			if (req) {
				req.onreadystatechange = function() {
					if (req.readyState == 4) {
						// only if "OK"
						if (req.status == 200) {
							document.getElementById('basesalary').value	= req.responseText;
						} 
						else {
							alert("Problem while using XMLHTTP:\n" + req.statusText);
						}
					}				
				}			
				req.open("GET", strURL, true);
				req.send(null);
			}
		}
	}
	
	window.onload=getState(<?php echo isset($idpaises) ? $idpaises : '';?>);
	window.onload=getCity(<?php echo isset($state_id) ? $state_id : '';?>);
	//location.reload(); 
</script>

<!--Start page content-->
<div id="content">
	
	<!--Start breadcrumbs-->
	<div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="#" class="current">Registro de Funcionarios</a>
	</div>
  </div>
	<!--End-breadcrumbs-->
	
	<!--Start container-->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"> <i class="icon-pencil"></i> </span>
            <h5>Registro de funcionario</h5>
          </div>
					<div class="widget-content nopadding">
						<!--Start form-->
						<form action="posts.php" id="form-wizard" name="form-wizard" class="form-horizontal" method="post">
							<input type="hidden" id="accion" name="accion" value="admitirEmpleado" />
							<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '';?>" />
							<input type="hidden" id="edit" name="edit" value="<?php echo isset($edit) ? $edit : '';?>" />
							
							<!--Start modal-->
							<div id="myModal" class="modal hide">
								<div class="modal-header">
									<button data-dismiss="modal" class="close" type="button">×</button>
									<h3>Detalle</h3>
								</div>
								
								<div class="modal-body" id="modal-body">
									<p>Here is the text coming you can put also image if you want…</p>
								</div>
								
							</div>
							<!--End modal-->
							
							<!--Start step 1-->
							<div id="form-wizard-1" class="step">
								
							<!--Profile-->
							<input type="hidden" id="profile_id" name="profile_id" value="<?php echo isset($profile_id) ? $profile_id : '';?>" />
							<!--Title-->
							<div class="control-group">
								<label class="control-label"><h5>Dados pessoales</h5></label>
							</div>
							
							<!--Full Name-->
							<div class="control-group">
							  <label class="control-label">Nome completo</label>
							  <div class="controls">
								<input required id="profile_firstname" type="text" name="profile_firstname" placeholder="Nome" class="span4 m-wrap" value="<?php echo isset($firstname) ? $firstname : ''?>"/>
								<br>
								<input id="profile_lastname" type="text" name="profile_lastname" placeholder="Sobrenome" class="span4 m-wrap" value="<?php echo isset($lastname) ? $lastname : ''?>"/>
							  </div>
							</div>
							
							<!--Sex-->
							<div class="control-group">
								<label class="control-label">Sexo</label>
								<div class="controls">
									<?php
										$sqlQuery = " SELECT sex_id, sex FROM sex";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('sex_id', $resultado, isset($sex_id) ? $sex_id : '', '', '', false, 'span4 m-wrap', '');
									?>
								</div>
							</div>
							
							<!--Birth Date-->
							<div class="control-group">
								<label class="control-label">Data de nascimento</label>
								<div class="controls">
									<div data-date="" class="input-append date datepicker">
										<input id="birthdate" name="birthdate" type="text" data-date-format="yyyy-mm-dd" placeholder="AAAA-MM-DD" value="<?php echo isset($birthdate) ? $birthdate : '' ;?>">
										<span class="add-on"><i class="icon-th"></i></span>
									</div>
								</div>
							</div>
							
							<!--Birth Place-->
							<div class="control-group">
								<label class="control-label">Lugar de nascimento</label>
								
								<!--Country-->
								<div class="controls">
									<?php
										$sqlQuery = " SELECT idpaises, nombre FROM paises";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('idpaises', $resultado, isset($idpaises) ? $idpaises : '', 'getState(this.value)', '', false, 'span4 m-wrap', 'País');
									?>
								</div><br>
								
								<!--State-->
								<div class="controls">
									<SELECT ID="state_id" NAME="state" SIZE="1" onchange="getCity(this.value)" class="span4 m-wrap"><OPTION VALUE="0">Estado</OPTION></SELECT>
								</div><br>
								
								<!--City-->
								<div class="controls">
									<SELECT ID="birth_city_id" NAME="birth_city_id" SIZE="1" onchange="setCity(this.value)" class="span4 m-wrap"><OPTION VALUE="0">Cidade</OPTION></SELECT>
								</div>
								
								
							</div>

							
							<!--Father/Mother Name-->
							<div class="control-group">
							  <label class="control-label">Filiaçao</label>
							  <div class="controls">
								<input id="fathername" type="text" name="fathername" placeholder="Nome do pai" class="span4 m-wrap" value="<?php echo isset($fathername) ? $fathername : '';?>"/>
								<br>
								<input id="mothername" type="text" name="mothername" placeholder="Nome da mai" class="span4 m-wrap" value="<?php echo isset($mothername) ? $mothername : '';?>"/>
							  </div>
							</div>
							
							<!--Civil status-->
							<div class="control-group">
								<label class="control-label">Estado civil</label>
								<div class="controls">
									<SELECT ID="civilstatus" NAME="civilstatus" SIZE="1" class="span4 m-wrap">
										<OPTION VALUE="0">Solteiro</OPTION>
										<OPTION VALUE="1">Casado</OPTION>
									</SELECT>
								</div>
							</div>
							
							<!--Married Name-->
							<div class="control-group">
							  <label class="control-label">Nome cônjuge</label>
							  <div class="controls">
								<input id="marriedname" type="text" name="marriedname" placeholder="Nome completo" class="span4 m-wrap" value="<?php echo isset($marriedname) ? $marriedname : '';?>"/>
							  </div>
							</div>
										
							<!--Education-->
							<div class="control-group">
								<label class="control-label">Grau de escolaridade</label>
								<div class="controls">
									<?php
										$sqlQuery = " SELECT education_id, education FROM education";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('education_id', $resultado, isset($education_id) ? $education_id : '', '', '', false, 'span4 m-wrap', '');
									?>
								</div>
							</div>
							
							<!--Address-->
							<input type="hidden" id="address_id" name="address_id" value="<?php echo isset($address_id) ? $address_id : '';?>" />
							<div class="control-group">
							  <label class="control-label">Residencia</label>
							  <div class="controls">
								  
								<!--City-->
								<?php
									$sqlQuery = " SELECT city_id, city FROM city WHERE state_id = 19";
									$resultado = resultFromQuery($sqlQuery);
									echo comboFromArray('city_id', $resultado, isset($city_id) ? $city_id : '', '', '', false, 'span4 m-wrap', 'Cidade');
								?><br><br>
								
								<input id="neightborhood" type="text" name="neightborhood" placeholder="Bairro" class="span4 m-wrap" value="<?php echo isset($neightborhood) ? $neightborhood : '';?>"><br>
								
								<input id="street" type="text" name="address" placeholder="Endereço" class="span4 m-wrap" value="<?php echo isset($street) ? $street : '';?>"><br>
								
								<input id="addressnumber" type="text" name="addressnumber" placeholder="Numero" class="span4 m-wrap" value="<?php echo isset($number) ? $number : '';?>"><br>
								
								<input id="addressfloor" type="text" name="addressfloor" placeholder="Piso" class="span4 m-wrap" value="<?php echo isset($floor) ? $floor : '';?>"><br>
								
								<input id="addressapartment" type="text" name="addressapartment" placeholder="Apartamento" class="span4 m-wrap" value="<?php echo isset($apartment) ? $apartment : '';?>">
								
							  </div>
							</div>
							
							<!--Phone 1 & 2-->
							<div class="control-group">
								<label for="normal" class="control-label">Telefone</label>
								<div class="controls">
									<input type="text" id="mask-phone" name="phone1" placeholder="Telefone 1" class="span4 mask text" value="<?php echo isset($phone1) ? $phone1 : '';?>">
									<br>
									<!--FALTA OBTENER PHONE 2! -->
									<input type="text" id="mask-phone2" name="phone2" placeholder="Telefone 2" class="span4 mask text"  value="<?php echo isset($phone2) ? $phone2 : '';?>">
								</div>
							</div>
							
						  </div>
							<!--End step 1-->
						  
							<!--Start step 2-->
							<div id="form-wizard-2" class="step">

								<!--Title-->
								<div class="control-group">
									<label class="control-label"><h5>Documentação</h5></label>
								</div>

								<!--Carteira-->  
								<input type="hidden" id="workingcard_id" name="workingcard_id" value="<?php echo isset($workingcard_id) ? $workingcard_id : '';?>" />
								<div class="control-group">
							  <label class="control-label">Carteira de trabalho</label>
							  <div class="controls">
								  
								  <!--Number-->  
								  <input type="text" id="carteiranumber" name="carteiranumber" placeholder="Número" class="span4 mask text" value="<?php echo isset($carteiranumber) ? $carteiranumber : '';?>"><br>
								  
								  <!--Serie-->
								  <input type="text" id="carteiraserie" name="carteiraserie" placeholder="Serie" class="span4 mask text" value="<?php echo isset($carteiraserie) ? $carteiraserie : '';?>"><br>
					
								  <!--Date-->
								  <div data-date=""  class="input-append date datepicker">
									  <input id="carteiradate" name="carteiradate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de expedição" value="<?php echo isset($carteiradate) ? $carteiradate : '';?>">
									  <span class="add-on"><i class="icon-th"></i></span>
								  </div>
								  
							  </div>
							</div>

								<!--CPF--> 
								<input type="hidden" id="cpfcard_id" name="cpfcard_id" value="<?php echo isset($cpfcard_id) ? $cpfcard_id : '';?>" />
								<div class="control-group">
								  <label class="control-label">CPF</label>
								  <div class="controls">
									  <!--Number-->  
									  <input type="text" id="cpfnumber" name="cpfnumber" placeholder="Número" class="span4 mask text" value="<?php echo isset($cpfnumber) ? $cpfnumber : '';?>"><br>
									  
								  </div>
								</div>

								<!--Eleitor Title--> 
								<input type="hidden" id="votingcard_id" name="votingcard_id" value="<?php echo isset($votingcard_id) ? $votingcard_id : '';?>" />
								<div class="control-group">
								  <label class="control-label">Titulo Eleitoral</label>
								  <div class="controls">
									  
									  <!--Number-->  
									  <input type="text" id="eleitornumber" name="eleitornumber" placeholder="Número de Inscrição" class="span4 mask text" value="<?php echo isset($eleitornumber) ? $eleitornumber : '';?>"><br>
									  
									  <!--Zone-->  
									  <input type="text" id="eleitorzone" name="eleitorzone" placeholder="Zona" class="span4 mask text" value="<?php echo isset($eleitorzone) ? $eleitorzone : '';?>"><br>
									  
									  <!--Section-->  
									  <input type="text" id="eleitorsection" name="eleitorsection" placeholder="Seção" class="span4 mask text" value="<?php echo isset($eleitorsection) ? $eleitorsection : '' ;?>"><br>
									  
									  <!--Date-->
									  <div data-date=""  class="input-append date datepicker">
										  <input id="eleitordate" name="eleitordate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de emissão" value="<?php echo isset($eleitordate) ? $eleitordate : '';?>">
										  <span class="add-on"><i class="icon-th"></i></span>
									  </div>
										
								  </div>
								</div>

								<!--Identification Register--> 
								<input type="hidden" id="rgcard_id" name="rgcard_id" value="<?php echo isset($rgcard_id) ? $rgcard_id : '';?>" />
								<div class="control-group">
								  <label class="control-label">Registro de identidade civil (RG)</label>
								  <div class="controls">
									  
									  <!--Number-->  
									  <input type="text" id="idnumber" name="idnumber" placeholder="Número" class="span4 mask text" value="<?php echo isset($idnumber) ? $idnumber : '';?>"><br>
									  
									  <!--Expeditor-->  
									  <input type="text" id="idexpeditor" name="idexpeditor" placeholder="Órgão expedidor" class="span4 mask text" value="<?php echo isset($idexpeditor) ? $idexpeditor : '';?>"><br>
									  
									  <!--Date-->
									  <div data-date=""  class="input-append date datepicker">
										  <input id="iddate" name="iddate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de expedição" value="<?php echo isset($iddate) ? $iddate : '';?>">
										  <span class="add-on"><i class="icon-th"></i></span>
									  </div>
										
								  </div>
								</div>

								<!--PIS--> 
								<input type="hidden" id="piscard_id" name="piscard_id" value="<?php echo isset($piscard_id) ? $piscard_id : '';?>" />
								<div class="control-group">
								  <label class="control-label">Programa de Integração Social - PIS</label>
								  <div class="controls">
									  
									  <!--Number-->  
									  <input type="text" id="pisnumber" name="pisnumber" placeholder="Número" class="span4 mask text" value="<?php echo isset($pisnumber) ? $pisnumber : '';?>"><br>
									  
									  <!--Bank-->  
									  <input type="text" id="pisbanknumber" name="pisbanknumber" placeholder="Nũmero do banco" class="span4 mask text" value="<?php echo isset($pisbanknumber) ? $pisbanknumber : '';?>"><br>
									  
									  <!--Date-->
									  <div data-date=""  class="input-append date datepicker">
										  <input id="pisdate" name="pisdate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de expedição" value="<?php echo isset($pisdate) ? $pisdate : '';?>">
										  <span class="add-on"><i class="icon-th"></i></span>
									  </div>
										
								  </div>
								</div>

								<!--Militar Certificate-->  
								<input type="hidden" id="militarycard_id" name="militarycard_id" value="<?php echo isset($militarycard_id) ? $militarycard_id : '';?>" />
								<div class="control-group">
								  <label class="control-label">Certificado militar</label>
								  <div class="controls">
									  
									  <!--Number-->  
									  <input type="text" id="milcertnumber" name="milcertnumber" placeholder="Número" class="span4 mask text" value="<?php echo isset($milcertnumber) ? $milcertnumber : '';?>"><br>
									  
									  <!--Serie-->  
									  <input type="text" id="milcertserie" name="milcertserie" placeholder="Série" class="span4 mask text" value="<?php echo isset($milcertserie) ? $milcertserie : '';?>"><br>
									  
									  <!--Category-->  
									  <input type="text" id="milcertcategory" name="milcertcategory" placeholder="Categoria" class="span4 mask text" value="<?php echo isset($milcertcategory) ? $milcertcategory : '';?>"><br>

								  </div>
								</div>

								<!--Habilitation Card-->
								<input type="hidden" id="habilitationcard_id" name="habilitationcard_id" value="<?php echo isset($habilitationcard_id) ? $habilitationcard_id : '';?>" />
								<div class="control-group">
									<label class="control-label">Carteira de habilitação</label>
									<div class="controls">
										<!--Number-->  
										<input type="text" id="habilitationnumber" name="habilitationnumber" placeholder="Número" class="span4 mask text" value="<?php echo isset($habilitationnumber) ? $habilitationnumber : '';?>"><br>
								  
										<!--Category-->  
										<input type="text" id="habilitationcategory" name="habilitationcategory" placeholder="Categoria" class="span4 mask text" value="<?php echo isset($habilitationcategory) ? $habilitationcategory : '';?>"><br>
								  
										<!--Expedition Date-->
										<div data-date=""  class="input-append date datepicker">
									  <input id="habilitationdate" name="habilitationdate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de expedição" value="<?php echo isset($habilitationdate) ? $habilitationdate : '';?>">
									  <span class="add-on"><i class="icon-th"></i></span>
								  </div><br>
								  
										<!--Expedition Date-->
										<div data-date=""  class="input-append date datepicker">
											<input id="habilitationvaliddate" name="habilitationvaliddate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de validade" value="<?php echo isset($habilitationvaliddate) ? $habilitationvaliddate : '';?>">
											<span class="add-on"><i class="icon-th"></i></span>
										</div>
									</div>
								</div>

							</div>
							<!--End step 2-->
						  
							<!--Start step 3-->
							<div id="form-wizard-3" class="step">
								
								<!--Contract-->
								<div class="control-group">
									<label class="control-label"><h5>Contratação</h5></label>
								</div>
							
								<!--Company-->  
								<div class="control-group">
									<label class="control-label">Empresa</label>
									<div class="controls">
								  <!--Contract Company-->
								  <?php
										$sqlQuery = " SELECT idempresa, nombre FROM empresa ";
										$sqlQuery .= " WHERE usointerno = 1";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('idempresa', $resultado, isset($idempresa) ? $idempresa : '', '', '', false, 'span4 m-wrap', '');
									?>
									<br><br>
														  
								  <!--Admission Date-->
								  <div data-date="" class="input-append date datepicker">
										<input id="admissiondate" name="admissiondate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de admissão" value="<?php echo isset($admission) ? $admission : '' ?>">
										<span class="add-on"><i class="icon-th"></i></span>
								  </div><br>
								  
								  <!--Contract Date-->
								  <div data-date="" class="input-append date datepicker">
										<input id="contractdate" name="contractdate" type="text" data-date-format="yyyy-mm-dd" placeholder="Data de contrataçao" value="<?php echo isset($contract) ? $contract : '' ?>">
										<span class="add-on"><i class="icon-th"></i></span>
								  </div>
									
							  </div>
								</div>
							
								<!--Function-->  
								<div class="control-group">
									<label class="control-label">Função</label>
									<div class="controls">
								  <?php
										$sqlQuery = " SELECT jobcategory_id, name FROM jobcategory ";
										$resultado = resultFromQuery($sqlQuery);
										echo comboFromArray('jobcategory_id', $resultado, isset($jobcategory_id) ? $jobcategory_id : '', 'getBasesalary(this.value)', '', false, 'span4 m-wrap');
									?>
							  </div>
								</div>
							
								<!--Salary-->                
								<div class="control-group">
									
									<label class="control-label">Salario</label>
									<div class="controls">
										
										<!--Base Salary-->
										<div class="input-prepend"> <span class="add-on">R$</span>
											<input id="basesalary" type="text" placeholder="Básico" class="span4 m-wrap" readonly value="<?php echo isset($basesalary) ? $basesalary : '' ?>">
										</div><br>
								
										<!--Bonus Salary-->
										<div class="input-prepend"> <span class="add-on">R$</span>
										  <!--Si el usuario esta autorizado coloca el campo como visible y si contiene algun valor -->
										  <input id="bonussalary" name="bonussalary" placeholder="Abono" class="span4 m-wrap" <?php echo $_SESSION["idusuarios_tipos"] == 1 || $_SESSION["idusuarios_tipos"] == 4 ? 'type="number"'.(isset($bonussalary) ? ' value="'.$bonussalary.'"' : '') : ' type="hidden"' ?>>
										</div><br>

										<label><input type="checkbox" name="unhealthy" id="unhealthy" <?php echo isset($unhealthy) && $unhealthy != 0 ? 'checked' : ''?>/>Insalubre</label>

									</div>
								</div>
							
								<!--Work Hours-->  
								<div class="control-group">
									<label class="control-label">Horario</label>
									<div class="controls">
								  
								  <!--From-->  
								  <input type="text" id="mask-fromhours" name="fromhour" placeholder="Das horas" class="span4 mask text" value="<?php echo isset($fromhour) ? $fromhour : '' ?>"><br>
								  
								  <!--From-->  
								  <input type="text" id="mask-tohours" name="tohour" placeholder="Até as horas" class="span4 mask text" value="<?php echo isset($tohour) ? $tohour : '' ?>"><br>
								  
								  <!--Interval-->  
								  <input type="text" id="mask-intervalhours" name="intervalhour" placeholder="Horas de intervalo" class="span4 mask text" value="<?php echo isset($intervalhour) ? ltrim($intervalhour, 0) : '' ?>"><br>
									
							  </div>
								</div>
							
								<!--Transport-->  
								<div class="control-group">
									<label class="control-label">Transporte</label>
									<div class="controls">
								  
								  <!--Has transport-->
								  <SELECT ID="transport" NAME="transport" SIZE="1" onchange="" STYLE="" class="span4 m-wrap">
									  <OPTION VALUE="0">Não</OPTION>
									  <OPTION <?php echo isset($transport) ? 'selected' : '' ?> VALUE="1">Sim</OPTION>
								  </SELECT><br><br>
								  
								  <!--Route-->  
								  <input type="text" id="traject" name="traject" placeholder="Trajeto" class="span4 mask text" value="<?php echo isset($traject) ? $traject : '' ?>"><br>
								  
								  <!--Value-->
								  <div class="input-prepend"> <span class="add-on">R$</span>
									<input id="transportvalue" name="transportvalue" type="text" placeholder="Valor da passagem" class="sspan4 m-wrap" value="<?php echo isset($transport) ? $transport : '' ?>">
								  </div>
								  
								  
							  </div>
								</div>
							
								<!--Contract Type-->  
								<div class="control-group">
									<label class="control-label">Contrato de experiência</label>
									<div class="controls">
								  
								  <!--Has experience contract-->  
								  <SELECT ID="experiencecontract" NAME="experiencecontract" SIZE="1" onchange="" STYLE="" class="span4 m-wrap">
									  <OPTION VALUE="0">Não</OPTION>
									  <OPTION VALUE="1">30 días</OPTION>
									  <OPTION VALUE="2">45 días</OPTION>
									  <OPTION VALUE="3">60 días</OPTION>
									  <OPTION VALUE="4">90 días</OPTION>
								  </SELECT><br><br>
								  
			  
								  
							  </div>
								</div>

							</div>
							<!--End step 3-->
						  
							<div class="form-actions">
							<input id="back" class="btn btn-primary" type="reset" value="Back" />
							<input id="next" class="btn btn-primary" type="submit" value="Next" />
							<div id="status"></div>
						  </div>
							<div id="submitted"></div>
						</form>
						<!--End form-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--End container-->

</div>
<!--End page content-->


<!--end-main-container-part-->

<?php include "footer.php"; ?>

