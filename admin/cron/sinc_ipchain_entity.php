<?php
// error_reporting(0);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$start = microtime(true);
$start_time = date('H:i');
header('Content-type:application/json;charset=utf-8');
require_once('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
ignore_user_abort(true);
set_time_limit(0);
$settings = new Settings;

$cehck = $settings->sinc_data_entity_ipchain();
$settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_ipchain_entity '.$start_time.' '.'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.');

?>
