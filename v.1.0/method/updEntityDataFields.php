<?
// обновление поля в физического лица в единой  базе данных

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (is_string($massiv_field_value) && is_int($id_entity)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'updEntityDataFields',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->mass_update_entity_field($massiv_field_value,$id_entity);
                          $settings->recording_history($resource,'updEntityDataFields',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для обновления поля компании в единой базе данных'),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
