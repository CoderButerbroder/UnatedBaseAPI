<?
/* Загрузка рещидентов из 1С-аренда


*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$_GET['key']) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ключ авторизации'),JSON_UNESCAPED_UNICODE);exit;}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

if ($_GET['key'] == $settings->get_global_settings('api_key_1c_rent')) {

      // name поля = null
      var_dump($_FILES);

      $xml = simplexml_load_file($_FILES['residents']['tmp_name']);
      $json = json_encode($xml,JSON_UNESCAPED_UNICODE);
      $array = json_decode($json,TRUE);

      var_dump($json);

      $check = $settings->update_residents_lpm($json);

      echo $check;

} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены', 'data_referer' => $_POST, 'file' => $_FILES),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
