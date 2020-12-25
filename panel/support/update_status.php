<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

if(!isset($_POST["status"]) || !isset($_POST["search"])){
  echo json_encode(array('response' => false, 'description' => 'Недостаточно параметров'), JSON_UNESCAPED_UNICODE);
  exit();
}

$status = trim($_POST["status"]);

if($status != 'open' && $status != 'close' && $status != 'work') {
  echo json_encode(array('response' => false, 'description' => 'Ошибка получения статуса'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

echo $settings->update_status_support_tiket(trim($_POST["search"]), $status);

?>
