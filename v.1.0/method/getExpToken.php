<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {
    echo json_encode(array('response' => false, 'description' => 'Для получения срока годности токена, обязательно требуется токен'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$token_answer = $settings->token_expiration_check($token);

echo $token_answer;

// $date_test = '{"response":true,"data":[{"user":"cf984170e648791061171339dd8b5c9d","referer":"api.kt-segment.ru","data_making":"2020-10-23 20:39:48","data_die":"2020-10-24 20:39:48"}]}';
//
// var_dump(json_decode($date_test));


?>
