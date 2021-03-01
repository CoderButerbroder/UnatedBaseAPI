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
    $array_metods = (object) ['bo' => 'Бухгалтерская отчетность','changes' => 'Отслеживание изменений',
                              'check' => 'Проверка контрагента', 'egr' => 'Получение данных о компании',
                              'fsrar' => 'Лицензии ФСРАР', 'innfl' => 'Узнать ИНН по паспортным данным',
                              'mon' => 'Мониторинг изменений по списку компаний', 'multinfo' => 'Реквизиты группы компаний',
                              'mvdpass' => 'Проверка паспорта на недействительность',
                              'nalogbi' => 'Проверка блокировок счета', 'multcheck' => 'Проверка группы компаний',
                              'stat' => 'Статистика запросов',
                              'search' => 'Поиск компаний', 'vyp' => 'Выписка из ЕГРЮЛ'];

    $count_metod = 0;
    foreach ($json_check->Методы as $key => $value) {
      ?>
        <tr>
            <td><?php echo $array_metods->$key." [ ".$key." ]"; ?></td>
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
