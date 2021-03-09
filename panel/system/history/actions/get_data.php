<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

$arr_result = (object) [];

$flag_error = false;

//числовый ключ чтоб понять по какому столбцу запрашивается сортировка
$array_table_colump = array('0' => 'id',
                            '1' => 'id_user',
                            '3' => 'action',
                            '4' => 'type_message',
                            // '5' => 'data',
                            '6' => 'date_time',

                          );

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
if($order_num_request < 0 || $order_num_request > 6) $flag_error = true;
$searh_value = $_POST["search"]["value"];

$order_request = $array_table_colump[$order_num_request];
if(!$order_request) $flag_error = true;
$type_order_request = (trim($_POST["order"][0]["dir"]) == 'asc') ? 'ASC' : 'DESC';

if($flag_error){
  //echo json_encode(array('response' => false, 'data' => $limit_count, 'description' => 'Ошибка, Попробуйте позже'), JSON_UNESCAPED_UNICODE);
  exit();
}

$settings = new Settings;

$get_data = $settings->get_history_apiusers_datatable($order_request, $type_order_request, $limit_start, $limit_count, $searh_value);

$arr_result->recordsTotal = $get_data->recordsTotal;
$arr_result->recordsFiltered = $get_data->recordsFiltered;
$arr_result->data = (array) $arr_result->data;

$count_row = 1;

foreach ($get_data->data as $key => $value) {

  $temp_obj_data = (object) [];

  $temp_obj_data->Tboil = ($temp_data_usr_company == false) ? false : $temp_data_usr_company;

  $temp_obj_data->id = $value->id;
  $temp_obj_data->id_user = $value->id_user;
  // $temp_obj_data->FIO = $value->id_user;
  $temp_data_api_user = $settings->get_data_user_api($value->id_user);
  $temp_obj_data->FIO = ($temp_data_api_user != false) ? "$temp_data_api_user->lastname $temp_data_api_user->name $temp_data_api_user->second_name" : "Error";
  $temp_obj_data->action = $value->action;
  $temp_obj_data->type = $value->type_message;
  $temp_obj_data->content = $value->data;
  $temp_obj_data->d_time = date('H:i d.m.Y', strtotime($value->date_time));

  array_push($arr_result->data, $temp_obj_data);
  $count_row++;
}

//echo json_encode(array('response' => true, 'data' => $arr_result) ,JSON_UNESCAPED_UNICODE);
echo json_encode($arr_result ,JSON_UNESCAPED_UNICODE);


?>
