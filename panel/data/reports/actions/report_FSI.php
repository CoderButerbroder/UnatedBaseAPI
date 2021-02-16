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
$period_select->start =  date('Y-m-d 00:00:00', (strtotime(trim($_POST["start"]))-86400) );
$period_select->end =  date('Y-m-d 23:59:59', strtotime(trim($_POST["end"])));

// $period_select->start =  NULL;
// $period_select->end =  NULL;

// var_dump($period_select->start);
// echo "</br>";
// var_dump($period_select->end);
// exit();

// $period_select->period = 'month';
// $period_select->start =  date('Y-m-d H:i:s', strtotime('01.01.2020'));
// $period_select->end =  date('Y-m-d H:i:s', strtotime('30.02.2021'));

if ($period_select->start && $period_select->end && $period_select->period != 'year' && $period_select->period != 'month' && $period_select->period != 'week') {
  exit();
}

if ( $period_select->period == 'year' ) $period_select->name = 'Год';
if ( $period_select->period == 'month' ) $period_select->name = 'Месяц';
if ( $period_select->period == 'week' ) $period_select->name = 'Неделя';

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$arr_FSI_count = $settings->get_count_main_entity_fci_groupby_time_reg(true, $period_select->period, $period_select->start , $period_select->end );
$arr_FSI_YMNIK_count = $settings->get_count_main_entity_fci_program_groupby_time_reg(true,'У', $period_select->period, $period_select->start , $period_select->end );

$arr_FSI_count_financing = $settings->get_count_sum_support_fci_groupby_time_reg(false, $period_select->period, $period_select->start , $period_select->end );


$arr_FSI_count_service =  $settings->get_count_main_entity_skolkovo_programs_groupby_time_reg(false,
                                      ['Запрос на консультацию проекта при подаче заявки в ФСИ','Информационное сопровождение проекта ФСИ'],
                                      $period_select->period, $period_select->start , $period_select->end );

$arr_SK_count = $settings->get_count_main_entity_skolkovo_groupby_time_reg(true, $period_select->period, $period_select->start , $period_select->end );
$arr_SK_count_event =  $settings->get_count_main_support_ticket_groupby_time_add(true, 'Запрос на консультацию компании - Фонд Сколково', $period_select->period , $period_select->start , $period_select->end );
$arr_SK_count_event_incr =  $settings->get_count_main_entity_skolkovo_visit_event_groupby_time_reg(true, $period_select->period, $period_select->start , $period_select->end );
$arr_SK_count_service =  $settings->get_count_main_entity_skolkovo_programs_groupby_time_reg(false,
                                      ['Запрос на консультацию компании - Фонд Сколково','Информационное сопровождение проекта Сколково'],
                                      $period_select->period, $period_select->start , $period_select->end );



$arr_CKP_count =  $settings->get_count_main_support_ticket_groupby_time_add(true, 'Получение услуги центра коллективного пользования (производство)', $period_select->period , $period_select->start , $period_select->end );

