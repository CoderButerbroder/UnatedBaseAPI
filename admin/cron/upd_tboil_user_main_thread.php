<?php
error_reporting(0);
$start = microtime(true);
$start_time = date('H:i');

include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');

ignore_user_abort(true);
set_time_limit(0);

$iter = $argv[1];
$arr_users = json_decode($argv[2]);

$settings = new Settings;

if ( $iter == '' || !is_array($arr_users) ){
  $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON ERR] \nfrom: upd_tboil_user_main_thread \niter:".$iter." count user: ".count($arr_users));
  exit();
}
/*
сообственно сам for/foreach с вызовом функция и тд
*/
$settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \nfrom: upd_tboil_user_main_thread \niter:".$iter." \ncount:".count($arr_users)."\none_id:".$arr_users[0]."\nstart: ".$start_time."\nEnd: ".round(microtime(true) - $start, 4)." сек.");

?>
