<?php
/*

Метод для получения данных из налоговой службы или базы данных

*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($inn) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'checkEntityFNS',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->serch_and_record_fns_data($inn);
                          $settings->recording_history($resource,'checkEntityFNS',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для выдачи данных по юридическому лицу по ИНН', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}




?>
