<?php
//проверка на дату, явл ли день запуска скрипта последним в месяце
if( cal_days_in_month(CAL_GREGORIAN, date("n"),  date("Y")) > date("d") ){
  // exit();
}

include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
$settings = new Settings;

$file_report = file_get_contents('https://'.$_SERVER["SERVER_NAME"].'/panel/data/reports/actions/report_count_month.php');

$temp = tempnam(sys_get_temp_dir(), 'report.xlsx');

file_put_contents($temp, $file_report);

$check = $settings->send_email_user_attach('vi9905@yandex.ru','test','Проверка работаспособности теории относительности', (object) array(0 => (object) array('file' => $temp, 'name' => 'report.xlsx')));
//
if(json_decode($check)->response == false){
  $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] email_send_results_count Ошибка отправки отчета');
}

?>
