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
                <h5 class="card-title">Привяжите свои аккаунты социальных сетей прямо сейчас и авторизуйтесь через них без ввода логина и пароля</h5>
                <h6 class="card-subtitle mb-2 text-muted"><script src="//ulogin.ru/js/ulogin.js"></script><div id="uLogin_fab20c8b" data-uloginid="fab20c8b"></div></h6>
                  <div class="row mt-3" id="social_user_link">
                </div>
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
