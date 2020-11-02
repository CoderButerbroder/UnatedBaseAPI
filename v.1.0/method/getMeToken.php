<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$key || !$pass) {
    echo json_encode(array('error' => 'Обязательно требуется логин и пароль'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$response = $settings->get_user_token($key,$pass,$resource);

echo $response;


?>
