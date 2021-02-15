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
// $period_select->period = $_POST["period"];
// $period_select->data1 =  date('Y-m-d H:i:s', strtotime(trim($_POST["period_1"])));
// $period_select->data2 =  date('Y-m-d H:i:s', strtotime(trim($_POST["period_2"])));

$period_select->period = 'month';
$period_select->data1 =  date('Y-m-d H:i:s', strtotime( '01.11.2020' ));
$period_select->data2 =  date('Y-m-d H:i:s', strtotime( '15.02.2021' ));

if ($period_select->data1 && $period_select->data2 && $period_select->period != 'year' && $period_select->period != 'month' && $period_select->period != 'week') {
  exit();
}

if ( $period_select->period == 'year' ) $period_select->name = 'Год';
if ( $period_select->period == 'month' ) $period_select->name = 'Месяц';
if ( $period_select->period == 'week' ) $period_select->name = 'Неделя';


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

/* получаем данные */



// получение количества мероприятий
function get_count_main_events_groupby_time_reg($increment=false,$period='data',$start=NULL,$end=NULL) {
        global $database;


        if (!$start) {
          $start_date = $database->prepare("SELECT start_datetime_event FROM $this->MAIN_events ORDER BY start_datetime_event ASC LIMIT 1");
          $start_date->execute();
          $start = $start_date->fetch(PDO::FETCH_COLUMN);
        }
        if (!$end) {
          $end_date = $database->prepare("SELECT start_datetime_event FROM $this->MAIN_events ORDER BY start_datetime_event DESC LIMIT 1");
          $end_date->execute();
          $end = $end_date->fetch(PDO::FETCH_COLUMN);
        }

        $strokaSQL = "SELECT ";

        if ($period == 'year') {
              $strokaSQL .= " count(DISTINCT($this->MAIN_events.`id`)) as sum,
                              YEAR($this->MAIN_events.`start_datetime_event`) as yeard,
                              count(DISTINCT($this->MAIN_events.`id`)) as event_groupby";
        }
        if ($period == 'month') {
              $strokaSQL .= " count(DISTINCT($this->MAIN_events.`id`)) as sum,
                              MONTH($this->MAIN_events.`start_datetime_event`) as monthd,
                              YEAR($this->MAIN_events.`start_datetime_event`) as yeard,
                              count(DISTINCT($this->MAIN_events.`id`)) as event_groupby";
        }
        if ($period == 'week') {
              $strokaSQL .= " count(DISTINCT($this->MAIN_events.`id`)) as sum,
                              WEEK($this->MAIN_events.`start_datetime_event`) as weekd,
                              YEAR($this->MAIN_events.`start_datetime_event`) as yeard,
                              count(DISTINCT($this->MAIN_events.`id`)) as event_groupby";
        }
        if ($period == 'day') {
              $strokaSQL .= " count(DISTINCT($this->MAIN_events.`id`)) as sum,
                              DAY($this->MAIN_events.`start_datetime_event`) as dayd,
                              MONTH($this->MAIN_events.`start_datetime_event`) as monthd,
                              YEAR($this->MAIN_events.`start_datetime_event`) as yeard,
                              count(DISTINCT($this->MAIN_events.`id`)) as event_groupby";
        }
        if ($period == 'data') {
              $strokaSQL .= " * ";
        }

        $strokaSQL .= " FROM $this->MAIN_events
                        WHERE start_datetime_event BETWEEN :starting AND :ending ";

        if ($period == 'year') {
            $strokaSQL .= " GROUP BY YEAR(start_datetime_event)
                            HAVING SUM($this->MAIN_events.`id`) > 0
                            ORDER BY YEAR(start_datetime_event) ASC";
        }
        if ($period == 'month') {
            $strokaSQL .= " GROUP BY MONTH(start_datetime_event), YEAR(start_datetime_event)
                            HAVING SUM($this->MAIN_events.`id`) > 0
                            ORDER BY YEAR(start_datetime_event), MONTH(start_datetime_event) ASC";
        }
        if ($period == 'week') {
            $strokaSQL .= " GROUP BY WEEK(start_datetime_event), YEAR(start_datetime_event)
                            HAVING SUM($this->MAIN_events.`id`) > 0
                            ORDER BY YEAR(start_datetime_event), WEEK(start_datetime_event) ASC";
        }
        if ($period == 'day') {
            $strokaSQL .= " GROUP BY DAY(start_datetime_event),MONTH(start_datetime_event), YEAR(start_datetime_event)
                            HAVING SUM($this->MAIN_events.`id`) > 0
                            ORDER BY YEAR(start_datetime_event), MONTH(start_datetime_event),DAY(start_datetime_event) ASC";
        }
        if ($period == 'data') {
            $strokaSQL .= " ";
        }

        $statement = $database->prepare($strokaSQL);
        $statement->bindParam(':starting', $start, PDO::PARAM_STR);
        $statement->bindParam(':ending', $end, PDO::PARAM_STR);
        $statement->execute();
        $data_events = $statement->fetchAll(PDO::FETCH_OBJ);

        if (!$data_events) {
            return 0;
            exit;
        }

        if ($increment == true) {
          foreach ($data_events as $key => $value) {
            if ($count_sum != 0) {$value->percent = $value->sum * 100 / $count_sum;}
            else {$value->percent = 0;}
            $value->sum = $value->sum + $count_sum;
            $count_sum = $value->sum;
          }
        }

        return $data_events;
        exit;
}


// var_dump(get_count_main_events_groupby_time_reg(false, $period_select->period));


echo json_encode(get_count_main_events_groupby_time_reg(false, $period_select->period), JSON_UNESCAPED_UNICODE);


exit();









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
  // add_null_in_data_week($arr_FSI_count);
}

// if (is_array($arr_FSI_count) && count($arr_FSI_count) > 0 && $arr_FSI_count != 0 ) get_list_date_arr($arr_FSI_count);


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
$sheet->setTitle('Мероприятия');


$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);


$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Выбранный Перииод');
$sheet->setCellValueByColumnAndRow(2,$actual_row, $period_select->name);
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'С '.date('H:i d.m.Y', strtotime( $period_select->data1 )) );
$sheet->setCellValueByColumnAndRow(4,$actual_row, 'по '.date('H:i d.m.Y', strtotime( $period_select->data2 )) );
$actual_row++;


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
