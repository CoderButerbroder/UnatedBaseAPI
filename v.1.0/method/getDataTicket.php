<?php
/*
Метод для получения данных тикета с различными параметрами get_data_support_ticket
$ticket_type_result = full - все / message - только  переписка по тикету / conclusion - с решением
$ticket_type_search поиск по id tiket = true или по user_tboil = false создавшего тикет для получения всех его тикетов и истории переписки
$ticket_search = передаваемое значение по которму будет происходить поиск
*/

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (isset($ticket_type_result) && isset($ticket_type_search) && isset($ticket_search)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'getDataTicket',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
            $check_id_referer = $settings->get_data_referer($resource);
            if (json_decode($check_id_referer)->response) {
                           $response = $settings->get_data_support_ticket($ticket_search, $ticket_type_search);
                           $settings->recording_history($resource,'getDataTicket',$response);
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
