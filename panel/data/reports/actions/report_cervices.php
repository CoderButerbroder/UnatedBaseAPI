<?php
/* Отчет по сервисам */
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

$period_select = (object) [];
$arr_data_period = [];

$period_select->period = (isset($_POST["period"])) ? trim($_POST["period"]) : 'month';
$period_select->start =  date('Y-m-d 00:00:00', (strtotime(trim($_POST["start"]))-86400) );
$period_select->end =  date('Y-m-d 23:59:59', strtotime(trim($_POST["end"])));

// $period_select->start = '2020-11-01 00:00:00';
// $period_select->end = '2021-02-16 23:59:59';

if ($period_select->start && $period_select->end && $period_select->period != 'year' && $period_select->period != 'month' && $period_select->period != 'week') {
  exit();
}

if ( $period_select->period == 'year' ) $period_select->name = 'Год';
if ( $period_select->period == 'month' ) $period_select->name = 'Месяц';
if ( $period_select->period == 'week' ) $period_select->name = 'Неделя';

/* сортировки */
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

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

/* получаем данные */
$arr_count_all_time_cervices = $settings->get_sum_all_services_lpmtech();


/* опытное производство, протоптип, инжинеринг */
//1. Количество обращений по сервису "Получение услуги ЦКП"
$arr_CKP_count = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Получение услуги центра коллективного пользования (производство)', $period_select->period , $period_select->start , $period_select->end );
//2. Количество обращений по сервису "Получение услуг конструкторского бюро"
$arr_design_bureau_count = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Получение услуги конструкторского бюро', $period_select->period , $period_select->start , $period_select->end );

/* Информационое освещение */
//1. Количество обращений по сервису "Запрос информационной поддержки проекта"
$arr_supp_project_count = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Запрос информационной поддержки проекта', $period_select->period , $period_select->start , $period_select->end );
//2. Количество обращений по сервису "Подача предложения в каталог производственных возможностей"
$arr_request_katalog_count = $settings->get_count_main_support_ticket_groupby_time_add(false, 'Подача предложения в каталог производственных возможностей', $period_select->period , $period_select->start , $period_select->end );

if ($period_select->period == 'week') {
  add_null_in_data_week($arr_CKP_count);
  add_null_in_data_week($arr_design_bureau_count);
  add_null_in_data_week($arr_supp_project_count);
  add_null_in_data_week($arr_request_katalog_count);
}

if (is_array($arr_CKP_count) && count($arr_CKP_count) > 0 && $arr_CKP_count != 0 ) get_list_date_arr($arr_CKP_count);
if (is_array($arr_design_bureau_count) && count($arr_design_bureau_count) > 0 && $arr_design_bureau_count != 0 ) get_list_date_arr($arr_design_bureau_count);
if (is_array($arr_supp_project_count) && count($arr_supp_project_count) > 0 && $arr_supp_project_count != 0 ) get_list_date_arr($arr_supp_project_count);
if (is_array($arr_request_katalog_count) && count($arr_request_katalog_count) > 0 && $arr_request_katalog_count != 0 ) get_list_date_arr($arr_request_katalog_count);

sort($arr_data_period);

//
//
// echo  json_encode($arr_data_period, JSON_UNESCAPED_UNICODE);
// exit();

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
$spreadsheet->getProperties()->setTitle("Показатели по сервисам");
$spreadsheet->getProperties()->setSubject("Показатели по сервисам");
$spreadsheet->getProperties()->setCreator("Fulldata");
$spreadsheet->getProperties()->setManager("Fulldata");
$spreadsheet->getProperties()->setCompany("ОАО Ленполиграфмаш");
$spreadsheet->getProperties()->setCategory("Работа");
$spreadsheet->getProperties()->setKeywords("Отчет, Fulldata, Фуллдата, ЛПМ, E-Spb, Сервисы, Показатели, Данные");
$spreadsheet->getProperties()->setDescription("Показатели по сервисам");
$spreadsheet->getProperties()->setLastModifiedBy("Fulldata");
$spreadsheet->getProperties()->setCreated(date("d.m.Y"));
$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Сервисы');


