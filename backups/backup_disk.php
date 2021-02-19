<?php
//
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

//получение токена
// $domen_auth_yandex = 'oauth.yandex.ru';
// $id_app_yndex = '';
// echo 'https://'.$domen_auth_yandex.'/authorize?response_type=token&client_id='.$id_app_yndex;
// $wtf = 'https://api.kt-segment.ru/#access_token=AgAAAAAZ6KK_AAblMpwDfcrt8k-TudQhWr8VL8g&token_type=bearer&expires_in=31536000';
// require_once('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
// /backups

if (!isset($argv[1])) {
  exit();
}

$backup_name = $argv[1];
$path = '/home/httpd/vhosts/api.kt-segment.ru/httpdocs/backups/';

if(!file_exists($path.$backup_name)){
  $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[BACKUP] ФАЙЛ БЕКАПА не найден');
} else {

  session_start();
  include('/home/httpd/vhosts/api.kt-segment.ru/httpdocs/general/core.php');
  $settings = new Settings;

  $token_yandex = $settings->get_global_settings('token_app_yandex_disk');

  // Путь и имя файла на нашем сервере.
  $file = $path.$backup_name;

  // Папка на Яндекс Диске (уже должна быть создана).
  $path = '/backup_FULLDATA/';

  // Запрашиваем URL для загрузки.
  $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/upload?path=' . urlencode($path . basename($file)));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token_yandex));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HEADER, false);
  $res = curl_exec($ch);
  curl_close($ch);
  $res = json_decode($res);

  if (empty($res->error)) {
  	// Если ошибки нет, то отправляем файл на полученный URL.
  	$fp = fopen($file, 'r');

   	$ch = curl_init($res->href);
  	curl_setopt($ch, CURLOPT_PUT, true);
  	curl_setopt($ch, CURLOPT_UPLOAD, true);
  	curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
  	curl_setopt($ch, CURLOPT_INFILE, $fp);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	curl_setopt($ch, CURLOPT_HEADER, false);
  	curl_exec($ch);
  	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  	curl_close($ch);

  	if ($http_code == 201) {

      $ch_free = curl_init('https://cloud-api.yandex.net/v1/disk/');
      curl_setopt($ch_free, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token_yandex));
      curl_setopt($ch_free, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch_free, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch_free, CURLOPT_HEADER, false);
      $res_free = curl_exec($ch_free);
      curl_close($ch_free);
      $res_free = json_decode($res_free);
      if(!is_object($res_free)){
        $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[BACKUP] Ошибка получения свободного места');
      } else {
        $free_disk = (round($res_free->total_space / 1024 / 1024 / 1024, 2) - round($res_free->used_space / 1024 / 1024 / 1024, 2))." ГБ.";
        $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[BACKUP] Бекап загружен, свободно '.$free_disk);
      }
  	} else {
      $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[BACKUP] Ошибка загрузки бекапа на диск Code: '.$res->http_code);
    }
  } else {
    //отправляем ошибку
    $settings->telega_send($settings->get_global_settings('telega_chat_error'), '[BACKUP]'.$res->err." mes: ".$res->message);
  }

}

?>
