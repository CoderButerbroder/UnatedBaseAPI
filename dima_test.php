<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;



$data = $settings->get_count_main_entity_skolkovo_visit_event_groupby_time_reg(true,'week');
var_dump($data);

// $data = $settings->get_count_users_groupby_time_reg('year','2020.01.01','2021.02.09');
// var_dump($data);

// $date_added = date("Y-m-d H:i:s");
// $last_time_message = '2020-12-29 20:34:45';

// $start_date = new DateTime($date_added);
// $since_start = $start_date->diff(new DateTime($last_time_message));
// echo $since_start->days.' days total<br>';
// echo $since_start->y.' years<br>';
// echo $since_start->m.' months<br>';
// echo $since_start->d.' days<br>';
// echo $since_start->h.' hours<br>';
// echo $since_start->i.' minutes<br>';
// echo $since_start->s.' seconds<br>';
//
// $origin = date_create($date_added);
// $target = date_create($last_time_message);
// $interval = date_diff($origin, $target);
// $hours = intval($interval->format('%H'));
// $days =  intval($interval->format('%d'));
// $month = intval($interval->format('%m'));
// $years = intval($interval->format('%Y'));
//
// var_dump($hours);
// var_dump($days);
// var_dump($month);
// var_dump($years);
//
//
//
// $now_date = new DateTime(date($date_added));    //время сейчас
// $old_date = new DateTime($last_time_message); //дата с которой отчитываем
// $interval = $now_date->diff($old_date);
// echo $interval->format("%H:%I:%S - времени прошло");
//
//
// $time_now = strtotime($date_added);
// $time_need = strtotime($last_time_message);
//
// echo ($time_now-$time_need)/60/60;
// // echo '$period == "year"';
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

//
// $data = $settings->get_users_entity_data();
// var_dump($data);







?>
