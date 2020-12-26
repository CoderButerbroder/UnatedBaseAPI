<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$inn = 7701899412;

// echo '$period == "year"';
// $data = $settings->get_count_entity_groupby_time_reg('year');
// var_dump($data);
//
// echo '$period == "month"';
// $data = $settings->get_count_entity_groupby_time_reg('month');
// var_dump($data);
//
// echo '$period == "week"';
// $data = $settings->get_count_entity_groupby_time_reg('week');
// var_dump($data);
//
// echo '$period == "day"';
// $data = $settings->get_count_entity_groupby_time_reg('day');
//
// var_dump($data);


$data = $settings->get_count_entity_skolkovo_groupby_time_reg('year');
var_dump($data);
$data = $settings->get_count_entity_skolkovo_groupby_time_reg('month');
var_dump($data);
$data = $settings->get_count_entity_skolkovo_groupby_time_reg('week');
var_dump($data);
$data = $settings->get_count_entity_skolkovo_groupby_time_reg('day');
var_dump($data);

?>
