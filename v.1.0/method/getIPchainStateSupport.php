<?php
/*
 Получение данных по компании и поддержке которую она получила "если"
 $inn = инн юридичского лица получившего поддержку сколково или фси
*/

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (isset($inn)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'getIPchainStateSupport',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->IPCHAIN_entity_inner_join($inn);
                          $settings->recording_history($resource,'getIPchainStateSupport',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были получены', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
