<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

header('Content-type:application/json;charset=utf-8');

$login = $_POST['login'];
$pass = $_POST['password'];
$resource = $_POST['HTTP_REFERER'];
$token = $_POST['token'];

// для тестового режима
$key = 'cf984170e648791061171339dd8b5c9d';
$pass = 'D5841495i';
$resource = 'https://api.kt-segment.ru/v.1.0/method/expToken';
$token = 'VVaub1LEL5K5tP1YgPXeXKlb+i4R5JP4LSPdODs4kPT158X7Fucr1irfeJlfvyaEPN4xlfw8IoEz7d9sNgANfwS5xt1I44yR0iDFGfB2DB6XcWf2nJpCEC1edXlOgWDsVhmotCg7J8xjm4seOK8BrfU2RTPZQZJn608LUamMzkw=';

?>
