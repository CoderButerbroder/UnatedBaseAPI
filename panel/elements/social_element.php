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
    <div class="col-md-4">
      <div class="panel panel-border" style="padding: 2rem">
        <a href="<?php echo $key->profile;?>" style="color: #000;">
          <h3><?php echo $array_social[$key->network];?>  <?php echo $key->network;?></h3>
          <h2><img style="width: 50px; height: 50px;" src="<?php echo $key->photo_big;?>" class="img-circle">  <?php echo $key->first_name.' '.$key->last_name;?></h2>
        </a>
        <center>
              <button onclick='delete_social("<?php echo $key->hash;?>");' class="btn btn-space btn-danger"><i class="icon icon-left mdi mdi-delete"></i> Отвязать аккаунт</button>
        </center>
      </div>
    </div>
<?php }} else { ?>
  <center>
    <h4>Нет привязанных аккаунтов</h4>
  </center>
<?php } ?>
