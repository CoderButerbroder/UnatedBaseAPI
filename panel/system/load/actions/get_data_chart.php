<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

$period_select = trim($_POST["period"]);

if ($period_select != 'year' && $period_select != 'month' && $period_select != 'week' && $period_select != 'day' ) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка полученного параметра'), JSON_UNESCAPED_UNICODE);
  exit();
}

$arr_select_month = array('1' => (object) array('name' => 'Январь', ),
                          '2' => (object) array('name' => 'Февраль', ),
                          '3' => (object) array('name' => 'Март', ),
                          '4' => (object) array('name' => 'Апрель', ),
                          '5' => (object) array('name' => 'Май', ),
                          '6' => (object) array('name' => 'Июнь', ),
                          '7' => (object) array('name' => 'Июль', ),
                          '8' => (object) array('name' => 'Август', ),
                          '9' => (object) array('name' => 'Сентябрь', ),
                          '10' => (object) array('name' => 'Октябрь', ),
                          '11' => (object) array('name' => 'Ноябрь', ),
                          '12' => (object) array('name' => 'Декабрь' ) );

$arr_data_ru_json_chart = json_decode(file_get_contents('https://'.$_SERVER["SERVER_NAME"].'/assets/vendors/apexcharts/ru.json'));

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
// session_destroy();
session_write_close();

$settings = new Settings;

function add_null_in_data_week( $arr_in ) {
  foreach( $arr_in as $key => $value ) {
    if($value->weekd >= 1 && $value->weekd <= 9 ){
      $value->weekd = '0'.$value->weekd;
    }
  }
}

function get_list_date_arr( $arr_in ) {
  global $period_select;
  global $arr_data_period;
  foreach ($arr_in as $key => $value) {
    if ( $period_select == 'day' )   $data_i = strtotime($value->yeard.'-'.$value->monthd.'-'.$value->dayd);
    if ( $period_select == 'week' )  $data_i = strtotime(  $value->yeard.'W'.$value->weekd );
    if ( $period_select == 'month')  $data_i = strtotime( '01.'.$value->monthd.'.'.$value->yeard );
    if ( $period_select == 'year' )  $data_i = strtotime( '01.01.'.$value->yeard );
    if (!in_array($data_i, $arr_data_period)) {
        array_push($arr_data_period, $data_i);
    }
  }
}

function set_get_arr_result($name, $arr_in, $type){
  global $period_select;
  global $arr_data_period;

  $arr_temp = (object) [];
  $arr_temp->name = $name;
  $arr_temp->data = [];
  $arr_temp->type = $type;

  foreach ($arr_data_period as $key_data => $value_data) {
    $flag_value_bool = false;
    $flag_value_int = 0;

    foreach ($arr_in as $key2 => $value2) {
      if($period_select == 'day'){
        $date1 = strtotime($value2->yeard.'-'.$value2->monthd.'-'.$value2->dayd);
      }
      if($period_select == 'week'){
        $date1 = strtotime($value2->yeard.'W'.$value2->weekd);
      }
      if($period_select == 'month'){
        $date1 = strtotime('01.'.$value2->monthd.'.'.$value2->yeard);
      }
      if($period_select == 'year'){
        $date1 = strtotime('01.01.'.$value2->yeard);
      }

      if ($value_data == $date1) {
        $flag_value_bool = true;
        $flag_value_int = $value2->sum;
        break;
      }
    }
    if ($flag_value_bool) {
      array_push($arr_temp->data, $flag_value_int);
    } else {
      array_push($arr_temp->data, 0);
    }
  }

  return $arr_temp;
}


if($_POST["chart"] == 'load_method') {

    $arr_data_method = $settings->get_log_api_response_group_by_method();

    $arr_result = (object) [];
    $arr_result->data = [];
    $arr_result->xaxis = [];

    foreach($arr_data_method as $key => $value){
      array_push($arr_result->data, $value->sum);
      array_push($arr_result->xaxis, $value->method);
    }

    echo json_encode($arr_result, JSON_UNESCAPED_UNICODE);

}


