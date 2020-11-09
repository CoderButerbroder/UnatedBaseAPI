<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();

$data_user = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);

$check_auth = $settings->auth_user_social($data_user,$session_id,$ip);

if (json_decode($check_auth)->response) {
    header('Location: /profile');
    exit;
} else {
    header('Content-type:application/json;charset=utf-8');
    echo $check_auth;
}

?>
