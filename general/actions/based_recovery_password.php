<?php
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$action = $_GET['action'];

if ($action == 'recovery') {

      $email = $_POST['email'];
      $check_recovery = $settings->recovery_user($email);
      echo $check_recovery;

}
if ($action == 'new_pass') {

    $recovery_link = $_GET['recovery_link'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == $confirm_password) {
          $check_new_pass = $settings->new_pass_user($recovery_link,$password);
          echo $check_new_pass;
    } else {
          return json_encode(array('response' => false, 'description' => 'Пароли не совпадают'),JSON_UNESCAPED_UNICODE);
    }

}



?>
