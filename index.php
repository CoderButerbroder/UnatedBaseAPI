<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$dadata = new DaData;
$settings = new Settings;
$ip = $settings->get_ip();
$session_id = session_id();
if ($_SERVER['HTTP_REFERER']) {
  $check_record_user_referer = $settings->record_user_referer($session_id,$ip,$_SERVER['HTTP_REFERER']);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LPM Connect">
    <meta name="author" content="LPM">

    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery.pagepiling.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="//ulogin.ru/js/ulogin.js"></script>
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/auth.js"></script>

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <!-- swetalert2 -->
    <script src="js/sweetalert2.all.js"></script>
    <link rel="stylesheet" href="css/sweetalert2.css">


    <title>LPM Connect</title>

  </head>
<body>


   <!-- Loader -->
   <div class="loader">
    <div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>
   </div>

    <!-- Click capture -->
   <div class="click-capture"></div>

    <!-- Navbar -->
    <nav  class="navbar navbar-desctop"  style="border: none; background-color: rgba(255, 255, 255, 0.01); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);">
      <div class="position-relative w-100">

	    <!-- Brand-->
	    <a class="navbar-brand" href="#home"><img src="lpm-connect3.png" height="30"></a>
		<div class="container d-block">
        <a href="#"><span data-toggle="modal" data-target="#auth" class="phone btnn my-1 d-none d-md-block" style="top: 0px; padding: 2px 10px; border-radius: 15px; color: #000; letter-spacing: 1px;">Войти</span></a>
	      <a href="tel:78122348594"><span class="phone  my-0 d-none d-md-block" >8 812 234 85 94</span></a>
	      <a href="mailto:support@lpm-connect.ru"><span class="email  my-0 d-none d-md-block" >support@lpm-connect.ru</span></a>

		</div>

	    <!-- Toggler -->
	    <button class="toggler">
	      <span class="toggler-icon"></span>
	      <span class="toggler-icon"></span>
	      <span class="toggler-icon"></span>
	    </button>
	  </div>
	</nav>


    <nav class="navbar-bottom">
	  <div class="social">

	    <!-- Social -->
	    <ul class="social-icons mr-auto mr-lg-0 d-none d-sm-block">
	      <li><a href="https://www.facebook.com/lenpoligraphmash/"><ion-icon name="logo-facebook"></ion-icon></a></li>
	      <li><a href="https://vk.com/lpmtech"><ion-icon name="logo-vk"></ion-icon></a></li>
	      <li><a href="https://www.instagram.com/lenpoligraphmash/"><ion-icon name="logo-instagram"></ion-icon></a></li>
	     </ul>
	  </div>

	  <!-- Copyright -->
	  <div class="copy d-none d-sm-block">© LPM. 2020.</div>
    </nav>

    <!-- Navbar Mobile -->
    <nav  class="navbar navbar-mobile">
      <ion-icon class="close" name="close-outline"></ion-icon>

      <!-- language -->
<!--       <ul class="language">
         <li class="active"><a href="#">ENG</a></li>
         <li><a href="#">FRA</a></li>
         <li><a href="#">GER</a></li>
      </ul> -->

      <ul class="navbar-nav navbar-nav-mobile">
        <li class="active"><a class="nav-link active" data-menuanchor="home" href="#home">Начало</a></li>
        <li><a class="nav-link" data-menuanchor="about" href="#about">О платформе</a></li>
        <li><a class="nav-link" data-menuanchor="partners" href="#partners">Сайты</a></li>
        <li><a class="nav-link" data-menuanchor="news" href="#news">Новости</a></li>
        <li><a class="nav-link" data-menuanchor="developers" href="#developers">Разработчикам</a></li>
        <li><a class="nav-link" href="#"><span data-toggle="modal" data-target="#auth" class="phone btnn my-1" style="padding: 2px 10px; border-radius: 15px; color: #000; letter-spacing: 0px;">Войти</span></a></li>
      </ul>
      <div class="navbar-mobile-footer">
        <p>© LPM. 2020. Все права защищены.</p>
        <p><ion-icon class="text-muted" style="width: 12px; height: 12px; margin-right: 9px;" name="navigate"></ion-icon><?php echo json_decode($dadata->iplocate($dadata->get_ip()))->location->data->city;?></p>
      </div>
    </nav>

    <div class="modal fade" id="auth" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="authLabel" aria-hidden="true" style="overflow: hidden;">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header" style=" color: #000; padding: 0rem; border-bottom: none;">
            <img src="lpm-connect3.png" height="20"style="margin: 10px 10px;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><ion-icon style="width: 35px; height: 35px;" class="text-secondary" name="close-outline"></ion-icon></span>
            </button>
          </div>
          <div class="modal-body" style="top: -15px;">
              <div class="row">
                  <div class="col-md-6" >
                    <center>
                      <img src="/img/icons8-palec.png" alt="" width="42">
                      <h4>Авторизация</h4>
                    </center>
                    <form onsubmit="check_auth(this); return false;">
                      <div class="form-group" style="margin-top: 4%;">
                        <input type="text" class="form-control" name="email" placeholder="Телефон или почта" aria-describedby="" autofocus required autocomplete="email" oninput="check_log_input(this)">
                      </div>

                      <div class="input-group" style="margin-top: 4%; margin-bottom: 4%;">
                        <input type="password" name="pass" class="form-control" placeholder="Пароль" required  autocomplete="password">
                        <div class="input-group-append">
                            <button class="form-control btn-link" type="button" onclick="change_view_pass(this);"><i class="far fa-eye"></i></button>
                        </div>
                      </div>
                      <div class="text-right text-small"><a href="#" style="color: #afc71e;">Забыли пароль?</a></div>

                      <button type="submit" class="btnn btn-block">Войти</button>
                      <div class="text-center text-small" style="margin-top: 10px;"><a href="/?register" style="color: #afc71e;">Еще нет аккаунта?</a></div>
                    </form>
                  </div>
                  <div class="col-md-6">
                    <center>
                      <img src="/img/icons8-social.png" class="d-none d-sm-block" alt="" width="42">
                      <h4>Социальные сети</h4>
                      <div class="col-md-8 col-xs-9">
                        <div id="uLogin69bf6abc" data-ulogin="display=panel;fields=first_name,last_name,email;mobilebuttons=0;sort=default;theme=flat;providers=vkontakte,yandex,odnoklassniki,googleplus,mailru,linkedin,google,livejournal,lastfm,foursquare;redirect_uri=https%3A%2F%2Fapi.kt-segment.ru%2Ftestform.php"></div>
                        <a href="#"><span data-toggle="modal" data-target="#auth" class="phone btnn my-1 d-none d-md-block" style="top: 0px; padding: 2px 10px; border-radius: 15px; color: #000; letter-spacing: 1px;">Войти через Tboil</span></a>
                      </div>

                    </center>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="register" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true" style="overflow: hidden;">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header" style=" color: #000; padding: 0rem; border-bottom: none;">
            <img src="lpm-connect3.png" height="20"style="margin: 10px 10px;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><ion-icon style="width: 35px; height: 35px;" class="text-secondary" name="close-outline"></ion-icon></span>
            </button>
          </div>
          <div class="modal-body" style="top: -15px;">
              <div class="row">
                  <div class="col-md-6" >
                    <center>
                      <img src="/img/icons8-palec.png" alt="" width="42">
                      <h4>Регистрация</h4>
                    </center>
                    <form onsubmit="return false;">
                      <div class="form-group" style="margin-top: 4%;">
                        <input type="text" name="email" class="form-control" placeholder="Почта" aria-describedby="" required autocomplete="new-email" >
                      </div>

                      <div class="input-group" style="margin-top: 4%; margin-bottom: 4%;">
                        <input type="password" name="pass" class="form-control" placeholder="Пароль" required  autocomplete="new-password">
                        <div class="input-group-append">
                            <button class="form-control btn-link" type="button" onclick="change_view_pass(this);"><i class="far fa-eye"></i></button>
                        </div>
                      </div>

                      <button type="submit" class="btnn btn-block">Регистрация</button>
                      <div class="text-center text-small" style="margin-top: 10px;"><a href="/?auth" style="color: #afc71e;">Уже зарегистрированы?</a></div>
                    </form>
                  </div>
                  <div class="col-md-6">
                    <center>
                      <img src="/img/icons8-social.png" class="d-none d-sm-block" alt="" width="42">
                      <h4>Социальные сети</h4>
                      <div class="col-md-8 col-xs-9">
                        <div id="uLogin7cda9a92" data-ulogin="display=panel;fields=first_name,last_name,email;mobilebuttons=0;sort=default;theme=flat;providers=vkontakte,yandex,odnoklassniki,googleplus,mailru,linkedin,google,livejournal,lastfm,foursquare;redirect_uri=https%3A%2F%2Fapi.kt-segment.ru%2Ftestform.php"></div>
                        <a href="#"><span data-toggle="modal" data-target="#auth" class="phone btnn my-1 d-none d-md-block" style="top: 0px; padding: 2px 10px; border-radius: 15px; color: #000; letter-spacing: 1px;">Войти через Tboil</span></a>
                      </div>

                    </center>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <div id="pagepiling">

      <!-- Masthead -->
      <section id="home" class="navbar-is-white text-white pp-scrollable d-flex align-items-center section position-absolute" style="background-image:url('normal-1.jpg.png'); background-blend-mode: multiply;" role="main">
        <div class="intro">
          <div class="scroll-wrap">
	          <div class="container">
	            <div class="row">
	              <div class="col-lg-6 col-xl-5">
	              	<!-- <div class="mb-2">Я новая<br> система</div> -->
	              	<div class="line"></div>
	                <h1 class="text-white"><span class="text-primary text-typed a-typed a-typed-about">Один</span> аккаунт</h1>
	                <h1 class="text-white">Тысячи <span class="text-primary text-typed a-typed a-typed-about">возможностей.</span></h1>
	                <button data-toggle="modal" data-target="#register" type="submit" class="btnn" style="border-radius: 100px; margin-top: 25px;">Присоединиться</button>

	            </div>
	            </div>
            </div>
          </div>
        </div>
      </section>

      <!-- About -->
      <section id="about" class="section pp-scrollable d-flex align-items-center position-absolute">
        <div class="intro">
          <div class="scroll-wrap">
            <div class="container">
              <div class="row align-items-center">
                <div class="col-md-6 pr-md-5 pr-lg-0">
                  <div class="mb-4 text-dark">О платформе</div>
                  <h2>Практически <br><span class="text-primary">без ограничений</span> <br> возможностей</h2>
                  <div class="mt-5 pt-2">
                    <p>ЕДИНАЯ ТОЧКА ДЛЯ ВХОДА</p>
                    <p>ЕДИНОЕ УПРАВЛЕНИЕ АККАУНТАМИ</p>
                    <p>БЕЗОПАСНОЕ ХРАНЕНИЕ ДАННЫХ</p>
                    <p>БЫСТРАЯ АВТОРИЗАЦИЯ</p>
                  </div>
                </div>
                <div class="mt-5 mt-md-0 col-md-6 col-lg-5  offset-lg-1">
                  <div class="position-relative">
                  	<img alt="" class="border-radius w-100" src="lpm-connect4.png">
                  	<a href="https://www.youtube.com/watch?v=oBn8xk7mDeM" class="icon-play popup-youtube"></a>
                  	<div class="experience-info">
                  	  <div class="experience-number">4</div>
					  <div class="experience-text">ЛУЧШИХ<br> ПРЕИМУЩЕСТВА <br> СИСТЕМЫ</div>
                  	</div>
                  </div>
                </div>
              </div>
             </div>
          </div>
        </div>
      </section>


      <!-- Partners -->
      <section id="projects" class="section pp-scrollable position-absolute">
        <div class="intro">
          <div class="scroll-wrap">
	          <div class="container">
		          <h2 class="mb-0"> Сайты</h2>
              <div class="">Поддерживающие систему авторизаций LPM-connect</div>
		          	<div class="mt-5 pt-2">
		            <div class="row-partners row align-items-center ">
    		           <div class="col-partner col-sm-6 col-md-4  col-xl-3">
    		              <img alt="" src="/img/lpmtech_logo.png">
    		           </div>
    		            <div class="col-partner col-sm-6 col-md-4  col-xl-3">
    		              <img alt="" src="/img/tboil_logo.png">
    		           </div>
    		            <div class="col-partner col-sm-6 col-md-4  col-xl-3">
    		              <img alt="" src="/img/e-spb_logo.png">
    		           </div>
    		           <div class="col-partner col-sm-6 col-md-4  col-xl-3">
    		              <img alt="" src="/img/barcamp_logo.png">
    		           </div>

		         </div>
		       </div>
           </div>
        </div>
        </div>
      </section>


      <!-- News-->
      <section id="news" class="section pp-scrollable position-absolute">
        <div class="intro">
          <div class="scroll-wrap">
	          <div class="container">
		          <div class="d-block d-md-flex align-items-center justify-content-between">
		             <h2 class="mb-0 mb-3 mb-md-0">Новости</h2>
		             <a href="#" class="btnn">Смотреть все</a>
		          </div>
		          	<div class="mt-5 pt-4">
		            <div class="news-row row">
		           <div class="col-lg-4">
		              <a href="#"><img alt="" class="w-100" src="img/news/1.jpg"></a>
		              <p class="mt-4">01.12.2020</p>
					  <h4 class="mt-3">Название новости 1</h4>
					  <a href="">Подробнее 🡒</a>
		           </div>
		            <div class="col-lg-4">
		              <a href="#"><img alt="" class="w-100" src="img/news/2.jpg"></a>
		              <p class="mt-4">01.12.2020</p>
					  <h4 class="mt-3">Название новости 2</h4>
					  <a href="">Подробнее 🡒</a>
		           </div>
		           <div class="col-lg-4">
		              <a href="#"><img alt="" class="w-100" src="img/news/3.jpg"></a>
		              <p class="mt-4">01.12.2020</p>
					  <h4 class="mt-3">Название новости 3</h4>
					  <a href="">Подробнее 🡒</a>
		           </div>
		         </div>
		       </div>
           </div>
        </div>
        </div>
      </section>

      <!-- Contact -->
      <section id="developers" class="section pp-scrollable position-absolute">
        <div class="intro">
          <div class="scroll-wrap">
	          <div class="container">
		          <div class="row">
					 <div class="col-md-4">
					 	<h2 class="text">Разработчикам</h2>
					 	<p class="text">Для быстрой и простой интеграции мы разработали API, а также готовые примеры интеграций.</p>

            <!-- <?php var_dump($dadata->find_entity('7840390119'))?> -->
				<!-- <h3 class="text mt-5 pt-5">69 Queen St, Melbourne Australia</h3>
				<h3 class="text">(+706) 898-0751</h3> -->
        <button type="submit" class="btnn">Смотреть документацию</button>

        <p style="margin-top: 15px;" class="text">По всем вопросам и предложениям обращайтесь на указаный ниже email.</p>

				<p class="text-muted mt-3">developers@lpm-connect.ru</p>
				 </div>
				 <div class="col-md-5 offset-md-2">
           <div class="mt-5">
             <img alt="" style="width: 100%;" src="/img/3795950.jpg">
           </div>

				 </div>
	          </div>
	       </div>
       </div>
    </div>
   </section>
  </div>

<?php if (isset($_GET['auth'])) {?>
<script> $(document).ready(function() {$('#auth').modal('show')});</script>
<?php } ?>

<?php if (isset($_GET['register'])) {?>
<script> $(document).ready(function() {$('#register').modal('show')});</script>
<?php } ?>



  <!-- Optional JavaScript -->

  <script src="js/popper.min.js" ></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
  <script src="js/jquery.validate.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/jquery.pagepiling.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/interface.js"></script>

</body>
</html>
