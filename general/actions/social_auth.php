<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();

$data_user_str = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);

$data_user = json_decode($data_user_str);

if(is_object($data_user)) {
  $check_auth = $settings->auth_user_social($data_user_str);
  echo $check_auth;
} else {
  echo  json_encode(array('response' => false, 'description' => 'Ошибка, попробуйте позже'),JSON_UNESCAPED_UNICODE);
}

?>
