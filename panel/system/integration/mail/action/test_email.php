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

$check_mail = json_decode($settings->send_email_user(trim($_POST["email"]),'Тестовое сообщение','Тест отправки сообщения из настроек'));
if ($check_mail->response == false || $check_mail == false) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка отправки email'), JSON_UNESCAPED_UNICODE);
  exit();
}

if ($check_mail->response == true ) {
  echo json_encode(array('response' => true, 'description' => 'Тестовый email отправлен'), JSON_UNESCAPED_UNICODE);
  exit();
}

?>
