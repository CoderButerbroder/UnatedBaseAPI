<?php
/*

Метод для получения данных о пользователе с tboil по его id_tboil

*/

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($data_user_tboil) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'getDataUserTboil',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->getUser_tboil($id_user_tboil);
                          $settings->recording_history($resource,'getDataUserTboil',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для получения данных по пользователю Tboil', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}


?>
