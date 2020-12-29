<?php
/*
Метод для добавления тикета поддержки в фуллдата
*/

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (isset($type_support) && isset($id_tboil) && isset($name) && isset($short_description) && isset($full_description) && isset($target) && isset($question_desc) && isset($links_add_files) && isset($link_to_photo) && isset($programma_fci) && isset($contact_face) && isset($contacts)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewTicket',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
            $check_id_referer = $settings->get_data_referer($resource);
            if (json_decode($check_id_referer)->response) {
                           $response = $settings->add_new_support_ticket($type_support,$id_tboil,$name,$short_description,$full_description,$target,$question_desc,$links_add_files,$link_to_photo,$programma_fci,$contact_face,$contacts,json_decode($check_id_referer)->data->id);
                           $settings->recording_history($resource,'addNewTicket',$response);
                           echo $response;
            } else {
                  echo $check_id_referer;
            }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для получения данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}


?>
