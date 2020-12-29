<?
/*
Добавление сервисов комментариев к сервису
$id_services_comments_on_referer = id комментария на рефере
$id_service_on_referer = id сервиса на рефере
$id_user_tboil = id пользователя tboil добавившего комментарий
$comment = комментарий
$status = стаутс комментария
$date_update = дата обновления комментраия
$comments_hash = хэш комментария
*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_services_comments_on_referer && $id_service_on_referer && $id_user_tboil && isset($comment) && isset($status) && isset($date_update) && isset($comments_hash)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'updNewTechServicesComment',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
            $check_id_referer = $settings->get_data_referer($resource);
            if (json_decode($check_id_referer)->response) {
                  $response = $settings->tech_services_comments($id_services_comments_on_referer,$id_service_on_referer,$id_user_tboil,$comment,$status,$date_update,$comments_hash,json_decode($check_id_referer)->data->id);
                              $settings->recording_history($resource,'updNewTechServicesComment',$response);
                  echo $response;
            } else {
                  echo $check_id_referer;
            }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для регистрации на tboil', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
