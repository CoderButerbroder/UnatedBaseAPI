<?php
/*

Метод для получения данных по пользователю из единой базы данных по id_tboil
$id_user_tboil = id_tboil поользователя в единой базе данных
*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_user_tboil) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'getUserTboil',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->get_all_data_user_id_tboil($id_user_tboil);
                          $settings->recording_history($resource,'getUserTboil',$response);
              if (json_decode($response)->response) {
                      $check_id_referer = $settings->get_data_referer($resource);
                      $check_add_account = $settings->add_user_accounts(json_decode($response)->user_id_in_ebd,json_decode($check_id_referer)->data->id);
                                           $settings->recording_history($check_add_account,'Добавление аккаунта пользователя addUserInEBD',$check_add_account);
              }
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для получения данных по пользователю tboil из единой базы данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}




?>
