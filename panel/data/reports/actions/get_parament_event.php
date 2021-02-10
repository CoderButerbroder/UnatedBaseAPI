<?php
/* просто получение всех мероприятий */
session_start();

if (!isset($_SESSION["key_user"])) {
  // echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_array = json_decode($settings->get_all_events('all'));

if(is_object($data_array) && $data_array->response != false){
  echo '<option default disabled selected value="false">Не выбрана</option>';
  foreach($data_array->data as $value){
    echo '<option value="'.$value->id.'">'.$value->id.' '.$value->name.'</option>';
  }
}

?>
