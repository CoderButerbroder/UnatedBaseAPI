<?php
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
global $wpdb;

$wpdb->query( "DELETE FROM `barcamp_for_arkin` WHERE id > 0");

$all_users = $wpdb->get_results( "SELECT * FROM `pceb9_users` WHERE user_registered > '2019-10-21 00:00:00' ORDER BY user_registered ASC");

foreach ($all_users as $user) {

   $nickname = get_user_meta($user->ID, 'nickname', true);
   if (!$nickname) {
     $nickname = ' ';
   }
   $doljnost = get_user_meta($user->ID, 'doljnost', true);
   if (!$doljnost) {
     $doljnost = ' ';
   }
   $telephon = get_user_meta($user->ID, 'telephon', true);
   if (!$telephon) {
     $telephon = ' ';
   }
   $company_name = get_user_meta($user->ID, 'company_name', true);
   if (!$company_name) {
     $company_name = ' ';
   }
   $uradres = get_user_meta($user->ID, 'uradres', true);
   if (!$uradres) {
     $uradres = ' ';
   }
   $website = get_user_meta($user->ID, 'website', true);
   if (!$website) {
     $website = ' ';
   }
   $company_razmer = get_user_meta($user->ID, 'company_razmer', true);
   if (!$company_razmer) {
     $company_razmer = ' ';
   }
   $company_dohod = get_user_meta($user->ID, 'company_dohod', true);
   if (!$company_dohod) {
     $company_dohod = ' ';
   }
   $company_sector = get_user_meta($user->ID, 'company_sector', true);
   if (!$company_sector) {
     $company_sector = ' ';
   }
   $company_dolya = get_user_meta($user->ID, 'company_dolya', true);
   if (!$company_dolya) {
     $company_dolya = ' ';
   }
   $company_status_export = get_user_meta($user->ID, 'company_status_export', true);
   if (!$company_status_export) {
     $company_status_export = ' ';
   }
   $company_predlog = get_user_meta($user->ID, 'company_predlog', true);
   if (!$company_predlog ) {
     $company_predlog = ' ';
   }
   $last_name = get_user_meta($user->ID, 'last_name', true);
   if (!$last_name) {
     $last_name = ' ';
   }
   $first_name = get_user_meta($user->ID, 'first_name', true);
   if (!$first_name) {
     $first_name = ' ';
   }
   $about_file = get_user_meta($user_id, 'about_file', true);
   $link_about_file = the_guid($about_file);
   if (!$link_about_file) {
     $link_about_file = ' ';
   }
   $email = $user->user_email;
/*
   echo $nickname;
   echo $doljnost;
   echo $telephon;
   echo $password;
   echo $company_name;
   echo $uradres;
   echo $website;
   echo $company_razmer;
   echo $company_dohod;
   echo $company_sector;
   echo $company_dolya;
   echo $company_status_export;
   echo $company_predlog;
   echo $last_name;
   echo $first_name;
   echo $about_file;
   echo $link_about_file;
   echo $email;*/


       $new_sql = $wpdb->insert(
       'barcamp_for_arkin',
       array( 'nickname' => $nickname,
       'doljnost' => $doljnost,
       'telephon' => $telephon,
       'company_name' => $company_name,
       'uradres' => $uradres,
       'website' => $website,
       'company_razmer' => $company_razmer,
       'company_dohod' => $company_dohod,
       'company_sector' => $company_sector,
       'company_dolya' => $company_dolya,
       'company_status_export' => $company_status_export,
       'company_predlog' => $company_predlog,
       'last_name' => $last_name,
       'first_name' => $first_name,
       'about_file' => $link_about_file,
       'email' => $email
     ));
     /*if ($new_sql) {
       echo 'ОК:  '.$user->ID.'</br>';
     }
     else {
       echo 'NO:  '.$user->ID.'</br>';
     }*/


}

$table_exel = $wpdb->get_results("SELECT * FROM `barcamp_for_arkin`");

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once __DIR__ . '/../../src/Bootstrap.php';

// путь к файлу https://barcampspb.com/wp-content/themes/monstroid2/vendor/phpoffice/phpspreadsheet/samples/Basic/generator_exel.php

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Barcamp system')
    ->setLastModifiedBy('Barcamp system')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Dump');

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Логин')
    ->setCellValue('B1', 'Должность')
    ->setCellValue('C1', 'Телефон')
    ->setCellValue('D1', 'Название компании')
    ->setCellValue('E1', 'Адрес')
    ->setCellValue('F1', 'Сайт')
    ->setCellValue('G1', 'Размер компании')
    ->setCellValue('H1', 'Доход компании')
    ->setCellValue('I1', 'Отрасль')
    ->setCellValue('J1', 'Доля экспорта')
    ->setCellValue('K1', 'Опыт')
    ->setCellValue('L1', 'Предложения')
    ->setCellValue('M1', 'Фамилия')
    ->setCellValue('N1', 'Имя')
    ->setCellValue('O1', 'Почта');

$row_start = 2;
$i = 0;
foreach ($table_exel as $key) {
  $row_next = $row_start + $i;
// Add some data
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$row_next, $key->nickname);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$row_next, $key->doljnost);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$row_next, $key->telephon);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$row_next, $key->company_name);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$row_next, $key->uradres);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$row_next, $key->website);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$row_next, $key->company_razmer);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$row_next, $key->company_dohod);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$row_next, $key->company_sector);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$row_next, $key->company_dolya);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$row_next, $key->company_status_export);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$row_next, $key->company_predlog);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$row_next, $key->last_name);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$row_next, $key->first_name);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$row_next, $key->email);
  /*  ->setCellValue('C1', 'Hello')
    ->setCellValue('D2', 'world!');*/
  $i++;
}
// Miscellaneous glyphs, UTF-8
/*
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A4', 'Miscellaneous glyphs')
    ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Все компании баркемпа');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Barcamp_comany_system.xlsx"');
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
