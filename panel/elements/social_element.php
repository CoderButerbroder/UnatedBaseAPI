<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_social = $user->get_user_social($_SESSION['cur_user_id']);

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
