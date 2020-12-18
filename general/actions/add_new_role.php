<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// header('Content-type:application/json;charset=utf-8');
$name_role = (isset(trim($_POST['name_role']))) ? trim($_POST['name_role']) : false;
if(!$name_role){
    echo json_encode(array('response' => false, 'description' => 'Обязательно введите уникальное имя роли'),JSON_UNESCAPED_UNICODE);
    exit;
}
$role_copy = (isset($_POST['role_copy'])) ? $_POST['role_copy'] : 0;


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;


$check_role = $settings->add_role_in_sistem($name_role,$role_copy);

echo $check_role;


?>
