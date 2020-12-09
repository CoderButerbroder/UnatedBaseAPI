<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

// $massiv_field_value = array('email' => 'web@kt-segment.ru',
//                             'inn' => '789987987'
// );
//
// $massiv_field_value = serialize($massiv_field_value);
//
// $id_user_tboil = '104120';
//
$cehck = $settings->get_data_for_ipchain();
//
$cehck = json_decode($cehck);
var_dump($cehck)






?>