$sheet->getColumnDimension("A")->setWidth(80);
for ($i = 2; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getcolumndimensionbycolumn($i)->setAutoSize(true);
}

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие данные:');
for ($i = 1; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('8af28f');
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество юр лиц, воспользовавшихся сервисом (за все время):');

$arr_serv = [ 'Запрос письма о поддержке проекта',
              'Запрос информационной поддержки проекта',
              'Запрос на консультацию проекта при подаче заявки в ФСИ',
              'Запрос на консультацию компании - Фонд Сколково',
              'Информационное сопровождение проекта ФСИ',
              'Информационное сопровождение проекта Сколково',
              'Консультация по подготовке заявки на статус участника',
              'Консультация по регистрации объектов ИС',
              'Организация встречи с инвестором/индустриальным партнером и т.д.',
              'Организация участия стартапа в мероприятиях',
              'Получение услуги центра коллективного пользования (производство)',
              'Получение услуги конструкторского бюро',
              'Подача предложения в каталог производственных возможностей',
              'Подбор стартапов под тех.запрос.',
              'Перевод материалов на английский язык',
              'Технологический запрос крупной компании'];

foreach ($arr_serv as $key => $value) {
  $actual_row++;
  $sheet->setCellValueByColumnAndRow(1,$actual_row, $value);
  if (isset($arr_count_all_time_cervices[$value])) {
    $sheet->setCellValueByColumnAndRow(2,$actual_row, $arr_count_all_time_cervices[$value]);
  } else $sheet->setCellValueByColumnAndRow(2,$actual_row, 0);

}
$sheet->getStyle('A1:B'.$actual_row)->applyFromArray($styleArray);

$actual_row++;
$actual_row++;
$actual_row++;
$marr_int_row_services = $actual_row;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Период '.$period_select->name.' C '.date('d.m.Y', strtotime($period_select->start)).' по '.date('d.m.Y', strtotime($period_select->end)));
for ($i = 1; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('8af28f');
}

$actual_row++;
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
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Технологические запросы');
for ($i = 1; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество юр лиц, воспользовавшихся сервисом');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Подбор стартапа под технологический запрос (мэтчинг) - количество встреч');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. Технологических запросов крупных компаний (заведение в ЛК на платформе) - количество запросов');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '4. Количество юр.лиц, привлеченных с внешних платформ/событий');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Командообразование');
for ($i = 1; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество проектов-участников сервиса Командообразование');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Количество новых участников');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. Количество юр.лиц, привлеченных с внешних платформ/событий');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'ЦМИТ (детские образовательные программы)');
for ($i = 1; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество мероприятий на тбойле для детей');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Количество физ.лиц привлеченных в сервис с внешних платформ/ событий (Руками, приложение Технопарка)');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Открытый университет');
for ($i = 1; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество физ.лиц привлеченных в сервис с внешних платформ/ событий (мероприятия)');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2.Партнерские образовательные программы с резидентами');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. Корпоративные программы в рамках Повышения производительности труда и занятости');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '4. Количество новых курсов');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '5. Количество новых пользователей');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '6. денежный поток в месяц');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Опытное производство, прототип, инжиниринг');
for ($i = 1; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество обращений по сервису "Получение услуги ЦКП"');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_CKP_count);
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Количество обращений по сервису "Получение услуг конструкторского бюро"');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_design_bureau_count);
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3.  Количество юр лиц, привлеченных в сервис с внешних платформ и событий');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Информационое освещение');
for ($i = 1; $i <= (count($arr_data_period)+1) ; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('e6e6e6');
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество обращений по сервису "Запрос информационной поддержки проекта"');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_supp_project_count);
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Количество обращений по сервису "Подача предложения в каталог производственных возможностей"');
foreach ($arr_data_period as $key => $value) {
  set_cell_value($sheet, $key, $actual_row, $value, $arr_request_katalog_count);
}

$sheet->getStyle('A'.$marr_int_row_services.':'.($sheet->getcolumndimensionbycolumn(count($arr_data_period)+1)->getcolumnIndex()).$actual_row)->applyFromArray($styleArray);

$writer = new Xlsx($spreadsheet);
$now = date("_H_i_d_m_Y");
$now = trim($now);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_cervices_FULLDATA'.$now.'.xlsx"');
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
