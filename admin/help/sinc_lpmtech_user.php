<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

ignore_user_abort(true);
set_time_limit(0);

$settings = new Settings;

$data_lpmtech_company = $settings->get_all_lpmtech_user();

// $check_download_fns = $settings->fns_base('7813621871');
// if (json_decode($check_download_fns)->response) {
//      echo 'ОК - 7813621871 <br>';
// } else {
//      echo 'NOT OK - 7813621871 <br>';
// }
//
// exit;

// загрузка данных из фнс
// foreach ($data_lpmtech_company as $key) {
//         if ($key->inn) {
//
//              $check_download_fns = $settings->fns_base($key->inn);
//              if (json_decode($check_download_fns)->response) {
//                   echo 'ОК -'.$key->inn.'<br>';
//              } else {
//                   echo 'NOT OK -'.$key->inn.'<br>';
//              }
//
//         }
// }


// привязка компаний по уже имеющимся id tboil и инн
foreach ($data_lpmtech_company as $key) {

          $msp = ' ';
          if ($key->website) {$site = $key->website;} else {$site = ' ';}
          $region = ' ';
          $staff = ' ';
          $district = ' ';
          $street = ' ';
          $house = ' ';
          $type_inf = ' ';
          $additionally = ' ';
          $export = ' ';
          $branch = ' ';

        if ($key->inn) {
              if ($key->id_tboil) {
                  $check_privazka = $settings->register_entity($key->id_tboil,$key->inn,$msp,$site,$region,$staff,$district,$street,$house,$type_inf,$additionally,$export,$branch);
                  $check_privazka = json_decode($check_privazka);
                  if ($check_privazka->response) {
                    echo 'Привязка компании '.$key->inn.' c пользоватлем '.$key->id_tboil.'  прошла успешно<br>';
                  } else {
                    echo $check_privazka->description.'<br>';
                  }
              } else {
                  $check_user = $settings->search_user_email($key->user_email);
                  $check_user = json_decode($check_user);
                  if ($check_user->response) {
                    $check_privazka = $settings->register_entity($check_user->data->id_tboil,$key->inn,$msp,$site,$region,$staff,$district,$street,$house,$type_inf,$additionally,$export,$branch);
                    if ($check_privazka->response) {
                      echo 'Привязка компании '.$key->inn.' c пользоватлем '.$check_user->data->id_tboil.'  прошла успешно<br>';
                    } else {
                      echo $check_privazka->description.'<br>';
                    }
                  }
              }
        }
}



?>
