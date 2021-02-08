<?php

//проверка на дату, явл ли день запуска скрипта последним в месяце
if( cal_days_in_month(CAL_GREGORIAN, date("n"),  date("Y")) > date("d") ){
  // exit();
}

// session_start();
include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
$settings = new Settings;

$file_report = file_get_contents('https://'.$_SERVER["SERVER_NAME"].'/panel/data/reports/actions/report_count_month.php');

$check = $settings->send_email_user('vi9905@yandex.ru','test','Проверка работаспособности теории относительности', array($file_report) );

if(json_decode($check)->response == false){
  $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] email_send_results_count Ошибка отправки отчета');
}

// var_dump($file_report);


?>
