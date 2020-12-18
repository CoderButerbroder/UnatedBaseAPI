<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">Настройки пользоватля</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
                  <div class="card">
                      <div class="card-body">
                        <h4>Привяжите свои аккаунты социальных сетей прямо сейчас и авторизуйтесь через них без ввода логина и пароля</h4>
                        <script src="//ulogin.ru/js/ulogin.js"></script><div id="uLogin_fab20c8b" data-uloginid="fab20c8b"></div>
                        <h4>Список привязанных аккаунтов</h4>
                        <div class="row" id="social_user_link">

                        </div>

                      </div>
                  </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    $("#social_user_link").load("/user/elements/social_element > *");
});
</script>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
