<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$phone = '79819867151';

$check_email = $settings->standart_phone($phone);

// $check_email =  json_encode([$email]);

var_dump(json_decode($check_email));


?>
