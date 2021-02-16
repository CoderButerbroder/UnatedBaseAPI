<?php
/* Отчет по показателям Tboil */
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}


$period_select = (object) [];
$period_select->period = (isset($_POST["period"])) ? trim($_POST["period"]) : 'month';
$period_select->start =  date('Y-m-d 00:00:00', (strtotime(trim($_POST["start"]))-86400) );
$period_select->end =  date('Y-m-d 23:59:59', strtotime(trim($_POST["end"])));

$period_select->start =  '2020-11-01 00:00:00';
$period_select->end = '2021-02-16 23:59:59';

if ($period_select->start && $period_select->end && $period_select->period != 'year' && $period_select->period != 'month' && $period_select->period != 'week') {
  exit();
}

if ( $period_select->period == 'year' ) $period_select->name = 'Год';
if ( $period_select->period == 'month' ) $period_select->name = 'Месяц';
if ( $period_select->period == 'week' ) $period_select->name = 'Неделя';


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$arr_data_event_summ = $settings->get_count_main_events_groupby_time_reg(false, $period_select->period, $period_select->start, $period_select->end);
$arr_data_users = $settings->get_count_main_users_groupby_time_reg(false, $period_select->period, $period_select->start, $period_select->end);

foreach ($arr_data_users as $key => $value) {
  if ($count_sum != 0) {$value->percent = $value->sum * 100 / $count_sum;}
  else {$value->percent = 0;}
  $value->sum = $value->sum + $count_sum;
  $count_sum = $value->sum;
}

echo json_encode($arr_data_users, JSON_UNESCAPED_UNICODE);
exit();

$arr_data_period = [];

function add_null_in_data_week( $arr_in ) {
  foreach( $arr_in as $key => $value ) {
    if($value->weekd >= 1 && $value->weekd <= 9 ){
      $value->weekd = '0'.$value->weekd;
    }
  }
}

function get_list_date_arr( $arr_in ) {
  global $period_select;
  global $arr_data_period;
  foreach ($arr_in as $key => $value) {
    if ( $period_select->period == 'week' )  $data_i = strtotime(  $value->yeard.'W'.$value->weekd );
    if ( $period_select->period == 'month')  $data_i = strtotime( '01.'.$value->monthd.'.'.$value->yeard );
    if ( $period_select->period == 'year' )  $data_i = strtotime( '01.01.'.$value->yeard );
    if (!in_array($data_i, $arr_data_period)) {
        array_push($arr_data_period, $data_i);
    }
  }
}

function set_cell_value($sheet_in, $key, $row, $data_in, $arr_in, $iter = -1) {
  global $period_select;
  if ($iter != -1) {
    $sheet_in->setCellValueByColumnAndRow(($key+2), $row, $iter);
  } else {
    $sheet_in->setCellValueByColumnAndRow(($key+2), $row, 0);
  }
  if(is_Array($arr_in)) {
    foreach ($arr_in as $key2 => $value2) {
      // echo $value2->sum.' '.$value2->yeard.'-'.$value2->monthd.'-'.$value2->dayd."</br>";

      if($period_select->period == 'day'){
        $date1 = strtotime($value2->yeard.'-'.$value2->monthd.'-'.$value2->dayd);
      }
      if($period_select->period == 'week'){
        $date1 = strtotime($value2->yeard.'W'.$value2->weekd);
      }
      if($period_select->period == 'month'){
        $date1 = strtotime('01.'.$value2->monthd.'.'.$value2->yeard);
      }
      if($period_select->period == 'year'){
        $date1 = strtotime('01.01.'.$value2->yeard);
      }

      if($data_in == $date1) {
        $sheet_in->setCellValueByColumnAndRow(($key+2), $row, $value2->sum );
        $iter=$value2->sum;
        return $iter;
      } else {
        if ($iter == -1) {
          $sheet_in->setCellValueByColumnAndRow(($key+2), $row, 0);
        }
      }
    }
  } else {
    $sheet_in->setCellValueByColumnAndRow(($key+2), ($row), 0);
  }

  return $iter;
}

if ($period_select->period == 'week') {
  add_null_in_data_week($arr_data_event_summ);
  add_null_in_data_week($arr_data_users);
}

if (is_array($arr_data_event_summ) && count($arr_data_event_summ) > 0 && $arr_data_event_summ != 0 ) get_list_date_arr($arr_data_event_summ);
if (is_array($arr_data_users) && count($arr_data_users) > 0 && $arr_data_users != 0 ) get_list_date_arr($arr_data_users);

sort($arr_data_period);


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
$sheet->setTitle('Точка кипения СПб');

for ($i = 1 ; $i <= count($arr_data_period)+1  ; $i++ ) {
  $sheet->getcolumndimensionbycolumn($i)->setAutoSize(true);
  $sheet->getCellByColumnAndRow($i,2)->getStyle()->getFont()->setBold(true);
}

// $sheet->getColumnDimension('A')->setAutoSize(true);

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Выбранный Перииод '.'C '.date('d.m.Y', strtotime($period_select->start)).' по '.date('d.m.Y', strtotime($period_select->end)));
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатель работы ТК СПб:');
foreach ($arr_data_period as $key => $value) {
  if($period_select->period == 'year'){
      $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row),  date('Y',  $value) );
  }
  if ( $period_select->period == 'month' ) {
    $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row),  $arr_select_month[date('n',  $value)]->name.' '.date('Y',  $value)  );
  }
  if ( $period_select->period == 'week' ) {
    $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row),  date('W',  $value)." неделя ".$arr_select_month[date('n',  $value)]->name.' '.date('Y',  $value)  );
  }
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (регистрации. количество)');
for ($i = 1 ; $i <= count($arr_data_period)+1  ; $i++ ) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}

$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Всего пользователей, акк-в');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, ($key), $actual_row, $value, $arr_data_users);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Прирост к предыдщему месяцу, %');

$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Новые пользователи за месяц, акк-в');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'в т.ч. активные');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (процессы, количество)');
for ($i = 1 ; $i <= count($arr_data_period)+1  ; $i++ ) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}

$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Рекомендации по мероприятиям ');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Сервисы для организаторов мероприятий = сервисы для построения сообществ');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество мероприятий');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, ($key), $actual_row, $value, $arr_data_event_summ);
}
$actual_row++;






$writer = new Xlsx($spreadsheet);
$now = date("_H_i_d_m_Y");
$now = trim($now);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_Tboil_FULLDATA'.$now.'.xlsx"');
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
