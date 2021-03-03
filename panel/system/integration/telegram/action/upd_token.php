<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

if(!isset($_POST["token"]) || !isset($_POST["chat_err"]) || !isset($_POST["chat_Victor"]) || !isset($_POST["Chat_Dmitriy"])) {
      echo json_encode(array('response' => false, 'description' => 'Ошибка, Проверьте правильность заполнения полей'), JSON_UNESCAPED_UNICODE);
      exit();
}


$telega_token = trim($_POST["token"]);
$telega_chat_error = trim($_POST["chat_err"]);
$telega_chat_victor = trim($_POST["chat_Victor"]);
$telega_chat_dmitriy = trim($_POST["Chat_Dmitriy"]);

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value || $data_user_rules == false) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {


  $telega_token2 = $settings->get_global_settings('telega_token');
  $telega_chat_error2 = $settings->get_global_settings('telega_chat_error');
  $telega_chat_victor2 = $settings->get_global_settings('telega_chat_victor');
  $telega_chat_dmitriy2 = $settings->get_global_settings('telega_chat_dmitriy');


  if ( $telega_token == $telega_token2 &&
       $telega_chat_error == $telega_chat_error2 &&
       $telega_chat_victor == $telega_chat_victor2 &&
       $telega_chat_dmitriy == $telega_chat_dmitriy2 ) {
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

  if ($telega_token != $telega_token2) upd_glob( 'telega_token', $telega_token );
  if ($telega_chat_error != $telega_chat_error2) upd_glob( 'telega_chat_error', $telega_chat_error );
  if ($telega_chat_victor != $telega_chat_victor2) upd_glob( 'telega_chat_victor', $telega_chat_victor );
  if ($telega_chat_dmitriy != $telega_chat_dmitriy2) upd_glob( 'telega_chat_dmitriy', $telega_chat_dmitriy );

  echo json_encode(array('response' => true, 'description' => 'Успешное обновление параметров'), JSON_UNESCAPED_UNICODE);

}


?>
