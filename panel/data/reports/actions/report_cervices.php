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


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

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
$sheet->setTitle('Сервисы');


$sheet->getColumnDimension('A')->setAutoSize(true);

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Общие данные:');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('8af28f');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Количество юр лиц, воспользовавшихся сервисом (за все время):');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Технологические запросы');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Запрос письма о поддержке проекта');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. Запрос информационной поддержки проекта');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '4. Получение услуги ЦКП');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '5. Получение услуг конструкторского бюро');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '6. Запрос на консультацию проектом при подаче заявки в ФСИ');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '7. Запрос на консультацию компанией при подаче заявки на получение статуса участника Сколково');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '8. Подача предложения в каталог производственных возможностей');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '9. Подбор стартапов под тех.запрос');
$actual_row++;
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'По месяцам');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('8af28f');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Показатель');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Наименование направления работы -');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Технологические запросы');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('e6e6e6');
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
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('e6e6e6');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество проектов-участников сервиса Командообразование');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Количество новых участников');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3. Количество юр.лиц, привлеченных с внешних платформ/событий');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'ЦМИТ (детские образовательные программы)');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('e6e6e6');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество мероприятий на тбойле для детей');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Количество физ.лиц привлеченных в сервис с внешних платформ/ событий (Руками, приложение Технопарка)');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Открытый университет');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('e6e6e6');
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
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('e6e6e6');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество обращений по сервису "Получение услуги ЦКП"');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Количество обращений по сервису "Получение услуг конструкторского бюро"');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '3.  Количество юр лиц, привлеченных в сервис с внешних платформ и событий');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Информационое освещение');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('e6e6e6');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '1. Количество обращений по сервису "Запрос информационной поддержки проекта"');
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, '2. Количество обращений по сервису "Подача предложения в каталог производственных возможностей"');
$actual_row++;






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
