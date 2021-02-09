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


if ( $period_select->period == 'year' ) $period_select->name = 'Год';
if ( $period_select->period == 'month' ) $period_select->name = 'Месяц';
if ( $period_select->period == 'week' ) $period_select->name = 'Неделя';
if ( $period_select->period == 'day') $period_select->name = 'День';

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_array_general = $settings->get_count_users_groupby_time_reg($period_select->period, $period_select->data1, $period_select->data2);
$data_array_accaunt = $settings->get_count_users_entity_groupby_time_reg($period_select->period, $period_select->data1, $period_select->data2);

$data_summ = array_pop($data_array_general);
$data_summ2 = array_pop($data_array_accaunt);


function period($a, $b) {
  global $period_select;
  if($period_select->period == 'day'){
    $date1 = $a->yeard.'-'.$a->monthd.'-'.$a->dayd;
    $date2 = $b->yeard.'-'.$b->monthd.'-'.$b->dayd;
  }
  if($period_select->period == 'week'){
    $date1 = $a->yeard.'W'.$a->weekd;
    $date2 = $b->yeard.'W'.$b->weekd;
  }
  if($period_select->period == 'month'){
      $date1 = '01.'.$a->monthd.'.'.$a->yeard;
      $date2 = '01.'.$b->monthd.'.'.$b->yeard;
  }
  if($period_select->period == 'year'){
      $date1 = '01.01.'.$a->yeard;
      $date2 = '01.01.'.$b->yeard;
  }

  // else {
  //   $date1 = $a->yeard.'-'.$a->monthd.'-00';
  //   $date2 = $b->monthd.'-'.$b->yeard.'-00';
  // }
  if (( strtotime( $date1 ) == strtotime( $date2 ) )) {
      return 0;
  }
  return ( strtotime( $date1 ) < strtotime( $date2 ) ) ? -1 : 1;
}


// тут твориться магия сюда не лезть, пожалуйста.. №2
$arr_merge_count = array_merge($data_array_general, $data_array_accaunt);

if ($period_select->period == 'week') {
  foreach( $arr_merge_count as $key => $value ) {
    if($value->weekd >= 1 && $value->weekd <= 9 ){
      $value->weekd = '0'.$value->weekd;
    }
  }
}

// if ($period_select->period == 'month') {
//   foreach( $arr_merge_count as $key => $value ) {
//     if($value->monthd >= 1 && $value->monthd <= 9 ){
//       $value->monthd = '0'.$value->monthd;
//     }
//   }
// }



usort($arr_merge_count, 'period');



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
// //
// // // foreach ($arr_merge_count as $key => $value) {
// // //   echo $value->weekd.' '.$value->yeard."</br>";
// // // };
// exit();

require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/office/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


$spreadsheet = new Spreadsheet();
//$sheet = $spreadsheet->getActiveSheet();
$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Пользователи');

$sheet->getColumnDimension('A')->setWidth(40);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(10);
// $sheet->getColumnDimensionByColumn(5)->setWidth(20);
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие данные');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Детализация по');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'Дата с');
$sheet->setCellValueByColumnAndRow(4,$actual_row, 'По');
$actual_row++;
$sheet->setCellValueByColumnAndRow(2,$actual_row, $period_select->name);
$sheet->setCellValueByColumnAndRow(3,$actual_row, date('d.m.Y', strtotime($period_select->data1)));
$sheet->setCellValueByColumnAndRow(4,$actual_row, date('d.m.Y', strtotime($period_select->data2)));
$actual_row++;
$actual_row++;
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество общее (зарег-но в системе)');
$sheet->setCellValueByColumnAndRow(2,4, 'Сумма');
$sheet->setCellValueByColumnAndRow(2,5, $data_summ->summ_all);
$sheet->setCellValueByColumnAndRow(2,6, $data_summ2->summ_all);

$actual_row = 4;
$actual_col = 1;
$temp_data_foreach;
$temp_data_arr_foerch = [];

$test = array_values($arr_merge_count);

