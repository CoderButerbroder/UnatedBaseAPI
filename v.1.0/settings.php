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

$export = $_POST["export"];
$branch = $_POST["branch"];
$technology = $_POST["technology"];

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

$id_service_on_referer = $_POST['id_service_on_referer'];
$category = $_POST['category'];
$object_type = $_POST['object_type'];
$link_preview = $_POST['link_preview'];
$data_added = $_POST['data_added'];
$service_hash = $_POST['service_hash'];

$id_services_comments_on_referer = $_POST['id_services_comments_on_referer'];
$comment =  $_POST['comment'];
$date_update = $_POST['date_update'];
$comments_hash = $_POST['comments_hash'];


$id_services_rating_on_referer = $_POST['id_services_rating_on_referer'];
$id_comment = $_POST['id_comment'];
$rating = $_POST['rating'];

$id_services_view_on_referer = $_POST['id_services_view_on_referer'];
$view = $_POST['view'];

$massiv_field_value = $_POST['massiv_field_value'];


$name_request = $_POST['name_request'];
$id_requests_on_referer = $_POST['id_requests_on_referer'];
$links_to_logos = $_POST['links_to_logos'];
$demand = $_POST['demand'];
$collection_time = $_POST['collection_time'];
$request_hash = $_POST['request_hash'];
$date_added = $_POST['date_added'];
$type_request = $_POST['type_request'];


$id_event_on_referer = $_POST['id_event_on_referer'];
$type_event = $_POST['type_event'];
$organizer = $_POST['organizer'];
$start_datetime_event = $_POST['start_datetime_event'];
$end_datetime_event = $_POST['end_datetime_event'];


$type = $_POST['type'];
$number = $_POST['number'];

$place = $_POST['place'];
$interest = $_POST['interest'];

$ticket_type_result = $_POST['ticket_type_result'];
$ticket_type_search = $_POST['ticket_type_search'];
$ticket_search = $_POST['ticket_search'];

$id_ticket = $_POST["id_ticket"];
$id_ticket_msg = $_POST["id_ticket_msg"];
$id_ticket_conclusion = $_POST["id_ticket_conclusion"];
$id_support_ticket = $_POST["id_support_ticket"];
$message = $_POST["message"];
$action = $_POST["action"];
$type_user = $_POST["type_user"];

$type_support = $_POST['type_support'];
$id_tboil = $_POST['id_user_tboil'];
$short_description = $_POST['short_description'];
$full_description = $_POST['full_description'];
$question_desc = $_POST['question_desc'];
$links_add_files = $_POST['links_add_files'];
$link_to_photo = $_POST['link_to_photo'];
$programma_fci = $_POST['programma_fci'];
$contact_face = $_POST['contact_face'];
$contacts = $_POST['contacts'];
$hash_tiket_support = $_POST['hash_tiket_support'];
$target = $_POST['target'];



/* для тестового режима */

// $key = 'cf984170e648791061171339dd8b5c12';
// $pass = 'D5841495i';
// $resource = 'https://api.kt-segment.ru/v.1.0/method/expToken';
// $token = 'VVaub1LEL5K5tP1YgPXeXKlb+i4R5JP4LSPdODs4kPT158X7Fucr1irfeJlfvyaEPN4xlfw8IoEz7d9sNgANfwS5xt1I44yR0iDFGfB2DB6XcWf2nJpCEC1edXlOgWDsVhmotCg7J8xjm4seOK8BrfU2RTPZQZJn608LUamMzkw=';

?>
