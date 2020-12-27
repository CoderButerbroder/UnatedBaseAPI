<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}
$_POST["period_count_company"] = 'month';

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$data_count_company = $settings->count_main_entity();
$data_count_company_SK = $settings->count_main_entity_skolkovo();
$data_count_company_FSI = $settings->count_main_entity_fci();
$data_count_company_brach_arr_obj = $settings->get_count_entity_branch();
$data_count_company_type_arr_obj = $settings->get_count_entity_type_inf();
$data_count_company_export = $settings->get_count_entity_export();
$data_count_company_period = $settings->get_count_entity_groupby_time_reg($_POST["period_count_company"]);
$data_count_company_SK_period = $settings->get_count_entity_skolkovo_groupby_time_reg($_POST["period_count_company"]);
$data_count_company_FSI_period = $settings->get_count_entity_fci_groupby_time_reg($_POST["period_count_company"]);
$data_count_company_EXPORT_period = $settings->get_count_entity_export_groupby_time_reg($_POST["period_count_company"]);

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

/* тут твориться магия сюда не лезть, пожалуйста..*/
$arr_merge_count_company = array_merge($data_count_company_period, $data_count_company_SK_period, $data_count_company_FSI_period, $data_count_company_EXPORT_period);
usort($arr_merge_count_company, 'period');

$defaut_value = '-';

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

//$sheet->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getStyle('A1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ff6020');
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Блок "Юридические лица"');
$actual_row++;
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количественные показатели');

$spreadsheet->getActiveSheet()->getStyle('A4')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ffff00');
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие данные');
$spreadsheet->getActiveSheet()->getRowDimension(4)->setRowHeight(20);
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'Сумма всего');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество общее');
$sheet->setCellValueByColumnAndRow(3,$actual_row, $data_count_company);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество участников Сколково');
$sheet->setCellValueByColumnAndRow(3,$actual_row, $data_count_company_SK);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество участников ФСИ');
$sheet->setCellValueByColumnAndRow(3,$actual_row, $data_count_company_FSI);
$actual_row++;
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество по отраслям:');

//отрасль
foreach ($data_count_company_brach_arr_obj as $key_branch => $value_branch) {
  $sheet->setCellValueByColumnAndRow(2,$actual_row, $key_branch);
  $sheet->setCellValueByColumnAndRow(3,$actual_row, $value_branch);
  $actual_row++;
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество по типам:');

//тип инфроструктуры
foreach ($data_count_company_type_arr_obj as $key => $value) {
  $sheet->setCellValueByColumnAndRow(2,$actual_row, $key);
  $sheet->setCellValueByColumnAndRow(3,$actual_row, $value);
  $actual_row++;
}
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество осуществляющих экспорт:');
$sheet->setCellValueByColumnAndRow(3,$actual_row, $data_count_company_export);
$actual_row++;
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Выбранный диапозон');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'Кол-во за выбранный текущий период');
$actual_count_period_company = end($data_count_company_period);
$temp_value_period_company_SK  = end($data_count_company_SK_period);
$temp_value_period_company_FSI  = end($data_count_company_FSI_period);
$temp_value_period_company_EXPORT  = end($data_count_company_EXPORT_period);


  if ($_POST["period_count_company"] == 'year') {
    $sheet->setCellValueByColumnAndRow(2,$actual_row, 'Год '.date('Y'));
    $actual_row++;
    $temp_value_period_company = (date('Y') == $actual_count_period_company->yeard) ? $actual_count_period_company->sum : 0;
    $temp_value_period_company_SK = (date('Y') == $temp_value_period_company_SK->yeard) ? $temp_value_period_company_SK->sum : 0;
    $temp_value_period_company_FSI = (date('Y') == $temp_value_period_company_FSI->yeard) ? $temp_value_period_company_FSI->sum : 0;
    $temp_value_period_company_EXPORT = (date('Y') == $temp_value_period_company_EXPORT->yeard) ? $temp_value_period_company_EXPORT->sum : 0;
  }
  if ($_POST["period_count_company"] == 'month') {
    $sheet->setCellValueByColumnAndRow(2,$actual_row, 'Месяц '.$arr_select_month[date('m')]->name);
    $actual_row++;
    $temp_value_period_company = (date('m') == $actual_count_period_company->monthd) ? $actual_count_period_company->sum : 0;
    $temp_value_period_company_SK = (date('m') == $temp_value_period_company_SK->monthd) ? $temp_value_period_company_SK->sum : 0;
    $temp_value_period_company_FSI = (date('m') == $temp_value_period_company_FSI->monthd) ? $temp_value_period_company_FSI->sum : 0;
    $temp_value_period_company_EXPORT = (date('m') == $temp_value_period_company_EXPORT->monthd) ? $temp_value_period_company_EXPORT->sum : 0;
  }
  if ($_POST["period_count_company"] == 'week') {
    $sheet->setCellValueByColumnAndRow(2,$actual_row, 'Неделя '.date('W'));
    $actual_row++;
    $temp_value_period_company = (date('W') == $actual_count_period_company->weekd) ? $actual_count_period_company->sum : 0;
    $temp_value_period_company_SK = (date('W') == $temp_value_period_company_SK->weekd) ? $temp_value_period_company_SK->sum : 0;
    $temp_value_period_company_FSI = (date('W') == $temp_value_period_company_FSI->weekd) ? $temp_value_period_company_FSI->sum : 0;
    $temp_value_period_company_EXPORT = (date('W') == $temp_value_period_company_EXPORT->weekd) ? $temp_value_period_company_EXPORT->sum : 0;
  }
  if ($_POST["period_count_company"] == 'day') {
    $sheet->setCellValueByColumnAndRow(2,$actual_row, 'День '.date('d'));
    $actual_row++;
    $temp_value_period_company =  (date('d') == $actual_count_period_company->dayd) ? $actual_count_period_company->sum : 0;
    $temp_value_period_company_SK = (date('d') == $temp_value_period_company_SK->dayd) ? $temp_value_period_company_SK->sum : 0;
    $temp_value_period_company_FSI = (date('d') == $temp_value_period_company_FSI->dayd) ? $temp_value_period_company_FSI->sum : 0;
    $temp_value_period_company_EXPORT = (date('d') == $temp_value_period_company_EXPORT->dayd) ? $temp_value_period_company_EXPORT->sum : 0;
  }

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество новых компаний:');
$sheet->setCellValueByColumnAndRow(3,$actual_row, $temp_value_period_company);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество новых участников Сколково:');
$sheet->setCellValueByColumnAndRow(3,$actual_row, $temp_value_period_company_SK);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество новых участников ФСИ:');
$sheet->setCellValueByColumnAndRow(3,$actual_row, $temp_value_period_company_FSI);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество новых осуществляющих экспорт:');
$sheet->setCellValueByColumnAndRow(3,$actual_row, $temp_value_period_company_EXPORT);
$actual_row++;
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Указанный месяц:');

$sheet->setCellValueByColumnAndRow(1,($actual_row+1), 'Количество новых компаний:');
if ($_POST["period_count_company"] != 'month') {
  $data_count_company_period = $settings->get_count_entity_groupby_time_reg('month');
}
$temp_data_foreach = '000000';
$temp_data_arr_foerch = (object) array();
foreach ($arr_merge_count_company as $key => $value) {
  if($temp_data_foreach != $value->monthd.$value->yeard) {
    $sheet->setCellValueByColumnAndRow(($key+2), $actual_row,  $arr_select_month[$value->monthd]->name.'.'.$value->yeard);
    $temp_name_to_obj = $value->monthd.$value->yeard;
    $temp_data_arr_foerch->$temp_name_to_obj = $key+2;
  }
  if($temp_data_foreach == $value->monthd.$value->yeard) {
    continue;
  }
  $temp_data_foreach = $value->monthd.$value->yeard;

  $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row+1), 0);
  $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row+2), 0);
  $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row+3), 0);
  $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row+4), 0);

  if(isset($value->entity_groupby)){
    $sheet->setCellValueByColumnAndRow(($key+2), ($actual_row+1), $value->sum);
  }

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
$actual_row++;
$actual_row++;



