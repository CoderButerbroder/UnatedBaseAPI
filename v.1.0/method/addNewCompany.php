<?php
// Регистрация нового пользователя (физического лица) через тбоил и запись данных в базу данных
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_user_tboil && $inn && $msp && $site && $region && $staff && $district && $street && $house && $type_inf && $additionally) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewCompany',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->register_entity($id_user_tboil,$inn,$msp,$site,$region,$staff,$district,$street,$house,$type_inf,$additionally);
                          $settings->recording_history($resource,'addNewCompany',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для регистрации на tboil'),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
