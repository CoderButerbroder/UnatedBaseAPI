<?php
/*

Метод для загрузки данных в базу данных после авторизации пользователя через Tboil

1) Пользователь авторизкется на tboil
2) Рефер получает nолучает токен
3) рефер забирает данные по токену
4) отсылает их в этот метод
5) этот метод возвращает данные о пользователь если пользоватль зарегистрирован,
   если пользовать не зарегистрирован то регистрирует пользователя и отдает его даные

*/



include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($data_user_tboil) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addUserInEBD',$check_valid_token);


      if (json_decode($check_valid_token)->response) {
              $response = $settings->auth_from_tboil($data_user_tboil,$resource);
                          $settings->recording_history($resource,'addUserInEBD',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для регистрации пользователя в единой базе данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}




?>
