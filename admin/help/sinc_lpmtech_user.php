<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

// ignore_user_abort(true);
// set_time_limit(0);

$settings = new Settings;

    $token_tboil = $settings->get_global_settings('tboil_token');
    $hosting_name = $settings->get_global_settings('hosting_name');

    $data_user_tboil = file_get_contents("https://tboil.spb.ru/api/v2/getUser/?token=".$token_tboil."&userId=77368");

    if (!json_decode($data_user_tboil)->success) {
        $token_tboil = $settings->refresh_token_tboil();

        if (!json_decode($token_tboil)->response) {
            $settings->add_errors_migrate(0, 'tboil_token');

            header('Location: http://'.$hosting_name.'/admin/help/sinc_tboil_user.php');
            exit;
        }
        $token_tboil = json_decode($token_tboil)->token;
    }



    $data_user_tboil = file_get_contents("https://tboil.spb.ru/api/v2/getUser/?token=".$token_tboil."&userId=77368");

    echo $data_user_tboil;
?>
