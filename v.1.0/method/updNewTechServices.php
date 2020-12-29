<?
/*
Добавление сервисов в едину юазу данных
$id_service_on_referer = id сервиса на рефере
$id_entity = id компании в единой базе данных
$id_user_tboil = id пользователя tboil
$name = название сервиса
$category = категория сервиса
$object_type = тип объекта
$description = описание
$district = район
$street = улица
$link_preview = ссылка на картинку превью
$links_add_files = сслыки на добавленые файлы через запятую
$status = стаутус
$additionally = добавочное поле с данными из yandex map
$data_added = дата добавления
$service_hash = хэш сервиса
*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_service_on_referer && $id_entity && $id_user_tboil && isset($name) && isset($category) && isset($object_type) && isset($description) && isset($district) && isset($street) && isset($link_preview) && isset($links_add_files) && isset($status) && isset($additionally) && isset($data_added) && isset($service_hash)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'updNewTechServices',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
            $check_id_referer = $settings->get_data_referer($resource);
            if (json_decode($check_id_referer)->response) {
                  $response = $settings->tech_services($id_service_on_referer,$id_entity,$id_user_tboil,$name,$category,$object_type,$description,$district,$street,$link_preview,$links_add_files,$status,$additionally,$data_added,$service_hash,json_decode($check_id_referer)->data->id);
                              $settings->recording_history($resource,'updNewTechServices',$response);
                  echo $response;
            } else {
                echo $check_id_referer;
            }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для обновления данных по сервису в единой базе данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
