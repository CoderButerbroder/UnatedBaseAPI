<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$response = $settings->recording_history($resource,'test','тест');

echo $response;

?>