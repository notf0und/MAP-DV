<?php 
include "head.php";
$idlocales = $_SESSION["idlocales"];

if ($_SESSION['employee']){
	$employee_id = $_SESSION['employee'];
	
	//SELECT
	$sqlQuery = "SELECT ";
	$sqlQuery .= "E.employee_id id, ";
	$sqlQuery .= "P.firstname firstname, ";
	$sqlQuery .= "P.lastname, ";
	$sqlQuery .= "P.sex_id, ";
	$sqlQuery .= "P.birthdate, ";
	$sqlQuery .= "PAIS.idpaises, ";
	$sqlQuery .= "S.state_id, ";
	$sqlQuery .= "C.city_id, ";
	$sqlQuery .= "P.fathername, ";
	$sqlQuery .= "P.mothername, ";
	$sqlQuery .= "P.civilstatus, ";
	$sqlQuery .= "P.marriedname, ";
	$sqlQuery .= "P.education_id, ";
	$sqlQuery .= "A.city_id, ";
	$sqlQuery .= "A.neightborhood, ";
	$sqlQuery .= "A.street, ";
	$sqlQuery .= "A.number, ";
	$sqlQuery .= "A.floor, ";
	$sqlQuery .= "A.apartment ";
	
	//FROM
	$sqlQuery .= "FROM employee E ";

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
	
	//WHERE
	$sqlQuery .= "WHERE E.employee_id = ".$employee_id;
	
	$resultadoStringSQL = resultFromQuery($sqlQuery);

	if ($row = siguienteResult($resultadoStringSQL)){
		$firstname = $row->firstname;
		$lastname = $row->lastname;
		$sex_id = $row->sex_id;
		$birthdate = $row->birthdate;
		$idpaises = $row->idpaises;
		$state_id = $row->state_id;
		$birth_city_id = $row->city_id;
		$fathername = $row->fathername;
		$mothername = $row->mothername;
		$civilstatus = $row->civilstatus;
		$education_id = $row->education_id;
		$city_id = $row->city_id;
		$neightborhood = $row->neightborhood;
		$street = $row->street;
		$number = $row->number;
		$floor = $row->floor;
		$apartment = $row->apartment;
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
				if ($state_id){
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
				if ($birth_city_id){
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
	
	window.onload=getState(<?php echo $idpaises;?>);
	window.onload=getCity(<?php echo $state_id;?>);
	//location.reload(); 
</script>
<div id="content">
	
  <!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="index.php" title="Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
		<a href="salarios.php" title="Área Contable" class="tip-bottom">Área Contable</a>
		<a href="funcionarios.php" title="Funcionarios" class="tip-bottom">Funcionarios</a>
		<a href="#" class="current">Registro de Funcionarios</a>
	</div>
  </div>
<!--End-breadcrumbs-->
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
				<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id;?>" />
				
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
				
				<!--Title-->
				<div class="control-group">
					<label class="control-label"><h5>Dados pessoales</h5></label>
                </div>
                
                <!--Full Name-->
                <div class="control-group">
				  <label class="control-label">Nome completo</label>
                  <div class="controls">
                    <input required id="profile_firstname" type="text" name="profile_firstname" placeholder="Nome" class="span4 m-wrap" value="<?php echo $firstname;?>"/>
                    <br>
                    <input id="profile_lastname" type="text" name="profile_lastname" placeholder="Sobrenome" class="span4 m-wrap" value="<?php echo $lastname;?>"/>
                  </div>
                </div>
                
                <!--Sex-->
                <div class="control-group">
					<label class="control-label">Sexo</label>
					<div class="controls">
						<?php
							$sqlQuery = " SELECT sex_id, sex FROM sex";
							$resultado = resultFromQuery($sqlQuery);
							echo comboFromArray('sex_id', $resultado, $sex_id, '', '', false, 'span4 m-wrap', '');
						?>
					</div>
				</div>
                
                <!--Birth Date-->
                <div class="control-group">
					<label class="control-label">Data de nascimento</label>
					<div class="controls">
						<div data-date="" class="input-append date datepicker">
							<input id="birthdate" name="birthdate" type="text" data-date-format="yyyy-mm-dd" placeholder="AAAA-MM-DD" value="<?php echo $birthdate;?>">
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
							echo comboFromArray('idpaises', $resultado, $idpaises, 'getState(this.value)', '', false, 'span4 m-wrap', 'País');
						?>
					</div><br>
					
					<!--State-->
					<div class="controls">
						<SELECT ID="state_id" NAME="state" SIZE="1" onchange="getCity(this.value)" class="span4 m-wrap"><OPTION VALUE="0">Estado</OPTION></SELECT>
					</div><br>
					
					<!--City-->
					<div class="controls">
						<SELECT ID="birth_city_id" NAME="city" SIZE="1" onchange="setCity(this.value)" class="span4 m-wrap"><OPTION VALUE="0">Cidade</OPTION></SELECT>
					</div>
					
					
				</div>

				
				<!--Father/Mother Name-->
                <div class="control-group">
                  <label class="control-label">Filiaçao</label>
                  <div class="controls">
                    <input id="fathername" type="text" name="fathername" placeholder="Nome do pai" class="span4 m-wrap" value="<?php echo $fathername;?>"/>
                    <br>
                    <input id="mothername" type="text" name="mothername" placeholder="Nome da mai" class="span4 m-wrap" value="<?php echo $mothername;?>"/>
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
                    <input id="marriedname" type="text" name="marriedname" placeholder="Nome completo" class="span4 m-wrap" value="<?php echo $marriedname;?>"/>
                  </div>
                </div>
							
                <!--Education-->
                <div class="control-group">
					<label class="control-label">Grau de escolaridade</label>
					<div class="controls">
						<?php
							$sqlQuery = " SELECT education_id, education FROM education";
							$resultado = resultFromQuery($sqlQuery);
							echo comboFromArray('education_id', $resultado, $education_id, '', '', false, 'span4 m-wrap', '');
						?>
					</div>
				</div>
				
				<!--Address-->
                <div class="control-group">
                  <label class="control-label">Residencia</label>
                  <div class="controls">
					  
					<!--City-->
					<?php
						$sqlQuery = " SELECT city_id, city FROM city WHERE state_id = 19";
						$resultado = resultFromQuery($sqlQuery);
						echo comboFromArray('city_id', $resultado, $city_id, '', '', false, 'span4 m-wrap', 'Cidade');
					?><br><br>
					
					<input id="neightborhood" type="text" name="neightborhood" placeholder="Bairro" class="span4 m-wrap" value="<?php echo $neightborhood;?>"><br>
					
                    <input id="street" type="text" name="address" placeholder="Endereço" class="span4 m-wrap" value="<?php echo $street;?>"><br>
                    
                    <input id="addressnumber" type="text" name="addressnumber" placeholder="Numero" class="span4 m-wrap" value="<?php echo $number;?>"><br>
                    
					<input id="addressfloor" type="text" name="addressfloor" placeholder="Piso" class="span4 m-wrap" value="<?php echo $floor;?>"><br>
					
					<input id="addressapartment" type="text" name="addressapartment" placeholder="Apartamento" class="span4 m-wrap" value="<?php echo $apartment;?>">
					
                  </div>
                </div>
                
                <!--Phone 1 & 2-->
                <div class="control-group">
					<label for="normal" class="control-label">Telefone</label>
					<div class="controls">
						<input type="text" id="mask-phone" name="phone1" placeholder="Telefone 1" class="span4 mask text">
						<br>
						<input type="text" id="mask-phone2" name="phone2" placeholder="Telefone 2" class="span4 mask text">
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
                <div class="control-group">
                  <label class="control-label">Carteira de trabalho</label>
                  <div class="controls">
					  
					  <!--Number-->  
					  <input type="text" id="carteiranumber" name="carteiranumber" placeholder="Número" class="span4 mask text"><br>
					  
					  <!--Serie-->
					  <input type="text" id="carteiraserie" name="carteiraserie" placeholder="Serie" class="span4 mask text"><br>
		
					  <!--Date-->
					  <div data-date=""  class="input-append date datepicker">
						  <input id="carteiradate" name="carteiradate" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Data de expedição">
						  <span class="add-on"><i class="icon-th"></i></span>
					  </div>
					  
                  </div>
                </div>
                
                <!--CPF-->  
                <div class="control-group">
                  <label class="control-label">CPF</label>
                  <div class="controls">
					  <!--Number-->  
					  <input type="text" id="cpfnumber" name="cpfnumber" placeholder="Número" class="span4 mask text"><br>
					  
                  </div>
                </div>
                
                <!--Eleitor Title-->  
                <div class="control-group">
                  <label class="control-label">Titulo Eleitoral</label>
                  <div class="controls">
					  
					  <!--Number-->  
					  <input type="text" id="eleitornumber" name="eleitornumber" placeholder="Número de Inscrição" class="span4 mask text"><br>
					  
					  <!--Zone-->  
					  <input type="text" id="eleitorzone" name="eleitorzone" placeholder="Zona" class="span4 mask text"><br>
					  
					  <!--Section-->  
					  <input type="text" id="eleitorsection" name="eleitorsection" placeholder="Seção" class="span4 mask text"><br>
					  
					  <!--Date-->
					  <div data-date=""  class="input-append date datepicker">
						  <input id="eleitordate" name="eleitordate" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Data de emissão">
						  <span class="add-on"><i class="icon-th"></i></span>
					  </div>
						
                  </div>
                </div>
                
                <!--Identification Register-->  
                <div class="control-group">
                  <label class="control-label">Registro de identidade civil (RG)</label>
                  <div class="controls">
					  
					  <!--Number-->  
					  <input type="text" id="idnumber" name="idnumber" placeholder="Número" class="span4 mask text"><br>
					  
					  <!--Expeditor-->  
					  <input type="text" id="idexpeditor" name="idexpeditor" placeholder="Órgão expedidor" class="span4 mask text"><br>
					  
					  <!--Date-->
					  <div data-date=""  class="input-append date datepicker">
						  <input id="iddate" name="iddate" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Data de expedição">
						  <span class="add-on"><i class="icon-th"></i></span>
					  </div>
						
                  </div>
                </div>
                
                
                <!--PIS-->  
                <div class="control-group">
                  <label class="control-label">Programa de Integração Social - PIS</label>
                  <div class="controls">
					  
					  <!--Number-->  
					  <input type="text" id="pisnumber" name="pisnumber" placeholder="Número" class="span4 mask text"><br>
					  
					  <!--Bank-->  
					  <input type="text" id="pisbanknumber" name="pisbanknumber" placeholder="Nũmero do banco" class="span4 mask text"><br>
					  
					  <!--Date-->
					  <div data-date=""  class="input-append date datepicker">
						  <input id="pisdate" name="pisdate" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Data de expedição">
						  <span class="add-on"><i class="icon-th"></i></span>
					  </div>
						
                  </div>
                </div>
                
                
                <!--Militar Certificate-->  
                <div class="control-group">
                  <label class="control-label">Certificado militar</label>
                  <div class="controls">
					  
					  <!--Number-->  
					  <input type="text" id="milcertnumber" name="milcertnumber" placeholder="Número" class="span4 mask text"><br>
					  
					  <!--Serie-->  
					  <input type="text" id="milcertserie" name="milcertserie" placeholder="Série" class="span4 mask text"><br>
					  
					  <!--Category-->  
					  <input type="text" id="milcertcategory" name="milcertcategory" placeholder="Categoria" class="span4 mask text"><br>

				  </div>
				</div>
				
				<!--Habilitation Card-->  
                <div class="control-group">
                  <label class="control-label">Carteira de habilitação</label>
                  <div class="controls">
					  
					  <!--Number-->  
					  <input type="text" id="habilitationnumber" name="habilitationnumber" placeholder="Número" class="span4 mask text"><br>
					  
					  <!--Category-->  
					  <input type="text" id="habilitationcategory" name="habilitationcategory"placeholder="Categoria" class="span4 mask text"><br>
					  
					  
					  <!--Expedition Date-->
					  <div data-date=""  class="input-append date datepicker">
						  <input id="habilitationdate" name="habilitationdate" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Data de expedição">
						  <span class="add-on"><i class="icon-th"></i></span>
					  </div><br>
					  
					  <!--Expedition Date-->
					  <div data-date=""  class="input-append date datepicker">
						  <input id="habilitationvaliddate" name="habilitationvaliddate" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Data de validade">
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
							echo comboFromArray('idempresa', $resultado, $idempresa, '', '', false, 'span4 m-wrap', '');
						?>
						<br><br>
											  
					  <!--Admission Date-->
					  <div data-date="" class="input-append date datepicker">
							<input id="admissiondate" name="admissiondate" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Data de admissão">
							<span class="add-on"><i class="icon-th"></i></span>
					  </div><br>
					  
					  <!--Contract Date-->
					  <div data-date="" class="input-append date datepicker">
							<input id="contractdate" name="contractdate" type="text" value="" data-date-format="yyyy-mm-dd" placeholder="Data de contrataçao">
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
							echo comboFromArray('jobcategory_id', $resultado, $jobcategory_id, 'getBasesalary(this.value)', '', false, 'span4 m-wrap');
						?>
                  </div>
                </div>
                
                <!--Salary-->                
				<div class="control-group">
				  <label class="control-label">Salario</label>
				  <div class="controls">
					<!--Base Salary-->    
					<div class="input-prepend"> <span class="add-on">R$</span>
					  <input id="basesalary" type="text" placeholder="Básico" class="sspan4 m-wrap" readonly>
					</div><br>
					
					<!--Bonus Salary-->  
					<div class="input-prepend"> <span class="add-on">R$</span>
					  <input id="bonussalary" name="bonussalary" type="number" placeholder="Bonus" class="sspan4 m-wrap">
					</div>
				  </div>
				</div>
				
                <!--Work Hours-->  
                <div class="control-group">
                  <label class="control-label">Horario</label>
                  <div class="controls">
					  
					  <!--From-->  
					  <input type="text" id="mask-fromhours" name="fromhour"placeholder="Das horas" class="span4 mask text"><br>
					  
					  <!--From-->  
					  <input type="text" id="mask-tohours" name="tohour" placeholder="Até as horas" class="span4 mask text"><br>
					  
					  <!--Interval-->  
					  <input type="text" id="mask-intervalhours" name="intervalhour" placeholder="Horas de intervalo" class="span4 mask text"><br>
					  	
                  </div>
                </div>
                
				<!--Transport-->  
                <div class="control-group">
                  <label class="control-label">Transporte</label>
                  <div class="controls">
					  
					  <!--Has transport-->
					  <SELECT ID="transport" NAME="transport" SIZE="1" onchange="" STYLE="" class="span4 m-wrap">
						  <OPTION VALUE="0">Não</OPTION>
						  <OPTION VALUE="1">Sim</OPTION>
					  </SELECT><br><br>
					  
					  <!--Route-->  
					  <input type="text" id="traject" name="traject" placeholder="Trajeto" class="span4 mask text"><br>
					  
					  <!--Value-->
					  <div class="input-prepend"> <span class="add-on">R$</span>
						<input id="transportvalue" name="transportvalue" type="text" placeholder="Valor da passagem" class="sspan4 m-wrap">
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
</div>

<!--end-main-container-part-->

<?php include "footer.php"; ?>

