<?php
if (empty($_GET['role']) || isset($_GET['role'])) {

}
include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Права роли - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php
$data_all_roles_json = $settings->get_role_data_name($name_role);


// $data_all_roles->response = false;
?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">Права роли {тут роль}</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
                  <div class="card">
                      <div class="card-body">

                          <?php var_dump($data_all_roles_json); ?>

                      </div>
                  </div>
    </div>
</div>



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
