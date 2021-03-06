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
                    <h5 class="text-muted font-weight-normal mb-4">Восстановление доступа</h5>
                    <form class="forms-sample" id="form_rec_p" action="general/actions/recovery_pass?action=new_pass&recovery_link=<?php echo $_GET['link']; ?>">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Новый пароль</label>
                        <input type="password" name="password" class="form-control" placeholder="Пароль" required  autocomplete="new-password"  oninput="verification_passwords(this)">
                        <i style="" class="icon_pass far fa-eye" onclick="change_view_pass(this);"></i>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Повторите пароль</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Пароль" autocomplete="new-password" oninput="verification_passwords(this)">
                        <i style="" class="icon_pass far fa-eye" onclick="change_view_pass(this);"></i>
                      </div>
                      <div class="text-center" style="margin-bottom: 4%;">
                        <small id="small_text_rec_p" style="color:red">Укажите пароль от 6 символов <br> используя цифры и символы разного регистра</small>
                      </div>
                      <div id="capcha_rec_p"></div>
                      <div class="mt-3">
                        <button type="btn_sub" class="btn btn-primary mr-2 mb-2 mb-md-0 text-white">Восстановить</button>
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
