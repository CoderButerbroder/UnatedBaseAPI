<?
/*
Обновление мероприятия в единой базе данных
$id_event_on_referer = id мероприятия на рефере
$place = метсоположение
$interest = интерес пользователя
$type_event = тип мероприятия (жесткий выбор)
$name = название меропрития (титул)
$description = опписние меропритяия
$organizer = организатор мероприятия id tboil
$status = стаутус мероприятия
$start_datetime_event = дата и время начала мероприятия
$end_datetime_event = дата и время ококнчания мероприятия
*/

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_event_on_referer && isset($place) && isset($interest) && isset($type_event) && isset($name) && isset($description) && isset($organizer) && isset($status) && isset($start_datetime_event) && isset($end_datetime_event)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'updNewEvent',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
          $check_id_referer = $settings->get_data_referer($resource);
          if (json_decode($check_id_referer)->response) {
              $response = $settings->add_update_new_event($id_event_on_referer,$type_event,$name,$description,$organizer,$status,$start_datetime_event,$end_datetime_event,$place,$interest,json_decode($check_id_referer)->data->id);
                          $settings->recording_history($resource,'updNewEvent',$response);
                echo $response;
          } else {
                echo $check_id_referer;
          }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для обновления мероприятия в единой базе данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
