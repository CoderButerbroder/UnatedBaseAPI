<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}



if(!isset($_POST["tboil_admin_login"]) || !isset($_POST["tboil_admin_password"]) || !isset($_POST["tboil_site_id"])) {
      echo json_encode(array('response' => false, 'description' => 'Ошибка, Проверьте правильность заполнения полей'), JSON_UNESCAPED_UNICODE);
      exit();
}

$tboil_admin_login = trim($_POST["tboil_admin_login"]);
$tboil_admin_password = trim($_POST["tboil_admin_password"]);
$tboil_site_id = trim($_POST["tboil_site_id"]);


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value || $data_user_rules == false) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {

  $tboil_admin_login2 = $settings->get_global_settings('tboil_admin_login');
  $tboil_admin_password2 = $settings->get_global_settings('tboil_admin_password');
  $tboil_site_id2 = $settings->get_global_settings('tboil_site_id');


  if ($tboil_admin_login == $tboil_admin_login2 && $tboil_admin_password == $tboil_admin_password2 &&
      $tboil_site_id == $tboil_site_id2 ) {
        echo json_encode(array('response' => false, 'description' => 'Ошибка, Переданные параметры не явл. новыми'), JSON_UNESCAPED_UNICODE);
        exit();
  }

  $tboil_admin_login_check = $settings->update_global_settings('tboil_admin_login',$tboil_admin_login);
  $tboil_admin_password_check = $settings->update_global_settings('tboil_admin_password',$tboil_admin_password);
  $tboil_site_id_check = $settings->update_global_settings('tboil_site_id',$tboil_site_id);

  if ($tboil_admin_login_check == false &&  $tboil_admin_password_check == false &&  $tboil_site_id_check == false ) {
          echo json_encode(array('response' => false, 'description' => 'Ошибка, Ошибка обновления данных'), JSON_UNESCAPED_UNICODE);
            exit;
     }
   if ($tboil_admin_login_check ||  $tboil_admin_password_check || $tboil_site_id_check) {
           echo json_encode(array('response' => true, 'description' => 'Успешное обновление параметров'), JSON_UNESCAPED_UNICODE);
             exit;
      }
}


?>
