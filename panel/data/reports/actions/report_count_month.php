<?php
/* сводный отчет в конце месяца */
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// session_start();
//
if (!isset($_SESSION["key_user"]) && trim($_GET["code"]) != 'Y1pVV7llgEeXbavAIWHjljMtj72fM9OR' ) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$arr_data_request = $settings->count_main_support_ticket_groupby_category_referer('all');
$arr_data_request_month = $settings->count_main_support_ticket_groupby_category_referer_current_mounth('all');

$arr_data_request_close = $settings->count_main_support_ticket_groupby_category_referer('close');
$arr_data_request_month_close = $settings->count_main_support_ticket_groupby_category_referer_current_mounth('close');

//подсчет количества заявок
$data_count_request_all = 0;
foreach ($arr_data_request as $key => $value) {
  $data_count_request_all += $value->count_ticket;
}

//подсчет количества заявок за месяц
$data_count_request_month = 0;
foreach ($arr_data_request_month as $key => $value) {
  $data_count_request_month += $value->count_ticket;
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

$defaut_value = '-';

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
$spreadsheet->getProperties()->setTitle("Ежедневные показатели");
$spreadsheet->getProperties()->setSubject("Ежедневные показатели");
$spreadsheet->getProperties()->setCreator("Fulldata");
$spreadsheet->getProperties()->setManager("Fulldata");
$spreadsheet->getProperties()->setCompany("ОАО Ленполиграфмаш");
$spreadsheet->getProperties()->setCategory("Работа");
$spreadsheet->getProperties()->setKeywords("Отчет, Fulldata, Фуллдата, ЛПМ, E-Spb, Сервисы, Показатели, Данные");
$spreadsheet->getProperties()->setDescription("Ежедневный автоматический сгенерированный отчет");
$spreadsheet->getProperties()->setLastModifiedBy("Fulldata");
$spreadsheet->getProperties()->setCreated(date("d.m.Y"));
//$sheet = $spreadsheet->getActiveSheet();
$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Сводные показатели');


for ($i=1; $i < 6; $i++) {
  $sheet->getcolumndimensionbycolumn($i)->setAutoSize(true);
}


$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Актуальные данные на момент '.date('H:i d.m.Y'));
$actual_row++;
$sheet->mergeCells("A2:C2");
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие показетели');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 4; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('8af28f');
}

$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, '#');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Общее количество');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'Прирост '.$arr_select_month[date("n")]->name);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'ЛК:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, $settings->count_main_entity());
$sheet->setCellValueByColumnAndRow(3,$actual_row, $settings->count_main_entity_current_month());

$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Заявки:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, $data_count_request_all);
$sheet->setCellValueByColumnAndRow(3,$actual_row, $data_count_request_month);

$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Выполненные заявки:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, $settings->count_main_support_ticket('close'));
$sheet->setCellValueByColumnAndRow(3,$actual_row, $settings->count_main_support_ticket_current_mounth($status='close'));
$actual_row++;
$sheet->getStyle('A2:C6')->applyFromArray($styleArray);

$actual_row++;
$sheet->mergeCells("A8:E8");
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количественные показатели по площакам');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
for ($i=1; $i < 6; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('8af28f');
}
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Наименование');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Общее количество');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'Прирост '.$arr_select_month[date("n")]->name);
$sheet->setCellValueByColumnAndRow(4,$actual_row, 'Количество выполненных');
$sheet->setCellValueByColumnAndRow(5,$actual_row, 'Прирост выполненных за '.$arr_select_month[date("n")]->name);
$actual_row++;



$arr_unique_refer = array();

foreach ($arr_data_request as $key => $value) {
    array_push($arr_unique_refer,$value->resourse);
}

$arr_unique_refer = array_unique($arr_unique_refer);

// var_dump($arr_unique_refer);

$arr_result_data = array();

foreach ($arr_unique_refer as $key => $value) {
  $array_reqiest = ['Технологический запрос крупной компании' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Запрос письма о поддержке проекта' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Запрос информационной поддержки проекта' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Получение услуги центра коллективного пользования (производство)' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Получение услуги конструкторского бюро' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Запрос на консультацию проекта при подаче заявки в ФСИ' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Запрос на консультацию компании - Фонд Сколково' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Подача предложения в каталог производственных возможностей' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Подбор стартапов под тех.запрос.' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ] ];
  array_push($arr_result_data, (object) ['refer' => $value, 'data' => $array_reqiest]);
}

//проходим по площадкам которые есть в заявках
foreach ($arr_result_data as $key => $value) {
  //проходим по полученным заявкам
  foreach ($arr_data_request as $key_request => $value_request) {
    //если рефер площадки == площадке по которой проходим 1й фор
    if($value_request->resourse == $value->refer) {
      //если есть такой ключ то присваиваем значение количества заявок
      if( array_key_exists( $value_request->type_support, $value->data ) ) {
        $value->data[$value_request->type_support]->all_count = $value_request->count_ticket;
      }
    }
  }

  foreach ($arr_data_request_month as $key_request => $value_request) {
    //если рефер площадки == площадке по которой проходим 1й фор
    if($value_request->resourse == $value->refer) {
      //если есть такой ключ то присваиваем значение количества заявок
      if( array_key_exists( $value_request->type_support, $value->data ) ) {
        $value->data[$value_request->type_support]->mon_count = $value_request->count_ticket;
      }
    }
  }

  foreach ($arr_data_request_close as $key_request => $value_request) {
    //если рефер площадки == площадке по которой проходим 1й фор
    if($value_request->resourse == $value->refer) {
      //если есть такой ключ то присваиваем значение количества заявок
      if( array_key_exists( $value_request->type_support, $value->data ) ) {
        $value->data[$value_request->type_support]->completed = $value_request->count_ticket;
      }
    }
  }

  foreach ($arr_data_request_month_close as $key_request => $value_request) {
    //если рефер площадки == площадке по которой проходим 1й фор
    if($value_request->resourse == $value->refer) {
      //если есть такой ключ то присваиваем значение количества заявок
      if( array_key_exists( $value_request->type_support, $value->data ) ) {
        $value->data[$value_request->type_support]->increment = $value_request->count_ticket;
      }
    }
  }
}



