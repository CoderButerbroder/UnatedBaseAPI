<?php
error_reporting(0);
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// session_start();
include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core2.php');

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

            header('Location: http://'.$hosting_name.'/admin/help/sinc_tboil_user.php');
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

    $all_id_users_tboil = $data_user_tboil->data;

    // var_dump(count($data_user_tboil->data));
    // exit;
    // echo '<br><br>';

    // получение массива id_tboil
    $mass_id_tboil = $settings->get_mass_id_tboil();

    // var_dump(count($mass_id_tboil));
    // echo '<br><br>';

    $new_mass = array_diff($data_user_tboil->data, $mass_id_tboil);

    // var_dump($new_mass);
    $keys = array_keys($new_mass);
    $firstKey = $keys[0];


    $reversed = array_reverse($new_mass);
    $lastKey = $keys[0];

    $count = 0;

    foreach ($new_mass as $key => $value) {

              $curl = curl_init();
              $data_post = array( 'token' => $token,
                                  'referer' => 'https://'.$hosting_name.'/',
                                  'id_user_tboil' => $value);
              curl_setopt($curl, CURLOPT_URL, 'https://api.kt-segment.ru/v.1.0/method/getUserTboil.php');
              curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($curl, CURLOPT_POST, true);
              curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
              $out3 = curl_exec($curl);
              curl_close($curl);

              if (!json_decode($out3)->response) {

                      $data_user_tboil_cicle = file_get_contents("https://tboil.spb.ru/api/v2/getUser/?token=".$token_tboil."&userId=".$value);
                      $curl = curl_init();
                      $data_post = array( 'token' => $token,
                                          'referer' => 'https://'.$hosting_name.'/',
                                          'data_user_tboil' => $data_user_tboil_cicle);
                      curl_setopt($curl, CURLOPT_URL, 'https://'.$hosting_name.'/v.1.0/method/addUserInEBD.php');
                      curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                      curl_setopt($curl, CURLOPT_POST, true);
                      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
                      $out4 = curl_exec($curl);
                      curl_close($curl);

                      if (!json_decode($out4)->response) {
                            $settings->add_errors_migrate($value, 'не удалось добавить пользователя');
                            // echo 'не удалось добавить пользователя';
                      }
              }
    }



?>
