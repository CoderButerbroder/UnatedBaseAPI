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

function set_get_arr_result($name, $arr_in, $type, $flag_ink = false){
  global $period_select;
  global $arr_data_period;

  $incr = 0;

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
        if ($flag_ink) {
          if ($value2->sum > 0) {
            $incr = $value2->sum;
          }
          $flag_value_int = $incr;
        } else {
          $flag_value_int = $value2->sum;
        }
        break;
      }
    }
    if ($flag_value_bool) {
      array_push($arr_temp->data, $flag_value_int);
    } else {
      array_push($arr_temp->data, $incr);
    }
  }

  return $arr_temp;
}

function crop_el_arr(&$arr){
  global $period_select;

  foreach ($arr as $key => $value) {
    if ( ( $period_select == 'day' && $value->yeard == 0 && $value->monthd == 0 && $value->dayd )
        || ( $period_select == 'week' && $value->yeard == 0 && $value->weekd == 0)
        || ( $period_select == 'month' && $value->yeard == 0 && $value->monthd == 0)
        || ( $period_select == 'year' && $value->yeard == 0 ) ) {
          unset( $arr[$key] );
    }
  }
}


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

  $arr_data_users = $settings->get_count_main_users_groupby_time_reg(true, $period_select);

  $arr_data_users_active = $settings->get_main_users_activation_group_by_time_reg('Y', true, $period_select);
  $arr_data_users_no_active = $settings->get_main_users_activation_group_by_time_reg('N', true, $period_select);

  // var_dump( $arr_data_users_no_active );
  // echo "</br>";
  // echo "</br>";

  // foreach($arr_data_users as $key => $value){
  //   echo 'key:'.$key.' '.date('d.m.Y', strtotime('01.'.$value->monthd.'.'.$value->yeard) );
  //   echo "</br>";
  // }
  // echo "</br>";
  // echo "</br>";
  //
  // foreach($arr_data_users_active as $key => $value){
  //   echo 'key:'.$key.' '.date('d.m.Y', strtotime('01.'.$value->monthd.'.'.$value->yeard) );
  //   echo "</br>";
  // }
  // echo "</br>";
  // echo "</br>";
  //
  // foreach($arr_data_users_no_active as $key => $value){
  //   echo 'key:'.$key.' '.date('d.m.Y', strtotime('01.'.$value->monthd.'.'.$value->yeard) );
  //   echo "</br>";
  // }


  // $arr_begin_users = [];
  $arr_data_period = [];

  if ($period_select == 'week') {
    add_null_in_data_week($arr_data_users);
    add_null_in_data_week($arr_data_users_active);
    add_null_in_data_week($arr_data_users_no_active);
  }

  if (is_array($arr_data_users) && count($arr_data_users) > 0 && $arr_data_users != 0 ) crop_el_arr($arr_data_users);
  if (is_array($arr_data_users_active) && count($arr_data_users_active) > 0 && $arr_data_users_active != 0 ) crop_el_arr($arr_data_users_active);
  if (is_array($arr_data_users_no_active) && count($arr_data_users_no_active) > 0 && $arr_data_users_no_active != 0 ) crop_el_arr($arr_data_users_no_active);

  if (is_array($arr_data_users) && count($arr_data_users) > 0 && $arr_data_users != 0 ) get_list_date_arr($arr_data_users);
  if (is_array($arr_data_users_active) && count($arr_data_users_active) > 0 && $arr_data_users_active != 0 ) get_list_date_arr($arr_data_users_active);
  if (is_array($arr_data_users_no_active) && count($arr_data_users_no_active) > 0 && $arr_data_users_no_active != 0 ) get_list_date_arr($arr_data_users_no_active);

  $arr_data_period = array_unique( $arr_data_period );

  sort($arr_data_period);

  $arr_result = (object) [];
  $arr_result->data = [];
  $arr_result->time = [];

  array_push($arr_result->data,  set_get_arr_result( 'Все', $arr_data_users, 'line', true ) );
  array_push($arr_result->data,  set_get_arr_result( 'Активированные', $arr_data_users_active, 'line', true ) );
  array_push($arr_result->data,  set_get_arr_result( 'Не активированные', $arr_data_users_no_active, 'line', true ) );

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

  //
  // foreach($arr_data_users as $key => $value) {
  //   if($period_select == 'day'){
  //     $data_i = strtotime($value->yeard.'-'.$value->monthd.'-'.$value->dayd);
  //     $data_key = $value->dayd." ".$arr_select_month[date('n', $data_i)]->name." ".$value->yeard;
  //   }
  //   if ( $period_select == 'week' )  {
  //     $data_i = strtotime(  $value->yeard.'W'.$value->weekd );
  //     $data_key = $value->weekd." ".$arr_select_month[date('n', $data_i)]->name." ".$value->yeard;
  //   }
  //   if ( $period_select == 'month' ){
  //     $data_i = strtotime('01.'.$value->monthd.'.'.$value->yeard);
  //     $data_key = $arr_select_month[date('n', $data_i)]->name." ".$value->yeard;
  //   }
  //   if($period_select == 'year'){
  //     $data_key = $value->yeard;
  //   }
  //   $arr_begin_users[$data_key] = $value->sum;
  // }
  //
  // $arr_result_users = (object) [];
  // $arr_result_users->name = array_keys($arr_begin_users);
  // $arr_result_users->data = (array) $arr_result_users->data;
  // foreach($arr_begin_users as $key => $value){
  //   array_push($arr_result_users->data, $value);
  // }
  // echo json_encode($arr_result_users, JSON_UNESCAPED_UNICODE);

}

