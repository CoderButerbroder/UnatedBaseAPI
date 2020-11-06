<?php
header('Content-type:application/json;charset=utf-8');

$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
$user = json_decode($s, true);

var_dump($user);
?>
