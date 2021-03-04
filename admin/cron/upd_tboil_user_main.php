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



    $data_user_tboil = json_decode(file_get_contents("https://tboil.spb.ru/api/v2/getUsers/?token=".$token_tboil));

    if (!is_object($data_user_tboil) || $data_user_tboil == false) {
      $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON ERR] \n from: upd_tboil_user_main http 500? ");
    } else {

      $data_user_tboil->data = array_reverse($data_user_tboil->data);

      // echo count($data_user_tboil->data);
      // echo "\n";
      // $threads = 8;
      // $strs_per_thread = ceil(count($data_user_tboil->data) / $threads);

      $strs_per_thread = 1000;
      $threads = ceil(count($data_user_tboil->data) / $strs_per_thread);

      // echo "Threads: ".$threads."\n";
      // echo "Items per thread: ".$strs_per_thread."\n";

      $path_php = '/home/httpd/fcgi-bin/a353561_api/php-cli';

      $array_part = array_chunk($data_user_tboil->data, $strs_per_thread);

      for ($i = 0; $i  <  $threads; $i++) {
        $str_json = json_encode( $array_part[$i] );
        $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \n from: upd_tboil_user_main \n start: ".$start_time."\npotok:".$i."\ncount arr users:".count($array_part[$i]));
        passthru("(".$path_php." -f ".__DIR__."/upd_tboil_user_main_thread.php ".($i+1)." ".$str_json." & ) >> /dev/null 2>&1");
      }

    }

    $settings->telega_send($settings->get_global_settings('telega_chat_error'), "[CRON] \n from: upd_tboil_user_main \n start: ".$start_time."\n End: ".round(microtime(true) - $start, 4)." сек.");

?>
