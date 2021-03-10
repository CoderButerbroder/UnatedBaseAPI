<?php
session_start();
if (!isset($_SESSION["key_user"])) {
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

  // var_dump();
  $file = file_get_contents(base64_decode($_GET["data"]));
  // var_dump($http_response_header);
  if (!json_decode($file)) {
    // code...
    header("Content-Disposition: attachment; filename*=UTF-8''mysql_dump_every_12_00_19_02_2021.gzip");
    header("Content-Type: application/x-gzip");
  }

  echo $file;
  

  // header("Access-Control-Allow-Origin: *" "Content-Disposition: attachment;");

  // $id_app_yandex_disk2 = $settings->get_global_settings('id_app_yandex_disk');
  // $token_app_yandex_disk2 = $settings->get_global_settings('token_app_yandex_disk');

  // if($settings->update_global_settings('id_app_yandex_disk', $id_app_yandex_disk) || $settings->update_global_settings('token_app_yandex_disk', $token_app_yandex_disk)){
  //   echo json_encode(array('response' => true, 'description' => 'Параметры обновлены'), JSON_UNESCAPED_UNICODE);
  // } else {
  //   echo json_encode(array('response' => false, 'description' => 'Параметры небыли обновлены'), JSON_UNESCAPED_UNICODE);
  // }

  // $token_yandex = $settings->get_global_settings('token_app_yandex_disk');
  // $path_yandex_file = $settings->get_global_settings('path_yandex_disk');
  //
  // $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/download?path=' . urlencode($path_yandex_file));
  // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token_yandex));
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  // curl_setopt($ch, CURLOPT_HEADER, false);
  // $res = curl_exec($ch);
  // curl_close($ch);
  //


}


?>
