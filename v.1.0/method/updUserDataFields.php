<?
/* обновление поля в физического лица в единой  базе данных

$massiv_field_value = JSON массив поля ключ значение поля
$id_user_tboil = id пользователя tboil

*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($massiv_field_value && $id_user_tboil) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'updUserDataFields',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
              $response = $settings->mass_update_user_field($massiv_field_value,$id_user_tboil);
                          $settings->recording_history($resource,'updUserDataFields',$response);
              if (json_decode($response)->response) {
                          $settings->update_all_platform_referer($id_user_tboil,0);
              }
              echo $response;
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для обновления поля компании в единой базе данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
