<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$check_logout = $settings->logout();

header('Location: /');
exit;

?>
