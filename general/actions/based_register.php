<?php
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();
$login = $_POST['name'];
$password = $_POST['last_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$pass = $_POST['pass'];





$check_register = $settings->base_register_user($email,$password,$phone,$name,$last_name,$session_id,$ip);

$check_login = $settings->check_login_valid($login);

if (json_decode($check_login)->response) {
      $check_auth = $settings->auth_user($login,$password,$session_id,$ip);
      echo $check_auth;
      exit;
} else {
      echo $check_login;
      exit;
}


?>
