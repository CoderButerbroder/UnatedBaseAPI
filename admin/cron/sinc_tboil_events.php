<?php
error_reporting(0);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// session_start();
$start = microtime(true);
$start_time = date('H:i');
include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');

ignore_user_abort(true);
set_time_limit(0);

$settings = new Settings;

    $settings->delete_events_mister_proper();

    // $token_tboil = $settings->get_global_settings('tboil_token');
    $hosting_name = $settings->get_global_settings('hosting_name');
    $tboil_site = 'https://tboil.spb.ru';

    $token_tboil1 = $settings->refresh_token_tboil();
    $token_tboil = json_decode($token_tboil1)->token;

    $data_user_tboil = file_get_contents($tboil_site."/api/v2/getUser/?token=".$token_tboil."&userId=81920");



    if (!json_decode($data_user_tboil)->success) {
            $token_tboil = $settings->refresh_token_tboil();
            $token_tboil = json_decode($token_tboil)->token;
            // header('Location: https://'.$hosting_name.'/admin/cron/sinc_tboil_events.php');
            // exit;
    }

    $data_event_tboil = json_decode(file_get_contents($tboil_site."/api/v2/getEventsUsers/?token=".$token_tboil));

    $all_id_event_tboil = $data_event_tboil->data;

    //var_dump($data_event_tboil->data);

    //echo "<script> console.log(JSON.parse('".json_encode(array('response' => true, 'data' => $data_event_tboil->data),JSON_UNESCAPED_UNICODE)."')); </script>";

    $check_id_referer = $settings->get_data_referer($tboil_site);
    $resource_check_id_referer = isset(json_decode($check_id_referer)->data->id) ? trim(json_decode($check_id_referer)->data->id) : 0;

    $count_check_events = 0;
    $count_check = 0;

    $err_count_not_obj = 0;

    foreach ($all_id_event_tboil as $key => $value) {
      $count_check_events++;
      $err_count_not_obj = 0;
      $flag_while = true;

      while($flag_while) {
        if ($err_count_not_obj > 3) {
          $flag_while = false;
        }
        //получаем данные от Tboil
        $data_tboil_str = file_get_contents($tboil_site."/api/v2/getEvent/".$key."/?token=".$token_tboil);
        $data_tboil_obj = json_decode($data_tboil_str);
        //если не объект то значит получили кракозябру или 500ю
        if ($data_tboil_obj == false || !is_object($data_tboil_obj)) {
          $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON ERR] \n from: sinc_tboil_events \nhttp:".implode ( $http_response_header, "\nmess:".$data_tboil_str));
          $err_count_not_obj++;
        } else {
          $err_count_not_obj = 0;
          //проверка авось токен не валидный
          if (($data_tboil_obj->success == false) && ($data_tboil_obj->error == "Неправильный токен" || $data_tboil_obj->error == "\u041d\u0435\u043f\u0440\u0430\u0432\u0438\u043b\u044c\u043d\u044b\u0439 \u0442\u043e\u043a\u0435\u043d")) {
            $token_tboil_now = $settings->get_global_settings('tboil_token');
            //проверка на валидность токена, может другой скрипт уже его обновил
            //в ином случае перевыппускаем токен
            if ($token_tboil != $token_tboil_now) {
              $token_tboil = $token_tboil_now;
              continue;
            } else {
              //перевыпуск
              $token_tboil_str = $settings->refresh_token_tboil();
              $token_tboil = json_decode($token_tboil_str)->token;
              continue;
            }
          } else {
            //продолжаем котовасию
            $flag_while = false;
            if ($data_tboil_obj->success == false) {
              $str_err = "[CRON ERR] \n from: sinc_tboil_events \nevent_id : ".$key."\nmessage: ".$data_tboil_str;
              $settings->telega_send($settings->get_global_settings('telega_chat_error'), $str_err);
            } else {

              //ну наконец таки добрались до обновлений..
              $massiv_data_one_event = $data_tboil_obj->data;
              $type_event = 'individ';

                $date = $massiv_data_one_event->date_start.' '.$massiv_data_one_event->time_start;
                $date_new = date('Y-m-d H:i:s', strtotime($date));
                $date_and = '0000-00-00 00:00:00';
                $name = isset($massiv_data_one_event->name) ? $massiv_data_one_event->name : NULL;
                $description = isset($massiv_data_one_event->description) ? $massiv_data_one_event->description : NULL;
                $organizer = isset($massiv_data_one_event->organizer) ? $massiv_data_one_event->organizer : 0;
                $status = isset($massiv_data_one_event->STATUS) ? $massiv_data_one_event->STATUS : 'Черновик';
                $activation = isset($massiv_data_one_event->ACTIVE) ? $massiv_data_one_event->ACTIVE : NULL;
                $start_datetime_event = isset($date_new) ? $date_new : NULL;
                $end_datetime_event = isset($date_and) ? $date_and : NULL;
                $place = isset($massiv_data_one_event->place) ? $massiv_data_one_event->place : NULL;
                $interest = isset($massiv_data_one_event->interest) ? $massiv_data_one_event->interest : NULL;

                $response_upd = json_decode($settings->add_update_new_event($key,$type_event,$name,$description,$organizer,$status,$activation,$start_datetime_event,$end_datetime_event,$place,$interest,$resource_check_id_referer));
                if ($response_upd->response) {
                  $count_check++;
                    for ($i=0; $i < count($value); $i++) {
                        $settings->add_user_visit_events($key,$value[$i],'1',$resource_check_id_referer);
                    }
                } else {
                  $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \nfrom: sinc_tboil_events \nid_event: ".$key."\nError:".$response_upd->description);
                }
            }
          }

        }

      }
  }


    $settings->telega_send($settings->get_global_settings("telega_chat_error"), "[CRON] \nfrom: sinc_tboil_events\ncount events: ".$count_check_events."\n count upd events:".$count_check."\nstart: ".$start_time."\nВремя выполнения скрипта: ".round(microtime(true) - $start, 4)." сек.");

    exit;

?>
