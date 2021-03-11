<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;



// $data2 = $settings->add_history_users_fulldata('действие','тип','тут какая то дата');
$token_tboil = $settings->get_global_settings('tboil_token');

$data_user_tboil = json_decode(file_get_contents("https://tboil.spb.ru/api/v2/getUsers/?token=".$token_tboil));
$data_user_tboil->data = array_reverse($data_user_tboil->data);

foreach ($data_user_tboil->data as $key => $value) {
    echo $value.'<br>';
}
