<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// header('Content-type:application/json;charset=utf-8');
$email = (isset($_POST['email'])) ? trim($_POST['email']) : false;
$lastname = (isset($_POST['lastname'])) ? trim($_POST['lastname']) : false;
$name = (isset($_POST['name'])) ? trim($_POST['name']) : false;
$second_name = (isset($_POST['second_name'])) ? trim($_POST['second_name']) : false;
$phone = (isset($_POST['phone'])) ? trim($_POST['phone']) : false;
$send_email = (isset($_POST['send_email'])) ? true : false;

if(!$email || !$lastname || !$name || !$second_name || !$phone){
    echo json_encode(array('response' => false, 'description' => 'Обязательно введите уникальное имя роли'),JSON_UNESCAPED_UNICODE);
    exit;
}
$role_copy = (isset($_POST['role_copy'])) ? $_POST['role_copy'] : 0;




require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;


$check_role = $settings->regiter_user_in_sistem($email,$phone,$name,$last_name,$second_name,$send_email);

echo $check_role;


?>
