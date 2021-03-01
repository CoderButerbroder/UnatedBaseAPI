<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>

<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else { ?>
  <style>
    .block_link {
      display: block;
      color: black;
      text-decoration: none;
    }
    .block_link:hover {
      color: black;
      text-decoration: none;
    }
    .block_link:before {
      color: black;
      text-decoration: none;
    }
    .block_link:after {
      color: black;
      text-decoration: none;
    }
    .block_link:active {
      color: black;
      text-decoration: none;
    }
    .block_link:visited  {
      color: black;
      text-decoration: none;
    }
    .block_link:link {
      color: black;
      text-decoration: none;
    }
  </style>

  <div class="container-fluid" style="padding-top: 10px;">
    <h5>Интеграции системы</h5>
    <div class="row">

      <div class="col-md-3" style="margin-bottom: 25px;">
        <a href="/admin/settings/integration/smsru" class="card block_link">
          <img src="/assets/images/system/integration/logo-smsru.png" class="card-img-top" alt="SMS.RU">
          <div class="card-body">
            <center><strong>SMS.RU</strong></center>
            <p class="card-text">Сервис для отправки смс сообщений</p>
            <button type="button" class="btn btn-secondary btn-lg btn-block">Настройки</button>
          </div>
        </a>
      </div>


      <div class="col-md-3" style="margin-bottom: 25px;">
        <a href="/panel/system/integration/fns" class="card block_link">
          <img src="/assets/images/system/integration/fns-api.png" class="card-img-top" alt="ФНС API">
          <div class="card-body">
            <center><strong>ФНС API</strong></center>
            <p class="card-text">Сервис для загрузки данные о юридических лицах</p>
            <button type="button" class="btn btn-secondary btn-lg btn-block">Настройки</button>
          </div>
        </a>
      </div>


      <div class="col-md-3" style="margin-bottom: 25px;">
        <a href="/admin/settings/integration/yandexmap" class="card block_link">
          <img src="/assets/images/system/integration/yandex_map.png" class="card-img-top" alt="YANDEX MAP API">
          <div class="card-body">
            <center><strong>YANDEX MAP API</strong></center>
            <p class="card-text">Сервис для работы с адресами и метками карты</p>
            <button type="button" class="btn btn-secondary btn-lg btn-block">Настройки</button>
          </div>
        </a>
      </div>

      <div class="col-md-3" style="margin-bottom: 25px;">
        <a href="/admin/settings/integration/email" class="card block_link">
          <img src="/assets/images/system/integration/logo_email.png" class="card-img-top" alt="YANDEX MAP API">
          <div class="card-body">
            <center><strong>EMAIL письма</strong></center>
            <p class="card-text">Настройка аккаунта для писем от лица системы</p>
            <button type="button" class="btn btn-secondary btn-lg btn-block">Настройки</button>
          </div>
        </a>
      </div>

      <div class="col-md-3" style="margin-bottom: 25px;">
        <a href="/admin/settings/integration/tboil" class="card block_link">
          <img src="/assets/images/system/integration/tboil-api.png" class="card-img-top" alt="YANDEX MAP API">
          <div class="card-body">
            <center><strong>TBOIL API</strong></center>
            <p class="card-text">Интеграция с сайтом точки кипения</p>
            <button type="button" class="btn btn-secondary btn-lg btn-block">Настройки</button>
          </div>
        </a>
      </div>

      <div class="col-md-3" style="margin-bottom: 25px;">
        <a href="/admin/settings/integration/recaptacha" class="card block_link">
          <img src="/assets/images/system/integration/google-api.png" class="card-img-top" alt="YANDEX MAP API">
          <div class="card-body">
            <center><strong>Google recaptcha</strong></center>
            <p class="card-text">Интеграция защиты от ботов</p>
            <button type="button" class="btn btn-secondary btn-lg btn-block">Настройки</button>
          </div>
        </a>
      </div>


    </div>
  </div>





<?php }  ?>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
