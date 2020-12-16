<?php
header('Content-type:application/json;charset=utf-8');
if(isset($_POST['g-recaptcha-response'])){$captcha = $_POST['g-recaptcha-response'];}
if(!$captcha){
    echo json_encode(array('response' => false, 'description' => 'Капча не обнаружена'),JSON_UNESCAPED_UNICODE);
    exit;
}
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$chek_recaptcha = $settings->validate_recaptcha($captcha);

if (!json_decode($chek_recaptcha)->response) {
    echo $chek_recaptcha;
    exit;
}

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
