<?php
session_start();

if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_array = $settings->get_current_parameters($_POST["search"]);
var_dump($data_array);
if(is_array($data_array) && count($data_array)){
  echo '<option default disabled selected value="false">Не выбрана</option>';
  foreach($data_array as $value){
    echo '<option value="'.$value.'">'.$value.'</option>';
  }
} else {
  echo json_encode(array('response' => false, 'description' => 'Ошибка получения данных, попробуйте позже'), JSON_UNESCAPED_UNICODE);
  exit();
}

?>
