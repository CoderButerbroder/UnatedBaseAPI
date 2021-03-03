<?php
error_reporting(0);
$start = microtime(true);
$start_time = date('H:i');

include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');

ignore_user_abort(true);
set_time_limit(0);

$iter = $argv[1];
$arr_users = json_decode($argv[2]);

$settings = new Settings;

if ( $iter == '' || !is_array($arr_users) ){
  $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON ERR] \nfrom: upd_tboil_user_main_thread \niter:".$iter." count user: ".count($arr_users));
  exit();
}
/*
сообственно сам for/foreach с вызовом функция и тд
*/

$token_tboil = $settings->get_global_settings('tboil_token');
$tboil_domen = $settings->get_global_settings('tboil_domen');

$err_count_not_obj = 0;
foreach( $arr_users as $key => $value ) {
  $flag_while = true;
  while($flag_while) {
    //получаем данные от Tboil
    $data_user_tboil_cicle_str = file_get_contents("https://".$tboil_domen."/api/v2/getUser/?token=".$token_tboil."&userId=".$value);
    $data_user_tboil_cicle_obj = json_decode($data_user_tboil_cicle_str);
    //если не объект то значит получили кракозябру или 500ю
    if(!is_object($data_user_tboil_cicle_obj) ) {
      $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON ERR] \nfrom: upd_tboil_user_main_thread\nuser_id:".$value."\nmessage:".$data_user_tboil_cicle_str."\nhttp:".implode ( $http_response_header, "\n") );
      //проверка чтоб не повиснуть на 1м пользователе
      if ($err_count_not_obj >= 3) {
        $flag_while = false;
      }
      $err_count_not_obj++;
    } else {
      if ($data_user_tboil_cicle->success == false) {
        $flag_while = false;
        $str_err = "[CRON ERR] \n from: upd_tboil_user_main_thread \nuser_id : ".$value."\nmessage: ".$data_user_tboil_cicle_str;
        $settings->telega_send($settings->get_global_settings('telega_chat_error'), $str_err);
      } else {
        $data_user_tboil_one = $data_user_tboil_cicle->data;
        $flag_while = false;
        $massiv_field_value = [];

        $conformity_tboil = [ "lastName" => "last_name",
                              "name"=> "name",
                              "secondName" => "second_name",
                              "email" => "email",
                              "phone" => "photo",
                              "company" => "company",
                              "position" => "profession",
                              "birthday" => "DOB",
                              "city" => "adres",
                              "job" => "job",
                              "status" => "status",
                              "UF_COMMAND" => "team_build",
                              "ACTIVE" => "activation",
                              "statuss" => "position",
                              "sfera" => "scope" ];

        foreach ($data_user_tboil_one as $key_t => $value_t) {
          if (trim($value_t) != '' && $value_t != NULL ) {
            $massiv_field_value[$conformity_tboil[$key_t]] = $value_t;
          }
        }

        $check_upd = json_decode($settings->mass_update_user_field($massiv_field_value, $value));
        if ( !$check_upd ) {
          $str_err = "[CRON ERR] \n from: upd_tboil_user_main_thread \nuser_id : ".$value."\nmessage: внутренняя ошибка обновления";
          $settings->telega_send($settings->get_global_settings('telega_chat_error'), $str_err);
        } else {
          if ( $check_upd->response == false  ) {
            $str_err = "[CRON ERR] \n from: upd_tboil_user_main_thread \nuser_id : ".$value."\nmessage: ".$check_upd->description;
            $settings->telega_send($settings->get_global_settings('telega_chat_error'), $str_err);
          }
        }
      }
    }
  }
}


$settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \nfrom: upd_tboil_user_main_thread \niter:".$iter." \ncount:".count($arr_users)."\none_id:".$arr_users[0]."\nstart: ".$start_time."\nEnd: ".round(microtime(true) - $start, 4)." сек.");

?>
