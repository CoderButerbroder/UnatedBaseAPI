<?php
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

// if (!$key || !$pass) {
//     echo json_encode(array('response' => false, 'description' => 'Обязательно требуется логин и пароль'),JSON_UNESCAPED_UNICODE);
//     exit;
// }

$id_user_tboil = 1425;

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$response = $settings->getUser_tboil($id_user_tboil);

echo $response;


?>
