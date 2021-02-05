<?php

// error_reporting(0);
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();
include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
$settings = new Settings;
// var_dump($database);


// поиск компаний с ломаным инн 9 или 11 символов

$statement = $database->prepare("SELECT * FROM `MAIN_entity` WHERE `date_register` = '0000-00-00 00:00:00' ");
$statement->execute();
$data = $statement->fetchAll(PDO::FETCH_OBJ);

// foreach($data as $key => $value) {
//   if(iconv_strlen($value->inn) == 9 || iconv_strlen($value->inn) == 11 || trim($value->data_fns) == ''){
//     echo 'len: ';
//     echo iconv_strlen($value->inn);
//     echo ' inn: ';
//     echo $value->inn;
//     echo '</br>';
//   }
// }

// foreach ($data as $key => $value) {
//
//   $add_fns_database = $database->prepare("UPDATE `MAIN_entity` SET `date_register` = :date_register  WHERE `id` = :id");
//   $add_fns_database->bindParam(':id', $value->id, PDO::PARAM_INT);
//   $add_fns_database->bindParam(':date_register', $value->date_pickup, PDO::PARAM_STR);
//   $check_add = $add_fns_database->execute();
//   $count = $add_fns_database->rowCount();
//   if ($count <= 0) {
//     $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[TEST] id= '.$value->id);
//   }
// }


?>
