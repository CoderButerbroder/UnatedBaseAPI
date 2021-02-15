<?php
/*
Генерация xlsx отчета
выгрузка мероприятий согласно некому периоду
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

// $period_select->data1 =  date('Y-m-d H:i:s', strtotime(trim($_POST["period_1"])));
// $period_select->data2 =  date('Y-m-d H:i:s', strtotime(trim($_POST["period_2"])));

$period_select->start =  date('Y-m-d H:i:s', strtotime(trim($_POST["start"])));
$period_select->end =  date('Y-m-d H:i:s', strtotime(trim($_POST["end"])));



require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

/* получаем данные */

$arr_data_event = $settings->get_events_in_period($period_select->start, $period_select->end);

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
//$sheet = $spreadsheet->getActiveSheet();
$actual_row = 1;
$sheet = $spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Мероприятия');
$sheet->getColumnDimension("A")->setWidth(50);
for ( $i = 2 ; $i <= 4 ; $i++) {
  $sheet->getcolumndimensionbycolumn($i)->setAutoSize(true);
}
$sheet->getStyle('A2:D2')->getFont()->setBold(true);
$sheet->getStyle('A2:D2')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('8af28f');

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Выбранный Перииод');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'C '.date('d.m.Y', strtotime($period_select->start)));
$sheet->setCellValueByColumnAndRow(4,$actual_row, 'по '.date('d.m.Y', strtotime($period_select->end)));
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Наименование');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Количество участников');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'Дата мероприятия');
$sheet->setCellValueByColumnAndRow(4,$actual_row, 'Url');

foreach ($arr_data_event as $key => $value) {
  $actual_row++;
  $sheet->setCellValueByColumnAndRow(1, $actual_row, $value->name);
  $sheet->setCellValueByColumnAndRow(2, $actual_row, $value->sum);
  $sheet->setCellValueByColumnAndRow(3, $actual_row, date('H:i d.m.Y', strtotime($value->data_start)));
  $sheet->setCellValueByColumnAndRow(4, $actual_row, 'https://'.$_SERVER["SERVER_NAME"].'/panel/data/events/details?event='.$value->id);

}

$sheet->getStyle('A1:D'.$actual_row)->applyFromArray($styleArray);

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
