<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// header('Content-type:application/json;charset=utf-8');
$id_role = (isset(trim($_POST['id_role']))) ? trim($_POST['id_role']) : false;
if(!$id_role || $id_role == 1){
    echo json_encode(array('response' => false, 'description' => 'Не все обязательные параметры были переданы, пожалуйста попробуйте позже'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;


$check_role = $settings->delete_role_in_sistem($id_role);

echo $check_role;


?>
