<?php
session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

if(!isset($_POST["message"]) || !isset($_POST["chat"]) ) {
      echo json_encode(array('response' => false, 'description' => 'Ошибка, Проверьте правильность заполнения полей'), JSON_UNESCAPED_UNICODE);
      exit();
}


$message = trim($_POST["message"]);
$chat = trim($_POST["chat"]);

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value || $data_user_rules == false) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
} else {

  $telega_token = $settings->get_global_settings('telega_token');

  $data = array(
      'chat_id'      => $chat,
      'text'     => $message,
  );

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, 'https://api.telegram.org/bot' . $telega_token .  '/' . 'sendMessage');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //Отправляем через POST
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data); //Сами данные отправляемые
  $out = curl_exec($curl); //Получаем результат выполнения, который сразу расшифровываем из JSON'a в массив для удобства
  curl_close($curl); //Закрываем курл

  $data_out = json_decode($out);
  if ($data_out->ok) {
    echo json_encode([ 'response' => true, 'description' => 'Сообщение Отправлено' ], JSON_UNESCAPED_UNICODE);
  } else {
    echo json_encode([ 'response' => false, 'description' => 'Сообщение Не Отправлено' ], JSON_UNESCAPED_UNICODE);
  }

}


?>
