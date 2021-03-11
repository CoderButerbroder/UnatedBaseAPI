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

    $settings->delete_users_mister_proper();

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

    /*
        в теории тут уже имеем масив пользователей которых у нас нету
    */
    // echo count($new_mass);
    // echo "\n";
    $err_count_not_obj = 0;
    foreach ($new_mass as $key => $value) {
      // echo "f ".$key." id_user = ".$value."\n";
      $flag_while = true;
      $err_count_not_obj = 0;


      while($flag_while) {
        // echo "   w ".$key." id_user = ".$value."\n";

        $check_in_EBD = json_decode($settings->get_user_data_id_boil($value));
        //на всякий
        if(!is_object($check_in_EBD)){
          $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_tboil_user Ошибка проверки наличия пользователя в ЕБД '.$value);
          $flag_while = false;
        } else {
          //проверяем что этого пользователя нет в БД
          if ($check_in_EBD->response == false) {

                  $data_user_tboil_cicle_str = file_get_contents("https://tboil.spb.ru/api/v2/getUser/?token=".$token_tboil."&userId=".$value);
                  // echo $data_user_tboil_cicle_str."\n";
                  $data_user_tboil_cicle_obj = json_decode($data_user_tboil_cicle_str);
                  if(!is_object($data_user_tboil_cicle_obj) ) {
                    $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON ERR] sinc_tboil_user '.$data_user_tboil_cicle_str);
                    if ($err_count_not_obj >= 3) {
                      $flag_while = false;
                    }
                    $err_count_not_obj++;
                  } else {
                    $err_count_not_obj = 0;
                    if (($data_user_tboil_cicle_obj->success == false) && ($data_user_tboil_cicle_obj->error == "Неправильный токен" || $data_user_tboil_cicle_obj->error == "\u041d\u0435\u043f\u0440\u0430\u0432\u0438\u043b\u044c\u043d\u044b\u0439 \u0442\u043e\u043a\u0435\u043d")) {
                      $token_tboil_check = $settings->get_global_settings('tboil_token');
                      //смысл в том что соседний крон или еще что могли перевыпустить токен, и надо понять он закончился или его уже перевыпустили
                      if ($token_tboil_check == $token_tboil) {
                        $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_tboil_user token re_get');
                        $token_tboil_str = $settings->refresh_token_tboil();
                        $token_tboil_obj = json_decode($token_tboil_str);
                        if(!is_object($token_tboil_obj) || !$token_tboil_obj->response){
                          $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_tboil_user token re_get error');
                        } else {
                          $token_tboil = $token_tboil_obj->token;
                        }
                      } else {
                        $token_tboil = $token_tboil_check;
                      }
                    }
                      if ($data_user_tboil_cicle_obj->success == false) {
                        $flag_while = false;
                        $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON ERR] user_id='.$value." message:".$data_user_tboil_cicle_str);
                      } else {
                        //в теории можно добавлять
                        // echo "add \n";

                        $curl = curl_init();
                        $data_post = array( 'token' => $token,
                                            'referer' => 'https://'.$hosting_name.'/',
                                            'data_user_tboil' => $data_user_tboil_cicle_str);
                        curl_setopt($curl, CURLOPT_URL, 'https://'.$hosting_name.'/v.1.0/method/addUserInEBD.php');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
                        $out4 = curl_exec($curl);
                        curl_close($curl);

                        $obj_check_add_in_ebd = json_decode($out4);

                        if ($obj_check_add_in_ebd->response == true) {
                          $flag_while = false;
                        }

                        if ($obj_check_add_in_ebd == false) {
                          $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_tboil_user Ошибка добавления пользователя в ЕБД '.$value);
                          $flag_while = false;
                        }

                        if ( $obj_check_add_in_ebd->response == false && stripos(json_decode($out4)->description, 'Токен был просрочен') ) {
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
                      }
                  }
          }
        }
      }
    }

    $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] sinc_tboil_user '.$start_time.' '.'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.');

?>
