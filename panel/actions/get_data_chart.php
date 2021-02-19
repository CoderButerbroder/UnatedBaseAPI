<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

$arr_data_ru_json_chart = json_decode(file_get_contents('https://'.$_SERVER["SERVER_NAME"].'/assets/vendors/apexcharts/ru.json'));

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

if($_POST["chart"] == 'branch') {

  $arr_data_branch = $settings->get_count_entity_branch();
  $arr_result_branch = (object) [];
  $arr_result_branch->name = array_keys($arr_data_branch);
  $arr_result_branch->data = (array) $arr_result_branch->data;
  foreach($arr_data_branch as $key => $value){
    array_push($arr_result_branch->data, $value);
  }
  //
    echo json_encode($arr_result_branch, JSON_UNESCAPED_UNICODE);
}

if($_POST["chart"] == 'user') {
  $arr_data_users = $settings->get_count_all_users();
  $arr_result_usersusers = (object) [];
  $arr_result_usersusers->name = array_keys($arr_data_users);
  $arr_result_usersusers->data = (array) $arr_result_usersusers->data;
  foreach($arr_data_users as $key => $value){
    array_push($arr_result_usersusers->data, $value);
  }
  echo json_encode($arr_result_usersusers, JSON_UNESCAPED_UNICODE);
}

if($_POST["chart"] == 'company') {
  $data_count_company_period = $settings->get_count_entity_groupby_time_reg('month');
  $data_count_company_SK_period = $settings->get_count_entity_skolkovo_groupby_time_reg('month');
  $data_count_company_FSI_period = $settings->get_count_entity_fci_groupby_time_reg('month');
  $data_count_company_EXPORT_period = $settings->get_count_entity_export_groupby_time_reg('month');

  function period($a, $b) {
    if(isset($a->dayd)){
      $date1 = $a->dayd.'.'.$a->monthd.'.'.$a->yeard;
      $date2 = $a->dayd.'.'.$b->monthd.'.'.$b->yeard;
    } else {
      $date1 = '00.'.$a->monthd.'.'.$a->yeard;
      $date2 = '00.'.$b->monthd.'.'.$b->yeard;
    }
      if (( strtotime( $date1 ) == strtotime( $date2 ) )) {
          return 0;
      }
      return ( strtotime( $date1 ) < strtotime( $date2 ) ) ? -1 : 1;
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

  $arr_merge_count_company = array_merge($data_count_company_period, $data_count_company_SK_period, $data_count_company_FSI_period, $data_count_company_EXPORT_period);
  usort($arr_merge_count_company, 'period');

  $temp_data_foreach_count = 0;
  $temp_data_arr_foerch = (object) array();

  foreach ($arr_merge_count_company as $key => $value) {
    $temp_name_to_obj = $value->monthd.$value->yeard;
    if(!isset($temp_data_arr_foerch->$temp_name_to_obj)) {
      $temp_data_arr_foerch->$temp_name_to_obj =$arr_select_month[$value->monthd]->name.' '.$value->yeard;
      $temp_data_foreach_count++;
    }
  }

  $arr_result_company_count = (object) [];
  $arr_result_company_count->data = [];
  $arr_result_company_count->time = [];

  function search_value($value_arr, $value_search){
    foreach ($value_arr as $key2 => $value2) {
      if ($value_search == $value2->monthd.$value2->yeard) {
        return (object) array ( "response" => true , "sum" => $value2->sum);
      }
    }
    return array ( "response" => false );
  }

  $temp_data = (object)  array();
  $temp_data->name = 'Компании';
  $temp_data->data = [];
  $temp_data->time = [];

  foreach ($temp_data_arr_foerch as $key => $value) {
    $temp_search = search_value($data_count_company_period,$key);
    if($temp_search->response){
       array_push( $temp_data->data, $temp_search->sum );
    } else {
       array_push( $temp_data->data, "0" );
    }
  }
  array_push( $arr_result_company_count->data, $temp_data );

  $temp_data = (object)  array();
  $temp_data->name = 'Компании Сколково';
  $temp_data->data = [];

  foreach ($temp_data_arr_foerch as $key => $value) {
    $temp_search = search_value($data_count_company_SK_period,$key);
    if($temp_search->response){
       array_push( $temp_data->data, $temp_search->sum );
    } else {
       array_push( $temp_data->data, "0" );
    }
  }
  array_push( $arr_result_company_count->data, $temp_data );

  $temp_data = (object)  array();
  $temp_data->name = 'Компании ФСИ';
  $temp_data->data = [];

  foreach ($temp_data_arr_foerch as $key => $value) {
    $temp_search = search_value($data_count_company_FSI_period,$key);
    if($temp_search->response){
       array_push( $temp_data->data, $temp_search->sum );
    } else {
       array_push( $temp_data->data, "0" );
    }
  }

  array_push( $arr_result_company_count->data, $temp_data );


  $temp_data = (object)  array();
  $temp_data->name = 'Компании Экспорт';
  $temp_data->data = [];

  foreach ($temp_data_arr_foerch as $key => $value) {
    $temp_search = search_value($data_count_company_EXPORT_period,$key);
    if($temp_search->response){
       array_push( $temp_data->data, $temp_search->sum );
    } else {
       array_push( $temp_data->data, "0" );
    }
  }

  array_push( $arr_result_company_count->data, $temp_data );


  $temp_data = [];
  foreach ($temp_data_arr_foerch as $key => $value) {
    array_push( $arr_result_company_count->time, $value);
  }

  echo json_encode($arr_result_company_count, JSON_UNESCAPED_UNICODE);
}

?>
