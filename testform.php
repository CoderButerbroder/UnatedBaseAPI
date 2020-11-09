<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$dadata = new DaData;
$settings = new Settings;

$data_user = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
$user = json_decode($data_user, true);

$check_auth = $settings->auth_user_social($data_user);

var_dump($check_auth);
?>