$arr_service_FSI = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Запрос на консультацию проекта при подаче заявки в ФСИ', $period_select->period , $period_select->start , $period_select->end );
$arr_service_organization_meeting = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Организация встречи с инвестором/индустриальным партнером и т.д.', $period_select->period , $period_select->start , $period_select->end );
$arr_service_CKP = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Получение услуги центра коллективного пользования (производство)', $period_select->period , $period_select->start , $period_select->end );
$arr_service_sturtup_organization_meeting = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Организация участия стартапа в мероприятиях', $period_select->period , $period_select->start , $period_select->end );
$arr_service_translate = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Перевод материалов на английский язык', $period_select->period , $period_select->start , $period_select->end );
$arr_service_event = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Организация встречи с проектным менеджером Сколково', $period_select->period , $period_select->start , $period_select->end );
$arr_service_SK = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Запрос на консультацию компании - Фонд Сколково', $period_select->period , $period_select->start , $period_select->end );
$arr_service_support = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Запрос информационной поддержки проекта', $period_select->period , $period_select->start , $period_select->end );
$arr_service_preparation_application = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Консультация по подготовке заявки на статус участника', $period_select->period , $period_select->start , $period_select->end );
$arr_service_support_IS = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Консультация по регистрации объектов ИС', $period_select->period , $period_select->start , $period_select->end );
//
//
// echo json_encode($arr_FSI_count, JSON_UNESCAPED_UNICODE);
// exit();

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
  add_null_in_data_week($arr_FSI_count);
  add_null_in_data_week($arr_FSI_YMNIK_count);
  add_null_in_data_week($arr_FSI_count_service);
  add_null_in_data_week($arr_FSI_count_financing);
  add_null_in_data_week($arr_SK_count);
  add_null_in_data_week($arr_SK_count_event);
  add_null_in_data_week($arr_SK_count_event_incr);
  add_null_in_data_week($arr_SK_count_service);
  add_null_in_data_week($arr_CKP_count);
  add_null_in_data_week($arr_service_FSI);
  add_null_in_data_week($arr_service_organization_meeting);
  add_null_in_data_week($arr_service_CKP);
  add_null_in_data_week($arr_service_sturtup_organization_meeting);
  add_null_in_data_week($arr_service_translate);
  add_null_in_data_week($arr_service_event);
  add_null_in_data_week($arr_service_SK);
  add_null_in_data_week($arr_service_support);
  add_null_in_data_week($arr_service_preparation_application);
  add_null_in_data_week($arr_service_support_IS);
}

if (is_array($arr_FSI_count) && count($arr_FSI_count) > 0 && $arr_FSI_count != 0 ) get_list_date_arr($arr_FSI_count);
if (is_array($arr_FSI_YMNIK_count) && count($arr_FSI_YMNIK_count) > 0 && $arr_FSI_YMNIK_count != 0 ) get_list_date_arr($arr_FSI_YMNIK_count);
if (is_array($arr_FSI_count_financing) && count($arr_FSI_count_financing) > 0 && $arr_FSI_count_financing != 0 ) get_list_date_arr($arr_FSI_count_financing);

if (is_array($arr_FSI_count_service) && count($arr_FSI_count_service) > 0 && $arr_FSI_count_service != 0 ) get_list_date_arr($arr_FSI_count_service);
if (is_array($arr_SK_count) && count($arr_SK_count) > 0 && $arr_SK_count != 0 ) get_list_date_arr($arr_SK_count);
if (is_array($arr_SK_count_event) && count($arr_SK_count_event) > 0 && $arr_SK_count_event != 0 ) get_list_date_arr($arr_SK_count_event);
if (is_array($arr_SK_count_event_incr) && count($arr_SK_count_event_incr) > 0 && $arr_SK_count_event_incr != 0 ) get_list_date_arr($arr_SK_count_event_incr);
if (is_array($arr_SK_count_service) && count($arr_SK_count_service) > 0 && $arr_SK_count_service != 0 ) get_list_date_arr($arr_SK_count_service);

if (is_array($arr_CKP_count) && count($arr_CKP_count) > 0 && $arr_CKP_count != 0 ) get_list_date_arr($arr_CKP_count);

