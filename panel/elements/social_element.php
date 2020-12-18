<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();
if(!isset($_SESSION["key_user"])){
  echo "<h4>Ошибка авторизации</h4>";
  exit();
}
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));

$data_social = $settings->get_user_social($data_user->data->id);

$array_social = array('vkontakte' => '<i class="fab fa-vk"></i>',
                      'mailru' => '<i class="fas fa-at"></i>',
                      'yandex' => '<i class="fab fa-yandex"></i>',
                      'google' => '<i class="fab fa-google"></i>',
                      'odnoklassniki' => '<i class="fab fa-odnoklassniki"></i>'
);

if ($data_social) {
foreach ($data_social as $key) { ?>

    <div class="col-md-3">
      <div class="card">
        <div class="card-body" style="padding-right: 0;">
          <div class="row">
            <div class="col-5">
              <img style="width: 100px; " src="<?php echo $key->photo_big;?>">
            </div>
            <div class="col-6">
              <h5 class="card-title"><?php echo $key->network;?></h5>
              <p class="card-text"><?php echo $key->first_name.' '.$key->last_name;?></p>
              <button type="button" class="btn btn-danger btn-sm" onclick='action_social("del","<?php echo $key->hash;?>");'>Отвязать аккаунт</button>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php }} else { ?>
  <center>
    <h4>Нет привязанных аккаунтов</h4>
  </center>
<?php } ?>
