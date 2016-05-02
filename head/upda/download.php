<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Kolkata');
require_once('../../php_includes/class.DB.php');
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

$db = new dbConx();
$db_conx = $db->db;

$query = "SELECT feeders.id, feeders.feeder, substation.name FROM feeders LEFT JOIN substation ON feeders.ss_id = substation.id"; 
$result = $db_conx->query($query);
$substation = []; $feeder = []; 
while($row = $result->fetch_row()){
    $feeder[$row[0]] = $row[1];
    $substation[$row[0]] = $row[2]; 
}


$sql = "SELECT * FROM daily";
$query = $db_conx->query($sql);

// Instantiate a new PHPExcel object
$objPHPExcel = new PHPExcel();
$sheet = $objPHPExcel->getActiveSheet();
// Set document properties
$objPHPExcel->getProperties()->setCreator("Binot Mutum")
                             ->setLastModifiedBy("Binot Mutum")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");
// Set the active Excel worksheet to sheet 0
$objPHPExcel->setActiveSheetIndex(0); 
// Initialise the Excel row number

// Iterate through each result from the SQL query in turn
// We fetch each database result row into $row in turn

$default_border = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb'=>'1006A3')
);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Feeder Name')->setCellValue('B1', 'Substation Name')->setCellValue('C1', 'Month')->setCellValue('D1', '1st Day')->setCellValue('E1', '2nd Day')->setCellValue('F1', '3rd Day')->setCellValue('G1', '4th Day')->setCellValue('H1', '5th Day')->setCellValue('I1', '6th Day')->setCellValue('J1', '7th Day')->setCellValue('K1', '8th Day')->setCellValue('L1', '9th Day')->setCellValue('M1', '10th Day')->setCellValue('N1', '11th Day')
            ->setCellValue('O1', '12th Day')->setCellValue('P1', '13th Day')->setCellValue('Q1', '14th Day')->setCellValue('R1', '15th Day')
            ->setCellValue('S1', '16th Day')->setCellValue('T1', '17th Day')->setCellValue('U1', '18th Day')->setCellValue('V1', '19th Day')
            ->setCellValue('W1', '20th Day')->setCellValue('X1', '21st Day')->setCellValue('Y1', '22nd Day')->setCellValue('Z1', '23rd Day')
            ->setCellValue('AA1', '24th Day')->setCellValue('AB1', '25th Day')->setCellValue('AC1', '26th Day')->setCellValue('AD1', '27th Day')
            ->setCellValue('AE1', '28th Day')->setCellValue('AF1', '29th Day')->setCellValue('AG1', '30th Day')->setCellValue('AH1', '31st Day');

$rowCount = 2; 
while($row = $query->fetch_assoc()){
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$rowCount, $feeder[$row['feeder_id']])
    ->SetCellValue('B'.$rowCount, $substation[$row['feeder_id']])
    ->SetCellValue('C'.$rowCount, $row['month'])->SetCellValue('D'.$rowCount, $row['1'])->SetCellValue('E'.$rowCount, $row['2'])
    ->SetCellValue('F'.$rowCount, $row['3'])->SetCellValue('G'.$rowCount, $row['4'])->SetCellValue('H'.$rowCount, $row['5'])
    ->SetCellValue('I'.$rowCount, $row['6'])->SetCellValue('J'.$rowCount, $row['7'])->SetCellValue('K'.$rowCount, $row['8'])
    ->SetCellValue('L'.$rowCount, $row['9'])->SetCellValue('M'.$rowCount, $row['10'])->SetCellValue('N'.$rowCount, $row['11'])
    ->SetCellValue('O'.$rowCount, $row['12'])->SetCellValue('P'.$rowCount, $row['13'])->SetCellValue('Q'.$rowCount, $row['14'])
    ->SetCellValue('R'.$rowCount, $row['15'])->SetCellValue('S'.$rowCount, $row['16'])->SetCellValue('T'.$rowCount, $row['17'])
    ->SetCellValue('U'.$rowCount, $row['18'])->SetCellValue('V'.$rowCount, $row['19'])->SetCellValue('W'.$rowCount, $row['20'])
    ->SetCellValue('X'.$rowCount, $row['21'])->SetCellValue('Y'.$rowCount, $row['22'])->SetCellValue('Z'.$rowCount, $row['23'])
    ->SetCellValue('AA'.$rowCount, $row['24'])->SetCellValue('AB'.$rowCount, $row['25'])->SetCellValue('AC'.$rowCount, $row['26'])
    ->SetCellValue('AD'.$rowCount, $row['27'])->SetCellValue('AE'.$rowCount, $row['28'])->SetCellValue('AF'.$rowCount, $row['29'])
    ->SetCellValue('AG'.$rowCount, $row['30'])->SetCellValue('AH'.$rowCount, $row['31']);

    // Increment the Excel row counter
    $rowCount++; 
} 

$default_border = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb'=>'1006A3')
);
$style_header = array(
    'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
      )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'E1E0F7'),
    ),
    'font' => array(
        'bold' => true,
    )
);
 
$sheet->getStyle('A1:AH1')->applyFromArray( $style_header );
$sheet->getStyle('A2:AH'.$rowCount)->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
        )
    ));

$objPHPExcel->getActiveSheet()->setTitle('Simple-example');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Daily Interruption Report.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 2027 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>