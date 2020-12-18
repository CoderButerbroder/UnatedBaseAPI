<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();

$data_user_str = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);

$data_user = json_decode($data_user_str);
echo json_encode($data_user,JSON_UNESCAPED_UNICODE);
if(is_object($data_user)) {
  $check_auth = $settings->auth_user_social($data_user_str);
  echo $check_auth;
} else {
  return json_encode(array('response' => false, 'description' => 'Ошибка, попробуйте позже'),JSON_UNESCAPED_UNICODE);
}
//
// if (json_decode($check_auth)->response) {
//     header('Location: /profile');
//     exit;
// } else {
//     header('Content-type:application/json;charset=utf-8');
//     echo $check_auth;
// }

?>
