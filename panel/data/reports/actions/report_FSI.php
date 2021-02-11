<?php
/* отчет по показателям ФСИ */
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
$period_select->data1 =  date('Y-m-d H:i:s', strtotime(trim($_POST["start"])));
$period_select->data2 =  date('Y-m-d H:i:s', strtotime(trim($_POST["end"])));

$period_select->period = 'month';
$period_select->start =  date('Y-m-d H:i:s', strtotime('01.01.2020'));
$period_select->end =  date('Y-m-d H:i:s', strtotime('30.02.2021'));

if ($period_select->start && $period_select->end && $period_select->period != 'year' && $period_select->period != 'month' && $period_select->period != 'week') {
  exit();
}

if ( $period_select->period == 'year' ) $period_select->name = 'Год';
if ( $period_select->period == 'month' ) $period_select->name = 'Месяц';
if ( $period_select->period == 'week' ) $period_select->name = 'Неделя';


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$arr_FSI_count = $settings->get_count_main_entity_fci_groupby_time_reg(true, $period_select->period , $period_select->start , $period_select->end );
$arr_FSI_YMNIK_count = $settings->get_count_main_entity_fci_program_groupby_time_reg(true,'У', $period_select->period , $period_select->start , $period_select->end );

$arr_SK_count = $settings->get_count_main_entity_skolkovo_groupby_time_reg(true, $period_select->period , $period_select->start , $period_select->end );

$arr_data_period = [];


function get_list_date_arr( $arr_in ) {
  global $period_select, $arr_data_period;
  foreach ($arr_in as $key => $value) {
    if ( $period_select->period == 'week' )  $data_i = strtotime(  $value->yeard.'W'.$value->weekd );
    if ( $period_select->period == 'month')  $data_i = strtotime( '01.'.$value->monthd.'.'.$value->yeard );
    if ( $period_select->period == 'year' )  $data_i = strtotime( '01.01.'.$value->yeard );
    if (!in_array($data_i, $arr_data_period)) {
        array_push($arr_data_period, $data_i);
    }
  }
}

function set_cell_value($sheet_in, $key, $row, $data_in, $arr_in) {
  global $period_select;
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
        return 0;
      } else {
        $sheet_in->setCellValueByColumnAndRow(($key+2), $row, 0);
      }
    }
  } else {
    $sheet_in->setCellValueByColumnAndRow(($key+2), ($row), 0);
  }
}

if (is_array($arr_FSI_count) && count($arr_FSI_count) > 0 && $arr_FSI_count != 0 ) get_list_date_arr($arr_FSI_count);
if (is_array($arr_FSI_YMNIK_count) && count($arr_FSI_YMNIK_count) > 0 && $arr_FSI_YMNIK_count != 0 ) get_list_date_arr($arr_FSI_YMNIK_count);
if (is_array($arr_SK_count) && count($arr_SK_count) > 0 && $arr_SK_count != 0 ) get_list_date_arr($arr_SK_count);


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


require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/office/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


$spreadsheet = new Spreadsheet();
$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Фонды, Институты развития');

$sheet->getColumnDimension('A')->setAutoSize(true);

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатель');
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

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Наименование направления работы -');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'ФСИ');
$sheet->getCellByColumnAndRow(1,3)->getStyle()->getFont()->setBold(true);
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (регистрации. количество)');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 8; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество юр. лиц - участников программ ФСИ на платформе (нараст.итог)');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_FSI_count);
  set_cell_value($sheet, $key, ($actual_row+1), $value, $arr_FSI_YMNIK_count);
}
$actual_row++;


$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. количеcтво физ лиц участников программы Умник на платформе (нараст.итог)');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. прирост к предыдущему месяцу в процентах');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (процессы, количество)');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 8; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Письмо поддержки от представителя (ед. в мес)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Выезд для обследования предприятия подавшего заявку (нараст.итог)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. Аттестация умников через заведение проекта в сервис командообразование (нараст.итог)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Измерение');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 8; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'кол-во новых проектов (поданных) (ед. в мес)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'объем привлеченного финансирования, млн.руб, в мес');
$actual_row++;
$actual_row++;
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'СКОЛКОВО');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (регистрации. количество)');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 8; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество юр. лиц - участников Сколково на платформе (нараст.итог)');
foreach ($arr_data_period as $key => $value) {
  if(is_Array($arr_SK_count)) {
    foreach ($arr_SK_count as $key2 => $value2) {
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
        $sheet->setCellValueByColumnAndRow(($key+2), $actual_row, $value2->sum);
        $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row+1), $value2->percent."%");
        break;
      } else {
        $sheet->setCellValueByColumnAndRow(($key+2), $actual_row, 0);
        $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row+1), "0%");
      }
    }
  } else {
    $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row), 0);
    $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row+1), "0%");
  }
}


$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'прирост к предыдщему месяцу в процентах');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (процессы, количество)');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 8; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Организация встречи с проектным менеджером Сколково (нараст.итог)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Консультации по услугам ЦКП  (нараст.итог)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. Участие в мероприятиях и выставках (нараст.итог)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Измерение');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 8; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'количество новых резидентов в месяц (Сколково)');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'количество услуг в месяц (Сколково)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество заявок по сервисам ');
for ($i=1; $i < 8; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Информационное сопровождение проекта (консультации по сервисам, юридическим вопросам, отчетам и т.п.)');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Организация встречи с инвестором/индустриальным партнером и т.д.');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Маркетинговые услуги');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Консультация в части услуги ЦКП');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Организация участия стартапа в мероприятиях');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Консультация по заполнению заявки на грант');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Перевод материалов на английский язык');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Организация встречи с проектным менеджером Сколково');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Подготовка заявок к внешним конкурсам институтов развития');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Организация встречи с департаментом международных отношений Сколково');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Подготовка и размещение информации в СМИ/интернет');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Помощь в подготовке продуктовой презентации');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Помощь в подготовке заявки на статус участника');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Помощь в регистрации объектов ИС');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Предоставление юридического адреса');
$actual_row++;



$writer = new Xlsx($spreadsheet);
$now = date("_H_i_d_m_Y");
$now = trim($now);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_FSI_FULLDATA'.$now.'.xlsx"');
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
