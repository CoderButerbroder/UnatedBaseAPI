<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

// phpinfo();
//
// $data_array = array(104120,81920);
//
//
// $data = $settings->get_log_api_response('day');

// var_dump($data);

// $action = 'проверка действий';
// $type_of_message = 'text';

// $data2 = $settings->add_history_users_fulldata('действие','тип','тут какая то дата');
//
$data = $settings->get_log_api_response_group_by_referer(false,'year');

var_dump($data);
var_dump($data2);
