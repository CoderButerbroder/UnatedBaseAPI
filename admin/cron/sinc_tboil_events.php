<?php
error_reporting(0);
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
    $tboil_site = 'https://tboil.spb.ru';

    $data_user_tboil = file_get_contents($tboil_site."/api/v2/getUser/?token=".$token_tboil."&userId=81920");

    if (!json_decode($data_user_tboil)->success) {
        $token_tboil = $settings->refresh_token_tboil();

        if (!json_decode($token_tboil)->response) {
            $settings->add_errors_migrate(0, 'tboil_token');

            header('Location: https://'.$hosting_name.'/admin/cron/sinc_tboil_events.php');
            exit;
        }
        $token_tboil = json_decode($token_tboil)->token;
    }


    // корректность дтокена и обновление для eдиной базы данных
    // $curl = curl_init();
    // $token = $settings->get_global_settings('unated_base_token');
    //
    // $data_post = array('token' => $token,
    //                    'referer' => 'https://'.$hosting_name.'/'
    //                   );
    // curl_setopt($curl, CURLOPT_URL, 'https://'.$hosting_name.'/v.1.0/method/getValidToken');
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
    // $out1 = curl_exec($curl);
    // curl_close($curl);
    //
    // $out1 = stristr($out1, '{');
    // $arr_value = json_decode($out1);
    // if(!$arr_value->response) {
    //       $curl = curl_init();
    //       $log = $settings->get_global_settings('unated_base_login');
    //       $pas = $settings->get_global_settings('unated_base_pass');
    //       $data_post = array('login' => $log,
    //                          'password' => $pas,
    //                          'referer' => 'https://'.$hosting_name.'/'
    //                        );
    //       curl_setopt($curl, CURLOPT_URL, 'https://'.$hosting_name.'/v.1.0/method/getMeToken');
    //       curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    //       curl_setopt($curl, CURLOPT_POST, true);
    //       curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
    //       $out2 = curl_exec($curl);
    //       curl_close($curl);
    //
    //       $arr_value2 = json_decode($out2);
    //       if(!$arr_value2->response) {
    //           header('Location: http://'.$hosting_name.'/admin/help/sinc_tboil_user.php');
    //           exit;
    //       }
    //       else {
    //           $settings->update_global_settings('unated_base_token', $arr_value2->token);
    //           $token = $arr_value2->token;
    //       }
    // }



    $data_event_tboil = json_decode(file_get_contents($tboil_site."/api/v2/getEventsUsers/?token=".$token_tboil));

    $all_id_event_tboil = $data_event_tboil->data;

    // var_dump($all_id_event_tboil);
    // exit;

    foreach ($all_id_event_tboil as $key => $value) {

        $data_one_event = json_decode(file_get_contents($tboil_site."/api/v2/getEvent/".$key."/?token=".$token_tboil));

        $massiv_data_one_event = $data_one_event->data;

        $check_id_referer = $settings->get_data_referer($tboil_site);

        $date = $massiv_data_one_event->date.' '.$massiv_data_one_event->time;
        $date_new = date('Y-m-d H:i:s', strtotime($date));
        $date_and = '0000-00-00 00:00:00';

        $id_event_on_referer = isset($key) ? $key : ' ';
        $type_event = 'individ';
        $name = isset($massiv_data_one_event->name) ? $massiv_data_one_event->name : ' ';
        $description = isset($massiv_data_one_event->description) ? $massiv_data_one_event->description : ' ';
        $organizer = isset($massiv_data_one_event->organizer) ? $massiv_data_one_event->organizer : 0;
        $status = isset($massiv_data_one_event->status) ? $massiv_data_one_event->status : ' ';
        $start_datetime_event = isset($date_new) ? $date_new : ' ';
        $end_datetime_event = isset($date_and) ? $date_and : ' ';
        $place = isset($massiv_data_one_event->place) ? $massiv_data_one_event->place : ' ';
        $interest = isset($massiv_data_one_event->interest) ? $massiv_data_one_event->interest : ' ';
        $resource = isset(json_decode($check_id_referer)->data->id) ? trim(json_decode($check_id_referer)->data->id) : 0;



        $response = $settings->add_update_new_event($id_event_on_referer,$type_event,$name,$description,$organizer,$status,$start_datetime_event,$end_datetime_event,$place,$interest,$resource);

        if (json_decode($response)->response) {
            for ($i=0; $i < count($value); $i++) {
                $settings->add_user_visit_events($key,$value[$i],'1',$resource);
            }
        }

    }

    exit;

?>
