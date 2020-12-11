<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
require_once(__DIR__.'/core.php');

//global $database;
$settings = new Settings;

var_dump($settings->entity_additionally("306")) ;





?>
