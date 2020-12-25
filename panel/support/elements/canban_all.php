<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
if (!$_SESSION["key_user"]) {
    header('Location: /');
    exit;
}
$tiket_open = $settings->count_support_tickets('open');
$tiket_close = $settings->count_support_tickets('close');
$tiket_work = $settings->count_support_tickets('work');
$tiket_all = $tiket_open+$tiket_close+$tiket_work;
?>
<div class="col-md-4">
  <div class="">
    <button type="button" disabled class="btn btn-danger btn-block">Новые <span class="badge badge-light"><?php echo $tiket_open;?></span></button>
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
    <button type="button" disabled class="btn btn-primary btn-block">В работе <span class="badge badge-light"><?php echo $tiket_work;?></span></button>
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
            <button type="button" class="btn btn-sm btn-outline-success btn-block" onclick="update_status(this,'search', 'status');">Завершить <i style="width: 13px;" data-feather="check-circle"></i></button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<div class="col-md-4">
  <div class="">
    <button type="button" disabled class="btn btn-success btn-block">Закрытые <span class="badge badge-light"><?php echo $tiket_close;?></span></button>
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
