<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Права роли - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php
$name_role = $_GET['role'];
$data_role_json = $settings->get_role_data_name($name_role);

$data_roles = json_decode($data_role_json);

$alias_role = (isset($data_roles->data->alias)) ? $data_roles->data->alias : 'Ошибка';

$data_json = json_decode($data_roles->data->rules);
//
// var_dump($data_roles->data->rules);

$alert_text = 'Пожалуйста будьте осторожны при назначении прав в даном разделе настроек. Каждый пользователь получивший доступ к настройкам системы получает полный контроль над всем. Если вы не уверены в своих действиях, оставьте данный раздел пустым';
?>
<style media="screen">
  .accordion > .card .card-header a[aria-expanded="false"]:before {
    content: "";
  }
  .accordion > .card .card-header a[aria-expanded="true"]:before {
    content: "";
  }
</style>

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

                          <?php foreach ($data_json as $key => $value) {
                            ?>

                            <div class="card">
                              <form id="<?php echo $key?>" onsubmit="save_setting_rules(this,'<?php echo $key;?>','button_<?php echo $key;?>'); return false;">
                                <div class="card-header" role="tab" id="heading<?php echo $key;?>">
                                  <h6 class="mb-0">
                                    <div class="row">
                                        <div class="col-10 my-auto">
                                            <a data-toggle="collapse" href="#collapse_<?php echo $key;?>" aria-expanded="false" aria-controls="collapse_<?php echo $key;?>">
                                                  <?php echo $value->name;?>
                                            </a>
                                        </div>
                                        <div class="col-2 text-right">
                                            <button id="button_<?php echo $key;?>" type="submit" class="btn btn-sm btn-success">Сохранить</button>
                                        </div>
                                    </div>


                                  </h6>
                                </div>
                                <div id="collapse_<?php echo $key;?>" class="collapse" role="tabpanel" aria-labelledby="heading<?php echo $key;?>" data-parent="#accordion">
                                  <div class="card-body">
                                        <?php if ($value->alert) { ?>
                                            <div class="alert alert-primary" role="alert">
                                              <?php echo $alert_text;?>
                                            </div>
                                        <?php } ?>

                                          <?php foreach ($value->rule as $key2 => $value2) { ?>
                                              <div class="row border-bottom">
                                                <div class="col-sm-11 mt-2">
                                                  <?php echo $value2->description;?>
                                                </div>
                                                <div class="col-sm-auto form-check">
                                                      <label class="form-check-label">
                                                        <input type="checkbox" name="<?php echo $value2->name;?>" <?php if ($value2->value) {echo 'checked';}?> class="form-check-input">
                                                      </label>
                                                </div>
                                              </div>
                                          <? } ?>
                                          <!-- <button id="button_sistem" type="submit"  class="btn btn-sm btn-success">Сохранить</button> -->
                                  </div>
                                </div>
                              </form>
                            </div>



                          <?  } ?>



                              <!-- <div class="card">
                                <form id="" onsubmit="">
                                  <div class="card-header" role="tab" id="headin">
                                    <h6 class="mb-0">
                                      <div class="row">
                                          <div class="col-10 my-auto">
                                              <a data-toggle="collapse" href="#col" aria-expanded="false" aria-controls="coll">
                                                    Интеграция
                                              </a>
                                          </div>
                                          <div class="col-2 text-right">
                                              <button id="button" type="submit" class="btn btn-sm btn-success">Сохранить</button>
                                          </div>
                                      </div>


                                    </h6>
                                  </div>
                                  <div id="coll" class="collapse" role="tabpanel" aria-labelledby="headin" data-parent="#accordion">
                                    <div class="card-body">

                                      <div class="alert alert-info" role="alert">
                                        Новый функционал пока что в разработке. Но скоро будет уже тут :)
                                      </div>

                                  </div>
                                </form>
                              </div>
                            </div> -->

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

function save_setting_rules(id_form,type_settings,id_buton) {
        $("#"+id_buton).attr('disabled', 'disabled');
        $("#"+id_buton).html('Сохранение...');
        $.ajax({
            method: 'POST',
            url: 'https://<?php echo $_SERVER['SERVER_NAME'];?>/general/actions/save_rules',
            data: $(id_form).serialize()+'&type_settings='+type_settings+'&role=<?php echo $_GET['role'];?>',
                success: function(result) {
                  $("#"+id_buton).removeAttr('disabled');
                  $("#"+id_buton).html('Сохранить');
                  if (IsJsonString(result)) {
                    arr = JSON.parse(result);
                    if (arr["response"]) {
                        console.log('пупа');
                        alerts('success', arr["description"], '');
                    } else {
                        alerts('warning', 'Внимание', arr["description"]);
                    }
                  }
                },
                error: function(jqXHR, exception) {
                    alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
                    $("#"+id_buton).removeAttr('disabled');
                    $("#"+id_buton).html('Сохранить');
                }
        });
};
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
