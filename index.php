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
        <a href="#"><span class="phone btn my-1 d-none d-md-block" style="top: 0px; padding: 2px 10px; border-radius: 15px; color: #000; letter-spacing: 1px;">Войти</span></a>
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
      </ul>
      <div class="navbar-mobile-footer">
        <p>© LPM. 2020. Все права защищены.</p>
      </div>
    </nav>

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
	                <button type="submit" class="btn" style="border-radius: 100px; margin-top: 25px;">Присоединиться</button>

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

      <!-- Experience -->
<!--       <section id="experience" class="navbar-is-white text-white section pp-scrollable d-flex align-items-center position-absolute" style="background-image:url('img/bg/experience.jpg');">
        <div class="intro">
          <div class="scroll-wrap">
          <div class="container">
            <h2 class="text-white mb-0">Experience</h2>
            <div class="mt-5 pt-5">
              <div class="carousel-experience owl-carousel">
	              <div class="experience-item">
		              <div class="row row-experience">
		              	<div class="col-md-4"><a href=""><img alt="" src="img/behance.png"></a></div>
		              	<div class="col-md-4 my-4">2019-2020 <h3 class="my-0 text-white">Behance</h3></div>
		              	<div class="col-md-4">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete</div>
		              </div>
		              <div class="row row-experience">
		              	<div class="col-md-4"><a href=""><img alt="" src="img/cssdesignawards.png"></a></div>
		              	<div class="col-md-4 my-4">2017-2019 <h3 class="my-0 text-white">CSSDesign</h3></div>
		              	<div class="col-md-4">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete</div>
		              </div>
		              <div class="row row-experience">
		              	<div class="col-md-4"><a href=""><img alt="" src="img/envato.png"></a></div>
		              	<div class="col-md-4">2013-2017 <h3 class="my-0 text-white">Envato</h3></div>
		              	<div class="col-md-4">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete</div>
		              </div>
	              </div>
	              <div class="experience-item">
		              <div class="row row-experience">
		              	<div class="col-md-4"><a href=""><img alt="" src="img/behance.png"></a></div>
		              	<div class="col-md-4 my-4">2019-2020 <h3 class="my-0 text-white">Behance</h3></div>
		              	<div class="col-md-4">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete</div>
		              </div>
		              <div class="row row-experience">
		              	<div class="col-md-4"><a href=""><img alt="" src="img/cssdesignawards.png"></a></div>
		              	<div class="col-md-4 my-4">2017-2019 <h3 class="my-0 text-white">CSSDesignAwards</h3></div>
		              	<div class="col-md-4">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete</div>
		              </div>
		              <div class="row row-experience">
		              	<div class="col-md-4"><a href=""><img alt="" src="img/envato.png"></a></div>
		              	<div class="col-md-4 my-4">2013-2017 <h3 class="my-0 text-white">Envato</h3></div>
		              	<div class="col-md-4">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete</div>
		              </div>
	              </div>
              </div>
            </div>
            </div>
          </div>
        </div>
      </section> -->

      <!-- Skills -->
