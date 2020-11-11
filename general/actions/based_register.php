<?php
header('Content-type:application/json;charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();
$name = $_POST['name'];
$last_name = $_POST['last_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['pass'];

$check_register = $settings->base_register_user($email,$password,$phone,$name,$last_name,$session_id,$ip);

echo $check_register;
exit;


?>
