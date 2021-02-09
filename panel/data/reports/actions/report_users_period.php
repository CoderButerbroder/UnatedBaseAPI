<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}
$period_select = (object) [];
$period_select->period = $_POST["period"];
$period_select->data1 =  date('Y-m-d H:i:s', strtotime(trim($_POST["period_1"])));
$period_select->data2 =  date('Y-m-d H:i:s', strtotime(trim($_POST["period_2"])));

if ($period_select->data1 && $period_select->data2 && $period_select->period != 'year' && $period_select->period != 'month' && $period_select->period != 'week' && $period_select->period != 'day') {
  exit();
}

if ( $period_select->period == 'year' ) $period->name = 'Год';
if ( $period_select->period == 'month' ) $period->name = 'Месяц';
if ( $period_select->period == 'week' ) $period->name = 'Неделя';
if ( $period_select->period == 'day') $period->name = 'День';

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

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие данные');
$sheet->setCellValueByColumnAndRow(2,$actual_row, $period->name);
$sheet->setCellValueByColumnAndRow(3,$actual_row, $period->data1);
$sheet->setCellValueByColumnAndRow(4,$actual_row, $period->data2);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество общее (зарег-но в системе)');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Кол-во физ.лиц - владельцев аккаунтов юр.лиц');
$actual_row++;





$writer = new Xlsx($spreadsheet);
$now = date("_H_i_d_m_Y");
$now = trim($now);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_FULLDATA_users'.$now.'.xlsx"');
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