if($_POST["chart"] == 'company') {
  $data_count_company_period = $settings->get_count_entity_groupby_time_reg($period_select);
  $data_count_company_SK_period = $settings->get_count_entity_skolkovo_groupby_time_reg($period_select);
  $data_count_company_FSI_period = $settings->get_count_entity_fci_groupby_time_reg($period_select);
  $data_count_company_EXPORT_period = $settings->get_count_entity_export_groupby_time_reg($period_select);


  $arr_data_period = [];

  if ($period_select == 'week') {
    add_null_in_data_week($data_count_company_period);
    add_null_in_data_week($data_count_company_SK_period);
    add_null_in_data_week($data_count_company_FSI_period);
    add_null_in_data_week($data_count_company_EXPORT_period);
  }

  if (is_array($data_count_company_period) && count($data_count_company_period) > 0 && $data_count_company_period != 0 ) get_list_date_arr($data_count_company_period);
  if (is_array($data_count_company_SK_period) && count($data_count_company_SK_period) > 0 && $data_count_company_SK_period != 0 ) get_list_date_arr($data_count_company_SK_period);
  if (is_array($data_count_company_FSI_period) && count($data_count_company_FSI_period) > 0 && $data_count_company_FSI_period != 0 ) get_list_date_arr($data_count_company_FSI_period);
  if (is_array($data_count_company_EXPORT_period) && count($data_count_company_EXPORT_period) > 0 && $data_count_company_EXPORT_period != 0 ) get_list_date_arr($data_count_company_EXPORT_period);

  sort($arr_data_period);


  $arr_result = (object) [];
  $arr_result->data = [];
  $arr_result->time = [];

  array_push($arr_result->data,  set_get_arr_result( 'Компании', $data_count_company_period, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Компании Сколково', $data_count_company_SK_period, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Компании ФСИ', $data_count_company_FSI_period, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Компании Экспорт', $data_count_company_EXPORT_period, 'line' ) );

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

if($_POST["chart"] == 'FSI') {
  $arr_FSI_count = $settings->get_count_main_entity_fci_groupby_time_reg(true, $period_select);
  $arr_FSI_YMNIK_count = $settings->get_count_main_entity_fci_program_groupby_time_reg(true,'У', $period_select);
  $arr_FSI_count_service =  $settings->get_count_main_entity_skolkovo_programs_groupby_time_reg(false,
                                        ['Запрос на консультацию проекта при подаче заявки в ФСИ','Информационное сопровождение проекта ФСИ'],$period_select);


  $arr_data_period = [];

  if ($period_select == 'week') {
    add_null_in_data_week($arr_FSI_count);
    add_null_in_data_week($arr_FSI_YMNIK_count);
    add_null_in_data_week($arr_FSI_count_service);
  }

  if (is_array($arr_FSI_count) && count($arr_FSI_count) > 0 && $arr_FSI_count != 0 ) get_list_date_arr($arr_FSI_count);
  if (is_array($arr_FSI_YMNIK_count) && count($arr_FSI_YMNIK_count) > 0 && $arr_FSI_YMNIK_count != 0 ) get_list_date_arr($arr_FSI_YMNIK_count);
  if (is_array($arr_FSI_count_service) && count($arr_FSI_count_service) > 0 && $arr_FSI_count_service != 0 ) get_list_date_arr($arr_FSI_count_service);

  sort($arr_data_period);

  $arr_result = (object) [];
  $arr_result->data = [];
  $arr_result->time = [];

  array_push($arr_result->data,  set_get_arr_result( 'Юр.лица ФСИ', $arr_FSI_count, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Физ. лица Умник', $arr_FSI_YMNIK_count, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Новые проекты', $arr_FSI_count_service, 'line' ) );

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

if($_POST["chart"] == 'SK') {
  $arr_SK_count = $settings->get_count_main_entity_skolkovo_groupby_time_reg(true, $period_select);
  $arr_service_event = $settings->get_count_main_support_ticket_groupby_time_add(true, 'Организация встречи с проектным менеджером Сколково', $period_select);
  $arr_CKP_count =  $settings->get_count_main_support_ticket_groupby_time_add(true, 'Получение услуги центра коллективного пользования (производство)', $period_select);
  $arr_new_SK_count = $settings->get_count_main_entity_skolkovo_groupby_time_reg(false, $period_select);
  $arr_SK_count_service =  $settings->get_count_main_entity_skolkovo_programs_groupby_time_reg(false,
                                        ['Запрос на консультацию компании - Фонд Сколково','Информационное сопровождение проекта Сколково'], $period_select);


  $arr_data_period = [];

  if ($period_select == 'week') {
    add_null_in_data_week($arr_SK_count);
    add_null_in_data_week($arr_service_event);
    add_null_in_data_week($arr_CKP_count);
    add_null_in_data_week($arr_new_SK_count);
    add_null_in_data_week($arr_SK_count_service);
  }

  if (is_array($arr_SK_count) && count($arr_SK_count) > 0 && $arr_SK_count != 0 ) get_list_date_arr($arr_SK_count);
  if (is_array($arr_service_event) && count($arr_service_event) > 0 && $arr_service_event != 0 ) get_list_date_arr($arr_service_event);
  if (is_array($arr_CKP_count) && count($arr_CKP_count) > 0 && $arr_CKP_count != 0 ) get_list_date_arr($arr_CKP_count);
  if (is_array($arr_new_SK_count) && count($arr_new_SK_count) > 0 && $arr_new_SK_count != 0 ) get_list_date_arr($arr_new_SK_count);
  if (is_array($arr_SK_count_service) && count($arr_SK_count_service) > 0 && $arr_SK_count_service != 0 ) get_list_date_arr($arr_SK_count_service);

  sort($arr_data_period);

  $arr_result = (object) [];
  $arr_result->data = [];
  $arr_result->time = [];

  array_push($arr_result->data,  set_get_arr_result( 'Кол-во Юр.лиц - участников', $arr_SK_count, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Организация встречи с проектным менеджером Сколково', $arr_service_event, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Консультации по услугам ЦКП', $arr_CKP_count, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Новых резидентов', $arr_new_SK_count, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Кол-во услуг', $arr_SK_count_service, 'line' ) );

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

if($_POST["chart"] == 'event') {
  $arr_data_event_summ = $settings->get_count_main_events_groupby_time_reg(false, $period_select);
  // $arr_data_event_summ_confirm = $settings->get_count_main_events_groupby_time_reg(false, $period_select);
  $arr_data_users_event_summ = $settings->get_count_users_main_events_groupby_time_reg(false, $period_select);

  $arr_data_period = [];

  if ($period_select == 'week') {
    add_null_in_data_week($arr_data_event_summ);
    add_null_in_data_week($arr_data_users_event_summ);
    // add_null_in_data_week($arr_data_event_summ_confirm);
  }

  if (is_array($arr_data_event_summ) && count($arr_data_event_summ) > 0 && $arr_data_event_summ != 0 ) get_list_date_arr($arr_data_event_summ);
  if (is_array($arr_data_users_event_summ) && count($arr_data_users_event_summ) > 0 && $arr_data_users_event_summ != 0 ) get_list_date_arr($arr_data_users_event_summ);
  // if (is_array($arr_data_event_summ_confirm) && count($arr_data_event_summ_confirm) > 0 && $arr_data_event_summ_confirm != 0 ) get_list_date_arr($arr_data_event_summ_confirm);

  $arr_data_period = array_unique( $arr_data_period );
  sort($arr_data_period);

  $arr_result = (object) [];
  $arr_result->data = [];
  $arr_result->time = [];

  array_push($arr_result->data,  set_get_arr_result( 'Кол-во мероприятий', $arr_data_event_summ, 'line' ) );
  // array_push($arr_result->data,  set_get_arr_result( 'Кол-во одобренных мероприятий', $arr_data_event_summ_confirm, 'line' ) );
  array_push($arr_result->data,  set_get_arr_result( 'Кол-во участников', $arr_data_users_event_summ, 'column' ) );

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