if($_POST["chart"] == 'date_method') {

  $arr_data_method_load = $settings->get_log_api_response_group_by(false, $period_select);

  // $arr_data_users = $settings->get_count_main_users_groupby_time_reg(true, $period_select);

  // $arr_begin_users = [];
  $arr_data_period = [];

  if ($period_select == 'week') {
    add_null_in_data_week($arr_data_method_load);
  }

  if (is_array($arr_data_method_load) && count($arr_data_method_load) > 0 && $arr_data_method_load != 0 ) get_list_date_arr($arr_data_method_load);

  sort($arr_data_period);

  $arr_result = (object) [];
  $arr_result->data = [];
  $arr_result->time = [];
  $arr_result->colors = [];


  $arr_name_method = [];
  foreach ($arr_data_method_load as $key => $value) {
    array_push($arr_name_method, $value->method );
  }

  $arr_name_method = array_unique($arr_name_method);

  while(count($arr_result->colors) < count($arr_name_method)) {
    array_push( $arr_result->colors,  '#' . dechex(rand(0,10000000)) );
    $arr_result->colors = array_unique($arr_result->colors);
  }

  foreach ($arr_name_method as $key_name_m => $value_name_m) {
    $flag_break = false;
    $arr_temp = (object) [];
    $arr_temp->name = $value_name_m;
    $arr_temp->data = [];
    $arr_temp->type = 'line';

    foreach ($arr_data_period as $key_time => $value_time) {
      foreach ($arr_data_method_load as $key_method => $value_method) {
        if($period_select == 'day'){
         $temp_time_method = strtotime($value_method->yeard.'-'.$value_method->monthd.'-'.$value_method->dayd);
       }
       if($period_select == 'week'){
         $temp_time_method = strtotime($value_method->yeard.'W'.$value_method->weekd);
       }
       if($period_select == 'month'){
         $temp_time_method = strtotime('01.'.$value_method->monthd.'.'.$value_method->yeard);
       }
       if($period_select == 'year'){
         $temp_time_method = strtotime('01.01.'.$value_method->yeard);
       }
        if ($value_name_m == $value_method->method && $temp_time_method == $value_time) {
           array_push($arr_temp->data, $value_method->sum);
           $flag_break = true;
           break;
        }
      }
      if (!$flag_break) {
        array_push($arr_temp->data, 0);
      }
    }
    array_push($arr_result->data,  $arr_temp );
  }


  foreach ($arr_data_period as $key => $value) {
    if($period_select == 'day'){
      $data_key = date('d', $value)." ".$arr_select_month[date('n', $value)]->name." ".date('Y', $value);

    }
    if($period_select == 'week'){
      $data_key = intval(date('W', $value))." Нед. ".$arr_select_month[date('n', $value)]->name." ".date('Y', $value);

    }
    if($period_select == 'month'){
      $data_key = $arr_select_month[date('n', $value)]->name." ".date('Y', $value);

    }
    if($period_select == 'year'){
      $data_key = date('Y', $value);
    }
    array_push( $arr_result->time,  $data_key );
  }

  echo json_encode($arr_result, JSON_UNESCAPED_UNICODE);

}



if($_POST["chart"] == 'load_refer') {

  $arr_data_load = $settings->get_log_api_response_group_by_referer2();
  // echo json_encode($arr_data_load, JSON_UNESCAPED_UNICODE);

  $arr_result = (object) [];
  $arr_result->data = [];
  $arr_result->xaxis = [];

  foreach($arr_data_load as $key => $value){
   $refer_data = json_decode($settings->get_data_referer_id($value->referer));
   $temp_name = ($refer_data->data->resourse == '' || $refer_data->data->resourse == NULL) ? 'Err' : $refer_data->data->resourse;
    array_push($arr_result->data, $value->sum);
    array_push($arr_result->xaxis, $temp_name);
  }

  echo json_encode($arr_result, JSON_UNESCAPED_UNICODE);

}


if($_POST["chart"] == 'date_method_Scatter') {

  $arr_data_method_load = $settings->get_log_api_response_group_by(false, $period_select);


  $arr_data_period = [];

  if ($period_select == 'week') {
    add_null_in_data_week($arr_data_method_load);
  }

  if (is_array($arr_data_method_load) && count($arr_data_method_load) > 0 && $arr_data_method_load != 0 ) get_list_date_arr($arr_data_method_load);

  sort($arr_data_period);

  $arr_result = (object) [];
  $arr_result->data = [];
  $arr_result->time = [];
  $arr_result->colors = [];



  $arr_name_method = [];
  foreach ($arr_data_method_load as $key => $value) {
    array_push($arr_name_method, $value->method );
  }

  $arr_name_method = array_unique($arr_name_method);

  while(count($arr_result->colors) < count($arr_name_method)) {
    array_push( $arr_result->colors,  '#' . dechex(rand(0,10000000)) );
    $arr_result->colors = array_unique($arr_result->colors);
  }


  foreach ($arr_name_method as $key_name_m => $value_name_m) {
    $flag_break = false;
    $arr_temp = (object) [];
    $arr_temp->name = $value_name_m;
    $arr_temp->data = [];
    // $arr_temp->type = 'line';

    foreach ($arr_data_period as $key_time => $value_time) {
      foreach ($arr_data_method_load as $key_method => $value_method) {
        if($period_select == 'day'){
         $temp_time_method = strtotime($value_method->yeard.'-'.$value_method->monthd.'-'.$value_method->dayd);
       }
       if($period_select == 'week'){
         $temp_time_method = strtotime($value_method->yeard.'W'.$value_method->weekd);
       }
       if($period_select == 'month'){
         $temp_time_method = strtotime('01.'.$value_method->monthd.'.'.$value_method->yeard);
       }
       if($period_select == 'year'){
         $temp_time_method = strtotime('01.01.'.$value_method->yeard);
       }
        if ($value_name_m == $value_method->method && $temp_time_method == $value_time) {
          array_push($arr_temp->data, $value_method->sum );
           // array_push($arr_temp->data, [date('m.Y', $value_time), $value_method->sum] );
           $flag_break = true;
           break;
        }
      }
      if (!$flag_break) {
        array_push($arr_temp->data, 0);
        // array_push($arr_temp->data, [date('m.Y', $value_time), 0]);
      }
    }
    array_push($arr_result->data,  $arr_temp );
  }


  foreach ($arr_data_period as $key => $value) {
    if($period_select == 'day'){
      $data_key = date('d', $value)." ".$arr_select_month[date('n', $value)]->name." ".date('Y', $value);

    }
    if($period_select == 'week'){
      $data_key = intval(date('W', $value))." Нед. ".$arr_select_month[date('n', $value)]->name." ".date('Y', $value);

    }
    if($period_select == 'month'){
      $data_key = $arr_select_month[date('n', $value)]->name." ".date('Y', $value);

    }
    if($period_select == 'year'){
      $data_key = date('Y', $value);
    }
    array_push( $arr_result->time,  $data_key );
  }




  echo json_encode($arr_result, JSON_UNESCAPED_UNICODE);

}

?>
