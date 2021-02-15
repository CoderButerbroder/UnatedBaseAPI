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

$period_select = (object) [];
$period_select->period = $_POST["period"];
$period_select->start =  date('Y-m-d H:i:s', strtotime(trim($_POST["start"])));
$period_select->end =  date('Y-m-d H:i:s', strtotime(trim($_POST["end"])));

if ($period_select->start && $period_select->end && $period_select->period != 'year' && $period_select->period != 'month' && $period_select->period != 'week') {
  exit();
}

if ( $period_select->period == 'year' ) $period_select->name = 'Год';
if ( $period_select->period == 'month' ) $period_select->name = 'Месяц';
if ( $period_select->period == 'week' ) $period_select->name = 'Неделя';


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

/* получаем данные */

$arr_data_event_summ = $settings->get_count_main_events_groupby_time_reg(false, $period_select->period, $period_select->start, $period_select->end);
$arr_data_users_event_summ = $settings->get_count_users_main_events_groupby_time_reg(false, $period_select->period, $period_select->start, $period_select->end);
$arr_data_users_event_incr = $settings->get_count_users_main_events_groupby_time_reg(true, $period_select->period, $period_select->start, $period_select->end);


/* сортируем */


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
  add_null_in_data_week($arr_data_users_event_summ);
  add_null_in_data_week($arr_data_users_event_incr);

}

if (is_array($arr_data_event_summ) && count($arr_data_event_summ) > 0 && $arr_data_event_summ != 0 ) get_list_date_arr($arr_data_event_summ);
if (is_array($arr_data_users_event_summ) && count($arr_data_users_event_summ) > 0 && $arr_data_users_event_summ != 0 ) get_list_date_arr($arr_data_users_event_summ);
if (is_array($arr_data_users_event_incr) && count($arr_data_users_event_incr) > 0 && $arr_data_users_event_incr != 0 ) get_list_date_arr($arr_data_users_event_incr);

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


$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Выбранный Перииод');
$sheet->setCellValueByColumnAndRow(2,$actual_row, $period_select->name);
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'C '.date('d.m.Y', strtotime($period_select->start)));
$sheet->setCellValueByColumnAndRow(4,$actual_row, 'по '.date('d.m.Y', strtotime($period_select->end)));
$actual_row++;

$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Сумма');

foreach ($arr_data_period as $key => $value) {
  if($period_select->period == 'year'){
      $sheet->setCellValueByColumnAndRow(($key+3), ($actual_row),  date('Y',  $value) );
  }
  if ( $period_select->period == 'month' ) {
    $sheet->setCellValueByColumnAndRow(($key+3), ($actual_row),  $arr_select_month[date('n',  $value)]->name.' '.date('Y',  $value)  );
  }
  if ( $period_select->period == 'week' ) {
    $sheet->setCellValueByColumnAndRow(($key+3), ($actual_row),  date('W',  $value)." неделя ".$arr_select_month[date('n',  $value)]->name.' '.date('Y',  $value)  );
  }
}


for ( $i = 1 ; $i < count($arr_data_period)+3 ; $i++) {
  $sheet->getcolumndimensionbycolumn($i)->setAutoSize(true);
}
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие данные:');
$sheet->getStyle('A'.$actual_row.':'.($sheet->getcolumndimensionbycolumn(count($arr_data_period)+2)->getcolumnIndex()).$actual_row)->getFont()->setBold(true);
$sheet->getStyle('A'.$actual_row.':'.($sheet->getcolumndimensionbycolumn(count($arr_data_period)+2)->getcolumnIndex()).$actual_row)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('8af28f');
$actual_row++;

//получение индекса столбца старт для формулы
$start_col = $sheet->getcolumndimensionbycolumn(3)->getcolumnIndex();
$end_col = $sheet->getcolumndimensionbycolumn(count($arr_data_period)+3)->getcolumnIndex();

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Кол-во мероприятий (год/месяц/неделя)');

foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, ($key+1), $actual_row, $value, $arr_data_event_summ);
}
$sheet->setCellValueByColumnAndRow(2,$actual_row, '=SUM('.$start_col.'3:'.$end_col.'3)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Кол-во участников мероприятий (год/месяц/неделя)');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, ($key+1), $actual_row, $value, $arr_data_users_event_summ);
}
$sheet->setCellValueByColumnAndRow(2,$actual_row, '=SUM('.$start_col.$actual_row.':'.$end_col.''.$actual_row.')');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Прирост участников мероприятий в % по отношению к аналогичному предыдущему периоду');
foreach ($arr_data_period as $key => $value) {
  if(is_Array($arr_data_users_event_incr)) {
    foreach ($arr_data_users_event_incr as $key2 => $value2) {
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

      if($value == $date1) {
        $sheet->setCellValueByColumnAndRow(($key+3), ($actual_row), $value2->percent."%");
        break;
      } else {
        $sheet->setCellValueByColumnAndRow(($key+3), ($actual_row), "0%");
      }
    }
  } else {
    $sheet->setCellValueByColumnAndRow(($key+3), ($actual_row), "0%");
  }
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
