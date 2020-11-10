<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();
$login = $_POST['login'];
$password = $_POST['password'];

$check_login = $settings->check_login_valid($login);

if (json_decode($check_login)->response) {
      $check_auth = $settings->auth_user($login,$password,$session_id,$ip);
      if (json_decode($check_auth)->response) {
          header('Location: /profile');
          exit;
      } else {
          header('Content-type:application/json;charset=utf-8');
          echo $check_auth;
      }
} else {
      header('Content-type:application/json;charset=utf-8');
      echo $check_login;
}




?>
