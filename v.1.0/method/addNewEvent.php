<?
// Добавление мероприятия в единую базу данных

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_event_on_referer && isset($type_event) && isset($name) && isset($description) && isset($organizer) && isset($status) && isset($start_datetime_event) && isset($end_datetime_event)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewEvent',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
          $check_id_referer = $settings->get_data_referer($resource);
          if (json_decode($check_id_referer)->response) {
              $response = $settings->add_update_new_event($id_event_on_referer,$type_event,$name,$description,$organizer,$status,$start_datetime_event,$end_datetime_event,json_decode($check_id_referer)->data->id);
                          $settings->recording_history($resource,'addNewEvent',$response);
                echo $response;
          } else {
                echo $check_id_referer;
          }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для добавления мероприятия в единую базу данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
