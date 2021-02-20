<?
/* Загрузка рещидентов из 1С-аренда

$id_event_on_referer = id меропритяи на рефере
$id_entity = id компании в единой базе данных
$status = статус посещеения

*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$_GET['key']) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ключ авторизации'),JSON_UNESCAPED_UNICODE);exit;}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

if (($_GET['key'] == $settings->get_global_settings('api_key_1c_rent')) && $_FILES) {

      // name поля = null

      $xml = simplexml_load_file("residents.xml");
      $json = json_encode($xml,JSON_UNESCAPED_UNICODE);
      $array = json_decode($json,TRUE);

      var_dump($json);

      // для бэкапирования (если понадобиться)

      // $uploaddir = '/var/www/uploads/';
      // $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
      //
      //
      // if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
      //     echo "Файл корректен и был успешно загружен.\n";
      // } else {
      //     echo "Возможная атака с помощью файловой загрузки!\n";
      // }


} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для добавления посещения физ. лица мероприятия', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
