<?php
/*
  Получение токена
*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$key || !$pass) {
    echo json_encode(array('response' => false, 'description' => 'Обязательно требуется логин и пароль'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$response = $settings->get_user_token($key,$pass,$resource);
            $settings->recording_history($resource,'getMeToken',$response);
echo $response;


?>
