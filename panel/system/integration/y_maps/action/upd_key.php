<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}
if (!isset($_POST["secret_key"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$new_api_key_value = trim($_POST["secret_key"]);
$key_fns = $settings->get_global_settings('api_fns_key');
if ($new_api_key_value == $key_fns) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Указанный ключ соответствует установленному. Укажите новый ключ'), JSON_UNESCAPED_UNICODE);
  exit();
}

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {
  if($settings->update_global_settings('api_fns_key', $new_api_key_value)){
    echo json_encode(array('response' => true, 'description' => 'Ключ обновлен'), JSON_UNESCAPED_UNICODE);

  } else {
    echo json_encode(array('response' => false, 'description' => 'Ключ небыл обновлен'), JSON_UNESCAPED_UNICODE);
  }
}


?>