foreach ($arr_result_data as $key => $value) {
  $actual_row++;
  $sheet->mergeCells("A".($actual_row-1).":E".$actual_row);
  $sheet->setCellValueByColumnAndRow(1,($actual_row-1), $value->refer);
  $sheet->getCellByColumnAndRow(1,($actual_row-1))->getStyle()->getFont()->setBold(true);

  $actual_row++;
  foreach ($value->data as $key_type => $value_type) {
    $sheet->setCellValueByColumnAndRow(1,$actual_row, $key_type);
    $sheet->setCellValueByColumnAndRow(2,$actual_row, $value_type->all_count);
    $sheet->setCellValueByColumnAndRow(3,$actual_row, $value_type->mon_count);
    $sheet->setCellValueByColumnAndRow(4,$actual_row, $value_type->completed);
    $sheet->setCellValueByColumnAndRow(5,$actual_row, $value_type->increment);
    $actual_row++;
  }

}

$sheet->getStyle('A8:E'.($actual_row-1))->applyFromArray($styleArray);
$actual_row++;


// $arr_data_summ_all = $settings->get_count_users_groupby_time_reg('all');

$data_str_generate_report = date('07:00:00 d.m.Y');
$mark_data_str_generate_report = strtotime($data_str_generate_report);
//предположительно отчет должен быть сформирован за день до
$mark_data_str_generate_report -= (25200+1);

$arr_data_summ_day = $settings->get_count_users_groupby_time_reg('day',null, date("Y-m-d H:i:s", $mark_data_str_generate_report));
$arr_data_summ_month = $settings->get_count_users_groupby_time_reg('month');

// date('t', time())
$arr_data_event_all = $settings->get_count_main_events_groupby_time_add(true,'month');
$arr_data_event_month = $settings->get_count_main_events_groupby_time_add(false,'month',(date("Y-m-").'01 00:00:00'),date("Y-m-d 23:59:59"));
$arr_data_event_day = $settings->get_count_main_events_groupby_time_add(false,'day',(date("Y-m-d", $mark_data_str_generate_report).' 00:00:00'), date("Y-m-d 23:59:59", $mark_data_str_generate_report));

$summ_data_summ_all = (is_array($arr_data_summ_month)) ? (array_pop($arr_data_summ_month))->summ_all : '0';
$summ_data_summ_month = (is_array($arr_data_summ_month)) ? (array_pop($arr_data_summ_month))->sum : '0';
$summ_data_summ_day = (is_array($arr_data_summ_day)) ? (array_pop($arr_data_summ_day))->sum : '0';
$summ_data_summ_day = (is_array($arr_data_summ_day)) ? (array_pop($arr_data_summ_day))->sum : '0';

$summ_data_event_summ_all = (is_array($arr_data_event_all)) ? (array_pop($arr_data_event_all))->summ_all : '0';
$summ_data_event_summ_month = (is_array($arr_data_event_month)) ? (array_pop($arr_data_event_month))->sum : '0';
$summ_data_event_summ_month = (is_array($arr_data_event_month)) ? (array_pop($arr_data_event_month))->sum : '0';
$summ_data_event_summ_day = (is_array($arr_data_event_day)) ? (array_pop($arr_data_event_day))->sum : '0';
$summ_data_event_summ_day = (is_array($arr_data_event_day)) ? (array_pop($arr_data_event_day))->sum : '0';


$actual_row++;
$actual_row++;
$sheet->getStyle('A'.($actual_row).':D'.($actual_row+3))->applyFromArray($styleArray);
$actual_row++;

$sheet->mergeCells("A".($actual_row-1).":D".$actual_row);
$sheet->setCellValueByColumnAndRow(1,($actual_row-1), 'Tboil SPb');
$sheet->getCellByColumnAndRow(1,($actual_row-1))->getStyle()->getFont()->setBold(true);
$sheet->setCellValueByColumnAndRow(2,($actual_row-2), 'Всего');
$sheet->setCellValueByColumnAndRow(3,($actual_row-2), 'За месяц ('.$arr_select_month[date("n")]->name.')');
$sheet->setCellValueByColumnAndRow(4,($actual_row-2), 'За день ('.date("H:i d.m.Y", $mark_data_str_generate_report).')');
$sheet->getStyle('B'.($actual_row-2).':D'.($actual_row-2))->applyFromArray($styleArray);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1, $actual_row, 'Физические лица');
$sheet->setCellValueByColumnAndRow(2, $actual_row, $summ_data_summ_all);
$sheet->setCellValueByColumnAndRow(3, $actual_row, $summ_data_summ_month);
$sheet->setCellValueByColumnAndRow(4, $actual_row, $summ_data_summ_day);

$actual_row++;
$sheet->setCellValueByColumnAndRow(1, $actual_row, 'Мероприятия');
$sheet->setCellValueByColumnAndRow(2, $actual_row, $summ_data_event_summ_all);
$sheet->setCellValueByColumnAndRow(3, $actual_row, $summ_data_event_summ_month);
$sheet->setCellValueByColumnAndRow(4, $actual_row, $summ_data_event_summ_day);
$actual_row++;
$actual_row++;


$actual_row++;



$writer = new Xlsx($spreadsheet);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_FULLDATA'.date("_H_i_d_m_Y").'.xlsx"');
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
