<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

$settings = new Settings;
var_dump($settings->get_data_support_ticket(7, 'full', true));


?>