<!--       <section id="skills" class="section pp-scrollable d-flex align-items-center position-absolute">
        <div class="intro">
          <div class="scroll-wrap">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-5">
                <div class="position-relative">
                	<div class="photo-icon photo-icon-1"><img alt="" class="w-100" src="img/100x100.jpg"></div>
                	<div class="photo-icon photo-icon-2"><img alt="" class="w-100" src="img/80x80.jpg"></div>
                	<div class="photo-icon photo-icon-3"><img alt="" class="w-100" src="img/100x100-2.jpg"></div>
                	<img alt="" class="border-radius w-100" src="img/445x459-2.jpg">
                </div>
              </div>
              <div class="mt-5 mt-lg-0 col-lg-5 offset-lg-1">
                <h2>My mission is <span class="text-primary">develop</span> best design</h2>
                <p>I will help you build your brand and grow your business. I create clarifying strategy, beautiful logo and identity design.</p>
	          	<div class="mt-5 pt-2">
	          	  <div class="progress-item">
	              	<div class="row">
		              <h6 class="col-md-6 mt-0">Web Design</h6>
		              <h6 class="col-md-6 text-right mt-0">80%</h6>
	              	</div>
		            <div class="progress mb-5">
			          <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
			          </div>
			        </div>
			      </div>
			      <div class="progress-item">
		            <div class="row">
	              	  <h6 class="col-md-6 mt-0">Photoshop</h6>
	              	  <h6 class="col-md-6 text-right mt-0">70%</h6>
	          	    </div>
		            <div class="progress mb-5">
		              <div class="progress-bar" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
		            </div>
		          </div>
		          <div class="progress-item">
		            <div class="row">
	              	  <h6 class="col-md-6 mt-0">Media & Content</h6>
	              	  <h6 class="col-md-6 text-right mt-0">90%</h6>
	          	    </div>
		            <div class="progress">
		              <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
		            </div>
		          </div>
	            </div>
	          </div>
            </div>
           </div>
        </div>
        </div>
      </section> -->

      <!-- Projects -->
      <!-- <section id="projects" class="navbar-is-white text-white section pp-scrollable position-absolute">
      	<div class="project-wrap">
      	 <div class="bg-changer">
		    <div class="section-bg active" style="background-image:url(https://tboil.spb.ru/local/templates/lpm2019/images/header_main_page.png);"></div>
		    <div class="section-bg" style="background-image:url(https://lpmtech.ru/wp-content/uploads/2017/12/IMG_9653.jpg);"></div>
		    <div class="section-bg" style="background-image:url(https://www.hanselladvisory.com/content/uploads/strategy-1920-1080.png);"></div>
		    <div class="section-bg" style="background-image:url(img/bg/portfolio/bg4.jpg);"></div>
		    <div class="section-bg" style="background-image:url(img/bg/portfolio/bg5.jpg);"></div>
		    <div class="section-bg" style="background-image:url(img/bg/portfolio/bg6.jpg);"></div>
		  </div>
	        <div class="intro">
	          <div class="scroll-wrap">
		        <div class="container">
		          <h2 class="text-white mb-0">Проекты</h2>
		          <div class="">Проекты, которые поддерживают систему авторизаций LPM-connect</div>
		          <div class="mt-5 pt-2">
		              <div class="row-project-box row">
						<div class="col-project-box col-md-6 col-lg-4 col-xl-3">
						  <a href="https://tboil.spb.ru" class="project-box">
						     <div class="project-box-inner">
						        <h4>Точка кипения</h4>
						        <div class="project-category">tboil.spb.ru</div>
						     </div>
						  </a>
						</div>
						<div class="col-project-box col-md-6 col-lg-4 col-xl-3">
						  <a href="https://lpmtech.ru" class="project-box">
						     <div class="project-box-inner">
						        <h4>Технопарк ЛПМ</h4>
						        <div class="project-category">lpmtech.ru</div>
						     </div>
						  </a>
						</div>
						<div class="col-project-box col-md-6 col-lg-4 col-xl-3">
						  <a href="https://test.e-spb.tech" class="project-box">
						     <div class="project-box-inner">
						        <h4>E-SPB</h4>
						        <div class="project-category">e-spb.tech</div>
						     </div>
						  </a>
						</div>
						<div class="col-project-box col-md-6 col-lg-4 col-xl-3">
						  <a href="" class="project-box">
						     <div class="project-box-inner">
						        <h4>Fox</h4>
						        <div class="project-category">Illustration</div>
						     </div>
						  </a>
						</div>
						<div class="col-project-box col-md-6 col-lg-4 col-xl-3">
						  <a href="" class="project-box">
						     <div class="project-box-inner">
						        <h4>Creative Mess</h4>
						        <div class="project-category">Graphic Design</div>
						     </div>
						  </a>
						</div>
						<div class="col-project-box col-md-6 col-lg-4 col-xl-3">
						  <a href="" class="project-box">
						     <div class="project-box-inner">
						        <h4>Coffee</h4>
						        <div class="project-category">Photography</div>
						     </div>
						  </a>
						</div>
					   </div>
		          </div>
		        </div>
	          </div>
	        </div>
	     </div>
      </section> -->

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
    		           <!-- <div class="col-partner col-sm-6 col-md-4  col-xl-3">
    		              <img alt="" src="img/partners/5.png">
    		           </div>
    		           <div class="col-partner col-sm-6 col-md-4  col-xl-3">
    		              <img alt="" src="img/partners/6.png">
    		           </div>
    		           <div class="col-partner col-sm-6 col-md-4  col-xl-3">
    		              <img alt="" src="img/partners/7.png">
    		           </div>
                   <div class="col-partner col-sm-6 col-md-4  col-xl-3">
                      <img alt="" src="img/partners/7.png">
                   </div>

                   <div class="col-partner col-sm-6 col-md-4  col-xl-3">
                      <img alt="" src="img/partners/1.png">
                   </div>
                    <div class="col-partner col-sm-6 col-md-4  col-xl-3">
                      <img alt="" src="img/partners/2.png">
                   </div>
                    <div class="col-partner col-sm-6 col-md-4  col-xl-3">
                      <img alt="" src="img/partners/3.png">
                   </div>
                   <div class="col-partner col-sm-6 col-md-4  col-xl-3">
                      <img alt="" src="img/partners/4.png">
                   </div> -->
		         </div>
		       </div>
           </div>
        </div>
        </div>
      </section>

      <!-- Testimonials -->