if (is_array($arr_service_FSI) && count($arr_service_FSI) > 0 && $arr_service_FSI != 0 ) get_list_date_arr($arr_service_FSI);
if (is_array($arr_service_organization_meeting) && count($arr_service_organization_meeting) > 0 && $arr_service_organization_meeting != 0 ) get_list_date_arr($arr_service_organization_meeting);
if (is_array($arr_service_CKP) && count($arr_service_CKP) > 0 && $arr_service_CKP != 0 ) get_list_date_arr($arr_service_CKP);
if (is_array($arr_service_sturtup_organization_meeting) && count($arr_service_sturtup_organization_meeting) > 0 && $arr_service_sturtup_organization_meeting != 0 ) get_list_date_arr($arr_service_sturtup_organization_meeting);
if (is_array($arr_service_translate) && count($arr_service_translate) > 0 && $arr_service_translate != 0 ) get_list_date_arr($arr_service_translate);
if (is_array($arr_service_event) && count($arr_service_event) > 0 && $arr_service_event != 0 ) get_list_date_arr($arr_service_event);
if (is_array($arr_service_SK) && count($arr_service_SK) > 0 && $arr_service_SK != 0 ) get_list_date_arr($arr_service_SK);
if (is_array($arr_service_support) && count($arr_service_support) > 0 && $arr_service_support != 0 ) get_list_date_arr($arr_service_support);
if (is_array($arr_service_preparation_application) && count($arr_service_preparation_application) > 0 && $arr_service_preparation_application != 0 ) get_list_date_arr($arr_service_preparation_application);
if (is_array($arr_service_support_IS) && count($arr_service_support_IS) > 0 && $arr_service_support_IS != 0 ) get_list_date_arr($arr_service_support_IS);

sort($arr_data_period);

// var_dump($arr_FSI_count);
// echo "</br>";
// echo "</br>";
// var_dump($arr_FSI_YMNIK_count);
// echo "</br>";
// echo "</br>";
// var_dump($arr_FSI_count_service);
// echo "</br>";
// echo "</br>";
// var_dump($arr_FSI_count_financing);
// echo "</br>";
// echo "</br>";
// var_dump($arr_SK_count);
// echo "</br>";
// echo "</br>";
// var_dump($arr_SK_count_event);
// echo "</br>";
// echo "</br>";
// var_dump($arr_SK_count_event_incr);
// echo "</br>";
// echo "</br>";
// var_dump($arr_SK_count_service);
// echo "</br>";
// echo "</br>";
// var_dump($arr_CKP_count);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_FSI);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_organization_meeting);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_CKP);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_sturtup_organization_meeting);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_translate);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_event);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_SK);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_support);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_preparation_application);
// echo "</br>";
// echo "</br>";
// var_dump($arr_service_support_IS);
// echo "</br>";
// echo "</br>";
//
// exit();

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

$styleArray = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '00000000'],
        ]
    ]
];

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setTitle("Показетели Фонды, Институты развития");
$spreadsheet->getProperties()->setSubject("Показетели Фонды, Институты развития");
$spreadsheet->getProperties()->setCreator("Fulldata");
$spreadsheet->getProperties()->setManager("Fulldata");
$spreadsheet->getProperties()->setCompany("ОАО Ленполиграфмаш");
$spreadsheet->getProperties()->setCategory("Работа");
$spreadsheet->getProperties()->setKeywords("Отчет, Fulldata, Фуллдата, ЛПМ, Сервисы, Показатели, Данные");
$spreadsheet->getProperties()->setDescription("Сгенерированный отчет");
$spreadsheet->getProperties()->setLastModifiedBy("Fulldata");
$spreadsheet->getProperties()->setCreated(date("d.m.Y"));

$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Фонды, Институты развития');

