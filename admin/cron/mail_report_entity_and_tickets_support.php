<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
$settings = new Settings;

$data_all_company = $settings->count_main_entity();
$data_all_company_current_month = $settings->count_main_entity_current_month();
$count_main_support_ticket_all  = $settings->count_main_support_ticket('all');
$count_main_support_ticket_close = $settings->count_main_support_ticket('close');

$file_report = file_get_contents('https://'.$_SERVER["SERVER_NAME"].'/panel/data/reports/actions/report_count_month.php');

$temp = tempnam(sys_get_temp_dir(), 'report.xlsx');

file_put_contents($temp, $file_report);

// var_dump($data_all_company);
// var_dump($data_all_company_current_month);
// var_dump($count_main_support_ticket_all);
// var_dump($count_main_support_ticket_close);


$today = date("Y-m-d H:i:s");

// $secure_code = $argv[1];
//
// $secure_code_check = '60859d7695571dc4544a596838ec97dfcaf61b486f348d7692ba557f';


    // if ($secure_code != $secure_code_check) {
    //     $subject2 = 'ВНИМАНИЕ!!! Доступ к файлу ограничен!!!';
    //     $message2 = 'Не верный секретный ключ!';
    //     $sent_message2 = $settings->send_email_user('web@kt-segment.ru',$subject2,$message2);
    //     exit;
    // }


              // Кому отправляем
              $email = 'web@kt-segment.ru';

              // $email = 'starkovskii@lpmtech.ru,web@kt-segment.ru';

              $content = 'Всего юридических лиц зарегистрировано: <b>'.$data_all_company.'</b><br/><br/>';
              $content .= 'За текущий месяц юридических лиц зарегистрировано: <b>'.$data_all_company_current_month.'</b><br/><br/>';
              $content .= 'За текущий месяц юридических лиц зарегистрировано: <b>'.$data_all_company_current_month.'</b><br/><br/>';
              $content .= 'За текущий месяц юридических лиц зарегистрировано: <b>'.$data_all_company_current_month.'</b><br/><br/>';  


              $today2 = date("d.m.Y H:i");
              $today = date("H:i:s");
              $date_otchet = date("d.m.Y");

              $tema = 'Отчет от '.$date_otchet.' г. '.$today.' о регистрации юридических лиц';
              $tema_for_msg = 'Отчет от '.$date_otchet.' г. '.$today;


              $maildata =
                    array(
                      'title' => $tema,
                      'description' => $content,
                      'link_to_server' => 'https://'.$_SERVER['SERVER_NAME'],
                      'text_button' => 'Сгенерировать свежий отчет',
                      'link_button' => 'https://'.$_SERVER['SERVER_NAME'].'/panel/data/reports/actions/report_count_month',
                      'name_host' => $_SERVER['SERVER_NAME'],
                      'date' => $today2
                    );

              $template_email = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/assets/template/mail/for_mail_cron_entity_and_tickets.php');

              foreach ($maildata as $key => $value) {
                $template_email = str_replace('['.$key.']', $value, $template_email);
              }

              $check_mail = $settings->send_email_user_attach($email,$tema,$template_email,(object) array(0 => (object) array('file' => $temp, 'name' => 'report.xlsx')));

              if (!json_decode($check_mail)->response) {
                  $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] /admin/cron/mail_report_entity_and_tickets_support Ошибка отправки отчета '.$check_mail);
                  exit;
              }



?>
