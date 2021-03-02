<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>


<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else {

  $google_recaptacha_open = $settings->get_global_settings('google_recaptacha_open');
  $google_recaptacha_secret = $settings->get_global_settings('google_recaptacha_secret');

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/panel/system/integration/">Настройки Интеграций</a></li>
    <li class="breadcrumb-item active" aria-current="page">Настройки Google recaptcha</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5>Ключи для Google recaptcha <small><a href="https://www.google.com/recaptcha/admin/site/">Где получить?</a></small></h5>
        <div class="row">
          <form id="recaptcha" method="post" action="/panel/system/integration/recaptacha/action/upd_field_recaptacha" onsubmit="upd_key(this); return false;" style="width: 100%">
            <div class="col-md-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Открытый ключ</label>
                <input type="text" class="form-control" style="width: 100%" name="google_recaptacha_open" placeholder="Логин Обязательное поле" required value="<?php echo $google_recaptacha_open;?>">
                <i class="icon_pass far fa-eye-slash" onclick="change_view_pass(this);" style="right: 25px;"></i>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Секретный ключ</label>
                <input type="password" class="form-control" style="width: 100%" name="google_recaptacha_secret" placeholder="Пароль Обязательное поле" required value="<?php echo $google_recaptacha_secret;?>">
                <i class="icon_pass far fa-eye" onclick="change_view_pass(this);" style="right: 25px;"></i>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" id="recaptcha_submit" class="btn btn-success">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 my-auto">
      <div class="form-group text-center" >
        <div class="g-recaptcha" style="display: inline-block;" data-sitekey="<?php echo $google_recaptacha_open;?>"></div>
      </div>
  </div>

</div>

<?php }  ?>

<script src='https://www.google.com/recaptcha/api.js'></script>

<script type="text/javascript">


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
            setTimeout(function (){location.reload()}, 1500);

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
