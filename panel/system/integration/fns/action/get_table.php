<?php

//получение таблицы с количеством запросов и тп

session_start();
if (!isset($_SESSION["key_user"])) {

  echo '<div class="alert alert-danger" role="alert">
          Ошибка доступа, <a href="/login">повторите Авторизацию</a>
        </div>';
  exit();
}
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
session_write_close();

$key_fns = $settings->get_global_settings('api_fns_key');
$check_key = file_get_contents("https://api-fns.ru/api/stat?key=".$key_fns);

$nevalid_key = ($check_key == 'Ошибка: Неверный ключ (1)') ? true : false;

if ($nevalid_key) {
  echo '<div class="alert alert-danger" role="alert">
          Ошибка: Неверный ключ.
        </div>';
        exit();
} else {
  $json_check = json_decode($check_key);
}

?>

<div class="" style="position: absolute; top: 20px; right: 30px; color:blue;" onclick="upd_tbl()">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg>
</div>
<table class="table table-hover">
  <thead>
    <tr>
        <th>Метод</th>
        <th>Лимит</th>
        <th>Истрачено</th>
        <th>Тип лимита</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $array_metods = array('bo','changes','check','egr','innfl','multinfo','search','vyp');
    $count_metod = 0;
    foreach ($json_check->Методы as $key => $value) {
      ?>
        <tr>
            <td><?php echo $array_metods[$count_metod]; ?></td>
            <td><?php echo $value->Лимит;?></td>
            <td><?php
            $summ = $value->Истрачено-25;
            $summ2 = $value->Лимит-25;
            if ($summ >= $summ2) {
                echo '<div class="text-danger">'.$value->Истрачено.'</div>';
            } else {
                echo $value->Истрачено;
            }
            ?></td>
            <td><?php echo $value->ТипЛимита;?></td>
        </tr>
      <?php
      $count_metod++;
    }
    ?>
  </tbody>
</table>
