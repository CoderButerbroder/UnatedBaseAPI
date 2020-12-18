<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

$arr_result = (object) array();

$arr_result->draw = $_POST["draw"];
$limit_start = $_POST["start"] + 1;
$limit_count = $_POST["length"];
$searh_value = $_POST["search"]["value"];
$settings = new Settings;

global $database;
$count_users = $database->prepare("SELECT COUNT(*) AS COUNT FROM `MAIN_users` ");
$count_users->execute();
$data_count_users = $count_users->fetch(PDO::FETCH_OBJ);
if('' == $searh_value){
  $data_users = $database->prepare("SELECT * FROM `MAIN_users` ORDER BY id LIMIT {$limit_start}, {$limit_count} ");
} else {
  $data_users_count = $database->prepare("SELECT COUNT(*) AS COUNT FROM `MAIN_users` WHERE id_tboil LIKE '%{$searh_value}%' OR last_name LIKE '%{$searh_value}%' OR name LIKE '%{$searh_value}%' OR second_name LIKE '%{$searh_value}%' OR email LIKE '%{$searh_value}%' OR phone LIKE '%{$searh_value}%' ORDER BY id");
  $data_users_count->execute();
  $data_users_count_result = $data_users_count->fetch(PDO::FETCH_OBJ);
  $data_users = $database->prepare("SELECT * FROM `MAIN_users` WHERE id_tboil LIKE '%{$searh_value}%' OR last_name LIKE '%{$searh_value}%' OR name LIKE '%{$searh_value}%' OR second_name LIKE '%{$searh_value}%' OR email LIKE '%{$searh_value}%' OR phone LIKE '%{$searh_value}%' ORDER BY id LIMIT {$limit_start}, {$limit_count} ");

}
$data_users->execute();
$data_users = $data_users->fetchAll(PDO::FETCH_OBJ);

$arr_result->recordsTotal = $data_count_users->COUNT;
if('' == $searh_value){
  $arr_result->recordsFiltered = $data_count_users->COUNT;
} else {
  $arr_result->recordsFiltered = $data_users_count_result->COUNT;
}
$arr_result->data = (array) $arr_result->data ;

foreach ($data_users as $key => $value) {
  $temp_obj_data = (object) array();
  $temp_obj_data->Number = $value->id_tboil;
  $temp_obj_data->FIO = $value->last_name.' '.$value->name.' '.$value->second_name;
  $temp_obj_data->Email = $value->email;
  $temp_obj_data->Phone = $value->phone;
  array_push($arr_result->data, $temp_obj_data);
}

echo json_encode($arr_result ,JSON_UNESCAPED_UNICODE);


?>
