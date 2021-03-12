<?php
/*
Получение срока годности токена
*/

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {
    echo json_encode(array('response' => false, 'description' => 'Для получения срока годности токена, обязательно требуется токен'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$response = $settings->token_expiration_check($token);
            $settings->recording_history($resource,'getExpToken',$response);
echo $response;

?>
