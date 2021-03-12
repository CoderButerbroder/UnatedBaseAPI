<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);
ignore_user_abort(true);
set_time_limit(0);

session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}


include($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
// //
$settings = new Settings;

global $database;

//проверка МЕТОДОВ
/*
// $tboil_token = $settings->get_global_settings('tboil_token');

$host_api = 'https://'.'api.kt-segment.ru/';
$user_email = 'cf984170e648791061171339teyrufhdgd';
$user_pass = 'D5841495i';

// getMeToken
// key
// pass
// resource

$curl = curl_init();
$data_post = array( 'login' => $user_email,
                    'password' => $user_pass,
                    'referer' => $host_api
                  );
curl_setopt($curl, CURLOPT_URL, 'https://'.$_SERVER["SERVER_NAME"].'/v.1.0/method/getMeToken.php');
curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
$out1 = curl_exec($curl);
curl_close($curl);

var_dump($out1);
echo "</br>";

$data_out1 = json_decode($out1);

if ($data_out1->response) {
  $token = $data_out1->token;
  $inn = '7814193829';
  $curl = curl_init();
  $data_post = array( 'token' => $token,
                      'inn' => $inn,
                      'referer' => $host_api
                    );
  curl_setopt($curl, CURLOPT_URL, 'https://'.$_SERVER["SERVER_NAME"].'/v.1.0/method/getLPMEntity.php');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
  $out1 = curl_exec($curl);
  curl_close($curl);

  var_dump($out1);
} else {
  echo "</br><h1>Error</h1>";
}
*/

?>
