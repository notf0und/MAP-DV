<?php 
require_once 'Classes/PHPExcel.php';
include_once 'lib/dbUtils.php';

$rowCount = 2;

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


//Obtener detalles de la liquidación
if(isset($_GET['idresponsablesDepago'])){
		$sql = "select nombre, tabla from responsablesDePago where idresponsablesDepago = ".$_GET['idresponsablesDepago']." ";
		$result = resultFromQuery($sql);
		$row = mysql_fetch_object($result);
		$RDP = $row->nombre;
		$tablaRDP = $row->tabla;
		
		$sql = "select nombre from ".$tablaRDP." where id".$tablaRDP." = ".$_GET['id']." ";
		$result = resultFromQuery($sql);
		$row = mysql_fetch_object($result);
		$nombreRDP = $row->nombre;
}


 
$objPHPExcel = new PHPExcel();

// Set the active Excel worksheet to sheet 0 
$objPHPExcel->setActiveSheetIndex(0); 

$objPHPExcel->getActiveSheet()->setTitle('MP');

setPageSetup($objPHPExcel);

setCellSize($objPHPExcel);

setStyle($objPHPExcel);

//////Prepare data///////
//your MySQL Database Name of which database to use this 
$tablename = "_temp_liquidaciones_mp"; //your MySQL Table Name which one you have to create excel file 

$sql = "SET lc_time_names = 'pt_BR';";
$result = resultFromQuery($sql);

$sql = "SELECT MONTHNAME(DataIN) 'month', YEAR(DataIN) 'year' FROM ".$tablename.' LIMIT 1'; 
$result = resultFromQuery($sql);

$row = mysql_fetch_row($result);

$monthname = $row[0];
$year = $row[1];




// your mysql query here , we can edit this for your requirement 
$sql = "SELECT idmediapension, Titular 'PAX', Q, Agencia 'Agência', Posada 'Pousada', numeroexterno '# Voucher', DATE_FORMAT(DataIN,'%d/%m/%Y') 'IN', DATE_FORMAT(DataOUT,'%d/%m/%Y') 'OUT', N, M, Servicio, USD, Tarifa FROM ".$tablename.' ORDER BY Titular ASC'; 

$result = resultFromQuery($sql);
/////////////////////////



addColumnNames($objPHPExcel);

addData($objPHPExcel);

$mp = addTotal($objPHPExcel);


//Start BB
$sheetId = 1;
$rowCount = 2;

$objPHPExcel->createSheet(NULL, $sheetId);
$objPHPExcel->setActiveSheetIndex($sheetId);

$objPHPExcel->getActiveSheet()->setTitle('HTL');// BC DA NP

setPageSetup($objPHPExcel);

setCellSize($objPHPExcel);

setStyle($objPHPExcel);

$tablename = "_temp_liquidaciones_htl"; //your MySQL Table Name which one you have to create excel file 

$condition = "Posada = 'Brisas de Buzios'";

// your mysql query here , we can edit this for your requirement 
$sql = "SELECT idhoteleria, Titular 'PAX', Q, Agencia 'Agência', Posada 'Pousada', numeroexterno '# Voucher', DATE_FORMAT(DataIN,'%d/%m/%Y') 'IN', DATE_FORMAT(DataOUT,'%d/%m/%Y') 'OUT', N, M, Servicio, USD, Tarifa FROM ".$tablename." ";
isset($condition) ? $sql .= ' WHERE '.$condition : '';
$sql .= "ORDER BY Titular ASC"; 

$result = resultFromQuery($sql);
/////////////////////////

addColumnNames($objPHPExcel);

addData($objPHPExcel);

$bb = addTotal($objPHPExcel);

//End BB

//START BC
$condition = "Posada = 'Buzios Colinas'";

// your mysql query here , we can edit this for your requirement 
$sql = "SELECT idhoteleria, Titular 'PAX', Q, Agencia 'Agência', Posada 'Pousada', numeroexterno '# Voucher', DATE_FORMAT(DataIN,'%d/%m/%Y') 'IN', DATE_FORMAT(DataOUT,'%d/%m/%Y') 'OUT', N, M, Servicio, USD, Tarifa FROM ".$tablename." ";
isset($condition) ? $sql .= ' WHERE '.$condition : '';
$sql .= "ORDER BY Titular ASC"; 

$result = resultFromQuery($sql);
/////////////////////////

$rowCount += 2;

setStyle($objPHPExcel);

addColumnNames($objPHPExcel);

addData($objPHPExcel);

$bc = addTotal($objPHPExcel);
//END BC

//START DA
$condition = "Posada = 'Pousada Das Americas'";

// your mysql query here , we can edit this for your requirement 
$sql = "SELECT idhoteleria, Titular 'PAX', Q, Agencia 'Agência', Posada 'Pousada', numeroexterno '# Voucher', DATE_FORMAT(DataIN,'%d/%m/%Y') 'IN', DATE_FORMAT(DataOUT,'%d/%m/%Y') 'OUT', N, M, Servicio, USD, Tarifa FROM ".$tablename." ";
isset($condition) ? $sql .= ' WHERE '.$condition : '';
$sql .= "ORDER BY Titular ASC"; 

$result = resultFromQuery($sql);
/////////////////////////

$rowCount += 2;

setStyle($objPHPExcel);

addColumnNames($objPHPExcel);

addData($objPHPExcel);

$da = addTotal($objPHPExcel);
//END DA

