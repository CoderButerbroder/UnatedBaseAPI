<?php
// error_reporting(0);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
require_once('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
ignore_user_abort(true);
set_time_limit(0);
$settings = new Settings;

$cehck = $settings->sinc_data_ipobject_ipchain();

?>
