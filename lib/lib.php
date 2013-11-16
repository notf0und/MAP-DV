<?php
  // Biblioteca de funciones para acceso a base de datos
  include "dbUtils.php";
  
  // Biblioteca de clases
  include "classes.php";
  include "classesLib.php";

  // Definiciones de variables de sesion
  // Seguridad
  
// Validacion de usuario rrhh
function usuarioValido($user,$pass) {
  $sql = "SELECT clave CLAVE ".
      	 "from ddv2_usuarios ".
      	 "where upper(nombre) = upper('".$user."') ";
  $result = resultFromQuery($sql);
  // Validamos el usuario
  $row = siguienteResult($result);
	if ($row == false) {
		if ($user != '') {
		  return 'crearUsuario';
		} else {
			return false;
		}
	} else
  if ($row->CLAVE == $pass) {
		return true;
	} else {
		return false;
	}
}

function comboFromArray($nombre, $lista, $selectedId = -1, $onChangeFunction = '', $style = '', $required=false) {
	// Devuelve el string del TAG para mostrar un combo con los items de la lista.
	// La lista debe ser un array simple con los textos a mostrar.
	$re = "";
	if ($required){$re = "required ";}
	
	$combo = '<SELECT '.$re.'ID="'.$nombre.'" NAME="'.$nombre.'" SIZE="1" onchange="'.$onChangeFunction.'" STYLE="'.$style.'">';
	$combo = $combo . '<OPTION STYLE="'."display:none".'" VALUE=""></OPTION>';
	while ($row = mysql_fetch_array($lista, MYSQL_NUM)) {
		if (strpos($row[1],'inexistente') == false) {
			
			$combo = $combo . '<OPTION VALUE="'.$row[0].'"';
			
			if ($selectedId == $row[0]) {
				$combo = $combo . ' SELECTED';
			}
			
			$combo = $combo .'>'.$row[1].'</OPTION>';
		}
	}
	  $combo = $combo . '</SELECT>';
	  return $combo;
	}

function comboFromArrayComplejo($nombre, &$lista, $selectedId = -1, $onChangeFunction = '', $style = '', $ordenado = false) {
  // Devuelve el string del TAG para mostrar un combo con los items de la lista.
  // La lista debe ser un array complejo con los ID y Textos a mostrar.
  $arraylocal = $lista;
  if ($ordenado) {
    asort($arraylocal);
  }
  $combo = '<SELECT ID="'.$nombre.'" NAME="'.$nombre.'" SIZE="1" onchange="'.$onChangeFunction.'" STYLE="'.$style.'">';
  if (sizeOf($arraylocal) > 0) {
  	  for ($i = 0; $i < sizeOf($arraylocal["id"]); $i++) {
  	  	  $combo = $combo . '<OPTION STYLE="'.$style.'" VALUE="'.$arraylocal["id"][$i].'"';
  	  	  if ($selectedId == $arraylocal["id"][$i]) {
  	  	  	  $combo = $combo . ' SELECTED';
  	  	  }
  	  	  $combo = $combo .'>'.$arraylocal[$arraylocal["id"][$i]];
  	  }
  return $combo;
  }
  $combo = $combo . '</SELECT>';
}

function tableFromMatrix($tableID, $matrix, $titles) {
  // Devuelve una tabla con el contenido de la matriz $matrix
  // La matriz debe estar definida como un array de filas conteniendo arrays de celdas
  // La primera fila contiene los nombres de las columnas
  $table = '<TABLE id="'.$tableID.'" style="font-family:Tahoma" cellpadding=0 border=0>';
  if ($titles) {
    for ($col = 0; $col < sizeOf($matrix[0]); $col++) {
      $table = $table . '<TH>' . $matrix[0][$col] . '</TH>';
    }
  }
  for ($row = 1; $row < sizeOf($matrix); $row++) {
    $table = $table . '<TR id="'.$tableID.$row.'">';
    for ($col = 0; $col < sizeOf($matrix[$row]); $col++) {
      $table = $table . '<TD id="'.$tableID.$row.$col.'">' . $matrix[$row][$col] . '</TD>';
    }
    $table = $table . '</TR>';
  }
  $table = $table . '</TABLE>';
  return $table;
}

function debugEcho($var, $stop = true) {
	if ($stop) {
		die('DEBUG: ['.$var.']');
	} else {
		echo 'DEBUG: ['.$var.']';
	}
}

function horaStringFrom($fechaHora) {
  return substr($fechaHora, 11,8);

}

function proximoID($tabla, $conNombre = false) {
	$sql = "SELECT IFNULL(MAX(id";
	if ($conNombre) {
		$sql .= $tabla;
	}
	$sql .= ")+1,1) id ".
		   "FROM ".$tabla;
	$result = resultFromQuery($sql);
	$row = siguienteResult($result);
	$colname = dbFieldName($result, 0);
	$val = $row->$colname;
	return $val;
}

function formValue(&$campo) {
			return 'value="'.$campo.'"';
}

?> 
