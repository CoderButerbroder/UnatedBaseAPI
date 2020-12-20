<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// header('Content-type:application/json;charset=utf-8');
$css_style = (isset($_POST['css_style'])) ? trim($_POST['css_style']) : false;
if(!$css_style){
    echo json_encode(array('response' => false, 'description' => 'Не все обязательные параметры были переданы, пожалуйста попробуйте позже'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$user_data = json_decode($settings->get_cur_user($_SESSION['key_user']));

$check_role = $settings->switch_user_style($css_style,$user_data->data->id);

echo $check_role;

?>
