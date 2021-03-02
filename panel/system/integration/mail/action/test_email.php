<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}
if (!isset($_POST["email"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка получения email получателя'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();

$template_email = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/assets/template/mail/support_tikcet_status.php');

$data_refer = json_decode($settings->get_data_referer('https://'.$_SERVER["SERVER_NAME"]));
if (is_object($data_refer) && $data_refer->response == true && $template_email != false) {

  $maildata =
        array(
          'title' => 'Тестовое сообщение',
          'description' => 'Тестовое сообщение отправленное из панели настроек FULLDATA',
          'link_to_server' => 'https://'.$_SERVER["SERVER_NAME"],
          'text_button' => 'Обратно к настройкам интеграций',
          'link_button' => 'https://'.$_SERVER["SERVER_NAME"]."/panel/system/integration/",
          'link_to_logo' => $data_refer->data->link_to_logo,
          'alt_link_to_logo' => $data_refer->data->resourse,
          'color_button1' => $data_refer->data->color_button1,
          'text_color_button1' =>$data_refer->data->color_text_button1,
          'name_host' => $data_refer->data->resourse,
          'date' => date('H:i d.m.Y')
        );

} else {
  echo json_encode(array('response' => false, 'description' => 'Ошибка отправки email, Ошибка получения шаблона.'), JSON_UNESCAPED_UNICODE);
  exit();
}

foreach ($maildata as $key => $value) {
  $template_email = str_replace('['.$key.']', $value, $template_email);
}

$check_mail = json_decode($settings->send_email_user(trim($_POST["email"]),'Тестовое сообщение',$template_email));

if ($check_mail->response == false || $check_mail == false) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка отправки email'), JSON_UNESCAPED_UNICODE);
  exit();
}

if ($check_mail->response == true ) {
  echo json_encode(array('response' => true, 'description' => 'Тестовый email отправлен'), JSON_UNESCAPED_UNICODE);
  exit();
}

?>
