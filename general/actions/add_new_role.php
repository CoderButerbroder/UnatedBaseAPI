<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// header('Content-type:application/json;charset=utf-8');
$name_role = (isset($_POST['name'])) ? $_POST['name_role'] : false;
if(!$name_role){
    echo json_encode(array('response' => false, 'description' => 'Обязательно введите уникальное имя роли'),JSON_UNESCAPED_UNICODE);
    exit;
}
$role_copy = (isset($_POST['role_copy'])) ? $_POST['role_copy'] : false;

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

header('Location: /');
exit;

?>
