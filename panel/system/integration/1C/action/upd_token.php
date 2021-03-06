<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

if(!isset($_POST["token"])) {
      echo json_encode(array('response' => false, 'description' => 'Ошибка, Проверьте правильность заполнения полей'), JSON_UNESCAPED_UNICODE);
      exit();
}

$api_key_1c_rent = trim($_POST["token"]);

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value || $data_user_rules == false) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {


  $api_key_1c_rent2 = $settings->get_global_settings('api_key_1c_rent');


  if ($api_key_1c_rent == $api_key_1c_rent2 ) {
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

  if ($api_key_1c_rent != $api_key_1c_rent2) upd_glob( 'api_key_1c_rent', $api_key_1c_rent );

  echo json_encode(array('response' => true, 'description' => 'Успешное обновление параметров'), JSON_UNESCAPED_UNICODE);

}


?>
