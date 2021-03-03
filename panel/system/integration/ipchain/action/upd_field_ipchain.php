<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

if(!isset($_POST["domen"]) || !isset($_POST["token"]) || !isset($_POST["token_type"]) ||
  !isset($_POST["login"]) || !isset($_POST["password"]) ) {
      echo json_encode(array('response' => false, 'description' => 'Ошибка, Проверьте правильность заполнения полей'), JSON_UNESCAPED_UNICODE);
      exit();
}


$domen_ipchain = trim($_POST["domen"]);
$token_ipchain = trim($_POST["token"]);
$token_type_ipchain = trim($_POST["token_type"]);
$login_ipchain = trim($_POST["login"]);
$password_ipchain = trim($_POST["password"]);

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value || $data_user_rules == false) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {


  $domen_ipchain2 = $settings->get_global_settings('domen_ipchain');
  $token_ipchain2 = $settings->get_global_settings('token_ipchain');
  $token_type_ipchain2 = $settings->get_global_settings('token_type_ipchain');
  $login_ipchain2 = $settings->get_global_settings('login_ipchain');
  $password_ipchain2 = $settings->get_global_settings('password_ipchain');


  if ($domen_ipchain == $domen_ipchain2 &&
      $token_ipchain == $token_ipchain2 &&
      $token_type_ipchain == $token_type_ipchain2 &&
      $login_ipchain == $login_ipchain2 &&
      $password_ipchain == $password_ipchain2 ) {
        echo json_encode(array('response' => false, 'description' => 'Ошибка, Переданные параметры не явл. новыми'), JSON_UNESCAPED_UNICODE);
        exit();
  }


  function upd_glob($key, $param){
    global $settings;
    if ( $settings->update_global_settings($key, $param) ) {
        return true;
    } else {
      echo json_encode(array('response' => false, 'description' => 'Ошибка, Не удалось обновить поле '.$key), JSON_UNESCAPED_UNICODE);
      exit();
    }
  }

  if ($domen_ipchain != $domen_ipchain2) upd_glob( 'domen_ipchain', $domen_ipchain );
  if ($token_ipchain != $token_ipchain2) upd_glob( 'token_ipchain', $token_ipchain );
  if ($token_type_ipchain != $token_type_ipchain2) upd_glob( 'token_type_ipchain', $token_type_ipchain );
  if ($login_ipchain != $login_ipchain2) upd_glob( 'login_ipchain', $login_ipchain );
  if ($password_ipchain != $password_ipchain2) upd_glob( 'password_ipchain', $password_ipchain );

  echo json_encode(array('response' => true, 'description' => 'Успешное обновление параметров'), JSON_UNESCAPED_UNICODE);

}


?>
