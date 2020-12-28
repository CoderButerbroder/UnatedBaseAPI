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


// $data = $settings->get_count_entity_skolkovo_groupby_time_reg('year');
// var_dump($data);
// $data = $settings->get_count_entity_skolkovo_groupby_time_reg('month');
// var_dump($data);
// $data = $settings->get_count_entity_skolkovo_groupby_time_reg('week');
// var_dump($data);
// $data = $settings->get_count_entity_skolkovo_groupby_time_reg('day');
// var_dump($data);



// $data = $settings->get_count_entity_fci_groupby_time_reg('year');
// var_dump($data);
// $data = $settings->get_count_entity_fci_groupby_time_reg('month');
// var_dump($data);
// $data = $settings->get_count_entity_fci_groupby_time_reg('week');
// var_dump($data);
// $data = $settings->get_count_entity_fci_groupby_time_reg('day');
// var_dump($data);
// $data = $settings->get_count_entity_fci_groupby_time_reg('data');
// var_dump($data);



// $data = $settings->get_count_entity_export_groupby_time_reg('year');
// var_dump($data);
// $data = $settings->get_count_entity_export_groupby_time_reg('month');
// var_dump($data);
// $data = $settings->get_count_entity_export_groupby_time_reg('week');
// var_dump($data);
// $data = $settings->get_count_entity_export_groupby_time_reg('day');
// var_dump($data);



// $data = $settings->get_current_parameters('technology');
// var_dump($data);
// $data = $settings->get_entity_search_by_parameter('msp','Среднее предприятие');
// var_dump($data);

// $data = $settings->get_entity_by_category('регион');
// var_dump($data);
// $data = $settings->get_entity_by_category('Район');
// var_dump($data);
// $data = $settings->get_entity_by_category('Тип');
// var_dump($data);
// $data = $settings->get_entity_by_category('Отрасль');
// var_dump($data);
// $data = $settings->get_entity_by_category('УчасСколково');
// var_dump($data);
// $data = $settings->get_entity_by_category('УчасФСИ');
// var_dump($data);
// $data = $settings->get_entity_by_category('УчасЛПМ');
// var_dump($data);
// $data = $settings->get_entity_by_category('экспорт');
// var_dump($data);
// $data = $settings->get_entity_by_category('all');
// var_dump($data);


$data = $settings->get_users_entity_data();
var_dump($data);




?>
