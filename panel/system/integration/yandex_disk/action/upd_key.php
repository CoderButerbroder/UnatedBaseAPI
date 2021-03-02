<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}
if (!isset($_POST["id_app"]) || !isset($_POST["token_app"]) ) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {
  $id_app_yandex_disk = trim($_POST["id_app"]);
  $token_app_yandex_disk = trim($_POST["token_app"]);

  $id_app_yandex_disk2 = $settings->get_global_settings('id_app_yandex_disk');
  $token_app_yandex_disk2 = $settings->get_global_settings('token_app_yandex_disk');

  if ($id_app_yandex_disk == $id_app_yandex_disk2 && $token_app_yandex_disk == $token_app_yandex_disk2) {
    echo json_encode(array('response' => false, 'description' => 'Ошибка, Указанный параметры соответствуют установленным.'), JSON_UNESCAPED_UNICODE);
    exit();
  }

  if($settings->update_global_settings('id_app_yandex_disk', $id_app_yandex_disk) || $settings->update_global_settings('token_app_yandex_disk', $token_app_yandex_disk)){
    echo json_encode(array('response' => true, 'description' => 'Параметры обновлены'), JSON_UNESCAPED_UNICODE);
  } else {
    echo json_encode(array('response' => false, 'description' => 'Параметры небыли обновлены'), JSON_UNESCAPED_UNICODE);
  }
}


?>
