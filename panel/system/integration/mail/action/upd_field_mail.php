<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

if(!isset($_POST["email_host"]) && !isset($_POST["email_username"]) && !isset($_POST["email_pass"])
    && !isset($_POST["email_secure"])  && !isset($_POST["email_port"]) && !isset($_POST["email_name"])) {
      echo json_encode(array('response' => false, 'description' => 'Ошибка, Проверьте правильность заполнения полей'), JSON_UNESCAPED_UNICODE);
      exit();
}

$email_host = trim($_POST["email_host"]);
$email_username = trim($_POST["email_username"]);
$email_pass = trim($_POST["email_pass"]);
$email_secure = trim($_POST["email_secure"]);
$email_port = trim($_POST["email_port"]);
$email_name = trim($_POST["email_name"]);

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;


$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {

  $email_host2 = $settings->get_global_settings('email_host');
  $email_username2 = $settings->get_global_settings('email_username');
  $email_pass2 = $settings->get_global_settings('email_pass');
  $email_secure2 = $settings->get_global_settings('email_secure');
  $email_port2 = $settings->get_global_settings('email_port');
  $email_name2 = $settings->get_global_settings('email_name');

  if ($email_host == $email_host2 &&
      $email_username == $email_username2 &&
      $email_pass == $email_pass2 &&
      $email_secure == $email_secure2 &&
      $email_port == $email_port2 &&
      $email_name == $email_name2 ) {
        echo json_encode(array('response' => false, 'description' => 'Ошибка, Переданные параметры не явл. новыми'), JSON_UNESCAPED_UNICODE);
        exit();
  }

  $email_host_check = $settings->update_global_settings('email_host',$email_host);
  $email_username_check = $settings->update_global_settings('email_username',$email_username);
  $email_pass_check = $settings->update_global_settings('email_pass',$email_pass);
  $email_secure_check = $settings->update_global_settings('email_secure',$email_secure);
  $email_port_check = $settings->update_global_settings('email_port',$email_port);
  $email_name_check = $settings->update_global_settings('email_name',$email_name);

  if ($email_host_check == false &&  $email_username_check == false &&  $email_pass_check == false &&
        $email_secure_check == false &&  $email_port_check == false &&  $email_name_check) {
          echo json_encode(array('response' => false, 'description' => 'Ошибка, Ошибка обновления данных'), JSON_UNESCAPED_UNICODE);
            exit;
     }
   if ($email_host_check == true ||  $email_username_check == true ||  $email_pass_check == true ||
         $email_secure_check == true ||  $email_port_check == true ||  $email_name_check) {
           echo json_encode(array('response' => екгу, 'description' => 'Успешное обновление параметров'), JSON_UNESCAPED_UNICODE);
             exit;
      }


}


?>
