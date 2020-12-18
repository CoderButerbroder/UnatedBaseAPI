<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Настройки пользователя - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

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
                        <h6>Привяжите свои аккаунты социальных сетей прямо сейчас и авторизуйтесь через них без ввода логина и пароля</h6>
                        <script src="//ulogin.ru/js/ulogin.js"></script><div id="uLogin_fab20c8b" data-uloginid="fab20c8b"></div>
                        <h4>Список привязанных аккаунтов</h4>
                        <div class="row" id="social_user_link">

                        </div>

                        <!-- <div class="col-md-3">
                          <div class="card">
                            <div class="card-body">
                              <div class="row">
                                <div class="col-5">
                                  <img style="width: 100px; " src="https://sun1-92.userapi.com/impf/3Z4lMM88Idaa5KwH4Ei-CnvSMd0aiJmHs2S2Gw/H68cgi4z8qU.jpg?size=200x0&quality=96&crop=131,27,436,436&sign=0a068b0dbfab66d1e2062b33c1281e1d&c_uniq_tag=E63K6pqnYDrYmK7QH9Pd2_PGD8wqrGzxiJk4p_LZDYw&ava=1" alt="">
                                </div>
                                <div class="col-6">
                                  <h5 class="card-title">Vkontakte</h5>
                                  <p class="card-text">Виктор Кулик</p>
                                  <button type="button" class="btn  btn-danger">Отвязать аккаунт</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> -->

                      </div>
                  </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    $("#social_user_link").load("/panel/elements/social_element > *");
});

function add_social(token){
  action_social('add',token);
}

function action_social(type,value) {
    // alert("Удаление"+hash);
          // data_user = parseJSON(hash);
          $.ajax({
                method: 'POST',
                url: "https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/elements/action_social",
                data: "type="+type+"&value="+value,
                success: function(result) {
                  if (IsJsonString(result)) {
                    arr = JSON.parse(result);
                    if (arr["response"] == true) {
                        $("#social_user_link").load("/panel/elements/social_element > *");
                        Swal.fire({
                          icon: 'success',
                          title: 'Успешно',
                          text: arr["description"]
                        });
                    } else {
                      Swal.fire({
                        icon: 'error',
                        title: arr["description"]
                      });
                    }
                  }
                },
                error: function(jqXHR, exception) {
                  Swal.fire({
                    icon: 'error',
                    title: 'Ошибка',
                    text: 'Ошибка подключения, пожалуйтса попробуйте чуть позже'
                  });
                }
          });

  }









</script>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
