<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

echo "string";

$settings = new Settings;

var_dump($settings->add_new_support_ticket('',104120,'45','rjhjnrjt jgbcfybt','полное описание','цель?','вопрос чтоль','','','некая программа фси','Некое лицо','asd',2));





?>
