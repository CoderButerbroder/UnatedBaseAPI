<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>
<?php
  // var_dump($data_user_rules);
  echo json_encode($data_user_rules, JSON_UNESCAPED_UNICODE);
?>
<?php /*if (!$data_user_rules->dashboard->rule->view_dashboard->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else { ?>

<?php } */ ?>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
