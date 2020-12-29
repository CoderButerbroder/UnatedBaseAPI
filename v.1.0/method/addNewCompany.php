<?php
/* Регистрация нового пользователя юридического лица и привязка его к физическому лицу

$id_user_tboil =
$inn = инн компании
$msp = категория
$site = сайт компании
$region = регион
$staff = количество сотрудников в штате
$district = район
$street = улица
$house = дом
$type_inf = тип юр лица
$additionally = JSON из yandex map
$export = JSON по даыным экспорта юр.лица
$branch = JSON по данным отраслей юр.лица
$technology = JSON по данным технологий юр.лица

*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_user_tboil && $inn && isset($msp) && isset($site) && isset($region) && isset($staff) && isset($district) && isset($street) && isset($house) && isset($type_inf) && isset($additionally) && isset($export) && isset($branch) && isset($technology)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewCompany',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->register_entity($id_user_tboil,$inn,$msp,$site,$region,$staff,$district,$street,$house,$type_inf,$additionally,$export,stripcslashes($branch),stripcslashes($technology));
                          $settings->recording_history($resource,'addNewCompany',$response);
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для регистрации юридического лица в единой базе данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