foreach ($arr_merge_count as $key => $value) {

  if($period_select->period == 'day'){
    $date1 = strtotime($value->yeard.'-'.$value->monthd.'-'.$value->dayd);
  }
  if($period_select->period == 'week'){
    $date1 = strtotime($value->yeard.'W'.$value->weekd);
  }
  if($period_select->period == 'month'){
      $date1 = strtotime('01.'.$value->monthd.'.'.$value->yeard);
  }
  if($period_select->period == 'year'){
      $date1 = strtotime('01.01.'.$value->yeard);
  }

  // else {
  //   $date1 = strtotime($value->yeard.'-'.$value->monthd.'-00');
  // }
  if ($temp_data_foreach != $date1) {
      $temp_data_foreach = $date1;
      array_push( $temp_data_arr_foerch, $date1);
  }
}



foreach ($temp_data_arr_foerch as $key => $value) {

  if($period_select->period == 'year'){
      $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row),  date('Y',  $value) );
  }
  if ( $period_select->period == 'month' ) {
    $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row),  $arr_select_month[date('n',  $value)]->name.' '.date('Y',  $value)  );
  }
  if ( $period_select->period == 'week' ) {
    $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row),  date('W',  $value)." неделя ".$arr_select_month[date('n',  $value)]->name.' '.date('Y',  $value)  );
  }
  if ( $period_select->period == 'day') {
    $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row),  date('d',  $value)." ".$arr_select_month[date('n',  $value)]->name.' '.date('Y',  $value)  );
  }
  $sheet->getColumnDimensionByColumn($actual_col+2)->setWidth(20);

  // $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row),  date('d',  $value)." ".$arr_select_month[date('n',  $value)]->name.' '.date('Y',  $value)  );

    foreach ($data_array_general as $key2 => $value2) {
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
        $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+1), $value2->sum );
        break;
      } else {
        $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+1), 0);
      }
    }
    foreach ($data_array_accaunt as $key2 => $value2) {
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
        $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+2), $value2->sum );
        break;
      } else {
        $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+2), 0);
      }
    }
    $actual_col++;
}

 $sheet->setCellValueByColumnAndRow(1,$actual_row+2, 'Кол-во физ.лиц - владельцев аккаунтов юр.лиц');

 // $sheet->getActiveSheet()->calculateColumnWidths();

// foreach ($arr_merge_count as $key => $value) {
//   if($temp_data_foreach != trim($value->dayd.' '.$arr_select_month[$value->monthd]->name.' '.$value->yeard)) {
//     $sheet->setCellValueByColumnAndRow(($actual_col+2), $actual_row,  trim($value->dayd.' '.$arr_select_month[$value->monthd]->name.' '.$value->yeard));
//     $temp_name_to_obj = trim($value->dayd.' '.$arr_select_month[$value->monthd]->name.' '.$value->yeard);
//     $temp_data_arr_foerch->$temp_name_to_obj = ($actual_col+2);
//   }
//   if($temp_data_foreach == trim($value->dayd.' '.$arr_select_month[$value->monthd]->name.' '.$value->yeard)) {
//     continue;
//   }
//   $temp_data_foreach = trim($value->dayd.' '.$arr_select_month[$value->monthd]->name.' '.$value->yeard);
//
//   $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+1), 0);
//   $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+2), 0);
//
//   if(isset($value->users_groupby)){
//     $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+1), $value->sum);
//   }
//   $actual_col++;
// }
// $actual_row++;
// $actual_row++;

// foreach ($arr_merge_count as $key => $value) {
//   $temp_data_foreach = trim($value->dayd.' '.$arr_select_month[$value->monthd]->name.' '.$value->yeard);
//   //раскладка по месяцам
//   if (isset($value->users_entity_groupby)) {
//     $temp_data_foreach = trim($value->dayd.' '.$arr_select_month[$value->monthd]->name.' '.$value->yeard);
//     $sheet->setCellValueByColumnAndRow($temp_data_arr_foerch->$temp_data_foreach, $actual_row, $value->sum);
//   }
// }

// $sheet->setCellValueByColumnAndRow(1,$actual_row, 'Кол-во физ.лиц - владельцев аккаунтов юр.лиц');
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
