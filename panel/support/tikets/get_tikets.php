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

$arr_result = (object) array();

$flag_error = false;

//числовый ключ чтоб понять по какому столбцу запрашивается сортировка
$array_table_colump = array('0' => 'id',
                            '1' => 'id',
                            '2' => 'type_support',
                            '3' => 'name',
                            '4' => 'date_added',
                            '5' => 'status');

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
  //echo json_encode(array('response' => false, 'data' => $limit_count, 'description' => 'Ошибка, Попробуйте позже'), JSON_UNESCAPED_UNICODE);
  exit();
}

$settings = new Settings;

$get_data = $settings->get_ticket_datatable($order_request, $type_order_request, $limit_start, $limit_count, $searh_value);

$arr_result->recordsTotal = $get_data->recordsTotal;
$arr_result->recordsFiltered = $get_data->recordsFiltered;
$arr_result->data = (array) $arr_result->data;

$count_row = 1;
foreach ($get_data->data as $key => $value) {
  $temp_obj_data = (object) array();
  // $temp_obj_data->Row = $count_row;
  // $temp_obj_data->Name = $value->Name;
  // $temp_obj_data->INN = $value->Inn;
  // $temp_obj_data->OGRN = $value->Ogrn;
  //$temp_obj_data->Status =
  array_push($arr_result->data, $temp_obj_data);
  $count_row++;
}

//echo json_encode(array('response' => true, 'data' => $arr_result) ,JSON_UNESCAPED_UNICODE);
echo json_encode($arr_result ,JSON_UNESCAPED_UNICODE);


?>
