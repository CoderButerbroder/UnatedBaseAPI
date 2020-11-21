<?php
// Регистрация нового пользователя (физического лица) через тбоил и запись данных в базу данных
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($pass && $email && $name && $secondName && $lastName && $profession && $phone && $company && $city && $redirectUrl) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewUser',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->register_user($email,$name,$secondName,$lastName,$profession,$phone,$company,$city,$redirectUrl,$pass,$resource);
                          $settings->recording_history($resource,'addNewUser',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для регистрации на tboil'),JSON_UNESCAPED_UNICODE);
      exit;
}


/*
 Регистрация пользователя с перенаправлением из письма:

 POST /api/v2/registerRedirect/?token=токен
 поля формы (соответствуют полям при регистрации, тут после двоеточия пример значения):
 email:te22st2tes2t@test.ru
 name:Test
 secondName:TestTest
 lastName:Testest
 profession:test
 phone:+79180169656
 company:test
 city:Piter
 redirectUrl:http://vk.com
 password:PasswordD1

регистрируем пользовател на tboil
получаем ответ и причину уже возвращаем если ошибка
или продолжаем регистрацию у себя в бд приязывая ид тбоил к себе..
*/












?>
