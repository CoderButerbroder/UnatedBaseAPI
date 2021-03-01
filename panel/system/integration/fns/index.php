<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>

<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else {

$key_fns = $settings->get_global_settings('api_fns_key');
$check_key = file_get_contents("https://api-fns.ru/api/stat?key=".$key_fns);

$nevalid_key = ($check_key == 'Ошибка: Неверный ключ (1)') ? true : false;

if (!$nevalid_key) {
  $json_check = json_decode($check_key);
}


?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/panel/system/integration/">Настройки Интеграций</a></li>
    <li class="breadcrumb-item active" aria-current="page">Интеграция с ФНС</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5>Секретный ключ <small><a href="https://api-fns.ru/user?cabinet">Где получить?</a></small></h5>
            <div class="row mt-2">
              <form method="post"  onsubmit="upd_key(this); return false;" class="form-inline" style="width: 100%">
                <div class="col-md-8">
                    <div class="form-group">
                      <input type="password" name="secret_key" class="form-control " style="width:100%" id="key_api" autocomplete="current-password" value="<?php echo $key_fns;?>" required placeholder="Обязательное поле">
                      <i class="icon_pass far fa-eye" onclick="change_view_pass(this);" style="top: 35px; right: 25px;"></i>
                    </div>
                    <?php
                    if ($nevalid_key) { ?>
                      <small id="emailHelp" class="form-text text-danger">Ошибка: Неверный ключ</small>
                    <?}?>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="submit" class="btn btn-success btn-block">Сохранить</button>
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
            <div class="col-10 my-auto">
              Количественные показатели методов
            </div>
            <button type="button" onclick="upd_tbl()" class="col-2 btn btn-outline-primary">Обновить</button>
          </div>
        </div>

        <div class="card-body">
          <div id="spinner_table" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
          <div class="" id="div_count_fns_table">
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

  function get_tbl() {
    $('#div_count_fns_table').load('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/system/integration/fns/action/get_table', function() {
     $('#spinner_table').hide('fast');
    });
  }

  function upd_tbl() {
    $('#div_count_fns_table').html('');
    $('#spinner_table').show('fast');
    get_tbl();
  }

  function upd_key(form) {
    $.ajax({
      async: true,
      cache: false,
      type: 'POST',
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/system/integration/fns/action/upd_key',
      data: $(form).serialize(),
      success: function(result, status, xhr) {
        if (IsJsonString(result)) {
          ar_data = JSON.parse(result);
          if (ar_data["response"]) {
            alerts('success', result, '');
            upd_tbl();
          } else {
            alerts('warning', 'Ошибка', result);
          }
          alerts('warning', 'Ошибка', result);
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
