<?php
/*
Генерация xlsx отчета
по мероприятиям с общей статистикой
+ все небольшая статистика за выбранный период
*/

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$arr_select_month = array('1' => (object) array('name' => 'Январь', ),
                          '2' => (object) array('name' => 'Февраль', ),
                          '3' => (object) array('name' => 'Март', ),
                          '4' => (object) array('name' => 'Апрель', ),
                          '5' => (object) array('name' => 'Май', ),
                          '6' => (object) array('name' => 'Июнь', ),
                          '7' => (object) array('name' => 'Июль', ),
                          '8' => (object) array('name' => 'Август', ),
                          '9' => (object) array('name' => 'Сентябрь', ),
                          '10' => (object) array('name' => 'Октябрь', ),
                          '11' => (object) array('name' => 'Ноябрь', ),
                          '12' => (object) array('name' => 'Декабрь' ) );

$defaut_value = '-';

// echo  json_encode($arr_merge_count,JSON_UNESCAPED_UNICODE);
// exit();

require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/office/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


$spreadsheet = new Spreadsheet();
//$sheet = $spreadsheet->getActiveSheet();
$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Мероприятия');


$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие данные:');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('8af28f');
$actual_row++;


$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Кол-во мероприятий (год/месяц/неделя)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Кол-во участников мероприятий (год/месяц/неделя)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Прирост участников мероприятий в % по отношению к аналогичному предыдущему периоду');
$actual_row++;
$actual_row++;
$actual_row++;


$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Мероприятия');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('8af28f');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Выбранный Перииод');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Некий период');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1, $actual_row, 'Наименование');
$sheet->getCellByColumnAndRow(2, $actual_row)->getStyle()->getFont()->setBold(true);
$sheet->setCellValueByColumnAndRow(2, $actual_row, 'Кол-во уч.');
$sheet->getCellByColumnAndRow(2, $actual_row)->getStyle()->getFont()->setBold(true);
$sheet->setCellValueByColumnAndRow(3, $actual_row, 'Дата мероприятия');
$sheet->getCellByColumnAndRow(3, $actual_row)->getStyle()->getFont()->setBold(true);
$sheet->setCellValueByColumnAndRow(4, $actual_row, 'Ссылка');
$sheet->getCellByColumnAndRow(4, $actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 5; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}



$writer = new Xlsx($spreadsheet);
$now = date("_H_i_d_m_Y");
$now = trim($now);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_event_FULLDATA'.$now.'.xlsx"');
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

?>
