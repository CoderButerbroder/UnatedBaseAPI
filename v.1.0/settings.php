<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');

$key = $_POST['login'];
$login = $_POST['login'];
$pass = $_POST['password'];
$email = $_POST['email'];
$resource = $_POST['referer'];
$token = $_POST['token'];
$name = $_POST['name'];
$secondName = $_POST['secondname'];
$lastName = $_POST['lastname'];
$profession = $_POST['profession'];
$phone = $_POST['phone'];
$company = $_POST['company'];
$city = $_POST['city'];
$redirectUrl = $_POST['redirectUrl'];
$data_user_tboil = $_POST['data_user_tboil'];
$id_user_tboil = $_POST['id_user_tboil'];
$id_user = $_POST['id_user'];
$id_entity = $_POST['id_entity'];
$inn = $_POST['inn'];
$position = $_POST["position"];

$msp = $_POST["msp"];
$site = $_POST["site"];
$region = $_POST["region"];
$staff = $_POST["staff"];
$district = $_POST["district"];
$street = $_POST["street"];
$house = $_POST["house"];
$type_inf = $_POST["type_inf"];
$additionally = $_POST["additionally"];



$id_requests_on_referer = $_POST['id_requests_on_referer'];
$id_solution_on_referer = $_POST['id_solution_on_referer'];
$name_project = $_POST['name_project'];
$description = $_POST['description'];
$result_project = $_POST['result_project'];
$readiness = $_POST['readiness'];
$period = $_POST['period'];
$forms_of_support = $_POST['forms_of_support'];
$protection = $_POST['protection'];
$links_add_files = $_POST['links_add_files'];
$solutions_hash = $_POST['solutions_hash'];
$status = $_POST['status'];
$date_receiving = $_POST['date_receiving'];
$id_referer = $_POST['id_referer'];


$field = $_POST['field'];
$value_field = $_POST['value_field'];


/* для тестового режима */

// $key = 'cf984170e648791061171339dd8b5c12';
// $pass = 'D5841495i';
// $resource = 'https://api.kt-segment.ru/v.1.0/method/expToken';
// $token = 'VVaub1LEL5K5tP1YgPXeXKlb+i4R5JP4LSPdODs4kPT158X7Fucr1irfeJlfvyaEPN4xlfw8IoEz7d9sNgANfwS5xt1I44yR0iDFGfB2DB6XcWf2nJpCEC1edXlOgWDsVhmotCg7J8xjm4seOK8BrfU2RTPZQZJn608LUamMzkw=';

?>
