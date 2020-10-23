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
$login = 'cf984170e648791061171339dd8b5c9d';
$pass = 'D5841495i';
$resource = 'https://api.kt-segment.ru/v.1.0/method/expToken';
$token = '479AufsJN/hv5ziB+VwgrgiAbMuTbp0EJ5P+tlgPLJ58gj21m0oNcbMMgyAAWHKfGepWw/4uajvStVPqvSwByxtSa1XQ86uJaKKXKO0LqSGsTUjFSbIWsVdz6/6TtmEO+nESCtscCItTzm8bRieIgtS6stary9GMVlF+xREfK2NYRZlYn7/ZqVwueuNA2WZQ';

?>
