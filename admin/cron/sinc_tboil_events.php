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
    $count_check_events = 0;
    $count_check = 0;

    foreach ($all_id_event_tboil as $key => $value) {
      $count_check_events++;
        $data_one_event = json_decode(file_get_contents($tboil_site."/api/v2/getEvent/".$key."/?token=".$token_tboil));

        $massiv_data_one_event = $data_one_event->data;
        $type_event = 'individ';


        $id_event_on_referer = $key;
        $resource = isset(json_decode($check_id_referer)->data->id) ? trim(json_decode($check_id_referer)->data->id) : 0;

        if (is_object($massiv_data_one_event)) {
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
        }
        // if ($massiv_data_one_event == NULL || $massiv_data_one_event == null || $massiv_data_one_event == 'NULL' || $massiv_data_one_event == 'null') {
        //   $start_datetime_event = NULL;
        //   $end_datetime_event = NULL;
        //   $name = NULL;
        //   $description = NULL;
        //   $organizer = NULL;
        //   $status = 'одобрено';
        //   $activation = 'N';
        //   $start_datetime_event = NULL;
        //   $end_datetime_event = NULL;
        //   $place = NULL;
        //   $interest = NULL;
        // }

        $response = json_decode($settings->add_update_new_event($id_event_on_referer,$type_event,$name,$description,$organizer,$status,$activation,$start_datetime_event,$end_datetime_event,$place,$interest,$resource));

        if ($response->response) {
          $count_check++;
            for ($i=0; $i < count($value); $i++) {
                $settings->add_user_visit_events($key,$value[$i],'1',$resource);
            }
        } else {
          $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \nfrom: sinc_tboil_events \nid_event: ".$key."\nError:".$response->description);
        }

    }
    $settings->telega_send($settings->get_global_settings("telega_chat_error"), "[CRON] \nfrom: sinc_tboil_events\ncount events: ".$count_check_events."\n count upd events:".$count_check."\nstart: ".$start_time."\nВремя выполнения скрипта: ".round(microtime(true) - $start, 4)." сек.");

    exit;

?>
