<?php

//получение таблицы с количеством запросов и тп

session_start();
if (!isset($_SESSION["key_user"])) {
  echo '<div class="alert alert-danger" role="alert">
          <script> window.open("https://'.$_SERVER["SERVER_NAME"].'/"); </script> Ошибка доступа, <a href="https://'.$_SERVER["SERVER_NAME"].'/">повторите Авторизацию</a>
        </div>';
  exit();
}
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
session_write_close();

$login_tboil = $settings->get_global_settings('tboil_admin_login');
$password_tboil = $settings->get_global_settings('tboil_admin_password');
$token_tboil = $settings->get_global_settings('tboil_token');
$domen_tboil = $settings->get_global_settings('tboil_domen');
$site_id_tboil = $settings->get_global_settings('tboil_site_id');

if ($_GET["reget"] == 'true') {

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, 'https://'.$domen_tboil.'/api/v2/auth/');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "login=$login_tboil&password=$password_tboil");
  $out = curl_exec($curl);
  $admin_token = (json_decode($out));
  curl_close($curl);


  if($admin_token == false || $admin_token->success == false){
    echo '<div class="alert alert-danger" role="alert">
            Ошибка получения токена
          </div>';
    exit();
  }


  $token_tboil = $settings->update_global_settings('tboil_token',$admin_token->data->token);

  if ($token_tboil) {
    $token_tboil = $settings->get_global_settings('tboil_token');
  }

  if($token_tboil == false){
    echo '<div class="alert alert-danger" role="alert">
            Ошибка обновления/получения токена
          </div>';
    exit();
  }
}

// '/api/v2/getUsers/',
// '/api/v2/getEvents/',
// '/api/v2/getEventsData/',
// '/api/v2/getEventsUsers/',
// '/api/v2/getRequestStatus/',
// '/api/v2/getEvent/id_event/',

$arr_method = ['getUsers' => (object) ['status' => false, 'desc' => 'Получение списка всех пользователей' , 'param' => '?token='.$token_tboil ],
'getEvents' => (object) ['status' => false, 'desc' => 'Получениe списка всех мероприятий' , 'param' => '?token='.$token_tboil ],
'getEventsData' => (object) ['status' => false, 'desc' => 'Получениe данных о всех мероприятиях' , 'param' => '?token='.$token_tboil.'&period=weekly' ],
'getEventsUsers' => (object) ['status' => false, 'desc' => 'Получениe всех пользователей всех мероприятий' , 'param' => '?token='.$token_tboil.'&period=weekly' ],
'getRequestStatus' => (object) ['status' => false, 'desc' => 'Получениe статуса заявки' , 'param' => '?token='.$token_tboil.'&userId=1&eventId=1'],
'getEvent' => (object) ['status' => false, 'desc' => 'Получениe данных о мероприятии' , 'param' => '1/?token='.$token_tboil ]];

foreach( $arr_method as $key => $value ) {
  $temp_str = file_get_contents('https://'.$domen_tboil.'/api/v2/'.$key."/".$value->param);
  $value->status = (json_decode( $temp_str )->success) ? true : false;
}

?>

<table class="table table-hover">
  <thead>
    <tr>
        <th>Метод</th>
        <th>/url</th>
        <th>Статус</th>
    </tr>
  </thead>
  <tbody>
    <?php

      foreach ($arr_method as $key => $value) {
      ?>
          <tr>
            <td><?php echo $value->desc;?></td>
            <td><?php echo $key; ?></td>
            <td>
              <?php
                if($value->status) {
                  echo '<span class="badge mr-2 badge-success" style="word-wrap:">РАБОТАЕТ</span>';
                } else {
                  echo '<span class="badge mr-2 badge-danger" style="word-wrap:">НЕ РАБОТАЕТ</span>';
                }
              ?>
            </td>
          </tr>
      <?php
      }
      ?>
  </tbody>
</table>
