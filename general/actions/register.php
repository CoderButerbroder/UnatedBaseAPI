<?php
header('Content-type:application/json;charset=utf-8');
if(isset($_POST['g-recaptcha-response'])){$captcha = $_POST['g-recaptcha-response'];}
if(!$captcha){
    echo json_encode(array('response' => false, 'description' => 'Капча не обнаружена'),JSON_UNESCAPED_UNICODE);
    exit;
}
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();
$name = $_POST['name'];
$last_name = $_POST['last_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['pass'];

$chek_recaptcha = $settings->validate_recaptcha($captcha);

if (!json_decode($chek_recaptcha)->response) {
    echo $chek_recaptcha;
    exit;
}

$check_register = $settings->base_register_user($email,$password,$phone,$name,$last_name,$session_id,$ip);

if (json_decode($check_register)->response) {
    $check_email =  $settings->send_email_activation($_SESSION["key_user"]);
    if (json_decode($check_email)->response) {
        echo $check_email;
        exit;
    } else {
        echo json_encode(array('response' => false, 'description' => 'Пользователь был зарегистрирован, но письмо активации не удалось выслать на указанный email'),JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    echo $check_register;
    exit;
}



?>
