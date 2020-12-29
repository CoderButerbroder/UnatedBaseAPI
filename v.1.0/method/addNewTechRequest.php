<?
/* Добавление технологического запроса от лица компании


*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_requests_on_referer && $id_entity && $id_user_tboil && isset($name_request) && isset($description) && isset($demand) && isset($collection_time) && isset($links_to_logos) && isset($type_request) && isset($links_add_files) && isset($request_hash) && isset($status) && isset($date_added)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewTechRequest',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
          $check_id_referer = $settings->get_data_referer($resource);
          if (json_decode($check_id_referer)->response) {
              $response = $settings->tech_requests($id_requests_on_referer,$id_entity,$id_user_tboil,$name_request,$description,$demand,$collection_time,$links_to_logos,$type_request,$links_add_files,$request_hash,$status,$date_added,json_decode($check_id_referer)->data->id);
                          $settings->recording_history($resource,'addNewTechRequest',$response);
                echo $response;
          } else {
                echo $check_id_referer;
          }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для добавления технологического запроса в единую базу данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
