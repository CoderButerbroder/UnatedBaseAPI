<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>


<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else {

  // $google_recaptacha_open = $settings->get_global_settings('google_recaptacha_open');

  // tboil_admin_login
  // tboil_admin_password

  $login_tboil = $settings->get_global_settings('tboil_admin_login');
  $password_tboil = $settings->get_global_settings('tboil_admin_password');
  $token_tboil = $settings->get_global_settings('tboil_token');
  $domen_tboil = $settings->get_global_settings('tboil_domen');
  $site_id_tboil = $settings->get_global_settings('tboil_site_id');


  //проверка актуальности данных
  if (!json_decode(file_get_contents('https://'.$domen_tboil.'/api/v2/getUsers/?token='.$token_tboil))->success){

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, 'https://'.$domen_tboil.'/api/v2/auth/');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "login=$login_tboil&password=$password_tboil");
  $out = curl_exec($curl);
  $admin_token = (json_decode($out));
  curl_close($curl);

  $token_tboil = $settings->update_global_settings('tboil_token',$admin_token->data->token);
    if ($token_tboil) {
        $token_tboil = $settings->get_global_settings('tboil_token');
    }
  }

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/panel/system/integration/">Настройки Интеграций</a></li>
    <li class="breadcrumb-item active" aria-current="page">Настройки TBOIL API</li>
  </ol>
</nav>

<div class="row">

  <div class="col-md-6">
    <div class="card mb-2">
      <div class="card-body" style="min-height:50px;">
        <div id="spinner_token" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
        <div class="form-group" id="div_token_tboil">
          <label for="InputToken">Token TBOIL</label>
          <input id="InputToken"  type="password" class="form-control" style="width: 100%" name="tboil_token" disabled  value="<?php echo $token_tboil;?>">
          <i class="icon_pass far fa-eye" onclick="change_view_pass(this);" style="right: 25px;"></i>

          <div class="form-inline mt-2">
            <button type="button" onclick="copyToClipboard('<?php echo $token_tboil;?>')" class="btn btn-outline-success col-md-6">Скопировать</button>
            <button type="button" onclick="upd_token()" class="btn btn-outline-primary col-md-6">Перевыпустить</button>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5>Данные администратора <small><a href="https://<?php echo $domen_tboil; ?>">Где получить?</a></small></h5>
        <div class="row">
          <form id="tboil" method="post" action="/panel/system/integration/tboil/action/upd_field_tboil" onsubmit="upd_key(this); return false;" style="width: 100%">
            <div class="col-md-12">
              <div class="form-group">
                <label for="InputEmail">Логин TBOIL</label>
                <input id="InputEmail" type="email" class="form-control" style="width: 100%" name="tboil_admin_login" placeholder="Логин Обязательное поле" required value="<?php echo $login_tboil;?>">
              </div>
              <div class="form-group">
                <label for="InputPassword">Пароль TBOIL</label>
                <input id="InputPassword"  type="password" class="form-control" style="width: 100%" name="tboil_admin_password" placeholder="Пароль Обязательное поле" required value="<?php echo $password_tboil;?>">
                <i class="icon_pass far fa-eye" onclick="change_view_pass(this);" style="right: 25px;"></i>
              </div>
              <div class="form-group">
                <label for="InputSiteId">ID сайта на TBOIL</label>
                <input id="InputSiteId" type="number" class="form-control" style="width: 100%" name="tboil_site_id" placeholder="Сайт Id Обязательное поле" required value="<?php echo $site_id_tboil;?>">
              </div>
              <?php
                if (!$token_tboil) { ?>
                <small id="emailHelp" class="form-text text-danger">Ошибка: <?php echo $admin_token->error; ?></small>
              <?}?>
            </div>
            <div class="col-md-12">
              <button type="submit" name="submit" id="tboil_submit" class="btn btn-success">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6" >
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-6 my-auto">
            Количественные показатели методов
          </div>
          <button type="button" onclick="upd_tbl()" class="col-2 btn btn-outline-primary">Обновить</button>
          <button type="button" onclick="upd_tbl('reget=true')" class="col-4 btn btn-outline-primary">Обновить с перевыпуском токена</button>
        </div>
      </div>

      <div class="card-body" style="min-height:440px;">
        <div id="spinner_table" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
        <div class="" id="div_count_tboil_table">
        </div>
      </div>
    </div>
  </div>


</div>

<?php }  ?>

<script type="text/javascript">

  $(document).ready(function() {
    get_tbl();
  });

  function upd_token() {
    $('#spinner_token').show('fast');
    $('#div_token_tboil').load('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/system/integration/tboil/action/upd_token?', function() {
      $('#spinner_token').hide('fast');
    });
  }

  function copyToClipboard(text) {
    var input = document.body.appendChild(document.createElement("input"));
    input.value = text;
    input.focus();
    input.select();
    document.execCommand('copy');
    input.parentNode.removeChild(input);
  }

  function get_tbl(add_url = '') {
    $('#div_count_tboil_table').load('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/system/integration/tboil/action/get_table?'+add_url, function() {
     $('#spinner_table').hide('fast');
    });
  }

  function upd_tbl(check = '') {
    $('#div_count_fns_table').html('');
    $('#spinner_table').show('fast');
    get_tbl(check);
  }


  function upd_key(form) {
    $.ajax({
      async: true,
      cache: false,
      type: 'POST',
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>'+$(form).attr('action'),
      data: $(form).serialize(),
      success: function(result, status, xhr) {
        if (IsJsonString(result)) {
          ar_data = JSON.parse(result);
          if (ar_data["response"]) {
            alerts('success', ar_data["description"], '');
            upd_tbl('reget=true');
          } else {
            alerts('warning', 'Ошибка', ar_data["description"]);
          }
        } else {
          alerts('warning', 'Ошибка', result);
        }
      },
      error: function(jqXHR, textStatus) {
        alerts('error', 'Ошибка подключения', 'Попробуйте позже');
      }
    });
  }

  function change_view_pass(el) {
      if ($(el.previousElementSibling).attr('type') == 'password'){
        $(el).removeClass();
        $(el).addClass("icon_pass far fa-eye-slash");
        $(el.previousElementSibling).attr('type', 'text');
      } else {
        $(el).removeClass();
        $(el).addClass("icon_pass far fa-eye");
        $(el.previousElementSibling).attr('type', 'password');
      }
    return false;
  };


</script>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
