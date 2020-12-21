<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

$arr_result = (object) array();

$flag_error = false;

//числовый ключ чтоб понять по какому столбцу запрашивается сортировка
$array_table_colump = array('Number' => 'id_tboil', '0' => 'id_tboil',
                            'L_Name' => 'last_name', '1' => 'last_name',
                            'Name' => 'name', '2' => 'name',
                            'S_Name' => 'second_name', '3' => 'second_name',
                            'Email' => 'email', '4' => 'email',
                            'Phone' => 'phone', '5' => 'phone');

if(intval($_POST["draw"])){
  $arr_result->draw = intval($_POST["draw"]);
} else $flag_error = true;
if(intval($_POST["start"])){
  $limit_start = intval($_POST["start"]);
} else if($_POST["start"] == '0') { $limit_start = 0; } else $flag_error = true;
if(intval($_POST["length"])){
  $limit_count = intval($_POST["length"]);
} else $flag_error = true;
if(intval($_POST["order"][0]["column"])){
  $order_num_request = intval($_POST["order"][0]["column"]);
} else if($_POST["order"][0]["column"] == '0') { $order_num_request = 0; } else $flag_error = true;
if($order_num_request < 0 || $order_num_request > 5) $flag_error = true;
$searh_value = $_POST["search"]["value"];

$order_request = $array_table_colump[$order_num_request];
if(!$order_request) $flag_error = true;
$type_order_request = (trim($_POST["order"][0]["dir"]) == 'asc') ? 'ASC' : 'DESC';

if($flag_error){
  echo json_encode(array('response' => false, 'data' => $limit_count, 'description' => 'Ошибка, Попробуйте позже'), JSON_UNESCAPED_UNICODE);
  exit();
}

$settings = new Settings;
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

//echo json_encode($arr_result ,JSON_UNESCAPED_UNICODE);
echo json_encode(array('response' => true, 'data' => $arr_result) ,JSON_UNESCAPED_UNICODE);


?>
