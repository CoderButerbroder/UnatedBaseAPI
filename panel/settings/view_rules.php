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

$data_json = json_decode($settings->get_global_settings('default_rules'));
//
// var_dump($data_json);
?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item"><a href="/panel/settings/roles">Роли и права</a></li>
    <li class="breadcrumb-item active" aria-current="page">Права роли «<?php echo $alias_role;?>»</li>
  </ol>
</nav>

<?php if ($data_roles->response) { ?>
  <div class="row">
        <div class="col-md-12 stretch-card">
                  <div id="accordion" class="accordion" role="tablist" style="width: 100%">
                              <div class="card">
                                <form id="sistem_form">
                                  <div class="card-header" role="tab" id="heading1">
                                    <h6 class="mb-0">
                                      <a data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Настройки системы
                                          </div>
                                          <div class="col-6 text-right">
                                            <button id="button_sistem" onclick="save_setting_rules(sistem,button_sistem,sistem_form);" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>

                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapse1" class="collapse" role="tabpanel" aria-labelledby="heading1" data-parent="#accordion">
                                    <div class="card-body">
                                          <div class="alert alert-primary" role="alert">
                                          Пожалуйста будьте осторожны при назначении прав в даном разделе настроек. Каждый пользователь получивший доступ к настройкам системы получает полный контроль над всем. Если вы не уверены в своих действиях, оставьте данный раздел пустым
                                          </div>

                                            <?php foreach ($data_json->sistem as $key => $value) { ?>
                                                <div class="row border-bottom">
                                                  <div class="col-sm-11 mt-2">
                                                    <?php echo $value->description;?>
                                                  </div>
                                                  <div class="col-sm-auto form-check">
                                                        <label class="form-check-label">
                                                          <input type="checkbox" name="<?php $value->name;?>" <?php if ($value->value) {echo 'checked';}?> class="form-check-input">
                                                        </label>
                                                  </div>
                                                </div>
                                            <? }?>
                                            <button id="button_sistem" onclick="save_setting_rules(sistem,button_sistem,sistem_form);" class="btn btn-sm btn-success">Сохранить</button>
                                    </div>
                                  </div>
                                </form>
                              </div>


                                <div class="card">
                                  <div class="card-header" role="tab" id="headingUsers">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Настройки пользователей
                                          </div>
                                          <div class="col-6 text-right">
                                            <button type="button" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseUsers" class="collapse" role="tabpanel" aria-labelledby="headingUsers" data-parent="#accordion">
                                    <div class="card-body">

                                      <div class="alert alert-primary" role="alert">
                                      Пожалуйста будьте осторожны при назначении прав в даном разделе настроек. Каждый пользователь получивший доступ к настройкам системы получает полный контроль над всем. Если вы не уверены в своих действиях, оставьте данный раздел пустым
                                      </div>

                                        <?php foreach ($data_json->emploe as $key => $value) { ?>
                                            <div class="row border-bottom">
                                              <div class="col-sm-11 mt-2">
                                                <?php echo $value->description;?>
                                              </div>
                                              <div class="col-sm-auto form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" name="<?php $value->name;?>" <?php if ($value->value) {echo 'checked';}?> class="form-check-input">
                                                    </label>
                                              </div>
                                            </div>
                                        <? }?>

                                    </div>
                                  </div>
                                </div>

                                <div class="card">
                                  <div class="card-header" role="tab" id="headingDataCompany">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseDataCompany" aria-expanded="false" aria-controls="collapseDataCompany" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Данные компаний
                                          </div>
                                          <div class="col-6 text-right">
                                            <button type="button" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseDataCompany" class="collapse" role="tabpanel" aria-labelledby="headingDataCompany" data-parent="#accordion">
                                    <div class="card-body">

                                      <?php foreach ($data_json->entity as $key => $value) { ?>
                                          <div class="row border-bottom">
                                            <div class="col-sm-11 mt-2">
                                              <?php echo $value->description;?>
                                            </div>
                                            <div class="col-sm-auto form-check">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" name="<?php $value->name;?>" <?php if ($value->value) {echo 'checked';}?> class="form-check-input">
                                                  </label>
                                            </div>
                                          </div>
                                      <? }?>

                                    </div>
                                  </div>
                                </div>

                                <div class="card">
                                  <div class="card-header" role="tab" id="headingDataUser">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseDataUser" aria-expanded="false" aria-controls="collapseDataUser" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Данные пользователей
                                          </div>
                                          <div class="col-6 text-right">
                                            <button type="button" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseDataUser" class="collapse" role="tabpanel" aria-labelledby="headingDataUser" data-parent="#accordion">
                                    <div class="card-body">

                                      <?php foreach ($data_json->users as $key => $value) { ?>
                                          <div class="row border-bottom">
                                            <div class="col-sm-11 mt-2">
                                              <?php echo $value->description;?>
                                            </div>
                                            <div class="col-sm-auto form-check">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" name="<?php $value->name;?>" <?php if ($value->value) {echo 'checked';}?> class="form-check-input">
                                                  </label>
                                            </div>
                                          </div>
                                      <? }?>

                                    </div>
                                  </div>
                                </div>


                                <div class="card">
                                  <div class="card-header" role="tab" id="headingDataEvents">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseDataEvents" aria-expanded="false" aria-controls="collapseDataEvents" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Данные мероприятий
                                          </div>
                                          <div class="col-6 text-right">
                                            <button type="button" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseDataEvents" class="collapse" role="tabpanel" aria-labelledby="headingDataEvents" data-parent="#accordion">
                                    <div class="card-body">

                                      <?php foreach ($data_json->events as $key => $value) { ?>
                                          <div class="row border-bottom">
                                            <div class="col-sm-11 mt-2">
                                              <?php echo $value->description;?>
                                            </div>
                                            <div class="col-sm-auto form-check">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" name="<?php $value->name;?>" <?php if ($value->value) {echo 'checked';}?> class="form-check-input">
                                                  </label>
                                            </div>
                                          </div>
                                      <? }?>

                                    </div>
                                  </div>
                                </div>


                                <div class="card">
                                  <div class="card-header" role="tab" id="headingDataReports">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseDataReports" aria-expanded="false" aria-controls="collapseDataReports" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Данные отчетов
                                          </div>
                                          <div class="col-6 text-right">
                                            <button type="button" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseDataReports" class="collapse" role="tabpanel" aria-labelledby="headingDataReports" data-parent="#accordion">
                                    <div class="card-body">

                                      <?php foreach ($data_json->reports as $key => $value) { ?>
                                          <div class="row border-bottom">
                                            <div class="col-sm-11 mt-2">
                                              <?php echo $value->description;?>
                                            </div>
                                            <div class="col-sm-auto form-check">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" name="<?php $value->name;?>" <?php if ($value->value) {echo 'checked';}?> class="form-check-input">
                                                  </label>
                                            </div>
                                          </div>
                                      <? }?>

                                    </div>
                                  </div>
                                </div>



                                <div class="card">
                                  <div class="card-header" role="tab" id="headingSupports">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseSupports" aria-expanded="false" aria-controls="collapseSupports" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Тех.поддержка
                                          </div>
                                          <div class="col-6 text-right">
                                            <button type="button" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseSupports" class="collapse" role="tabpanel" aria-labelledby="headingSupports" data-parent="#accordion">
                                    <div class="card-body">

                                      <?php foreach ($data_json->support as $key => $value) { ?>
                                          <div class="row border-bottom">
                                            <div class="col-sm-11 mt-2">
                                              <?php echo $value->description;?>
                                            </div>
                                            <div class="col-sm-auto form-check">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" name="<?php $value->name;?>" <?php if ($value->value) {echo 'checked';}?> class="form-check-input">
                                                  </label>
                                            </div>
                                          </div>
                                      <? }?>

                                    </div>
                                  </div>
                                </div>


                                <div class="card">
                                  <div class="card-header" role="tab" id="headingDashbord">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseDashbord" aria-expanded="false" aria-controls="collapseDashbord" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Дашборд
                                          </div>
                                          <div class="col-6 text-right">
                                            <button type="button" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseDashbord" class="collapse" role="tabpanel" aria-labelledby="headingDashbord" data-parent="#accordion">
                                    <div class="card-body">

                                      <?php foreach ($data_json->dashboard as $key => $value) { ?>
                                          <div class="row border-bottom">
                                            <div class="col-sm-11 mt-2">
                                              <?php echo $value->description;?>
                                            </div>
                                            <div class="col-sm-auto form-check">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" name="<?php $value->name;?>" <?php if ($value->value) {echo 'checked';}?> class="form-check-input">
                                                  </label>
                                            </div>
                                          </div>
                                      <? }?>

                                    </div>
                                  </div>
                                </div>


                                <div class="card">
                                  <div class="card-header" role="tab" id="headingIntegry">
                                    <h6 class="mb-0">
                                      <a class="collapsed" data-toggle="collapse" href="#collapseIntegry" aria-expanded="false" aria-controls="collapseIntegry" style="color: #fff;">
                                        <div class="row">
                                          <div class="col-6 mt-2 pl-3" style="color: #000;">
                                            Интеграция
                                          </div>
                                          <div class="col-6 text-right">
                                            <button type="button" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                        </div>
                                      </a>
                                    </h6>
                                  </div>
                                  <div id="collapseIntegry" class="collapse" role="tabpanel" aria-labelledby="headingIntegry" data-parent="#accordion">
                                    <div class="card-body">

                                      <div class="alert alert-info" role="alert">
                                        Новый функционал пока что в разработке. Но скоро будет уже тут :)
                                      </div>

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



<script>

function save_setting_rules(type_settings,id_buton,id_form) {
        $("#"+id_buton).attr('disabled', true);
        $("#"+id_buton).html('Сохранение...');
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            method: 'POST',
            url: 'https://<?php echo $_SERVER['SERVER_NAME'];?>/general/actions/save_rules',
            data: $form.serialize()+'&type_settings='+type_settings+'&role=<?php echo $_GET['role'];?>',
                success: function(result) {
                  $("#"+id_buton).attr('disabled', false);
                  $("#"+id_buton).html('Сохранить');
                  if (IsJsonString(result)) {
                    arr = JSON.parse(result);
                    if (arr["response"] == true) {
                        alerts('success', arr["description"], '');
                    } else {
                        alerts('warning', 'Внимание', arr["description"]);
                    }
                  }
                },
                error: function(jqXHR, exception) {
                    alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
                    $("#"+id_buton).attr('disabled', false);
                    $("#"+id_buton).html('Сохранить');
                }
        });
};
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
