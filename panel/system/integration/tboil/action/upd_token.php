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

?>

<label for="InputToken">Token TBOIL</label>
<input id="InputToken"  type="password" class="form-control" style="width: 100%" name="tboil_token" disabled  value="<?php echo $token_tboil;?>">
<i class="icon_pass far fa-eye" onclick="change_view_pass(this);" style="right: 25px;"></i>

<div class="form-inline mt-2">
  <button type="button" onclick="copyToClipboard('<?php echo $token_tboil;?>')" class="btn btn-outline-success col-md-6">Скопировать</button>
  <button type="button" onclick="upd_token()" class="btn btn-outline-primary col-md-6">Перевыпустить</button>
</div>
