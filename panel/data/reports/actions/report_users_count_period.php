<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_count_company_period = $settings->get_count_entity_groupby_time_reg('month');


$data_count_company_SK_period = $settings->get_count_entity_skolkovo_groupby_time_reg('month');
$data_count_company_FSI_period = $settings->get_count_entity_fci_groupby_time_reg('month');
$data_count_company_EXPORT_period = $settings->get_count_entity_export_groupby_time_reg('month');



function period($a, $b) {
  if(isset($a->dayd)){
    $date1 = $a->dayd.'.'.$a->monthd.'.'.$a->yeard;
    $date2 = $a->dayd.'.'.$b->monthd.'.'.$b->yeard;
  } else {
    $date1 = '00.'.$a->monthd.'.'.$a->yeard;
    $date2 = '00.'.$b->monthd.'.'.$b->yeard;
  }
    if (( strtotime( $date1 ) == strtotime( $date2 ) )) {
        return 0;
    }
    return ( strtotime( $date1 ) < strtotime( $date2 ) ) ? -1 : 1;
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

// тут твориться магия сюда не лезть, пожалуйста..
$arr_merge_count_company = array_merge($data_count_company_period, $data_count_company_SK_period, $data_count_company_FSI_period, $data_count_company_EXPORT_period);
uasort($arr_merge_count_company, 'period');


$defaut_value = '-';

require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/office/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


$spreadsheet = new Spreadsheet();
//$sheet = $spreadsheet->getActiveSheet();
$actual_row = 1;
$actual_col = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Пользователи');

    $sheet->setCellValueByColumnAndRow(1,$actual_row, 'Указанный месяц:');

    $sheet->setCellValueByColumnAndRow(1,($actual_row+1), 'Количество новых компаний:');

    $temp_data_foreach = '000000';
    $temp_data_arr_foerch = (object) array();
    foreach ($arr_merge_count_company as $key => $value) {
      if($temp_data_foreach != $value->monthd.$value->yeard) {
        $sheet->setCellValueByColumnAndRow(($actual_col+2), $actual_row,  $arr_select_month[$value->monthd]->name.'.'.$value->yeard);
        $temp_name_to_obj = $value->monthd.$value->yeard;
        $temp_data_arr_foerch->$temp_name_to_obj = ($actual_col+2);
      }
      if($temp_data_foreach == $value->monthd.$value->yeard) {
        continue;
      }
      $temp_data_foreach = $value->monthd.$value->yeard;

      $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+1), 0);
      $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+2), 0);
      $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+3), 0);
      $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+4), 0);

      if(isset($value->entity_groupby)){
        $sheet->setCellValueByColumnAndRow(($actual_col+2), ($actual_row+1), $value->sum);
      }
      $actual_col++;
    }
    $actual_row++;
    $actual_row++;

    foreach ($arr_merge_count_company as $key => $value) {
      $temp_data_foreach = $value->monthd.$value->yeard;
      //раскладка по месяцам SK
      if (isset($value->skolkovo_groupby)) {
        $temp_data_foreach = $value->monthd.$value->yeard;
        $sheet->setCellValueByColumnAndRow($temp_data_arr_foerch->$temp_data_foreach, $actual_row, $value->sum);
      }
      //раскладка по месяцам FSI
      if (isset($value->fci_groupby)) {
        $temp_data_foreach = $value->monthd.$value->yeard;
        $sheet->setCellValueByColumnAndRow($temp_data_arr_foerch->$temp_data_foreach, ($actual_row+1), $value->sum);
      }
      //раскладка по месяцам EXPORT
      if (isset($value->export_groupby)) {
        $temp_data_foreach = $value->monthd.$value->yeard;
        $sheet->setCellValueByColumnAndRow($temp_data_arr_foerch->$temp_data_foreach, ($actual_row+2), $value->sum);
      }
    }
    $sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество новых участников Сколково:');
    $actual_row++;

    $sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество новых участников ФСИ:');
    $actual_row++;
    $sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество новых осуществляющих экспорт:');

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
