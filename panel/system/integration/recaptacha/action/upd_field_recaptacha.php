<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

if(!isset($_POST["google_recaptacha_open"]) && !isset($_POST["google_recaptacha_secret"]) ) {
      echo json_encode(array('response' => false, 'description' => 'Ошибка, Проверьте правильность заполнения полей'), JSON_UNESCAPED_UNICODE);
      exit();
}

$google_recaptacha_open = trim($_POST["google_recaptacha_open"]);
$google_recaptacha_secret = trim($_POST["google_recaptacha_secret"]);


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value || $data_user_rules == false) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {

  $google_recaptacha_open2 = $settings->get_global_settings('google_recaptacha_open');
  $google_recaptacha_secret2 = $settings->get_global_settings('google_recaptacha_secret');


  if ($google_recaptacha_open == $google_recaptacha_open2 &&
      $google_recaptacha_secret == $google_recaptacha_secret2 ) {
        echo json_encode(array('response' => false, 'description' => 'Ошибка, Переданные параметры не явл. новыми'), JSON_UNESCAPED_UNICODE);
        exit();
  }

  $google_recaptacha_open_check = $settings->update_global_settings('google_recaptacha_open',$google_recaptacha_open);
  $google_recaptacha_secret_check = $settings->update_global_settings('google_recaptacha_secret',$google_recaptacha_secret);

  if ($google_recaptacha_open_check == false &&  $google_recaptacha_secret_check == false ) {
          echo json_encode(array('response' => false, 'description' => 'Ошибка, Ошибка обновления данных'), JSON_UNESCAPED_UNICODE);
            exit;
     }
   if ($google_recaptacha_open_check == true ||  $google_recaptacha_secret_check == true ) {
           echo json_encode(array('response' => true, 'description' => 'Успешное обновление параметров'), JSON_UNESCAPED_UNICODE);
             exit;
      }
}


?>
