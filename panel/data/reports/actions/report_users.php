<?php
/* выгрузка пользователей */
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

$data_array = $settings->get_users_entity_data();

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

require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/office/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


$spreadsheet = new Spreadsheet();
//$sheet = $spreadsheet->getActiveSheet();
$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Пользователи');

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Фамилия:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Имя:');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'Отчество:');
$sheet->setCellValueByColumnAndRow(4,$actual_row, 'Email:');
$sheet->setCellValueByColumnAndRow(5,$actual_row, 'Телефон:');
$sheet->setCellValueByColumnAndRow(6,$actual_row, 'Должность:');
$sheet->setCellValueByColumnAndRow(7,$actual_row, 'Юр. лицо:');
$actual_row++;
foreach ($data_array as $key => $value) {
  $sheet->setCellValueByColumnAndRow(1,$actual_row, $value["last_name"]);
  $sheet->setCellValueByColumnAndRow(2,$actual_row, $value["name"]);
  $sheet->setCellValueByColumnAndRow(3,$actual_row, $value["second_name"]);
  $sheet->setCellValueByColumnAndRow(4,$actual_row, $value["email"]);
  //$sheet->setCellValueByColumnAndRow(5,$actual_row, $value["phone"]);

  $spreadsheet->getActiveSheet()->getCell(('E'.$actual_row))
    ->setValueExplicit( $value["phone"], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING );
  $sheet->setCellValueByColumnAndRow(6,$actual_row, $value["position"]);
  $sheet->setCellValueByColumnAndRow(7,$actual_row, $value["name_entity"]);
  $actual_row++;
}

$writer = new Xlsx($spreadsheet);
$now = date("_H_i_d_m_Y");
$now = trim($now);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_users_FULLDATA'.$now.'.xlsx"');
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
