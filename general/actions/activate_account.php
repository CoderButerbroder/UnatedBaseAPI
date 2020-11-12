<?php
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$hash = $_GET['link'];

$check_login = $settings->email_activation($hash);

if (json_decode($check_login)->response) {
      header('Location: /profile');
      exit;
} else {
      echo $check_login;
      exit;
}


?>
