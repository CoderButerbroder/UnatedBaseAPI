<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {
    echo json_encode(array('error' => 'Для расшифровки обязательно требуется токен'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$token_answer = $settings->decode_token($token);

echo $token_answer;

?>
