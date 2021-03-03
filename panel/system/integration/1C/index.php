<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>


<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else {

  $api_key_1c_rent = $settings->get_global_settings('api_key_1c_rent');

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/panel/system/integration/">Настройки Интеграций</a></li>
    <li class="breadcrumb-item active" aria-current="page">Настройки API 1C</li>
  </ol>
</nav>

<div class="row">

  <div class="col-md-6">

    <div class="card">
      <div class="card-body">
        <h5>Секретный ключ <small><a href="#">Где получить?</a></small></h5>
        <div class="row">
          <form  method="post" action="/panel/system/integration/1C/action/upd_token" onsubmit="upd_key(this); return false;" style="width: 100%">
            <div class="col-md-12 mt-2">
              <div class="form-group">
                <label for="InputToken">Токен</label>
                <div class="input-group">
                  <input id="InputToken" type="password" class="form-control" name="token" placeholder="Токен Обязательное поле" required value="<?php echo $api_key_1c_rent;?>">
                  <i class="icon_pass far fa-eye" onclick="change_view_pass(this);" style="top: 35px;"></i>
                  <div class="input-group-append">
                    <span class="input-group-text btn btn-outline-primary" onclick=" copyToClipboard(this)"><i style="height:15; width:15;" data-feather="copy"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" name="submit" class="btn btn-success">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>

<?php } ?>

<script type="text/javascript">

  function setTooltip(obj, message) {
    $(obj).tooltip('hide')
      .attr('data-original-title', message)
      .tooltip('show');

    setTimeout(function() {
      $(obj).tooltip('hide');
    }, 700);
  }


  function copyToClipboard(obj) {
    var el = $(obj).parent().parent();
    var value = $(el).find('.form-control').val();

    if (value == '' || value == undefined ) {
      setTooltip(obj, 'Ошибка');
    } else {
      var input = document.body.appendChild(document.createElement("input"));
      input.value = value;
      input.focus();
      input.select();
      try {
        document.execCommand('copy');
        setTooltip(obj, 'Скопировано');
      } catch (err) {
        setTooltip(obj, 'Ошибка');
      }
      input.parentNode.removeChild(input);
    }
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
