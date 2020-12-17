

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Новый пользователь - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>
<?php
$data_all_roles_json = $settings->get_all_roles_sistem();

$data_all_roles = json_decode($data_all_roles_json);
?>
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">Добаление роли</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
                  <div class="card">
                      <div class="card-body">

                        <div class="container">
                          <form class="forms-sample">
                              <div class="form-group row">
                                <label for="name_role" class="col-sm-3 col-form-label">Название роли</label>
                                <div class="col-sm-9">
                                  <input type="text" name="name_role" class="form-control" id="name_role" placeholder="Например: эксперт">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="select_input" class="col-sm-3 col-form-label">Cкопировать права с роли</label>
                                <div class="col-sm-9">
                                  <select class="js-example-basic-single" name="role" id="select_input">
                                      <option default selected value="0">Не копировать</option>
                                      <?php foreach ($data_all_roles->data as $key => $value) { ?>
                                          <option value="<?php echo $value->id;?>"><?php echo $value->alias;?></option>
                                      <?}?>
                                    </select>
                                </div>
                              </div>

                              <div class="container-fluid text-center">
                                <button type="submit" class="btn btn-primary mr-2">Добавить роль</button>
                                <button type="reset" class="btn btn-light">Сброс</button>
                              </div>

                            </form>
                          </div>

                      </div>
                  </div>
    </div>
</div>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
