<?php
error_reporting(0);
$start = microtime(true);
$start_time = date('H:i');
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// session_start();
include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');

ignore_user_abort(true);
set_time_limit(0);

$settings = new Settings;

    $token_tboil = $settings->get_global_settings('tboil_token');
    $hosting_name = $settings->get_global_settings('hosting_name');
    $tboil_domen = $settings->get_global_settings('tboil_domen');


    $data_user_tboil = file_get_contents("https://tboil.spb.ru/api/v2/getUser/?token=".$token_tboil."&userId=81920");

    if (!json_decode($data_user_tboil)->success) {
        $token_tboil = $settings->refresh_token_tboil();

        if (!json_decode($token_tboil)->response) {
            $settings->add_errors_migrate(0, 'tboil_token');

            header('Location: http://'.$hosting_name.'/admin/cron/sinc_tboil_user.php');
            exit;
        }
        $token_tboil = json_decode($token_tboil)->token;
    }


    // корректность дтокена и обновление для eдиной базы данных
    $curl = curl_init();
    $token = $settings->get_global_settings('unated_base_token');

    $data_post = array('token' => $token,
                       'referer' => 'https://'.$hosting_name.'/'
                      );
    curl_setopt($curl, CURLOPT_URL, 'https://'.$hosting_name.'/v.1.0/method/getValidToken');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
    $out1 = curl_exec($curl);
    curl_close($curl);

    $out1 = stristr($out1, '{');
    $arr_value = json_decode($out1);
    if(!$arr_value->response) {
          $curl = curl_init();
          $log = $settings->get_global_settings('unated_base_login');
          $pas = $settings->get_global_settings('unated_base_pass');
          $data_post = array('login' => $log,
                             'password' => $pas,
                             'referer' => 'https://'.$hosting_name.'/'
                           );
          curl_setopt($curl, CURLOPT_URL, 'https://'.$hosting_name.'/v.1.0/method/getMeToken');
          curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
          $out2 = curl_exec($curl);
          curl_close($curl);

          $arr_value2 = json_decode($out2);
          if(!$arr_value2->response) {
              header('Location: http://'.$hosting_name.'/admin/help/sinc_tboil_user.php');
              exit;
          }
          else {
              $settings->update_global_settings('unated_base_token', $arr_value2->token);
              $token = $arr_value2->token;
          }
    }

// в свете последних событий..
try{

    $data_user_tboil = json_decode(file_get_contents("https://tboil.spb.ru/api/v2/getUsers/?token=".$token_tboil));

    if (!is_object($data_user_tboil) || $data_user_tboil == false) {
      $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON ERR] \n from: upd_tboil_user_main \nhttp:".implode ( $http_response_header, "\n"));
    } else {

      $data_user_tboil->data = array_reverse($data_user_tboil->data);

      $err_count_not_obj = 0;
      foreach( $data_user_tboil->data as $key => $value ) {
        $flag_while = true;
        $err_count_not_obj = 0;
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
            $err_count_not_obj = 0;
            if (($data_user_tboil_cicle_obj->success == false) && ($data_user_tboil_cicle_obj->error == "Неправильный токен" || $data_user_tboil_cicle_obj->error == "\u041d\u0435\u043f\u0440\u0430\u0432\u0438\u043b\u044c\u043d\u044b\u0439 \u0442\u043e\u043a\u0435\u043d")) {
              $str_err = "[CRON ERR] \n from: sinc_tboil_user_team_build \n message: token re_get";
              $settings->telega_send($settings->get_global_settings('telega_chat_error'), $str_err);
              $token_tboil_str = $settings->refresh_token_tboil();
              $token_tboil_obj = json_decode($token_tboil_str);
              if(!is_object($token_tboil_obj) || !$token_tboil_obj->response){
                $str_err = "[CRON ERR] \n from: sinc_tboil_user_team_build \n message: token re_get error";
                $settings->telega_send($settings->get_global_settings('telega_chat_error'), $str_err);
              } else {
                $token_tboil = $token_tboil_obj->token;
              }
              $count_token++;
            } else {

            if ($data_user_tboil_cicle_obj->success == false) {
              $flag_while = false;
              $str_err = "[CRON ERR] \n from: upd_tboil_user_main_thread \nuser_id : ".$value."\nmessage: ".$data_user_tboil_cicle_str;
              $settings->telega_send($settings->get_global_settings('telega_chat_error'), $str_err);
            } else {
              $data_user_tboil_one = $data_user_tboil_cicle_obj->data;
              $flag_while = false;
              $massiv_field_value = [];

              $conformity_tboil = [ "lastName" => "last_name",
                                    "name"=> "name",
                                    "secondName" => "second_name",
                                    "email" => "email",
                                    "phone" => "phone",
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
                if (array_key_exists($key_t , $conformity_tboil ) ) {
                  if (trim($value_t) != '' && $value_t != NULL ) {
                    $massiv_field_value[$conformity_tboil[$key_t]] = $value_t;
                  }
                }
              }

              //для дэбага
              // $str_err = "[CRON] \n from: upd_tboil_user_main_thread \nuser_id : ".$value."\nmessage: ".json_encode($massiv_field_value, JSON_UNESCAPED_UNICODE);
              // $settings->telega_send($settings->get_global_settings('telega_chat_victor'), $str_err);
              // exit();

              $check_upd_str = $settings->cron_mass_update_user_field(json_encode($massiv_field_value, JSON_UNESCAPED_UNICODE), $value);
              $check_upd = json_decode($check_upd_str);
              if ( $check_upd == false ) {
                $str_err = "[CRON ERR] \n from: upd_tboil_user_main_thread \nuser_id : ".$value."\nmessage: внутренняя ошибка обновления\nadd:".$check_upd_str;
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
      }

      /*
      // echo count($data_user_tboil->data);
      // echo "\n";
      // для управляемого количества потоков
      $threads = 3;
      $strs_per_thread = ceil(count($data_user_tboil->data) / $threads);

      //для нескольких потоков по определенному количеству пользователей на поток
      // $strs_per_thread = 1000;
      // $threads = ceil(count($data_user_tboil->data) / $strs_per_thread);

      // echo "Threads: ".$threads."\n";
      // echo "Items per thread: ".$strs_per_thread."\n";

      $path_php = '/home/httpd/fcgi-bin/a353561_api/php-cli';


      /*
      $array_part = array_chunk($data_user_tboil->data, $strs_per_thread);

      for ($i = 0; $i  <  $threads; $i++) {
        $str_json = json_encode( $array_part[$i] );
        $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \n from: upd_tboil_user_main \n start: ".$start_time."\npotok:".$i."\ncount arr users:".count($array_part[$i]));
        passthru("(".$path_php." -f ".__DIR__."/upd_tboil_user_main_thread.php ".($i+1)." ".$str_json." & ) >> /dev/null 2>&1");
      }
      */




    }

    $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \n from: upd_tboil_user_main \n start: ".$start_time."\n End: ".round(microtime(true) - $start, 4)." сек.");
  }
  //Перехватываем (catch) исключение, если что-то идет не так.
  catch (Exception $ex) {
      //Выводим сообщение об исключении.
      // echo $ex->getMessage();
      $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \nfrom: upd_tboil_user_main \nError Message:".$ex->getMessage());

  }
?>