//START NP
$condition = "Posada = 'New Paradise'";

// your mysql query here , we can edit this for your requirement 
$sql = "SELECT idhoteleria, Titular 'PAX', Q, Agencia 'Agência', Posada 'Pousada', numeroexterno '# Voucher', DATE_FORMAT(DataIN,'%d/%m/%Y') 'IN', DATE_FORMAT(DataOUT,'%d/%m/%Y') 'OUT', N, M, Servicio, USD, Tarifa FROM ".$tablename." ";
isset($condition) ? $sql .= ' WHERE '.$condition : '';
$sql .= "ORDER BY Titular ASC"; 

$result = resultFromQuery($sql);
/////////////////////////

$rowCount += 2;

setStyle($objPHPExcel);

addColumnNames($objPHPExcel);

addData($objPHPExcel);

$np = addTotal($objPHPExcel);
//END NP

$rowCount += 2;

$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, 'TOTAL (MP+HTL)');

$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowCount, $mp+$bb+$bc+$da+$np);

setStyle($objPHPExcel);


// Redirect output to a client’s web browser (Excel5) 
$name = $nombreRDP.' - '.$monthname.'-'.$year;
$filename = $name.'.xls';
header('Content-Type: application/vnd.ms-excel'); 
header('Content-Disposition: attachment;filename="'.$filename.'"'); 
header('Cache-Control: max-age=0'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
$objWriter->save('php://output');

function setCellSize($objPHPExcel){
	//Set Cells Size
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(26);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(9);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8);
}

function setPageSetup($objPHPExcel){
	
	//Set Orientation page (Landscape)
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

	//START SET PAGE MARGINS
	$sheet = $objPHPExcel->getActiveSheet();
	$pageMargins = $sheet->getPageMargins();

	// margin is set in inches (0cm)

	$pageMargins->setTop(0.2);
	$pageMargins->setBottom(0.2);
	$pageMargins->setLeft(0);
	$pageMargins->setRight(0);
	//END SET PAGE MARGINS
	
	//repeat headeings
	$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTop(array(1,2));
	
}

function setStyle($objPHPExcel){
	
	// Initialise the Excel row number 
	global $rowCount;  

	//Set Default Style
	$styleArray = array(
		'alignment' => array(
			'wrap'       => true,
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		),
		'font'  => array(
			'color' => array('rgb' => '000000'),
			'size'  => 10,
			'name'  => 'Ubuntu'
		),
		'borders' => array(
			'bottom' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		)
	);

	//Apply Default Style
	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);

	//START Set Field Names Styles
	$styleArray = array(
		'font'  => array(
			'bold'  => true,
			'background' => array('rgb' => 'E9E9E9'),
			'color' => array('rgb' => 'FFFFFF'),
			'size'  => 10,
			'name'  => 'Ubuntu'
		)
	);

	//Apply Field Names style
	$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':L'.$rowCount)->applyFromArray($styleArray);
	//END Set Field Names Styles

	//START Set Field Names Background
	$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':L'.$rowCount)->getFill()->applyFromArray(
		array(
			'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array('rgb' => '000000'),
		)
	);
	//END Set Field Names Background
}

function addColumnNames($objPHPExcel){
	global $rowCount, $result, $monthname, $year, $RDP, $nombreRDP;
		
	$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.'1', 'Responsable: '.$RDP.' '.$nombreRDP.' Periodo: '.$monthname.'/'.$year);
	$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
	
	$column = 'A';

	//Start of adding column names
	for ($i = 1; $i < mysql_num_fields($result); $i++)  

	{
		$objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, mysql_field_name($result,$i));
		$column++;
	}//End of adding column names 
} 

function addData($objPHPExcel){
	global $result, $rowCount;

	$rowCount++;  

	while($row = mysql_fetch_row($result))  
	{  
		$column = 'A';

	   for($j=1; $j<mysql_num_fields($result);$j++)  
		{  
			if(!isset($row[$j]))  

				$value = NULL;  

			elseif ($row[$j] != "")  

				$value = strip_tags($row[$j]);  

			else  

				$value = "";  


			$objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $value);
			$column++;
		}  

		$rowCount++;
	}
}

function addTotal($objPHPExcel){
	global $rowCount, $tablename, $condition;
	
	//START TOTAL MP
	$rowCount++;

	$sql = "SELECT SUM(usd) 'total' FROM ".$tablename;
	isset($condition) ? $sql .= ' WHERE '.$condition : '';
	$sql .= ' ORDER BY Titular ASC'; 

	$result = resultFromQuery($sql);

	$row = mysql_fetch_row($result);

	$subtotal = $row[0];

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, 'Subtotal');
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowCount, $subtotal);

	//START Set Field Names Styles
		$styleArray = array(
			'font'  => array(
				'bold'  => true,
				'background' => array('rgb' => 'E9E9E9'),
				'color' => array('rgb' => 'FFFFFF'),
				'size'  => 10,
				'name'  => 'Ubuntu'
			)
		);

		//Apply Field Names style
		$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':L'.$rowCount)->applyFromArray($styleArray);
		//END Set Field Names Styles

	//START Set Field Names Background
		$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':L'.$rowCount)->getFill()->applyFromArray(
			array(
				'type'       => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array('rgb' => '777777'),
			)
		);
		//END Set Field Names Background
		
	//END TOTAL MP
	return $subtotal;
}
