<?php
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');
if (!$token) {
    echo json_encode(array('response' => false, 'description' => 'Для расшифровки, обязательно требуется токен'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$response = $settings->decode_token($token);
            $settings->recording_history($resource,'getDataToken',$response);
echo $response;

?>
