<?php
	include "lib/sessionLib.php";
	header("Content-Type: text/html; charset=ISO-8859-1");
	function intGet( $campo ){
		return isset( $_GET[$campo] ) ? (int)$_GET[$campo] : 0;
	}	
	function retorno( $id )
	{
		$sql = "SELECT `idhabitaciones` id ,  `codigo` nome ,  `idposadas`  
			FROM `habitaciones`  
			WHERE `idposadas` = {$id} ";
		$sql .= "ORDER BY `codigo` ";
		
		$mysqli = new mysqli("localhost", "root", "", "dasamericas");
 
		
		$q = $mysqli->query( $sql ); 
//		$result = resultFromQuery($sql); 
	//	$q = $result; 
		
		//print_r($q);
		
		$json = Array();
		if( $q->num_rows > 0 )
		{
			while( $dados = $q->fetch_object() )
			{
				$json[]	= Array('nome'=> utf8_encode( $dados->nome ), 'id'=> $dados->id);
			}
		}
		else
			$json[]	= Array('nome'=> utf8_encode( 'nao encontrado' ), 'id'=> '0' );
			
 
		
		return json_encode( $json );
	}
	
	echo retorno( intGet('idCombo1') );
 ?>