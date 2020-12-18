

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
                          <form class="forms-sample" id="new_role_sistem" action="/general/actions/add_new_role">
                              <div class="form-group row">
                                <label for="name_role" class="col-sm-3 col-form-label">Название роли</label>
                                <div class="col-sm-9">
                                  <input type="text" name="name_role" class="form-control" id="name_role" required placeholder="Например: эксперт">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="select_input" class="col-sm-3 col-form-label">Cкопировать права с роли</label>
                                <div class="col-sm-9" id="select_role_copy">
                                    <!-- <select class="js-example-basic-single" name="role_copy" id="select_input">
                                      <option default selected value="0">Не копировать</option>
                                      <?php foreach ($data_all_roles->data as $key => $value) { ?>
                                          <option value="<?php echo $value->id;?>"><?php echo $value->alias;?></option>
                                      <?}?>
                                    </select> -->
                                </div>
                              </div>

                              <div class="container-fluid text-center">
                                <button type="submit" id="submit_button"  class="btn btn-primary mr-2">Добавить роль</button>
                                <button type="reset" id="reset_button"  class="btn btn-light">Сброс</button>
                              </div>

                            </form>
                          </div>

                      </div>
                  </div>
    </div>
</div>



<script>

$(document).ready(function($) {
        global_load_block('https://<?php echo $_SERVER['SERVER_NAME'];?>/panel/elements/all_roles_for_add.php','#select_role_copy');
        $('#new_role_sistem').submit(function(e) {
        jQuery("#submit_button").attr('disabled', true);
        jQuery("#reset_button").attr('disabled', true);
        $("#submit_button").html('Добавление роли');

      var $form = $(this);
      $.ajax({
            method: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
                success: function(result) {
                  jQuery("#submit_button").attr('disabled', false);
                  jQuery("#reset_button").attr('disabled', false);
                  $("#submit_button").html('Добавить роль');
                  global_load_block('https://<?php echo $_SERVER['SERVER_NAME'];?>/panel/elements/all_roles_for_add.php','#select_role_copy');
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
                    jQuery("#submit_button").attr('disabled', false);
                    jQuery("#reset_button").attr('disabled', false);
                    $("#submit_button").html('Добавить роль');
                    global_load_block('https://<?php echo $_SERVER['SERVER_NAME'];?>/panel/elements/all_roles_for_add.php','#select_role_copy');
                }
          });
      e.preventDefault();
    });
});
</script>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
