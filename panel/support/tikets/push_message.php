<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

if(!isset($_POST["msg"]) || !isset($_POST["search"])){
  echo json_encode(array('response' => false, 'description' => 'Недостаточно параметров'), JSON_UNESCAPED_UNICODE);
  exit();
}


require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;


$links_add_files = '';
$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
$msg = trim($_POST["msg"]);

$check_id_referer = $settings->get_data_referer('https://'.$_SERVER["SERVER_NAME"]);
$data_refer = json_decode($check_id_referer);

if(!$data_refer->response || !$data_user->response) {
  echo json_encode(array('response' => false, 'description' => 'Попробуйте позже'), JSON_UNESCAPED_UNICODE);
  exit();
}

echo $settings->add_new_support_messages(trim($_POST["search"]), $data_user->data->id, $msg, $links_add_files, $data_refer->data->id, 'support');

?>