<!--       <section id="testimonials" class="navbar-is-white text-white section pp-scrollable d-flex align-items-center position-absolute" style="background-image:url('img/bg/testimonials.jpg');">
        <div class="intro">
          <div class="scroll-wrap">
          <div class="container">
          	<div class="row">
	             <div class="col-lg-6 col-xl-5">
					<span class="icon-quote text-primary">"</span>
					<h2 class="title-uppercase text-white">Hey, this is <span class="text-primary">testimonials</span> from my best clients & partners</h2>
	            </div>
	            <div class="col-lg-5 col-xl-5  offset-lg-1 offset-xl-2">
	            	<div class="carousel-testimonials owl-carousel">
	            		<div>
	            			<p class="mb-5"><strong>Amanda</strong><br>Apple inc.</p>
		            		<p>“There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything..
							</p>

	            		</div>
	            		<div>
	            			<p class="mb-5"><strong>John</strong><br>Google</p>
		            		<p>“ If you are seeking an Interior designer that will understand exactly your needs, and someone who will utilise their creative and technical skills in parity with your taste, then Suzanne at The Bauhaus Studio is perfect.
							</p>
	            		</div>
	            	</div>
	            </div>
            </div>
          </div>
        </div>
        </div>
      </section> -->

      <!-- News-->
      <section id="news" class="section pp-scrollable position-absolute">
        <div class="intro">
          <div class="scroll-wrap">
	          <div class="container">
		          <div class="d-block d-md-flex align-items-center justify-content-between">
		             <h2 class="mb-0 mb-3 mb-md-0">Новости</h2>
		             <a href="#" class="btn">Смотреть все</a>
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
				<!-- <h3 class="text mt-5 pt-5">69 Queen St, Melbourne Australia</h3>
				<h3 class="text">(+706) 898-0751</h3> -->
        <button type="submit" class="btn">Смотреть документацию</button>

        <p style="margin-top: 15px;" class="text">По всем вопросам и предложениям обращайтесь на указаный ниже email.</p>

				<p class="text-muted mt-3">developers@lpm-connect.ru</p>
				 </div>
				 <div class="col-md-5 offset-md-2">
           <div class="mt-5">
             <img alt="" style="width: 100%;" src="/img/3795950.jpg">
           </div>
				 	<!-- <h3 class="text mt-0">Let's grab a coffee and jump on conversation <span class="text-primary">chat with me.</span></h3>
				 	<div class="mt-5">
						<form class="js-ajax-form">
							<div class="form-group">
				               <input type="text" name="name" class="form-control" placeholder="Name">
				            </div>
				             <div class="form-group">
				               <input type="email" name="email"  class="form-control" required="" placeholder="Email *">
				             </div>
				             <div class="form-group">
				              <textarea rows="3" name="message"  class="form-control" placeholder="Message"></textarea>
				             </div>
				             <div class="message" id="success-message">Your message is successfully sent...</div>
				             <div class="message" id="error-message">Sorry something went wrong</div>
				             <div class="form-group mb-0">
				               <button type="submit" class="btn">Contact me</button>
				             </div>
						</form>
				 	</div> -->
				 </div>
	          </div>
	       </div>
       </div>
    </div>
   </section>
  </div>

  <!-- Scrollbar -->
<!--    <div class="progress-nav">
    <ul class="navbar-nav">
      <li data-menuanchor="home" class="active"></li>
      <li data-menuanchor="about"></li>
      <li data-menuanchor="experience"></li>
      <li data-menuanchor="skills"></li>
      <li data-menuanchor="projects"></li>
      <li data-menuanchor="partners"></li>
      <li data-menuanchor="testimonials"></li>
      <li data-menuanchor="news"></li>
      <li data-menuanchor="developers"></li>
    </ul>
  </div> -->

  <!-- Optional JavaScript -->
  <script src="js/jquery-1.12.4.min.js"></script>
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
