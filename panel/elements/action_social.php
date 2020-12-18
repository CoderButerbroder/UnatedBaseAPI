<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

session_start();
if(!isset($_SESSION["key_user"])){
  echo json_encode(array('response' => false, 'description' => 'Ошибка, авторизации'),JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
//лишним не будет.., наверное
if(($_POST["type"] != 'add' && $_POST["type"] != 'del') || !$data_user->response ){
  echo json_encode(array('response' => false, 'description' => 'Ошибка, попробуйте позже'),JSON_UNESCAPED_UNICODE);
  exit();
}


if($_POST["type"] == 'add'){

  $data_user_str = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
  $data_user_soc = json_decode($data_user_str);
  if(is_object($data_user_soc)) {
    $check_auth = $settings->add_user_social($data_user->data->id,$data_user_str);
    echo $check_auth;
  } else {
    echo json_encode(array('response' => false, 'description' => 'Ошибка, попробуйте позже'),JSON_UNESCAPED_UNICODE);
  }
}
if($_POST["type"] == 'del'){
  $check_auth = $settings->delete_social($data_user->data->id,$data_user_str);
  echo $check_auth;
}




?>
