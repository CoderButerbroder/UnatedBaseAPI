<?
// Добавление мероприятия в единую базу данных
//add_new_support_ticket($id_tboil, $name, $description, $status, $id_referer)
//add_new_support_messages($id_support_ticket, $id_tboil, $message, $links_add_files, $id_referer, $type_user) {




// $id_tboil, $name, $description, $status = 'open'
//
// $message, $links_add_files, $type_user) {
//
//
//
//
//
//
//
//
// include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');
//
// if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
// if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}
//
// if ($id_event_on_referer && isset($place) && isset($interest) && isset($type_event) && isset($name) && isset($description) && isset($organizer) && isset($status) && isset($start_datetime_event) && isset($end_datetime_event)) {
//       require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
//       $settings = new Settings;
//       $check_valid_token = $settings->validate_token($token,$resource);
//                            $settings->recording_history($resource,'addNewTicket',$check_valid_token);
//
//       if (json_decode($check_valid_token)->response) {
//           $check_id_referer = $settings->get_data_referer($resource);
//           if (json_decode($check_id_referer)->response) {
//               $response = $settings->add_update_new_event($id_event_on_referer,$type_event,$name,$description,$organizer,$status,$start_datetime_event,$end_datetime_event,$place,$interest,json_decode($check_id_referer)->data->id);
//                           $settings->recording_history($resource,'addNewTicket',$response);
//                 echo $response;
//           } else {
//                 echo $check_id_referer;
//           }
//       } else {
//               echo $check_valid_token;
//       }
// } else {
//       echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для добавления тикета в единую базу данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
//       exit;
// }


?>
