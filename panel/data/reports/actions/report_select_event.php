<?php
/*
Генерация xlsx отчета
по выбранному мероприятию
*/

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}


$select = $_POST["event"];
if (trim($select) == '' && intval($select) <= 0 ){
  exit();
}


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;


$data_event = json_decode($settings->get_data_one_event($select));

if( $data_event == false || $data_event->response == false ){
  exit();
}

$data_users = json_decode($settings->get_users_event($data_event->data->id));

if( $data_users == false || $data_users->response == false ){
  exit();
}



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
$sheet->setTitle('Мероприятие');


//$sheet->getColumnDimension
for ($i=1; $i < 9; $i++) {
  $sheet->getcolumndimensionbycolumn($i)->setAutoSize(true);
}

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Выбранное мероприятие:');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('8af28f');

$sheet->setCellValueByColumnAndRow(2,$actual_row, strip_tags($data_event->data->name));
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Дата');
$sheet->setCellValueByColumnAndRow(2,$actual_row, date('H:i d.m.Y', strtotime($data_event->data->start_datetime_event)));
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Кол-во уч.');
$sheet->setCellValueByColumnAndRow(2,$actual_row, count($data_users->data));
$actual_row++;
$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Ссылка');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'https://'.$_SERVER["SERVER_NAME"].'/panel/data/events/details?event='.$data_event->data->id);
$actual_row++;
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'Участники');
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFont()->setBold(true);
$sheet->getCellByColumnAndRow(1,$actual_row)->getStyle()->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('8af28f');
$actual_row++;

$sheet->setCellValueByColumnAndRow(1,$actual_row, 'id Tboil:');
$sheet->setCellValueByColumnAndRow(2,$actual_row, 'Фамилия:');
$sheet->setCellValueByColumnAndRow(3,$actual_row, 'Имя:');
$sheet->setCellValueByColumnAndRow(4,$actual_row, 'Отчество:');
$sheet->setCellValueByColumnAndRow(5,$actual_row, 'Email:');
$sheet->setCellValueByColumnAndRow(6,$actual_row, 'Телефон:');
$sheet->setCellValueByColumnAndRow(7,$actual_row, 'Должность:');
$sheet->setCellValueByColumnAndRow(8,$actual_row, 'Юр. лицо:');

$styleArray = [
    // 'font' => [
    //     'bold' => false,
    // ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    ],

    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '00000000'],
        ],
        // 'top' => [
        //     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        // ],
        // 'right' => [
        //     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        // ],
        // 'left' => [
        //     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        // ],
        // 'bottom' => [
        //     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        // ],
    ]
];

for ($i=1; $i < 9; $i++) {
  $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getFont()->setBold(true);
}

$actual_row++;



foreach ($data_users->data as $key => $value) {
  $sheet->setCellValueByColumnAndRow(1,$actual_row, $value->id_tboil);
  $sheet->setCellValueByColumnAndRow(2,$actual_row, $value->last_name);
  $sheet->setCellValueByColumnAndRow(3,$actual_row, $value->name);
  $sheet->setCellValueByColumnAndRow(4,$actual_row, $value->second_name);
  $sheet->setCellValueByColumnAndRow(5,$actual_row, $value->email);
  $sheet->setCellValueByColumnAndRow(6,$actual_row, $value->phone);
  $sheet->setCellValueByColumnAndRow(7,$actual_row, $value->position);
  $sheet->setCellValueByColumnAndRow(8,$actual_row, $value->company);
  // for ($i=1; $i < 9; $i++) {
  //
  //   $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->applyFromArray($styleArray);
  //   // $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
  //   // $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
  //   // $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
  //   // $sheet->getCellByColumnAndRow($i,$actual_row)->getStyle()->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
  // }
  $actual_row++;
}

$sheet->getStyle('A7:H'.($actual_row-1))->applyFromArray($styleArray);

// $styleArray = array(
//     'borders' => array(
//         'outline' => array(
//             'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
//             'color' => array('argb' => 'FFFF0000'),
//         ),
//     ),
// );
// $sheet->getStyle('A7:H'.$actual_row)->applyFromArray($styleArray);
// $sheet->getStyle('A7:H'.$actual_row)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

$writer = new Xlsx($spreadsheet);
$now = date("_H_i_d_m_Y");
$now = trim($now);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report_event_'.$data_event->data->id.'_FULLDATA'.$now.'.xlsx"');
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
