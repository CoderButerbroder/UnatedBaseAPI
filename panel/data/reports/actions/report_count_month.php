<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// session_start();
//
// if (!isset($_SESSION["key_user"])) {
//   //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
//   exit();
// }


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


$spreadsheet = new Spreadsheet();
//$sheet = $spreadsheet->getActiveSheet();
$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Сводные показатели');

$sheet->getColumnDimension('A')->setWidth(40);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие показетели');
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

$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количественные показатели по площакам');
$actual_row++;

$sheet->setCellValueByColumnAndRow(4,$actual_row, 'Количество выполненных');
$sheet->setCellValueByColumnAndRow(5,$actual_row, 'Прирост '.$arr_select_month[date("n")]->name);
$actual_row++;



$arr_unique_refer = array();

foreach ($arr_data_request as $key => $value) {
    array_push($arr_unique_refer,$value->resourse);
}

$arr_unique_refer = array_unique($arr_unique_refer);

$arr_result_data = array();

foreach ($arr_unique_refer as $key => $value) {
  $array_reqiest = ['Технологический запрос крупной компании' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Запрос письма о поддержке проекта' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Запрос информационной поддержки проекта' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Получение услуги центра коллективного пользования (производство)' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Получение услуги конструкторского бюро' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Запрос на консультацию проекта при подаче заявки в ФСИ' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
                    'Запрос на консультацию компании при подаче заявки на статус "Участник Сколково"' => (object) [ 'mon_count' => 0, 'all_count' => 0, 'completed' => 0, 'increment' => 0 ],
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
  $sheet->setCellValueByColumnAndRow(1,$actual_row, $value->refer);
  $actual_row++;
  foreach ($value->data as $key_type => $value_type) {
    $sheet->setCellValueByColumnAndRow(1,$actual_row, $key_type);
    $sheet->setCellValueByColumnAndRow(2,$actual_row, $value_type->all_count);
    $sheet->setCellValueByColumnAndRow(3,$actual_row, $value_type->mon_count);
    $sheet->setCellValueByColumnAndRow(4,$actual_row, $value_type->completed);
    $sheet->setCellValueByColumnAndRow(5,$actual_row, $value_type->increment);
    $actual_row++;
  }
  $actual_row++;
}

// // var_dump();
// exit();


/*
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Технологический запрос крупной компании:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Запрос письма о поддержке проекта:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Запрос информационной поддержки проекта:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Получение услуги центра коллективного пользования (производство):');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Получение услуги конструкторского бюро:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Запрос на консультацию проекта при подаче заявки в ФСИ:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Запрос на консультацию компании при подаче заявки на статус "Участник Сколково":');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Подача предложения в каталог производственных возможностей:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Подбор стартапов под тех.запрос.:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, '0');
$sheet->setCellValueByColumnAndRow(3,$actual_row, '0');
$actual_row++;
*/

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
