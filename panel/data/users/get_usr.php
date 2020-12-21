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
$limit_start = $_POST["start"] ;
$limit_count = $_POST["length"];
$searh_value = $_POST["search"]["value"];
$settings = new Settings;

//числовый ключ чтоб понять по какому столбцу запрашивается сортировка
$array_table_colump = array('Number' => 'id_tboil', '0' => 'id_tboil',
                            'L_Name' => 'last_name', '1' => 'last_name',
                            'Name' => 'name', '2' => 'name',
                            'S_Name' => 'second_name', '3' => 'second_name',
                            'Email' => 'email', '4' => 'email',
                            'Phone' => 'phone', '5' => 'phone');

$order_num_request = $_POST["order"][0]["column"];
$order_request = $array_table_colump[$order_num_request];
$type_order_request = $_POST["order"][0]["dir"];

global $database;
$count_users = $database->prepare("SELECT COUNT(*) AS COUNT FROM `MAIN_users` ");
$count_users->execute();
$data_count_users = $count_users->fetch(PDO::FETCH_OBJ);
if('' == $searh_value){
  $data_users = $database->prepare("SELECT * FROM `MAIN_users` ORDER BY {$order_request} {$type_order_request} LIMIT {$limit_start}, {$limit_count} ");
} else {
  $data_users_count = $database->prepare("SELECT COUNT(*) AS COUNT FROM `MAIN_users` WHERE id_tboil LIKE '%{$searh_value}%' OR last_name LIKE '%{$searh_value}%' OR name LIKE '%{$searh_value}%' OR second_name LIKE '%{$searh_value}%' OR email LIKE '%{$searh_value}%' OR phone LIKE '%{$searh_value}%' ORDER BY id");
  $data_users_count->execute();
  $data_users_count_result = $data_users_count->fetch(PDO::FETCH_OBJ);
  $data_users = $database->prepare("SELECT * FROM `MAIN_users` WHERE id_tboil LIKE '%{$searh_value}%' OR last_name LIKE '%{$searh_value}%' OR name LIKE '%{$searh_value}%' OR second_name LIKE '%{$searh_value}%' OR email LIKE '%{$searh_value}%' OR phone LIKE '%{$searh_value}%' ORDER BY {$order_request} {$type_order_request} LIMIT {$limit_start}, {$limit_count} ");
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

$count_row = 1;
foreach ($data_users as $key => $value) {
  $temp_obj_data = (object) array();
  $temp_obj_data->Row = $count_row;
  $temp_obj_data->Number = $value->id_tboil;
  $temp_obj_data->L_Name = $value->last_name;
  $temp_obj_data->Name = $value->name;
  $temp_obj_data->S_Name = $value->second_name;
  $temp_obj_data->Email = $value->email;
  $temp_obj_data->Phone = $value->phone;
  array_push($arr_result->data, $temp_obj_data);
  $count_row++;
}

echo json_encode($arr_result ,JSON_UNESCAPED_UNICODE);


?>
