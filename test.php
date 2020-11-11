<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$login = '79819867151';

         $phones = array($login);

          $formats = array(
             '10' => '7##########',
             '11' => '7##########'
          );

          foreach ($phones AS $phone) {
                echo $settings->phone_format($phone, $format, $mask = '#');
          }



?>
