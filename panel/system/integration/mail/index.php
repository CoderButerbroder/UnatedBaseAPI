<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>


<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else {

  $email_host = $settings->get_global_settings('email_host');
  $email_username = $settings->get_global_settings('email_username');
  $email_pass = $settings->get_global_settings('email_pass');
  $email_secure = $settings->get_global_settings('email_secure');
  $email_port = $settings->get_global_settings('email_port');
  $email_name = $settings->get_global_settings('email_name');

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/panel/system/integration/">Настройки Интеграций</a></li>
    <li class="breadcrumb-item active" aria-current="page">Настройки почты</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form id="email_set" method="post" action="/panel/system/integration/mail/action/upd_field_mail" onsubmit="upd_key(this); return false;" class="form" style="width: 100%">
              <div class="row">
                  <div class="col-md-4">
                  <label for="exampleInputEmail1">Хост</label>
                  <input type="text" class="form-control" style="width: 100%" name="email_host" placeholder="Обязательное поле" required value="<?php echo $email_host;?>">
                  </div>
                  <div class="col-md-4">
                  <label for="exampleInputEmail1">Имя пользователя</label>
                  <input type="email" class="form-control" style="width: 100%" name="email_username" placeholder="Обязательное поле" required value="<?php echo $email_username;?>">
                  </div>
                  <div class="col-md-4">
                  <label for="exampleInputEmail1">Пароль</label>
                  <input type="password" class="form-control" style="width: 100%" name="email_pass" placeholder="Обязательное поле" required value="<?php echo $email_pass;?>">
                  <i class="icon_pass far fa-eye" onclick="change_view_pass(this);" style="right: 25px;"></i>
                  </div>
                  <div class="col-md-4">
                  <label for="exampleInputEmail1">Метод шифрования</label>
                    <select class="form-control" class="js-example-basic-single" name="email_secure" style="" id="exampleFormControlSelect1" required>
                        <option class="text-uppercase" value="<?php echo $email_secure;?>" selected><?php echo $email_secure;?></option>
                        <option value="ssl">SSL</option>
                        <option value="tls">TLS</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                  <label for="exampleInputEmail1">Порт</label>
                  <input type="number" class="form-control" style="width: 100%" name="email_port" placeholder="Обязательное поле" required value="<?php echo $email_port;?>">
                  </div>
                  <div class="col-md-4">
                  <label for="exampleInputEmail1">Имя отправителя</label>
                  <input type="text" class="form-control" style="width: 100%" name="email_name" placeholder="Обязательное поле" required value="<?php echo $email_name;?>">
                  </div>
                  <div class="col-md-4" style="margin-top: 10px;">
                    <button type="submit" name="submit" id="email_submit" class="btn btn-success ">Сохранить</button>
                  </div>
                </div>
                <small id="emailHelp" class="form-text text-danger">После изменения настроек обязательно отправьте себе тестовое сообщение</small>

           </form>
          </div>
        </div>
    </div>




    <div class="col-md-6" style="margin-top: 20px;">
      <div class="card">
        <div class="card-body">
          <h5>Тестовое сообщение</h5>
          <form id="test_email" method="post" action="/panel/system/integration/mail/action/test_email" onsubmit="upd_key(this); return false;" class="form" style="width: 100%">
            <div class="row">
              <div class="col-md-8">
                  <input type="email" class="form-control" required placeholder="Введите email" name="email" value="<?php echo $settings->get_cur_user($_SESSION['key_user'])->user->email;?>">
              </div>
              <div class="col-md-4">
                  <button type="submit" id="test_email_submit" class="btn btn-success btn-block">Отправить сообщение</button>
              </div>
              <div class="col-md-12" id="otvet_test" >

              </div>
            </div>
          </form>
        </div>
      </div>
    </div>


</div>

<?php }  ?>

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
            upd_tbl();
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
