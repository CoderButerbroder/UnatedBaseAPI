<?php
header('Content-type:application/json;charset=utf-8');
if(isset($_POST['g-recaptcha-response'])){$captcha = $_POST['g-recaptcha-response'];}
if(!$captcha){
    echo json_encode(array('response' => false, 'description' => 'Капча не обнаружена'),JSON_UNESCAPED_UNICODE);
    exit;
}
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();
$login = $_POST['login'];
$password = $_POST['password'];

$chek_recaptcha = $settings->validate_recaptcha($captcha);

if (!json_decode($chek_recaptcha)->response) {
    echo $chek_recaptcha;
    exit;
}

$check_login = $settings->check_login_valid($login);

if (json_decode($check_login)->response) {
      $check_auth = $settings->auth_user($login,$password,$session_id,$ip,json_decode($check_login)->type);
      echo $check_auth;
      exit;
} else {
      echo $check_login;
      exit;
}


?>
