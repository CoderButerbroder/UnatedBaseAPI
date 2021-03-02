<?
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$_GET['key']) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ключ авторизации'),JSON_UNESCAPED_UNICODE);exit;}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

if ($_GET['key'] == $settings->get_global_settings('api_key_1c_rent')) {


      $strochka = $_POST['mData'];

      $response = $settings->update_residents_lpm($strochka);

      if (json_decode($response)->response) {
          echo $response;
          exit;
      }
      else {
          $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] Ошибка загрузки файла из 1C-аренда причина - '.json_decode($response)->description);
          exit;
      }



} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[CRON] Ошибка загрузки файла из 1C-аренда, причина - Не все обязательные поля были заполнены');
      exit;
}



?>
