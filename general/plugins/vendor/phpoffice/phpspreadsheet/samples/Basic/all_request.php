<?php
include($_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/sp-theme-master/2020_connectdb.php');

global $database;

$id = $_GET['id'];

$status = $_GET['status'];

if ($id) {

$statement = $database->prepare("SELECT * FROM `2020_requests` WHERE id = :id");
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$request_detail = $statement->fetchAll(PDO::FETCH_OBJ);

}
else {

      if ($status == 'good') {
        $statement = $database->prepare("SELECT * FROM `2020_requests` WHERE 	status_request = 'Готово'");
        $statement->execute();
        $request_detail = $statement->fetchAll(PDO::FETCH_OBJ);
      }
      else {
            if ($status == 'notgood') {
                  $statement = $database->prepare("SELECT * FROM `2020_requests` WHERE 	status_request = 'Черновик'");
                  $statement->execute();
                  $request_detail = $statement->fetchAll(PDO::FETCH_OBJ);
            }
            else {
                  $statement = $database->prepare("SELECT * FROM `2020_requests`");
                  $statement->execute();
                  $request_detail = $statement->fetchAll(PDO::FETCH_OBJ);
            }
      }
}


use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once __DIR__ . '/../../src/Bootstrap.php';

// путь к файлу http://konkurs.kt-segment.ru/wp-content/themes/sp-theme-master/vendor/phpoffice/phpspreadsheet/samples/Basic/all_request.php

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Barcamp system')
    ->setLastModifiedBy('kruzhokNTI')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Dump');

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Номер заявки')
    ->setCellValue('B1', 'ФИО')
    ->setCellValue('C1', 'Телефон')
    ->setCellValue('D1', 'Email')
    ->setCellValue('E1', 'Тип участника')
    ->setCellValue('F1', 'Вы работаете или учитесь?')
    ->setCellValue('G1', 'Название кружка')
    ->setCellValue('H1', 'Организация, на базе которой действует кружок')
    ->setCellValue('I1', 'Регион')
    ->setCellValue('J1', 'Адрес организации')
    ->setCellValue('K1', 'Входит ли кружок в состав сети?')
    ->setCellValue('L1', 'О руководителе кружка')
    ->setCellValue('M1', 'О команде кружка')
    ->setCellValue('N1', 'Укажите, примерно, сколько школьников и студентов участвуют в работе кружка сегодня')
    ->setCellValue('O1', 'Условия участия в образовательных форматах')
    ->setCellValue('P1', 'Почему вы решили подать заявку на участие в Конкурсе кружков?')
    ->setCellValue('Q1', 'К какому типу вы отнесете ваш кружок?')
    ->setCellValue('R1', 'С какими науками и/или технологиями сейчас работают кружковцы?')
    ->setCellValue('S1', 'Темы исследований и проектов, над которыми работают участники кружка в настоящее время.')
    ->setCellValue('T1', 'Кружок занимается')
    ->setCellValue('U1', 'Кружковцы участвуют...')
    ->setCellValue('V1', 'Кружок образовался в')
    ->setCellValue('W1', 'Основные вехи в истории кружка')
    ->setCellValue('X1', 'Яркие примеры выпускников')
    ->setCellValue('Y1', 'Как изменилась работа кружка в условиях пандемии?')
    ->setCellValue('Z1', 'Какая поддержка/помощь нужна кружку? Консультации по каким вопросам могли бы продвинуть кружок вперед?')
    ->setCellValue('AA1', 'Планы по развитию кружка')
    ->setCellValue('AB1', 'Еще о кружке')
    ->setCellValue('AC1', 'Сайт (страница) кружка')
    ->setCellValue('AD1', 'СМИ о кружке')
    ->setCellValue('AE1', 'Номинации для возможного участия во втором этапе')
    ->setCellValue('AF1', 'Статус заявки')
    ->setCellValue('AG1', 'Дата подачи заявки')
    ->setCellValue('AH1', 'Дата рождения');

$row_start = 2;
$shag = 0;

foreach ($request_detail as $key) {

  $user = $database->prepare("SELECT * FROM `2020_users` WHERE id = :id");
  $user->bindParam(':id', $key->id_user, PDO::PARAM_INT);
  $user->execute();
  $all_user = $user->fetch(PDO::FETCH_OBJ);

  $type_direction = json_decode(stripslashes($key->type_direction), true);

  $job_learn = json_decode(stripslashes($key->job_learn), true);

  $comp_baza = json_decode(stripslashes($key->comp_baza), true);

  $comp_region = json_decode(stripslashes($key->comp_region), true);

  $network = json_decode(stripslashes($key->network), true);

  $typy_technologies = json_decode(stripslashes($key->typy_technologies), true);

  $technologies = json_decode(stripslashes($key->technologies), true);

  $technologies_stroka = ' ';

  if (is_array($technologies)) {

  for ($i = 0; $i <= count($technologies); $i++) {
     $technologies_stroka .= $technologies[$i]["value"].', ';
  }
  }
  else {
    $technologies_stroka = ' ';
  }






  $participation_olimp = json_decode(stripslashes($key->participation_olimp), true);

  $participation_event = json_decode(stripslashes($key->participation_event), true);

  $help = json_decode(stripslashes($key->help), true);

  $nomination = json_decode(stripslashes($key->nomination), true);

  $uchenik ='';

  $uchenik .= '1-4 классы - '.$key->grades14.PHP_EOL;
  $uchenik .=  '5-7 классы - '.$key->grades57.PHP_EOL;
  $uchenik .=  '8-11 классы - '.$key->grades811.PHP_EOL;
  $uchenik .=  'Cтуденты учреждений СПО - '.$key->gradesspo.PHP_EOL;
  $uchenik .=  'Cтуденты вузов - '.$key->gradesvuz.PHP_EOL;

  $uchastie ='В Олимпиадах'.PHP_EOL;


  $uchastie .= '- '.$participation_olimp["value1"].' профиль - '.$participation_olimp["value1_add"].PHP_EOL;
  $uchastie .= '- '.$participation_olimp["value2"].' профиль - '.$participation_olimp["value2ы_add"].PHP_EOL;

  $uchastie .='В инженерных cоревнованиях'.PHP_EOL;


  $uchastie .= '- 1e - '.$key->participation_inj_contest_1.PHP_EOL;
  $uchastie .='- число участников -'.$key->num_participants_engineering_competitions_1.PHP_EOL;
  $uchastie .='- число призеров -'.$key->num_prizewinner_engineering_competitions_1.PHP_EOL;
  $uchastie .='- число победителей -'.$key->num_winners_engineering_competitions_1.PHP_EOL;


  $uchastie .= '- 2e - '.$key->participation_inj_contest_2.PHP_EOL;
  $uchastie .='- число участников -'.$key->num_participants_engineering_competitions_2.PHP_EOL;
  $uchastie .='- число призеров -'.$key->num_prizewinner_engineering_competitions_2.PHP_EOL;
  $uchastie .='- число победителей -'.$key->num_winners_engineering_competitions_2.PHP_EOL;

  $uchastie .= '- 3e -'.$key->participation_inj_contest_3.PHP_EOL;
  $uchastie .='- число участников -'.$key->num_participants_engineering_competitions_3.PHP_EOL;
  $uchastie .='- число призеров -'.$key->num_prizewinner_engineering_competitions_3.PHP_EOL;
  $uchastie .='- число победителей -'.$key->num_winners_engineering_competitions_3.PHP_EOL;


  if ($participation_event) {
    $uchastie .='В мроприятях КД'.PHP_EOL;
    for ($i = 0; $i <= count($participation_event); $i++) {
      $uchastie .= $participation_event[$i]["value"].', ';
    }}

  $all_stud_examp ='';
  $all_stud_examp .= $key->stud_examp1.PHP_EOL.PHP_EOL;
  $all_stud_examp .= $key->stud_examp2.PHP_EOL.PHP_EOL;
  $all_stud_examp .= $key->stud_examp3.PHP_EOL.PHP_EOL;



  $row_next = $row_start + $shag;
  $test = 'test';

  // $pre_text_hiperlink = '=ГИПЕРССЫЛКА';
  // $text_hiperlink = $pre_text_hiperlink.'("https://konkurs2020.kruzhok.org/request/list/";"'.$key->id.'")';
// Add some data
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$row_next, $key->id);
$spreadsheet->setActiveSheetIndex(0)->getCell('A'.$row_next)->getHyperlink()->setUrl('https://konkurs2020.kruzhok.org/wp-content/themes/sp-theme-master/2020testing.php?id='.$key->id);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$row_next, $all_user->name.' '.$all_user->last_name.' '.$all_user->second_name);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$row_next, $all_user->phone);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$row_next, $all_user->email);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$row_next, $type_direction["value"]);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$row_next, $job_learn["value"]);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$row_next, $key->comp_name);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$row_next, $comp_baza["value"]);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$row_next, $comp_region["value"]);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$row_next, $key->comp_adress);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$row_next, $network["value_type"].' '.$network["value"]);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$row_next, $key->director);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$row_next, $key->team);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$row_next, $uchenik);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$row_next, $key->conditions);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$row_next, $key->reason);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$row_next, $key->typy_technologies);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$row_next, $technologies_stroka);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('S'.$row_next, $key->tasks_now);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('T'.$row_next, $key->circle_work);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('U'.$row_next, $uchastie);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('V'.$row_next, $key->date_create);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('W'.$row_next, $key->story_circle);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('X'.$row_next, $all_stud_examp);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('Y'.$row_next, $key->pandemic);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('Z'.$row_next, $help["value"]);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('AA'.$row_next, $key->plan);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('AB'.$row_next, $key->other);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('AC'.$row_next, $key->site);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('AD'.$row_next, $key->smi);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('AE'.$row_next, $nomination["value"]);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('AF'.$row_next, $key->status_request);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('AG'.$row_next, $key->date_begin);
$spreadsheet->setActiveSheetIndex(0)->setCellValue('AH'.$row_next, $all_user->birthday);
  /*  ->setCellValue('C1', 'Hello')
    ->setCellValue('D2', 'world!');*/
    $shag++;
}

// Miscellaneous glyphs, UTF-8
/*
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A4', 'Miscellaneous glyphs')
    ->setCellValue('A5', '?????????????????');*/

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Все Заявки НТИ');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="kruzhok_requests_nti.xlsx"');
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
