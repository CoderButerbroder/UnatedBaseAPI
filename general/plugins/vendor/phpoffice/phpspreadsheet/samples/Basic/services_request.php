<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once __DIR__ . '/../../src/Bootstrap.php';

// путь к файлу https://test.e-spb.tech/resources/vendor/phpoffice/phpspreadsheet/samples/Basic/services_request.php


$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);
    return;
}
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();


// Set document properties
$spreadsheet->getProperties()->setCreator('E-spb')
                              ->setLastModifiedBy('E-spb')
                              ->setTitle('E-spb запросы на сервисы')
                              ->setSubject('E-spb запросы на сервисы')
                              ->setDescription('Заявки на сервисы')
                              ->setKeywords('office 2007 openxml php')
                              ->setCategory('E-spb запросы на сервисы');

$arr_services = $get_info->get_services();

$count_lists = count($arr_services);
$active_list = 0;

foreach ($arr_services as $services_obj) {
  $worksheet1 = $spreadsheet->createSheet($active_list);
  $worksheet1->setTitle(mb_substr($services_obj->name, 0, 29));
  $worksheet1
      ->setCellValue('A1', '№ п/п')
      ->setCellValue('B1', 'Фамилия')
      ->setCellValue('C1', 'Имя')
      ->setCellValue('D1', 'Отчество')
      ->setCellValue('E1', 'Телефон')
      ->setCellValue('F1', 'Дата подачи заявки')
      ->setCellValue('G1', 'Статус')
      ->setCellValue('H1', 'Дата изменения статуса')
      ->setCellValue('I1', 'Название юр. лица')
      ->setCellValue('J1', 'ИНН')
      ->setCellValue('K1', 'КПП')
      ->setCellValue('L1', 'ОГРН')
      ->setCellValue('M1', 'ОКВЭД')
      ->setCellValue('N1', 'Вид субъекта МСП')
      ->setCellValue('O1', 'Сайт')
      ->setCellValue('P1', 'Регион')
      ->setCellValue('Q1', 'Штат сотрудников')
      ->setCellValue('R1', 'Отрасль')
      ->setCellValue('S1', 'Экспорт')
      ->setCellValue('T1', 'Вид пользователя')
      ->setCellValue('U1', 'Email');


  $data_request = $system->get_request_service($services_obj->id);

  if($data_request != '709'){

        $count_row = 2;

        foreach ($data_request as $active_request => $request_obj) {

          $converted_time_filing = date('Y-m-d H:i:s', strtotime($request_obj->date_filing));
          $reversed_time_filing = date('d.m.Y H:i', strtotime($converted_time_filing));

          $converted_time_status = date('Y-m-d H:i:s', strtotime($request_obj->date_status));
          $reversed_time_status = date('d.m.Y H:i', strtotime($converted_time_status));

          $data_user = $get_info->get_cur_user_id($request_obj->id_user);
          if($data_user != '625' && $data_user != '626' && $data_user != '627') {

          $worksheet1
                 ->setCellValue('A'.$count_row, $count_row-1)
                 ->setCellValue('B'.$count_row, $data_user->user->last_name)
                 ->setCellValue('C'.$count_row, $data_user->user->name)
                 ->setCellValue('D'.$count_row, $data_user->user->second_name)
                 ->setCellValue('E'.$count_row, $data_user->user->phone)
                 ->setCellValue('F'.$count_row, $reversed_time_filing)
                 ->setCellValue('G'.$count_row, $request_obj->status)
                 ->setCellValue('H'.$count_row, $reversed_time_status);

                 if ($data_user->user->type_user == 'company') {
                     $name = $data_user->entity->name;
                     $inn = $data_user->entity->inn;
                     $kpp = $data_user->entity->kpp;
                     $ogrn = $data_user->entity->ogrn;
                     $okved = $data_user->entity->okved;
                     $msp = $data_user->entity->msp;
                     $site = $data_user->entity->site;
                     $region = $data_user->entity->region;
                     $staff = $data_user->entity->staff;
                     $branch_mass = json_decode($data_user->entity->branch);
                           $string = '';
                           foreach ($branch_mass as $key => $value) {
                             $string .= $value->value.',';
                           }
                           $branch = substr($string, 0, -1);
                           $export_mass = json_decode($data_user->entity->export);
                            $string2 = '';
                            if ($export_mass->SNG) {$string2 .= 'СНГ, ';}
                            if ($export_mass->ES) {$string2 .= 'ЕС, ';}
                            if ($export_mass->all_world) {$string2 .= 'Весь мир, ';}
                            if ($export_mass->other) {$string2 .= implode(", ", $export_mass->other);}
                            $rest = substr($string2, -1);
                            if ($rest == ', ') {$export = substr($string2, 0, -1);}
                            else {$export = $string2;}
                 }
                 else {
                       $name = '';
                       $inn = '';
                       $kpp = '';
                       $ogrn = '';
                       $okved = '';
                       $msp = '';
                       $site = '';
                       $region = '';
                       $staff = '';
                       $branch = '';
                       $export = '';
                      }

             $worksheet1
                 ->setCellValue('I'.$count_row, $name)
                 ->setCellValue('J'.$count_row, $inn)
                 ->setCellValue('K'.$count_row, $kpp)
                 ->setCellValue('L'.$count_row, $ogrn)
                 ->setCellValue('M'.$count_row, $okved)
                 ->setCellValue('N'.$count_row, $msp)
                 ->setCellValue('O'.$count_row, $site)
                 ->setCellValue('P'.$count_row, $region)
                 ->setCellValue('Q'.$count_row, $staff)
                 ->setCellValue('R'.$count_row, $branch)
                 ->setCellValue('S'.$count_row, $export);

             if ($data_user->user->type_user == 'user') {$type_user = 'Физ. лицо';}
             if ($data_user->user->type_user == 'company') {$type_user = 'Юр. лицо';}

             $worksheet1
                 ->setCellValue('T'.$count_row, $type_user)
                 ->setCellValue('U'.$count_row, $data_user->user->email);
            $count_row++;
            }
        }

  }
  $active_list++;
}




// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="all_services_requests.xlsx"');
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
exit;
