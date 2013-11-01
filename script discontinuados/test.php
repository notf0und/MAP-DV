<?php 
	include "head.php";
	//var_dump($_POST);
	//die($_POST);
/*
	$datadiaria = date("Y-m-d");

	echo $datadiaria;
	
	$idposadas = 5;
	$idservicios = 4;
	$precio = valordiaria($datadiaria, $idposadas, $idservicios);
	echo 'Precio: '.$precio;
	*/
	

	/* 

	$idposadas = 6;
	$idservicios = 1;

	$start = strtotime('2013-07-10');
	$end = strtotime('2013-07-20');

	eliminarTablesLiquidacionesMP();
	crearTablesLiquidacionesMP();
	insertoBloqueDeMediapension($idposadas,$start,$end);


		creo una tabla e inserto los valores.
			la tabla tiene (id-data-precio) y se va a llamar _test_liquidaciones_MP_cuentas.
		insert los valores
		hago un disctinct de los valores
		e inserto SUM de cada disctinc por idliquidaciones
		borro contenido de la tabla _test_liquidaciones_MP_cuentas

	
	for ( $i = $start; $i <= $end; $i += 86400 ){
		$fechaActual = date("Y-m-d",$i);
		echo $fechaActual;
		echo ' : ';
		$precio = valordiaria($fechaActual, $idposadas, $idservicios);

//		insert los valores
			$sql = " INSERT INTO _temp_liquidaciones_mp_cuentas (data, precio) VALUES (";
			$sql .= " '".$fechaActual."',";
			$sql .= " ".$precio."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	
			echo $precio;
		echo ' <hr> ';
	}

	//		hago un disctinct de los valores

			$sql = " SELECT precio, MIN(data) AS min, MAX(data) AS max, SUM(precio) AS Total ";
			$sql .= " FROM  `_temp_liquidaciones_mp_cuentas` ";
			$sql .= " GROUP BY precio ";
			$resultadoDISTINCT= resultFromQuery($sql);	

//		e inserto SUM de cada distinct
		while ($rowLine = siguienteResult($resultadoDISTINCT)) {
			$sql = " INSERT INTO _temp_liquidaciones_mp (idmediapension, Titular, Q, Agencia, Posada, DataIN, DataOUT, numeroexterno, N, M, Servicio, USD, Tarifa) VALUES (";
			$sql .= " ".$row->idmediapension.",";
			$sql .= " '".$row->Titular."',";
			$sql .= " ".$row->Q.",";
			$sql .= " '".$row->Agencia."',";
			$sql .= " '".$row->Posada."',";
			$sql .= " '".$row->DataIN."',";
			$sql .= " '".$row->DataOUT."',";
			$sql .= " '".$row->numeroexterno."',";
			$sql .= " ".$row->N.",";
			$sql .= " ".$row->M.",";
			$sql .= " '".$row->Servicio."',";
			$sql .= " ".$rowLine->total_amount.",";
			$sql .= " ".$rowLine->tarifa."";
			$sql .= " ) ";
			$resultadoInsertLine = resultFromQuery($sql);	
		
		}

	
	*/
	
	//liquidacionServiciosOperadores(3,'2013-07-01','2013-07-31');	
	//echo '<script languaje="javascript"> self.location="mediapension.informes.liquidaciones.reporte.operadores.php"</script>';

	
	?>
<!--
<br><br><br><br><br><br><br><br><br>
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
	<script type="text/javascript">
	$(document).ready(function(){//inicio o jQuery
		$("select[name='combo1']").change(function(){
		var idCombo1 = $(this).val();//pegando o value do option selecionado
		//alert(idCombo1);//apenas para debugar a variável
		
			$.getJSON(//esse método do jQuery, só envia GET
				'combos-dependentes-function.inc.php',//script server-side que deverá retornar um objeto jSON
				{idCombo1: idCombo1},//enviando a variável
 
				function(data){
				//alert(data);//apenas para debugar a variável
					
					var option = new Array();//resetando a variável
					
					resetaCombo('combo2');//resetando o combo
					$.each(data, function(i, obj){
						
						
						option[i] = document.createElement('option');//criando o option
						$( option[i] ).attr( {value : obj.id} );//colocando o value no option
						$( option[i] ).append( obj.nome );//colocando o 'label'
 
						$("select[name='combo2']").append( option[i] );//jogando um à um os options no próximo combo
				});
			});
		});
	});	
	
	/* função pronta para ser reaproveitada, caso queria adicionar mais combos dependentes */
	function resetaCombo( el )
	{
		$("select[name='"+el+"']").empty();//retira os elementos antigos
		var option = document.createElement('option');					
		$( option ).attr( {value : '0'} );
		$( option ).append( 'Escolha' );
		$("select[name='"+el+"']").append( option );
	}
	</script>
</head>
<body>
<form action="" method="post">
	<fieldset>
		<label><select name="combo1">
			<option value="0">Escolha</option>
 
			<option value="1">Item 1</option>
			<option value="2">Item 2</option>
			<option value="3">Item 3</option>
		</select></label>
		
		<label><select name="combo2">
			<option value="0">Escolha</option>
		</select></label>
 
	</fieldset>
</form>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

</body>
</html>	
-->
<?php 
/*
$dataIN = '2013-07-10';
$dataOUT = '2013-07-20';
			$fechaagrabar = $dataIN;
			while(strtotime($dataOUT) >= strtotime($dataIN)){
				if(strtotime($dataOUT) != strtotime($fechaagrabar)){
					echo "$fechaagrabar<br />";
					$fechaagrabar = date("Y-m-d", strtotime($fechaagrabar . " + 1 day"));
				}else{
					echo "$fechaagrabar<br />";
					break;
				}	
			}			

*/

/*
$idmediapension = 99;
$qtdedepaxagora = 2;
$idposadas = 20;
$idservicios = 1;
$datadiaria = date("Y-m-d");
$precio = valordiaria($datadiaria, $idposadas, $idservicios);
$idadmision = admitirServicio($idmediapension, $qtdedepaxagora, $precio);
*/


?>		
<! --
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<script>
$(document).ready(function() {
    $( "#arr_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
    $( "#dep_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$('button').click(function() {
    var start = $('#arr_date').datepicker('getDate');
    var end   = $('#dep_date').datepicker('getDate');
    var days   = (end - start)/1000/60/60/24;
    alert(days);
});
</script>

<p>Choose your dates and hit <em>go</em>.</p>
<input type="text" id="arr_date">
<input type="text" id="dep_date">
<button>go</button>



<?php 
$algo="esto es algo que estoy escribiendo para probar el editor";
$texto = "aoisdodisasajdiadoajd"; 
$handle = printer_open("HP LaserJet Professional M1132 MFP"); 
printer_set_option($handle, PRINTER_MODE, "RAW"); 
printer_write($handle, $texto); 


	include "footer.php";
?>

-->
