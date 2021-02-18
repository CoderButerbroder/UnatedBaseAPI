<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(0);
$start = microtime(true);
$start_time = date('H:i');

// session_start();
include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');

ignore_user_abort(true);
set_time_limit(0);

$settings = new Settings;

    $token_tboil = $settings->get_global_settings('tboil_token');
    $hosting_name = $settings->get_global_settings('hosting_name');

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


    $data_user_tboil = json_decode(file_get_contents("https://tboil.spb.ru/api/v2/getUsers/?token=".$token_tboil));

    $all_id_users_tboil = $data_user_tboil->data;

    $count = 0;
    $count_token = 0;
    $array_no = array();
    $array_yes = array();

    echo count($all_id_users_tboil)."\n";

    for ($i = 0; $i < count($all_id_users_tboil); $i++) {

      $flag_while = true;
      while ($flag_while) {

        $data_user_tboil_cicle_str = file_get_contents("https://tboil.spb.ru/api/v2/getUser/?token=".$token_tboil."&userId=".$all_id_users_tboil[$i]);
        $data_user_tboil_cicle = json_decode($data_user_tboil_cicle_str);

        if(!is_object($data_user_tboil_cicle) ) {

          $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON ERR] sinc_tboil_user_team_build '.$data_user_tboil_cicle_str);
        } else {
          if ($data_user_tboil_cicle->success == 'false' && $data_user_tboil_cicle->error == "Неправильный токен") {
            echo "token tboil reget \n";
            $token_tboil = $settings->refresh_token_tboil();
            $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] token re_get');
            $count_token++;
          } else {
            $data_user_tboil_one = $data_user_tboil_cicle->data;
            if ( $data_user_tboil_one->userId == $all_id_users_tboil[$i] ){
              $flag_while = false;
              echo "token ".$count_token." ".$i." count = ".$count." userId = ".$data_user_tboil_one->userId." id_tboil = ".$all_id_users_tboil[$i]." count_no ".count($array_no)." count_yes = ".count($array_yes)."\n";
              $count++;
              if ($data_user_tboil_one->UF_COMMAND == 1) {
                array_push($array_yes,$data_user_tboil_one->userId);
              }
            } else {
              $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON ERR]'.$data_user_tboil_cicle);
            }
          }
        }
      }

    }

    echo "result:";
    echo "\n";
    var_dump($array_yes);


    // $data_no = $settings->update_mass_main_users_comand($array_no,0);
    // $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_tboil_user_team_build пользоветелей не участвующих в командообразовании '.count($data_no));
    //
    // $data_yes = $settings->update_mass_main_users_comand($array_yes,1);
    // $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_tboil_user_team_build пользоветелей в командообразовании '.count($data_yes));
    //
    $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_tboil_user_team_build '.$start_time.' '.'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.');

?>
