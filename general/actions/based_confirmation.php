<?php
session_id();
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

if (!$_SESSION["key_user"]) {
		http_response_code(404);
		exit;
}

$check_login = $settings->send_email_activation($_SESSION["key_user"]);
echo $check_login;

?>
