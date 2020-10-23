<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$login || !$pass) {
    echo json_encode(array('error' => 'Обязательно требуется логин и пароль'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$token_answer = $settings->get_user_token($login,$pass,$resource);

echo $token_answer;


?>
