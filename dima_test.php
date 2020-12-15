<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

// $token_tboil = $settings->get_global_settings('tboil_token');
// $tboil_site = 'https://tboil.spb.ru';
// // $token_tboil1 = $settings->refresh_token_tboil();
// // $token_tboil = json_decode($token_tboil1)->token;
//
// $data_one_event = json_decode(file_get_contents($tboil_site."/api/v2/getEvent/22849/?token=1".$token_tboil));
//
//
// var_dump($data_one_event);
// echo '<br>';
// echo $tboil_site."/api/v2/getEvent/22849/?token=1".$token_tboil;
//
//




?>
