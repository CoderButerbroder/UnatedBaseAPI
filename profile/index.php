<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
if (!$_SESSION["key_user"]) {
		header('Location: /?auth');
		exit;
}

$user_data = $settings->get_cur_user($_SESSION['key_user']);

$user_data = json_decode($user_data);

?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php echo $user_data->data->name.' '.$user_data->data->last_name;?> - Личный кабинет LPM-connect</title>
	<meta name="description" content="<?php echo $user_data->data->name.' '.$user_data->data->last_name;?> - Личный кабинет LPM-connect">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- STYLESHEETS -->
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/all.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/simple-line-icons.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/slick.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/simplebar.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">

	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style="background: url('/img/normal-1.jpg.png') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;">

<!-- preloader -->
<div id="preloader">
	<div class="outer">
		<div class="spinner">
			<div class="dot1"></div>
			<div class="dot2"></div>
		</div>
	</div>
</div>

<!-- site wrapper -->
<div class="site-wrapper">

	<!-- mobile header -->
	<div class="mobile-header py-2 px-3 mt-4">
		<button class="menu-icon mr-2">
			<span></span>
			<span></span>
			<span></span>
		</button>
		<a href="index.html" class="logo"><img src="https://via.placeholder.com/70x70" alt="Дмитрий Иванов" /></a>
		<a href="index.html" class="site-title dot ml-2"><?php echo $user_data->data->name.' '.$user_data->data->last_name;?></a>
	</div>

	<!-- header -->
	<header class="left float-left shadow-dark" id="header">
		<button type="button" class="close" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="header-inner d-flex align-items-start flex-column">
			<a href="#"><img class="rounded-circle" src="https://via.placeholder.com/70x70" alt="Дмитрий Иванов" /></a>
			<a href="#" class="site-title dot mt-3"><?php echo $user_data->data->name.' '.$user_data->data->last_name;?></a>
			<span class="site-slogan"></span>

			<!-- navigation menu -->
			<nav>
				<ul class="vertical-menu scrollspy">
					<!-- <li><a href="#request" class="active" style=""><i class="far fa-question-circle"></i>Запрос</a></li> -->
					<li><a href="#home" class="active" style=""><i class="icon-user"></i>Аккаунт</a></li>
					<!-- <li><a href="#about"><i class="icon-user"></i>About</a></li> -->
					<li><a href="#services"><i class="fas fa-share-alt"></i>Социальные сети</a></li>
					<li><a href="#resume"><i class="fas fa-shield-alt"></i>Безопасность</a></li>
					<!-- <li><a href="#works"><i class="icon-grid"></i>Works</a></li>
					<li><a href="#blog"><i class="icon-pencil"></i>Blog</a></li>-->
					<li><a href="#contact"><i class="fas fa-headset"></i>Поддержка</a></li>
				</ul>
			</nav>

			<!-- footer -->
			<div class="footer mt-auto">

				<!-- social icons -->
				<!-- <ul class="social-icons list-inline">
					<li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
					<li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
					<li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
					<li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
					<li class="list-inline-item"><a href="#"><i class="fab fa-dribbble"></i></a></li>
				</ul> -->

				<!-- copyright -->
				<span class="copyright">© LPM. 2020.</span>
			</div>
		</div>
	</header>

	<!-- main content area -->
	<main class="content float-right">

		<!-- section hero -->
		<!-- <section class="hero background parallax shadow-dark d-flex align-items-center" id="request">
			<div class="cta mx-auto mt-2">
				<h1 class="mt-0 mb-4"><?php echo $user_data->data->name; ?><span class="dot"></span></h1>
				<p class="mb-4">Похоже что вы не завешили регистрацию на сайте {тут рефер}, хотите продолжить данную регистрацию?</p>
				<a href="#" class="btn btn-default btn-lg mr-3"><i class="fas fa-check"></i>Продолжить</a>
				<div class="spacer d-md-none d-lg-none d-sm-none" data-height="10"></div>
				<a href="#" class="btn btn-border-light btn-lg"><i class="fas fa-times"></i>Отказаться</a>
			</div>
			<div class="overlay"></div>
		</section> -->

		<!-- section about -->
		<section id="home" class="shadow-blue white-bg padding" style="margin-top: 0px;">
			<h3 class="section-title">Данные физического лица</h3>
			<div class="spacer" data-height="80"></div>

			<div class="row">
				<div class="col-md-3">
					<img class="rounded-circle" src="https://via.placeholder.com/150x150" alt="about" />

				</div>
				<div class="col-md-9">
					<h2 class="mt-4 mt-md-0 mb-4"><?php echo $user_data->data->last_name.' '.$user_data->data->name.' '.$user_data->data->second_name;?></h2>
					<p class="mb-0"></p>
					<div class="row my-4">
						<div class="col-md-6">
							<p class="mb-2">ФИО: <span class="text-dark"></span></p>
							<p class="mb-0">Дата рождения: <span class="text-dark">1 Апреля, 1997</span></p>
						</div>
						<div class="col-md-6 mt-2 mt-md-0 mt-sm-2">
							<p class="mb-2">Адрес: <span class="text-dark">Cанкт-Петербург</span></p>
							<p class="mb-0">Email: <span class="text-dark"><?php echo $user_data->data->email;?></span></p>
						</div>
					</div>
					<a href="#" class="btn btn-default mr-3"><i class="fas fa-pencil-alt"></i>Редактировать информацию</a>
					<!-- <a href="#" class="btn btn-alt mt-2 mt-md-0 mt-xs-2"><i class="icon-envelope"></i>Hire me</a> -->
				</div>
			</div>
		</section>

		<section class="shadow-blue white-bg padding">
			<h3 class="section-title">Данные юридического лица</h3>
			<div class="spacer" data-height="80"></div>

			<div class="row">
				<div class="col-md-3">
					<img class="rounded-circle" src="https://via.placeholder.com/150x150" alt="about" />
				</div>
				<div class="col-md-9">
					<h2 class="mt-4 mt-md-0 mb-4"><?php echo $user_data->data->last_name.' '.$user_data->data->name.' '.$user_data->data->second_name;?></h2>
					<p class="mb-0"></p>
					<div class="row my-4">
						<div class="col-md-6">
							<p class="mb-2">ФИО: <span class="text-dark">Дмитрий Иванов</span></p>
							<p class="mb-0">Дата рождения: <span class="text-dark">1 Апреля, 1997</span></p>
						</div>
						<div class="col-md-6 mt-2 mt-md-0 mt-sm-2">
							<p class="mb-2">Адрес: <span class="text-dark">Cанкт-Петербург</span></p>
							<p class="mb-0">Email: <span class="text-dark">web@kt-segment.ru</span></p>
						</div>
					</div>
					<a href="#" class="btn btn-default mr-3"><i class="fas fa-pencil-alt"></i>Редактировать информацию</a>
					<!-- <a href="#" class="btn btn-alt mt-2 mt-md-0 mt-xs-2"><i class="icon-envelope"></i>Hire me</a> -->
				</div>
			</div>
		</section>

		<!-- section skills -->
		<section id="skills" class="shadow-blue white-bg padding">
			<h3 class="section-title">My skills</h3>
			<div class="spacer" data-height="80"></div>

			<p class="mb-0">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics</p>

			<div class="row mt-5">

				<div class="col-md-6">
					<!-- skill item -->
					<div class="skill-item">
						<div class="skill-info clearfix">
							<h4 class="float-left mb-3 mt-0">WordPress</h4>
							<span class="float-right">85%</span>
						</div>
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="85">
							</div>
						</div>
						<div class="spacer" data-height="50"></div>
					</div>
				</div>

				<div class="col-md-6">
					<!-- skill item -->
					<div class="skill-item">
						<div class="skill-info clearfix">
							<h4 class="float-left mb-3 mt-0">HTML & CSS</h4>
							<span class="float-right">90%</span>
						</div>
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="90">
							</div>
						</div>
						<div class="spacer" data-height="50"></div>
					</div>
				</div>

				<div class="col-md-6">
					<!-- skill item -->
					<div class="skill-item">
						<div class="skill-info clearfix">
							<h4 class="float-left mb-3 mt-0">jQuery</h4>
							<span class="float-right">60%</span>
						</div>
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="60">
							</div>
						</div>
						<div class="spacer d-md-none d-lg-none" data-height="50"></div>
					</div>
				</div>

				<div class="col-md-6">
					<!-- skill item -->
					<div class="skill-item">
						<div class="skill-info clearfix">
							<h4 class="float-left mb-3 mt-0">Sketch</h4>
							<span class="float-right">70%</span>
						</div>
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="70">
							</div>
						</div>
					</div>
				</div>

			</div>
		</section>

		<!-- section facts -->
		<section id="facts" class="shadow-dark color-white background parallax padding-50" data-image-src="images/background-1.jpg">

			<div class="row relative z-1">
				<div class="col-md-3 col-sm-6">
					<!-- fact item -->
					<div class="fact-item text-center">
						<i class="icon-like icon-circle"></i>
						<h2 class="count">157</h2>
						<span>Projects completed</span>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<!-- fact item -->
					<div class="fact-item text-center">
						<i class="icon-cup icon-circle"></i>
						<h2 class="count">2765</h2>
						<span>Cup of coffee</span>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<!-- fact item -->
					<div class="fact-item text-center">
						<i class="icon-emotsmile icon-circle"></i>
						<h2 class="count">350</h2>
						<span>Happy customers</span>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<!-- fact item -->
					<div class="fact-item text-center">
						<i class="icon-trophy icon-circle"></i>
						<h2 class="count">29</h2>
						<span>Awards won</span>
					</div>
				</div>
			</div>

			<div class="overlay"></div>

		</section>

		<!-- section services -->
		<section id="services" class="shadow-blue white-bg padding">
			<h3 class="section-title">Services</h3>
			<div class="spacer" data-height="80"></div>

			<div class="row">

				<div class="col-md-4 col-sm-6">
					<!-- service item -->
					<div class="service-item text-center">
						<i class="icon-globe icon-simple"></i>
						<h4 class="my-3">Development</h4>
						<p class="mb-0">Lorem ipsum dolor sit amet consectetuer elit.</p>
					</div>
					<div class="spacer" data-height="20"></div>
				</div>

				<div class="col-md-4 col-sm-6">
					<!-- service item -->
					<div class="service-item text-center">
						<i class="icon-chemistry icon-simple"></i>
						<h4 class="my-3">Design</h4>
						<p class="mb-0">Lorem ipsum dolor sit amet consectetuer elit.</p>
					</div>
					<div class="spacer" data-height="20"></div>
				</div>

				<div class="col-md-4 col-sm-6">
					<!-- service item -->
					<div class="service-item text-center">
						<i class="icon-directions icon-simple"></i>
						<h4 class="my-3">Advertising</h4>
						<p class="mb-0">Lorem ipsum dolor sit amet consectetuer elit.</p>
					</div>
					<div class="spacer" data-height="20"></div>
				</div>

				<div class="col-md-4 col-sm-6">
					<!-- service item -->
					<div class="service-item text-center">
						<i class="icon-rocket icon-simple"></i>
						<h4 class="my-3">SEO</h4>
						<p class="mb-0">Lorem ipsum dolor sit amet consectetuer elit.</p>
					</div>
					<div class="spacer d-md-none d-lg-none" data-height="20"></div>
				</div>

				<div class="col-md-4 col-sm-6">
					<!-- service item -->
					<div class="service-item text-center">
						<i class="icon-note icon-simple"></i>
						<h4 class="my-3">Copy Write</h4>
						<p class="mb-0">Lorem ipsum dolor sit amet consectetuer elit.</p>
					</div>
					<div class="spacer d-md-none d-lg-none" data-height="20"></div>
				</div>

				<div class="col-md-4 col-sm-6">
					<!-- service item -->
					<div class="service-item text-center">
						<i class="icon-bubbles icon-simple"></i>
						<h4 class="my-3">Support</h4>
						<p class="mb-0">Lorem ipsum dolor sit amet consectetuer elit.</p>
					</div>
				</div>

			</div>
		</section>

		<!-- section experience -->
		<section id="resume" class="shadow-blue white-bg padding">
			<h3 class="section-title">Experience</h3>
			<div class="spacer" data-height="80"></div>

			<!-- timeline -->
			<div class="timeline">
				<div class="entry">
					<div class="title">
						<span>2019 - Present</span>
					</div>
					<div class="body">
						<h4 class="mt-0">Academic Degree</h4>
						<p>Lorem ipsum dolor sit amet consectetuer adipiscing elit aenean commodo ligula eget dolor aenean massa.</p>
					</div>
				</div>
				<div class="entry">
					<div class="title">
						<span>2018 - 2015</span>
					</div>
					<div class="body">
						<h4 class="mt-0">Bachelor’s Degree</h4>
						<p>Lorem ipsum dolor sit amet consectetuer adipiscing elit aenean commodo ligula eget dolor aenean massa.</p>
					</div>
				</div>
				<div class="entry">
					<div class="title">
						<span>2015 - 2012</span>
					</div>
					<div class="body">
						<h4 class="mt-0">Honours Degree</h4>
						<p>Lorem ipsum dolor sit amet consectetuer adipiscing elit aenean commodo ligula eget dolor aenean massa.</p>
					</div>
				</div>
				<span class="timeline-line"></span>
			</div>
		</section>

		<!-- section works -->
		<section id="works" class="shadow-blue white-bg padding">
			<h3 class="section-title">Portfolio</h3>
			<div class="spacer" data-height="80"></div>

			<!-- portfolio filter (desktop) -->
			<ul class="portfolio-filter list-inline">
				<li class="current list-inline-item" data-filter="*">All Projects</li>
				<li class="list-inline-item" data-filter=".branding">Branding</li>
				<li class="list-inline-item" data-filter=".creative">Creative</li>
				<li class="list-inline-item" data-filter=".design">Design</li>
				<li class="list-inline-item" data-filter=".video">Video</li>
			</ul>

			<!-- portfolio filter (mobile) -->
			<div class="pf-filter-wrapper mb-4">
				<select class="portfolio-filter-mobile">
					<option value="*">Everything</option>
					<option value=".creative">Creative</option>
					<option value=".video">Video</option>
					<option value=".design">Design</option>
					<option value=".branding">Branding</option>
				</select>
			</div>

			<!-- portolio wrapper -->
			<div class="row portfolio-wrapper">

				<!-- portfolio item -->
				<div class="col-md-4 col-sm-6 grid-item video">
					<a href="work-single.html">
						<div class="portfolio-item">
							<div class="details">
								<h4 class="title">Creative Art</h4>
								<span class="term">Art, Creative</span>
							</div>
							<span class="plus-icon">+</span>
							<div class="thumb">
								<img src="https://via.placeholder.com/800x600" alt="Portfolio-title" />
								<div class="mask"></div>
							</div>
						</div>
					</a>
				</div>

				<!-- portfolio item -->
				<div class="col-md-4 col-sm-6 grid-item creative design">
					<a href="work-single.html">
						<div class="portfolio-item">
							<div class="details">
								<h4 class="title">Apple USB</h4>
								<span class="term">Creative, Design</span>
							</div>
							<span class="plus-icon">+</span>
							<div class="thumb">
								<img src="https://via.placeholder.com/800x600" alt="Portfolio-title" />
								<div class="mask"></div>
							</div>
						</div>
					</a>
				</div>

				<!-- portfolio item -->
				<div class="col-md-4 col-sm-6 grid-item branding">
					<a href="work-single.html">
						<div class="portfolio-item">
							<div class="details">
								<h4 class="title">Work Space</h4>
								<span class="term">Branding</span>
							</div>
							<span class="plus-icon">+</span>
							<div class="thumb">
								<img src="https://via.placeholder.com/800x600" alt="Portfolio-title" />
								<div class="mask"></div>
							</div>
						</div>
					</a>
				</div>

				<!-- portfolio item -->
				<div class="col-md-4 col-sm-6 grid-item creative">
					<a href="work-single.html">
						<div class="portfolio-item">
							<div class="details">
								<h4 class="title">Creative Bulb</h4>
								<span class="term">Creative</span>
							</div>
							<span class="plus-icon">+</span>
							<div class="thumb">
								<img src="https://via.placeholder.com/800x600" alt="Portfolio-title" />
								<div class="mask"></div>
							</div>
						</div>
					</a>
				</div>

				<!-- portfolio item -->
				<div class="col-md-4 col-sm-6 grid-item video branding">
					<a href="work-single.html">
						<div class="portfolio-item">
							<div class="details">
								<h4 class="title">iPhone 8</h4>
								<span class="term">Art, Branding</span>
							</div>
							<span class="plus-icon">+</span>
							<div class="thumb">
								<img src="https://via.placeholder.com/800x600" alt="Portfolio-title" />
								<div class="mask"></div>
							</div>
						</div>
					</a>
				</div>

				<!-- portfolio item -->
				<div class="col-md-4 col-sm-6 grid-item creative design">
					<a href="work-single.html">
						<div class="portfolio-item">
							<div class="details">
								<h4 class="title">Minimal Art</h4>
								<span class="term">Design, Creative</span>
							</div>
							<span class="plus-icon">+</span>
							<div class="thumb">
								<img src="https://via.placeholder.com/800x600" alt="Portfolio-title" />
								<div class="mask"></div>
							</div>
						</div>
					</a>
				</div>

			</div>

			<!-- more button -->
			<div class="load-more text-center mt-4">
				<a href="javascript:" class="btn btn-default"><i class="fas fa-circle-notch"></i> Load more</a>
				<!-- numbered pagination (hidden for infinite scroll) -->
				<ul class="portfolio-pagination list-inline d-none">
					<li class="list-inline-item">1</li>
					<li class="list-inline-item"><a href="works-2.html">2</a></li>
				</ul>
			</div>
		</section>

		<!-- section pricing table -->
		<section id="price" class="shadow-blue white-bg padding">
			<h3 class="section-title">Pricing table</h3>
			<div class="spacer" data-height="97"></div>

			<div class="row">

				<div class="col-md-4 pr-md-0 mt-3">
					<!-- price item -->
					<div class="price-item text-center">
						<i class="price-icon icon-energy"></i>
						<span class="plan">Basic</span>
						<h3 class="price"><em>$</em>14<span>/mo</span></h3>
						<div class="price-content">
							<ul>
								<li>Frontend Builder</li>
								<li>Regular Support</li>
								<li>1 Years of Updates</li>
							</ul>
						</div>
						<a href="#" class="btn btn-default">Get Started</a>
					</div>
				</div>

				<div class="col-md-4 px-md-0 my-5 my-md-0">
					<!-- price item -->
					<div class="price-item text-center recommended">
						<i class="price-icon icon-fire"></i>
						<span class="plan">Professional</span>
						<h3 class="price"><em>$</em>41<span>/mo</span></h3>
						<div class="price-content">
							<ul>
								<li>Frontend Builder</li>
								<li>Regular Support</li>
								<li>2 Years of Updates</li>
								<li>White Labeling</li>
							</ul>
						</div>
						<a href="#" class="btn btn-default">Get Started</a>
					</div>
				</div>

				<div class="col-md-4 pl-md-0 mt-0 mt-md-3">
					<!-- price item -->
					<div class="price-item text-center">
						<i class="price-icon icon-rocket"></i>
						<span class="plan">Premium</span>
						<h3 class="price"><em>$</em>99<span>/mo</span></h3>
						<div class="price-content">
							<ul>
								<li>Frontend Builder</li>
								<li>Regular Support</li>
								<li>3 Years of Updates</li>
							</ul>
						</div>
						<a href="#" class="btn btn-default">Get Started</a>
					</div>
				</div>

			</div>
		</section>

		<!-- section testimonials -->
		<section id="testimonials" class="shadow-blue white-bg padding">
			<h3 class="section-title">Testimonials</h3>
			<div class="spacer" data-height="97"></div>

			<div class="row testimonials-wrapper">

				<div class="col-md-6">
					<!-- testimonial item -->
					<div class="testimonial-item">
						<span class="symbol"><i class="fas fa-quote-left"></i></span>
						<p>I enjoy working with the theme and learn so much. You guys make the process fun and interesting. Good luck!</p>
						<div class="testimonial-details">
							<div class="thumb">
								<img src="https://via.placeholder.com/40x40" alt="customer-name" />
							</div>
							<div class="info">
								<h4>Helen Doe</h4>
								<span>Themeforest customer</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<!-- testimonial item -->
					<div class="testimonial-item">
						<span class="symbol"><i class="fas fa-quote-left"></i></span>
						<p>I enjoy working with the theme and learn so much. You guys make the process fun and interesting. Good luck!</p>
						<div class="testimonial-details">
							<div class="thumb">
								<img src="https://via.placeholder.com/40x40" alt="customer-name" />
							</div>
							<div class="info">
								<h4>Jane Doe</h4>
								<span>Themeforest customer</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<!-- testimonial item -->
					<div class="testimonial-item">
						<span class="symbol"><i class="fas fa-quote-left"></i></span>
						<p>I enjoy working with the theme and learn so much. You guys make the process fun and interesting. Good luck!</p>
						<div class="testimonial-details">
							<div class="thumb">
								<img src="https://via.placeholder.com/40x40" alt="customer-name" />
							</div>
							<div class="info">
								<h4>Helen Doe</h4>
								<span>Themeforest customer</span>
							</div>
						</div>
					</div>
				</div>

			</div>
		</section>

		<!-- section clients -->
		<section id="clients" class="shadow-dark background-blue color-white padding-50">

			<!-- hidden heading for validation -->
			<h2 class="d-none">Clients</h2>

			<!-- clients wrapper -->
			<div class="clients-wrapper row">

				<div class="col-md-3">
					<!-- client item -->
					<div class="client-item">
						<div class="inner">
							<img src="https://via.placeholder.com/143x20" alt="client-name" />
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<!-- client item -->
					<div class="client-item">
						<div class="inner">
							<img src="https://via.placeholder.com/143x20" alt="client-name" />
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<!-- client item -->
					<div class="client-item">
						<div class="inner">
							<img src="https://via.placeholder.com/143x20" alt="client-name" />
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<!-- client item -->
					<div class="client-item">
						<div class="inner">
							<img src="https://via.placeholder.com/143x20" alt="client-name" />
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<!-- client item -->
					<div class="client-item">
						<div class="inner">
							<img src="https://via.placeholder.com/143x20" alt="client-name" />
						</div>
					</div>
				</div>

			</div>

		</section>

		<!-- section blog -->
		<section id="blog" class="shadow-blue white-bg padding">
			<h3 class="section-title">Recent posts</h3>
			<div class="spacer" data-height="80"></div>

			<div class="row">

				<div class="col-md-4">
					<!-- blog item -->
					<div class="blog-item">
						<div class="thumb">
							<a href="#">
								<span class="category">Thoughts</span>
							</a>
							<a href="#">
								<img src="https://via.placeholder.com/800x600" alt="blog-title" />
							</a>
						</div>
						<h4 class="mt-4 mb-0"><a href="#">The Truth About Design In 3 Minutes</a></h4>
						<ul class="list-inline meta mb-0 mt-3">
							<li class="list-inline-item">13 March, 2019</li>
							<li class="list-inline-item">Дмитрий Иванов</li>
						</ul>
					</div>
				</div>

				<div class="col-md-4">
					<!-- blog item -->
					<div class="blog-item">
						<div class="thumb">
							<a href="#">
								<span class="category">Blog</span>
							</a>
							<a href="#">
								<img src="https://via.placeholder.com/800x600" alt="blog-title" />
							</a>
						</div>
						<h4 class="mt-4 mb-0"><a href="#">The Ugly Truth About Design</a></h4>
						<ul class="list-inline meta mb-0 mt-3">
							<li class="list-inline-item">13 March, 2019</li>
							<li class="list-inline-item">Дмитрий Иванов</li>
						</ul>
					</div>
				</div>

				<div class="col-md-4">
					<!-- blog item -->
					<div class="blog-item">
						<div class="thumb">
							<a href="#">
								<span class="category">Tech</span>
							</a>
							<a href="#">
								<img src="https://via.placeholder.com/800x600" alt="blog-title" />
							</a>
						</div>
						<h4 class="mt-4 mb-0"><a href="#">How To Become Better With UI Design</a></h4>
						<ul class="list-inline meta mb-0 mt-3">
							<li class="list-inline-item">13 March, 2019</li>
							<li class="list-inline-item">Дмитрий Иванов</li>
						</ul>
					</div>
				</div>

			</div>

		</section>

		<!-- section contact -->
		<section id="contact" class="shadow-blue white-bg padding">
			<h3 class="section-title">Get in touch</h3>
			<div class="spacer" data-height="80"></div>

			<div class="row">

				<div class="col-md-4 mb-4 mb-md-0">
					<!-- contact info -->
					<div class="contact-info mb-5">
						<i class="icon-phone"></i>
						<div class="details">
							<h5>Phone</h5>
							<span>+44 1632 960428</span>
						</div>
					</div>
					<div class="contact-info mb-5">
						<i class="icon-envelope"></i>
						<div class="details">
							<h5>Email address</h5>
							<span>hello@bako.com</span>
						</div>
					</div>
					<div class="contact-info">
						<i class="icon-location-pin"></i>
						<div class="details">
							<h5>Location</h5>
							<span>West Palm Beach, 4669 Travis Street</span>
						</div>
					</div>
				</div>

				<div class="col-md-8">
					<!-- Contact Form -->
					<form id="contact-form" class="contact-form" method="post" action="form/contact.php">

						<div class="messages"></div>

						<div class="row">
							<div class="column col-md-6">
								<!-- Name input -->
								<div class="form-group">
									<input type="text" class="form-control" name="InputName" id="InputName" placeholder="Your name" required="required" data-error="Name is required.">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="column col-md-6">
								<!-- Email input -->
								<div class="form-group">
									<input type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="Email address" required="required" data-error="Email is required.">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="column col-md-12">
								<!-- Email input -->
								<div class="form-group">
									<input type="text" class="form-control" id="InputSubject" name="InputSubject" placeholder="Subject" required="required" data-error="Subject is required.">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="column col-md-12">
								<!-- Message textarea -->
								<div class="form-group">
									<textarea name="InputMessage" id="InputMessage" class="form-control" rows="5" placeholder="Message" required="required" data-error="Message is required."></textarea>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>

						<button type="submit" name="submit" id="submit" value="Submit" class="btn btn-default"><i class="icon-paper-plane"></i>Send Message</button><!-- Send Button -->

					</form>
					<!-- Contact Form end -->
				</div>

			</div>

		</section>

	</main>

</div>

<!-- Go to top button -->
<a href="javascript:" id="return-to-top"><i class="fa fa-chevron-up"></i></a>

<!-- SCRIPTS -->
<script src="js/jquery-1.12.3.min.js"></script>
<script src="js/jquery.easing.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/jquery.counterup.min.js"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/isotope.pkgd.min.js"></script>
<script src="js/infinite-scroll.min.js"></script>
<script src="js/imagesloaded.pkgd.min.js"></script>
<script src="js/slick.min.js"></script>
<script src="js/contact.js"></script>
<script src="js/validator.js"></script>
<script src="js/custom.js"></script>

</body>
</html>
