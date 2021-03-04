<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

$settings = new Settings;

$arr_data_request_month = $settings->count_main_support_ticket_groupby_category_referer_current_mounth('all');

echo json_encode($arr_data_request_month, JSON_UNESCAPED_UNICODE);
echo "</br>";

//подсчет количества заявок за месяц
$data_count_request_month = 0;
foreach ($arr_data_request_month as $key => $value) {
  $data_count_request_month += $value->count_ticket;
}


echo "</br>data_count_request_month = $data_count_request_month</br>";


?>
