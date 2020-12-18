<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Роли - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>
<?php

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">Роли и права</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <a href="/panel/settings/new_role" type="button" class="btn btn-success btn-icon-text">
                  <i class="btn-icon-prepend" data-feather="plus"></i>
                  Добавить роль
                </a>
              </div>
            </div>
              <div class="mt-4" id="all_roles_list">


              </div>

          </div>
        </div>
    </div>
</div>




<script>

function delete_role(id_role) {

    $.ajax({
          method: 'POST',
          url: 'https://<?php echo $_SERVER['SERVER_NAME'];?>/general/actions/delete_role',
          data: 'id_role='+id_role,
              success: function(result) {
                  global_load_block('https://<?php echo $_SERVER['SERVER_NAME'];?>/panel/elements/list_all_roles','#all_roles_list');
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
                  global_load_block('https://<?php echo $_SERVER['SERVER_NAME'];?>/panel/elements/list_all_roles','#all_roles_list');
              }
        });
}


$(document).ready(function($) {
    global_load_block('https://<?php echo $_SERVER['SERVER_NAME'];?>/panel/elements/list_all_roles','#all_roles_list');
});
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