$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Выгрузка по категории');

$spreadsheet->getActiveSheet()->getStyle(('A'.$actual_row))->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ffff00');

$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Название компании');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Select');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'ИНН');
$actual_row++;

$arr_company_select = array('1' => (object) array('name' => 'ОАО Компания', 'inn' => 'ИНН', 'select' => 'select'),
                            '2' => (object) array('name' => 'ОJО Компания2', 'inn' => 'ИНН2', 'select' => 'select'));

foreach ($arr_company_select as $key => $value) {
  $sheet->setCellValueByColumnAndRow(1,$actual_row,  $value->name);
  $sheet->setCellValueByColumnAndRow(2,$actual_row,  $value->select);
  $sheet->setCellValueByColumnAndRow(3,$actual_row,  $value->inn);
  $actual_row++;
}
$actual_row++;
//$sheet->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getStyle(('A'.$actual_row))->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ff6020');
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Блок "Физические лица"');
$actual_row++;
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row,  'Общие данные');
$sheet->setCellValueByColumnAndRow(2,$actual_row,  $defaut_value);

$sheet->setCellValueByColumnAndRow(2,$actual_row,  'детализции');
$sheet->setCellValueByColumnAndRow(3,$actual_row,  'периода с');
$sheet->setCellValueByColumnAndRow(4,$actual_row,  'по...');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row,  'Количество общее (зарег-но в системе)');
$sheet->setCellValueByColumnAndRow(2,$actual_row,  $defaut_value);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row,  'Кол-во физ.лиц - владельцев аккаунтов юр.лиц');
$sheet->setCellValueByColumnAndRow(2,$actual_row,  $defaut_value);
$actual_row++;
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество новых физ.лиц');
$sheet->setCellValueByColumnAndRow(2,$actual_row,  $defaut_value);
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Кол-во новых физ.лиц - владельцев аккаунтов юр.лиц');
$sheet->setCellValueByColumnAndRow(2,$actual_row,  $defaut_value);
$actual_row++;


//$sheet->setCellValueByColumnAndRow(3,8, $data_count_company);


//$sheet->columnIndexFromString(1)->setWidth(12);
/*
//$sheet->setCellValue('A1', 'Hello World !');
// $sheet->setCellValueByColumnAndRow(1,1, 'Hello World !');
// $sheet->setCellValueByColumnAndRow(15,8, 'Hello World !');
// $sheet->setCellValueByColumnAndRow(9,80, 'Hello World !');
//
//столбец строка
// $sheet->setCellValueByColumnAndRow(9,80, 'Hello World !');
*/

$writer = new Xlsx($spreadsheet);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_FULLDATA'.date("_H_i_d_m_Y ").'.xlsx"');
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
