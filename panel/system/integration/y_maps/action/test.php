<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$arr_data_event_month = $settings->get_count_main_events_groupby_time_add(false,'month',(date("Y-m-").'01 00:00:00'),date("Y-m-d H:i:s"));
echo date("Y-m-").'01 00:00:00 '.date("Y-m-d H:i:s");

echo json_encode($arr_data_event_month, JSON_UNESCAPED_UNICODE );


?>
