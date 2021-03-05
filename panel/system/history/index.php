<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/ ?>
<title>История - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>

<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else { ?>

  <nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Система</a></li>
      <li class="breadcrumb-item active" aria-current="page">История</li>
    </ol>
  </nav>

  <div class="row">
      <div class="col-md-12 stretch-card">

      </div>
  </div>



<?php }  ?>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
