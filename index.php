<?php
session_start();
if ($_SESSION["key_user"]) {
  header('Location: /panel');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="FULLDATA ЛЕНПОЛИГРАФМАШ">
  <meta name="author" content="ЛЕНПОЛИГРАФМАШ">
	<title>Авторизация - FULLDATA ЛЕНПОЛИГРАФМАШ</title>
	<!-- core:css -->
	<link rel="stylesheet" href="/assets/vendors/core/core.css">
	<!-- endinject -->
  <!-- plugin css for this page -->
	<!-- end plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="/assets/fonts/feather-font/css/iconfont.css">
	<link rel="stylesheet" href="/assets/vendors/flag-icon-css/css/flag-icon.min.css">
	<!-- endinject -->
  <!-- Layout styles -->
	<link rel="stylesheet" href="/assets/css/demo_1/style.css">
  <!-- End layout styles -->
  <script src="/assets/js/jquery-3.5.1.min.js"></script>
  <link rel="shortcut icon" href="/assets/images/custom/favicon.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
  <script src="//ulogin.ru/js/ulogin.js"></script>
  <script src="/assets/js/sweetalert2.all.js"></script>
  <link rel="stylesheet" href="/assets/css/sweetalert2.css">
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
</head>
<body>
  <!-- Loader -->
  <div class="loader">
   <div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>
  </div>

	<div class="main-wrapper">
		<div class="page-wrapper full-page">
			<div class="page-content d-flex align-items-center justify-content-center">

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-8 col-xl-6 mx-auto">
						<div class="card">
							<div class="row">
                <div class="col-md-4 pr-md-0">
                  <div class="auth-left-wrapper">

                  </div>
                </div>
                <div class="col-md-8 pl-md-0">
                  <div class="auth-form-wrapper px-4 py-5">
                    <a href="#" class="noble-ui-logo d-block mb-2">FULLDATA <span>ЛЕНПОЛИГРАФМАШ</span></a>
                    <h5 class="text-muted font-weight-normal mb-4">Добро пожаловать! Пожалуйста авторизуйтесь.</h5>
                    <form class="forms-sample" id="form_auth" action="/general/actions/auth">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email или телефон</label>
                        <input type="text" name="login" class="form-control" id="exampleInputEmail1" placeholder="Email или телефон">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Пароль</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" autocomplete="current-password" placeholder="Пароль">
                        <i style="" class="icon_pass far fa-eye" onclick="change_view_pass(this);"></i>
                      </div>
                      <a href="#" onclick="$('#recovery').modal('show');" class="d-block mt-3 text-muted">Забыли пароль?</a>
                      <div id="capcha_auth"></div>
                      <div class="mt-3">
                        <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0 text-white">Вход</button>
                        <div class="mt-3" id="uLogin69bf6abc" data-ulogin="display=panel;fields=first_name,last_name,email;mobilebuttons=0;sort=default;theme=flat;providers=vkontakte,yandex,odnoklassniki,googleplus,mailru,google;redirect_uri=https://api.kt-segment.ru/general/actions/social_auth.php"></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>


  <div class="modal fade" id="recovery" class="modal_backdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="recoveryLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style=" color: #000; padding: 0rem; border-bottom: none;">
          <img src="/assets/images/custom/favicon2.png" height="20"style="margin: 10px 10px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: 5px 2px;">
            <i style="width: 35px; height: 35px;" class="fas fa-times text-secondary"></i>
          </button>
        </div>
        <div class="modal-body" style="top: -15px;">
            <div class="row justify-content-center">
                <div class="col-md-9 col-sm-12" >
                  <center>
                    <img src="/img/paper-plane.png" alt="" width="42">
                    <h4>Восстановление пароля</h4>
                  </center>
                  <form id="form_rec" action="general/actions/recovery_pass?action=recovery">
                    <div class="form-group" style="margin-top: 4%;">
                      <input style="border-radius: 5px;" type="email" class="form-control text-center" name="email" placeholder="Почта" aria-describedby="" autofocus required autocomplete="on" oninput="this.value=this.value.replace(/[^0-9A-Za-z\-\@\_\.]/g, '');">
                    </div>
                    <div id="capcha_rec"></div>
                    <button type="submit" class="btn btn-block btn-primary mr-2 mb-2 mb-md-0 text-white">Восстановить</button>
                  </form>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>



  <script type="text/javascript">
    $(document).ready(function() {
      $('.loader').fadeOut(200);
      $('.line').addClass('active');
    });
  </script>

	<!-- core:js -->
  <script src="/assets/js/auth.js" ></script>
	<script src="/assets/vendors/core/core.js"></script>
	<!-- endinject -->
  <!-- plugin js for this page -->
	<!-- end plugin js for this page -->
	<!-- inject:js -->
	<script src="/assets/vendors/feather-icons/feather.min.js"></script>
	<script src="/assets/js/template.js"></script>
	<!-- endinject -->
  <!-- custom js for this page -->
	<!-- end custom js for this page -->
</body>
</html>
