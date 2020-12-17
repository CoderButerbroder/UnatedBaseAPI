<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Роли - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>


<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">Все пользователи</li>
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
                      </div>
                  </div>
    </div>
</div>








<?php echo 'Роли в системе';?>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