$sheet->getColumnDimension('A')->setAutoSize(true);
foreach ($arr_data_period as $key => $value) {
  $sheet->getcolumndimensionbycolumn(($key+2))->setAutoSize(true);
}

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
for ($i=1; $i <= (count($arr_data_period)+1); $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество юр. лиц - участников программ ФСИ на платформе (нараст.итог)');
$temp_value_iter = 0;
foreach ($arr_data_period as $key => $value) {
  $temp_value_iter = set_cell_value($sheet, $key, $actual_row, $value, $arr_FSI_count, $temp_value_iter);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. количеcтво физ лиц участников программы Умник на платформе (нараст.итог)');
$temp_value_iter = 0;
foreach ($arr_data_period as $key => $value) {
  $temp_value_iter = set_cell_value($sheet, $key, ($actual_row), $value, $arr_FSI_YMNIK_count, $temp_value_iter);
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. прирост к предыдущему месяцу в процентах');
foreach ($arr_data_period as $key => $value) {
  if(is_Array($arr_FSI_count)) {
    foreach ($arr_FSI_count as $key2 => $value2) {
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
        $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row), $value2->percent."%");
        break;
      } else {
        $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row), "0%");
      }
    }
  } else {
    $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row), "0%");
  }
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (процессы, количество)');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i <= (count($arr_data_period)+1); $i++) {
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
for ($i=1; $i <= (count($arr_data_period)+1); $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'кол-во новых проектов (поданных) (ед. в '.$period_select->name.')');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_FSI_count_service);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'объем привлеченного финансирования, млн.руб, в '.$period_select->name);
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_FSI_count_financing);
}
$actual_row++;
$actual_row++;
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'СКОЛКОВО');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (регистрации. количество)');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i <= (count($arr_data_period)+1); $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество юр. лиц - участников Сколково на платформе (нараст.итог)');
$temp_value_iter = 0;
foreach ($arr_data_period as $key => $value) {
  $temp_value_iter = set_cell_value($sheet, $key, $actual_row, $value, $arr_SK_count, $temp_value_iter);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'прирост к предыдщему месяцу в процентах');
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
        $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row), $value2->percent."%");
        break;
      } else {
        $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row), "0%");
      }
    }
  } else {
    $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row), "0%");
  }
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатели платформы (процессы, количество)');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i <= (count($arr_data_period)+1); $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Организация встречи с проектным менеджером Сколково (нараст.итог)');
$temp_value_iter = 0;
foreach ($arr_data_period as $key => $value) {
  $temp_value_iter = set_cell_value($sheet, $key, $actual_row, $value, $arr_SK_count_event, $temp_value_iter);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Консультации по услугам ЦКП  (нараст.итог)');
$temp_value_iter = 0;
foreach ($arr_data_period as $key => $value) {
  $temp_value_iter = set_cell_value($sheet, $key, $actual_row, $value, $arr_CKP_count, $temp_value_iter);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. Участие в мероприятиях и выставках (нараст.итог)');
$temp_value_iter = 0;
foreach ($arr_data_period as $key => $value) {
  $temp_value_iter = set_cell_value($sheet, $key, $actual_row, $value, $arr_SK_count_event_incr, $temp_value_iter);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Измерение');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i <= (count($arr_data_period)+1); $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'количество новых резидентов в '.$period_select->name.' (Сколково)');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'количество услуг в '.$period_select->name.' (Сколково)');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_SK_count_service);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество заявок по сервисам ');
for ($i=1; $i <= (count($arr_data_period)+1); $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}


$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Консультация компании (консультации по сервисам, юридическим вопросам, отчетам и т.п.)');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_FSI);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Организация встречи с инвестором/индустриальным партнером и т.д.');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_organization_meeting);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Маркетинговые услуги');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setStrikethrough(true);
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Консультация в части услуги ЦКП');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_CKP);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Организация участия стартапа в мероприятиях');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_sturtup_organization_meeting);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Консультация по заполнению заявки на грант');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setStrikethrough(true);
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Перевод материалов на английский язык');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_translate);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Организация встречи с проектным менеджером Сколково');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_SK);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Подготовка заявок к внешним конкурсам институтов развития');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setStrikethrough(true);
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Организация встречи с департаментом международных отношений Сколково');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setStrikethrough(true);
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Информационное сопровождение проекта');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_support);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Помощь в подготовке продуктовой презентации');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setStrikethrough(true);
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Консультация по подготовке заявки на статус участника');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_preparation_application);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Консультация по регистрации объектов ИС');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_service_support_IS);
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Предоставление юридического адреса');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setStrikethrough(true);

for ( $i = 1; $i <= $actual_row; $i++) {
  for ($j=1; $j <= (count($arr_data_period)+1); $j++) {
    $sheet->getCellByColumnAndRow($j,$i)->getStyle()->applyFromArray($styleArray);
  }
}


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
