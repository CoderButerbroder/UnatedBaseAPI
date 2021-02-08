<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// header('Content-type:application/json;charset=utf-8');
include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
$settings = new Settings;

$host = 'api.kt-segment.ru';

$data_all_company = $settings->count_main_entity();
$data_all_company_current_month = $settings->count_main_entity_current_month();
$count_main_support_ticket_all  = $settings->count_main_support_ticket('all');
$count_main_support_ticket_close = $settings->count_main_support_ticket('close');
$count_main_support_ticket_current_mounth = $settings->count_main_support_ticket_current_mounth('close');

$file_report = file_get_contents('https://'.$host.'/panel/data/reports/actions/report_count_month.php');

$temp = tempnam(sys_get_temp_dir(), 'report.xlsx');

file_put_contents($temp, $file_report);

$secure_code = $argv[1];

$secure_code_check = 'd41d8cd98f00b204e9800998ecf8427e';

// /home/httpd/fcgi-bin/a353561_lpmtech/php-cli /home/httpd/vhosts/lpmtech.ru/httpdocs/wp-content/themes/salient/barcamp_cron.php 60859d7695571dc4544a596838ec97dfcaf61b486f348d7692ba557f

    if ($secure_code != $secure_code_check) {
        $subject2 = 'ВНИМАНИЕ!!! Доступ к файлу ограничен!!!';
        $message2 = 'Не верный секретный ключ!';
        $sent_message2 = $settings->send_email_user('web@kt-segment.ru',$subject2,$message2);
        $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] Не верный секретный ключ доступа к файлу крона');
        exit;
    }
              // Кому отправляем
              //$massiv_email = array('web@kt-segment.ru','dimos-eskimos2014@yandex.ru');

              //$massiv_email = array('web@kt-segment.ru','starkovskii@lpmtech.ru');

              $massiv_email = array('kirill.soloveychik@gmail.com','sashmeleva8@gmail.com','starkovskii@lpmtech.ru');


              $today2 = date("d.m.Y H:i");
              $today = date("H:i");
              $date_otchet = date("d.m.Y");

              // наполнение сообщения

              $tema = 'Отчет от '.$date_otchet.' г. '.$today.' о регистрации юридических лиц';
              $tema_for_msg = 'Отчет от '.$date_otchet.' г. '.$today;


              $content = 'Всего юридических лиц зарегистрировано: <b>'.$data_all_company.'</b><br/><br/>';
              $content .= 'За текущий месяц юридических лиц зарегистрировано: <b>'.$data_all_company_current_month.'</b><br/><br/>';
              $content .= 'Всего выполненных заявок: <b>'.$count_main_support_ticket_close.'</b><br/><br/>';
              $content .= 'За текущий месяц выполненных заявок: <b>'.$count_main_support_ticket_current_mounth.'</b><br/><br/>';
              $content .= 'Больше информации Вы можете узнать в приложеном файле!</b><br/><br/>';


              $maildata =
                    array(
                      'title' => $tema_for_msg,
                      'description' => $content,
                      'link_to_server' => 'https://'.$host,
                      'text_button' => 'СГЕНЕРИРОВАТЬ СВЕЖИЙ ОТЧЕТ',
                      'link_button' => 'https://'.$host.'/panel/data/reports/actions/report_count_month',
                      'name_host' => $_SERVER['SERVER_NAME'],
                      'date' => $today2
                    );

              $template_email = file_get_contents('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/assets/template/mail/for_mail_cron_entity_and_tickets.php');

              foreach ($maildata as $key => $value) {
                  $template_email = str_replace('['.$key.']', $value, $template_email);
              }

              // отправка сообщения
              for ($i=0; $i < count($massiv_email) ; $i++) {

                  $check_mail = $settings->send_email_user_attach($massiv_email[$i],$tema,$template_email,(object) array(0 => (object) array('file' => $temp, 'name' => 'report.xlsx')));

                  if (!json_decode($check_mail)->response) {
                      $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] Ошибка отправки отчета на почту '.$massiv_email[$i].'. Файл /admin/cron/mail_report_entity_and_tickets_support  '.$check_mail);
                      exit;
                  }

              }





?>
