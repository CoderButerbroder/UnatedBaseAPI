<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
if (!$_SESSION["key_user"]) {
    header('Location: /');
    exit;
}
else {
    $data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
    if (!$data_user->response) {
        header('Location: http://'.$_SERVER['SERVER_NAME'].'/general/actions/logout');
        exit;
    }
}
?>
<!-- core:css -->
<link rel="stylesheet" href="/assets/vendors/core/core.css">
<script src="/assets/js/jquery-3.5.1.min.js"></script>
<!-- endinject -->
<!-- plugin css for this page -->
<!-- end plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="/assets/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/fonts/feather-font/css/iconfont.css">
<link rel="stylesheet" href="/assets/vendors/flag-icon-css/css/flag-icon.min.css">
<!-- endinject -->
<!-- Layout styles -->
<link rel="stylesheet" href="/assets/css/demo_1/style.css">
<!-- End layout styles -->
<link rel="shortcut icon" href="/assets/images/custom/favicon.ico" />
<link rel="stylesheet" href="/assets/vendors/jquery-steps/jquery.steps.css">
<link rel="stylesheet" href="/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">

</head>
<body>
<div class="main-wrapper">

  <!-- partial:../../partials/_sidebar.html -->
  <nav class="sidebar">
    <div class="sidebar-header">
      <a href="#" class="sidebar-brand">
        FULLDATA<span></span>
      </a>
      <div class="sidebar-toggler not-active">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
    <div class="sidebar-body">
      <ul class="nav">
        <li class="nav-item nav-category">Главное</li>
        <li class="nav-item">
          <a href="/panel" class="nav-link">
            <i class="link-icon" data-feather="box"></i>
            <span class="link-title">Главная</span>
          </a>
        </li>
        <li class="nav-item nav-category">Тех. поддержка</li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#emails" role="button" aria-expanded="false" aria-controls="emails">
            <i class="link-icon" data-feather="mail"></i>
            <span class="link-title">Заявки</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="emails">
            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="#" class="nav-link">Все заявки <span class="ml-2 badge badge-light">250</span></a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">В работе <span class="ml-2 badge badge-primary">12</span></a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">Новые<span class="ml-2 badge badge-danger">14</span></a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">Закрытые <span class="ml-2 badge badge-success">25</span></a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item nav-category">Данные</li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#uiComponents" role="button" aria-expanded="false" aria-controls="uiComponents">
            <i class="link-icon" data-feather="briefcase"></i>
            <span class="link-title">Компании</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="uiComponents">
            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="#" class="nav-link">Все компании</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">Поиск компании</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">Компании IPCHhain</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#advancedUI" role="button" aria-expanded="false" aria-controls="advancedUI">
            <i class="link-icon" data-feather="users"></i>
            <span class="link-title">Пользователи</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="advancedUI">
            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="" class="nav-link">Все пользователи</a>
              </li>
              <li class="nav-item">
                  <a href="" class="nav-link">Поиск пользователя</a>
                </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#forms" role="button" aria-expanded="false" aria-controls="forms">
            <i class="link-icon" data-feather="inbox"></i>
            <span class="link-title">Мероприятия</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="forms">
            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="../../pages/forms/basic-elements.html" class="nav-link">Все проприятия</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link"  data-toggle="collapse" href="#charts" role="button" aria-expanded="false" aria-controls="charts">
            <i class="link-icon" data-feather="pie-chart"></i>
            <span class="link-title">Отчеты</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="charts">
            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="#" class="nav-link">Все отчеты</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">Отчеты по компаниям</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">Отчеты по пользователям</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">Отчеты по мероприятиям</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item nav-category">Настройки</li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#general-pages" role="button" aria-expanded="false" aria-controls="general-pages">
            <i class="link-icon" data-feather="book"></i>
            <span class="link-title">Пользователи</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="general-pages">
            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="/panel/settings/users" class="nav-link">Все пользователи</a>
              </li>
              <li class="nav-item">
                <a href="/panel/settings/new_user" class="nav-link">Добавить пользователя</a>
              </li>
              <li class="nav-item">
                <a href="/panel/settings/roles" class="nav-link">Роли и права</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#authPages" role="button" aria-expanded="false" aria-controls="authPages">
            <i class="link-icon" data-feather="unlock"></i>
            <span class="link-title">Система</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="authPages">
            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="" class="nav-link">Все настройки</a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">Нагрузка</a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">Логи</a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">История</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item nav-category">Документация</li>
        <li class="nav-item">
          <a href="#" target="_blank" class="nav-link">
            <i class="link-icon" data-feather="file-text"></i>
            <span class="link-title">Документация</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" target="_blank" class="nav-link">
            <i class="link-icon" data-feather="terminal"></i>
            <span class="link-title">Документация API</span>
          </a>
        </li>

      </ul>
    </div>
  </nav>
  <!-- <nav class="settings-sidebar">
    <div class="sidebar-body">
      <a href="#" class="settings-sidebar-toggler">
        <i data-feather="settings"></i>
      </a>
      <h6 class="text-muted">Sidebar:</h6>
      <div class="form-group border-bottom">
        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight" value="sidebar-light" checked>
            Light
          </label>
        </div>
        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark" value="sidebar-dark">
            Dark
          </label>
        </div>
      </div>
      <div class="theme-wrapper">
        <h6 class="text-muted mb-2">Light Theme:</h6>
        <a class="theme-item active" href="/demo_1/dashboard-one.html">
          <img src="/assets/images/screenshots/light.jpg" alt="light theme">
        </a>
        <h6 class="text-muted mb-2">Dark Theme:</h6>
        <a class="theme-item" href="/demo_2/dashboard-one.html">
          <img src="/assets/images/screenshots/dark.jpg" alt="light theme">
        </a>
      </div>
    </div>
  </nav> -->
  <!-- partial -->

  <div class="page-wrapper">

    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar">
      <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
      </a>
      <div class="navbar-content">
        <form class="search-form">
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i data-feather="search"></i>
              </div>
            </div>
            <input type="text" class="form-control" id="navbarForm" placeholder="Поиск">
          </div>
        </form>
        <ul class="navbar-nav">

          <li class="nav-item dropdown nav-profile">
            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="https://via.placeholder.com/30x30" alt="profile">
            </a>
            <div class="dropdown-menu" aria-labelledby="profileDropdown">
              <div class="dropdown-header d-flex flex-column align-items-center">
                <div class="figure mb-3">
                  <img src="https://via.placeholder.com/80x80" alt="">
                </div>
                <div class="info text-center">
                  <p class="name font-weight-bold mb-0"><?php echo $data_user->data->name.' '.$data_user->data->lastname; ?></p>
                  <p class="email text-muted mb-3"><?php echo $data_user->data->email;?></p>
                </div>
              </div>
              <div class="dropdown-body">
                <ul class="profile-nav p-0 pt-3">
                  <li class="nav-item">
                    <a href="/panel/profile" class="nav-link">
                      <i data-feather="user"></i>
                      <span>Профиль</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/general/actions/logout" class="nav-link">
                      <i data-feather="log-out"></i>
                      <span>Выход</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- partial -->
    <div class="page-content">
