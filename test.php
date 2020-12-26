<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
/*
require __DIR__.'/general/plugins/office/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
//$sheet = $spreadsheet->getActiveSheet();
$sheet = $spreadsheet->setActiveSheetIndex(0);
//$sheet->setCellValue('A1', 'Hello World !');
// $sheet->setCellValueByColumnAndRow(1,1, 'Hello World !');
// $sheet->setCellValueByColumnAndRow(15,8, 'Hello World !');
// $sheet->setCellValueByColumnAndRow(9,80, 'Hello World !');
//
//столбец строка
// $sheet->setCellValueByColumnAndRow(9,80, 'Hello World !');



$writer = new Xlsx($spreadsheet);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="all_services_requests.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
*/
?>
