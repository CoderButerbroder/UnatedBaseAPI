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
                                <div class="card-header" role="tab" id="heading2">
                                  <h6 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                      Настройки пользователей
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse2" class="collapse" role="tabpanel" aria-labelledby="heading2" data-parent="#accordion">
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
                                <div class="card-header" role="tab" id="heading2">
                                  <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                      Настройки пользователей
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse2" class="collapse" role="tabpanel" aria-labelledby="heading2" data-parent="#accordion">
                                  <div class="card-body">

                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" role="tab" id="heading3">
                                  <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                      Данные компаний
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse3" class="collapse" role="tabpanel" aria-labelledby="heading3" data-parent="#accordion">
                                  <div class="card-body">

                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" role="tab" id="headingFour">
                                  <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                      Данные пользователей
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse4" class="collapse" role="tabpanel" aria-labelledby="heading4" data-parent="#accordion">
                                  <div class="card-body">

                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" role="tab" id="heading5">
                                  <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                      Данные мероприятий
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse5" class="collapse" role="tabpanel" aria-labelledby="heading5" data-parent="#accordion">
                                  <div class="card-body">

                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" role="tab" id="heading6">
                                  <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                      Данные отчетов
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse6" class="collapse" role="tabpanel" aria-labelledby="heading6" data-parent="#accordion">
                                  <div class="card-body">

                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" role="tab" id="heading7">
                                  <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                      Тех. поддержка
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse7" class="collapse" role="tabpanel" aria-labelledby="heading7" data-parent="#accordion">
                                  <div class="card-body">

                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" role="tab" id="headingSix">
                                  <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                      Диск
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse8" class="collapse" role="tabpanel" aria-labelledby="headingSix" data-parent="#accordion">
                                  <div class="card-body">

                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" role="tab" id="headingSix">
                                  <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                      Интеграция
                                    </a>
                                  </h6>
                                </div>
                                <div id="collapse9" class="collapse" role="tabpanel" aria-labelledby="headingSix" data-parent="#accordion">
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
