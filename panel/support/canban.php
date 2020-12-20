<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Канбан - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>
<?php

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Тех. поддержка</a></li>
    <li class="breadcrumb-item active" aria-current="page">Канбан</li>
  </ol>
</nav>

<div class="row">

  <div class="col-md-4">
    <div class="">
      <button type="button" disabled class="btn btn-danger btn-block">Новые <span class="badge badge-light">14</span></button>
    </div>
    <div class="p-3" style="
                            border-left:  2px dashed rgba(156, 156, 156, 0.2);
                            border-top: 0px solid #000000;
                            border-right: 2px dashed rgba(156, 156, 156, 0.2);
                            border-bottom: 2px dashed rgba(156, 156, 156, 0.2);">


      <div class="card mt-3 mb-3">
        <div class="card-header">
          <div class="row">
            <div class="col-sm-6">
              <i data-feather="briefcase" class="text-danger" style="width: 1.2em;"></i> Кирилл Александрович
            </div>
            <div class="col-sm-6 text-right">
              <?php echo date("d.m.Y в H:i");?>
            </div>
          </div>
        </div>
        <div class="card-body">
          <p>Название запроса</p>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-sm btn-outline-info btn-block">Информация <i style="width: 13px;" data-feather="info"></i></button>
            </div>
            <div class="col-md-6">
              <button type="button" class="btn btn-sm btn-outline-primary btn-block">В работу <i style="width: 13px;" data-feather="arrow-right"></i></button>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
  <div class="col-md-4">
    <div class="">
      <button type="button" disabled class="btn btn-primary btn-block">В работе <span class="badge badge-light">12</span></button>
    </div>
    <div class="p-3" style="
                            border-left:  2px dashed rgba(156, 156, 156, 0.2);
                            border-top: 0px solid #000000;
                            border-right: 2px dashed rgba(156, 156, 156, 0.2);
                            border-bottom: 2px dashed rgba(156, 156, 156, 0.2);">
      <div class="card mt-3 mb-3">
        <div class="card-header">
          <div class="row">
            <div class="col-sm-6">
              <i data-feather="briefcase" class="text-primary" style="width: 1.2em;"></i> Кирилл Александрович
            </div>
            <div class="col-sm-6 text-right">
              <?php echo date("d.m.Y в H:i");?>
            </div>
          </div>
        </div>
        <div class="card-body">
          <p>Название запроса</p>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-sm btn-outline-danger btn-block">Приостановка <i style="width: 13px;" data-feather="alert-octagon"></i></button>
            </div>
            <div class="col-md-6">
              <button type="button" class="btn btn-sm btn-outline-success btn-block">Завершить <i style="width: 13px;" data-feather="check-circle"></i></button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="col-md-4">
    <div class="">
      <button type="button" disabled class="btn btn-success btn-block">Закрытые <span class="badge badge-light">25</span></button>
    </div>
    <div class="p-3" style="
                            border-left:  2px dashed rgba(156, 156, 156, 0.2);
                            border-top: 0px solid #000000;
                            border-right: 2px dashed rgba(156, 156, 156, 0.2);
                            border-bottom: 2px dashed rgba(156, 156, 156, 0.2);">
      <div class="text-center">
        Нет заявок
      </div>

    </div>
  </div>

</div>




<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
