<?php
//
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ignore_user_abort(true);
set_time_limit(0);
$start = microtime(true);
$start_time = date('H:i');
header('Content-type:application/json;charset=utf-8');
require_once('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
// ipchain_token
$settings = new Settings;
$mass_all_comany = json_decode($settings->ipchain_GetDigitalPlatformDataFast());

// $filename = 'somefile.txt';
// $text = serialize($mass_all_comany);
// file_put_contents($filename, $text);

var_dump(gettype($mass_all_comany));

foreach ($mass_all_comany as $key => $value) {
  if($value->Company->Name == 'ООО "ПОЛИМЕТ ТРЕЙДИНГ"' || $value->Company->Inn == '1831161930') {
    var_dump($value);
    echo "</br>";
    echo "</br>";
  }
}


$settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] test get all IPentity '.$start_time.' '.'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.');

?>
