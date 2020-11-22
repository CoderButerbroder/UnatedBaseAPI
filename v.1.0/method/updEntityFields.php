<?
// обновление поля в физического лица в единой  базе данных

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($field && $value_field && $id_user_tboil) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'updUserFields',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->update_user_field($field,$value_field,$id_user_tboil);
                          $settings->recording_history($resource,'updUserFields',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для регистрации на tboil'),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
