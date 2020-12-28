<?php
/*
Метод для получения списка тикетов поддержки пользователя
$ticket_type_result = full - все / message - только  переписка по тикету / conclusion - с решением
$ticket_type_search поиск по id tiket = true или по user_tboil = false создавшего тикет для получения всех его тикетов и истории переписки
$ticket_search = передаваемое значение по которму будет происходить поиск
*/

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (isset($id_user_tboil)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'getSupportUserTiketsHtml',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
            $check_id_referer = $settings->get_data_referer($resource);
            if (json_decode($check_id_referer)->response) {
                    $response = $settings->get_support_tikets_list($id_user_tboil);
                                $settings->recording_history($resource,'getSupportUserTiketsHtml',$response);
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
