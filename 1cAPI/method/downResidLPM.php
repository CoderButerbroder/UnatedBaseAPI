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

//       $strochka = '[
// {
// "ФирменноеНазвание": "Альянс Северо-Запад",
// "Корпус": "1",
// "РежимРаботы": "пн - пт 9:00 - 18:00",
// "Сайт": "http://alsltd.ru",
// "ИНН": "7810422927",
// "КакДобраться": "",
// "Направление": ""
// },
// {
// "ФирменноеНазвание": "АВАФОН",
// "Корпус": "1",
// "Этаж": "3",
// "РежимРаботы": "пн-пт 9:00 - 18:00",
// "Сайт": "www.avafon.com",
// "Логотип": "https://static.wixstatic.com/media/8e2d8b_dbce40d4cfdc46678d68648e164e3d3b~mv2.png/v1/fill/w_182,h_189,al_c,lg_1,q_85/8e2d8b_dbce40d4cfdc46678d68648e164e3d3b~mv2.webp",
// "ИНН": "7814639536",
// "КакДобраться": "",
// "Направление": "Производство"
// },
// {
// "ФирменноеНазвание": "Альянс, ООО",
// "Корпус": "1",
// "РежимРаботы": "пн - пт 9:00 - 18:00",
// "Сайт": "http://alsltd.ru",
// "ИНН": "7813263055",
// "КакДобраться": "",
// "Направление": ""
// },
// {
// "ФирменноеНазвание": "А2 КЛУБ ООО",
// "Корпус": "2",
// "Этаж": "1",
// "РежимРаботы": "18.00 - 23.59",
// "Сайт": "http://",
// "ИНН": "7813263168",
// "КакДобраться": "вход со стороны проспекта Медиков",
// "Направление": ""
// },
// {
// "ФирменноеНазвание": "Аванти",
// "Корпус": "1",
// "Этаж": "2",
// "Кабинет": "12",
// "РежимРаботы": "пн-пт 09:00 -18:00",
// "Сайт": "www.awanti.com",
// "ИНН": "7842495710",
// "КакДобраться": "",
// "Направление": "Производство"
// },
// {
// "ФирменноеНазвание": "А2",
// "Корпус": "2",
// "Этаж": "1",
// "РежимРаботы": "18.00 - 23.59",
// "Сайт": "http://",
// "ИНН": "7813528456",
// "КакДобраться": "вход со стороны проспекта Медиков",
// "Направление": ""
// },
// {
// "ФирменноеНазвание": "Альянс",
// "Корпус": "1",
// "РежимРаботы": "пн - пт 9:00 - 18:00",
// "Сайт": "http://alsltd.ru",
// "ИНН": "7838001453",
// "КакДобраться": "",
// "Направление": ""
// }
// ]';

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
