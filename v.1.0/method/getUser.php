<?php
/*

Метод для получения данных по пользователю из единой базы даннх

*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_user) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'getUser',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->get_all_data_user_id($id_user);
                          $settings->recording_history($resource,'getUser',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для получения данных пользоватея из единой базы данных по id', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}




?>
