<?
/* Добавление посещения мероприятия юридическим лицам

$id_event_on_referer = id меропритяи на рефере
$id_entity = id компании в единой базе данных
$status = статус посещеения 

*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_event_on_referer && $id_entity && isset($status)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addEntityVisitEvent',$check_valid_token);


      if (json_decode($check_valid_token)->response) {
              $check_id_referer = $settings->get_data_referer($resource);
              if (json_decode($check_id_referer)->response) {
                    $response = $settings->add_entity_visit_events($id_event_on_referer,$id_entity,$status,json_decode($check_id_referer)->data->id);
                                $settings->recording_history($resource,'addEntityVisitEvent',$response);
                    echo $response;
              } else {
                    echo $check_id_referer;
              }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для добавления посещения физ. лица мероприятия', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
