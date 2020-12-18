<?php
include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Права роли - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php
$name_role = $_GET['role'];
$data_role_json = $settings->get_role_data_name($name_role);

$data_roles = json_decode($data_role_json);

$alias_role = (isset($data_roles->data->alias)) ? $data_roles->data->alias : 'Ошибка';


?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item"><a href="/panel/settings/roles">Роли и права</a></li>
    <li class="breadcrumb-item active" aria-current="page">Права роли <?php echo $alias_role;?></li>
  </ol>
</nav>

<?php if ($data_roles->response) { ?>
  <div class="row">
        <div class="col-md-12 stretch-card">
                  <div id="accordion" class="accordion" role="tablist" style="width: 100%">
                                <div class="card">
                                  <div class="card-header" role="tab" id="heading1">
                                    <h6 class="mb-0">
                                      <a data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        Настройки системы
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapse1" class="collapse show" role="tabpanel" aria-labelledby="heading1" data-parent="#accordion">
                                    <div class="card-body">
                                      <div class="row">
                                          <div class="col-md-8">

                                          </div>
                                          <div class="col-md-2">

                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>


                                <div class="card">
                                  <div class="card-header" role="tab" id="headingTwo">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Настройки пользователей
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body">

                                    </div>
                                  </div>
                                </div>

                                <div class="card">
                                  <div class="card-header" role="tab" id="headingUsers">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
                                        Настройки пользователей
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseUsers" class="collapse" role="tabpanel" aria-labelledby="headingUsers" data-parent="#accordion">
                                    <div class="card-body">

                                    </div>
                                  </div>
                                </div>

                                <div class="card">
                                  <div class="card-header" role="tab" id="headingDataCompany">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseDataCompany" aria-expanded="false" aria-controls="collapseDataCompany">
                                        Данные компаний
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseDataCompany" class="collapse" role="tabpanel" aria-labelledby="headingDataCompany" data-parent="#accordion">
                                    <div class="card-body">

                                    </div>
                                  </div>
                                </div>

                                <div class="card">
                                  <div class="card-header" role="tab" id="headingDataUser">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseDataUser" aria-expanded="false" aria-controls="collapseDataUser">
                                        Данные пользователей
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseDataUser" class="collapse" role="tabpanel" aria-labelledby="headingDataUser" data-parent="#accordion">
                                    <div class="card-body">

                                    </div>
                                  </div>
                                </div>


                                <div class="card">
                                  <div class="card-header" role="tab" id="headingDataEvents">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseDataEvents" aria-expanded="false" aria-controls="collapseDataEvents">
                                        Данные мероприятий
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseDataEvents" class="collapse" role="tabpanel" aria-labelledby="headingDataEvents" data-parent="#accordion">
                                    <div class="card-body">

                                    </div>
                                  </div>
                                </div>


                </div>
        </div>
  </div>
<? } else { ?>
  <div class="alert alert-icon-danger" role="alert">
  	<i data-feather="alert-circle"></i>
  	Ошибка, настройки данной роли не обнаружены, пожалуйста попробуйте позже!<br>

  </div>
<?php } ?>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
