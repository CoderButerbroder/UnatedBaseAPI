<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
if (!$_SESSION["key_user"]) {
    header('Location: /');
    exit;
}
$data_massiv_open_tikets = json_decode($settings->get_support_tiket_status('open'))->data;
$tiket_open = count($data_massiv_open_tikets);
$data_massiv_close_tikets = json_decode($settings->get_support_tiket_status('close'))->data;
$tiket_close = count($data_massiv_close_tikets);
$data_massiv_work_tikets = json_decode($settings->get_support_tiket_status('work'))->data;
$tiket_work = count($data_massiv_work_tikets);
$tiket_all = $tiket_open+$tiket_close+$tiket_work;

?>
<link rel="stylesheet" href="/assets/fonts/feather-font/css/iconfont.css">
<div class="col-md-4">
  <div class="">
    <button type="button" disabled class="btn btn-danger btn-block">Новые <span class="badge badge-light"><?php echo $tiket_open;?></span></button>
  </div>
  <div class="p-3" style="
                          border-left:  2px dashed rgba(156, 156, 156, 0.2);
                          border-top: 0px solid #000000;
                          border-right: 2px dashed rgba(156, 156, 156, 0.2);
                          border-bottom: 2px dashed rgba(156, 156, 156, 0.2);">
    <?php if ($tiket_open > 0) { ?>
    <?php foreach ($data_massiv_open_tikets as $key => $value) { ?>

        <div class="card mt-3 mb-3">
          <div class="card-header">
            <div class="row">
              <div class="col-sm-4">
                <i data-feather="briefcase" class="text-danger" style="width: 1.2em;"></i>#<?php echo $value->id;?>
              </div>
              <div class="col-sm-4 text-right">
                <?php echo $settings->date_time_rus($value->date_added,true);?>
              </div>
              <div class="col-md-auto">
                  <button class="btn p-0" type="button" id="dropdownMenuButton<?php echo $value->id;?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $value->id;?>">
                    <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                    <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                    <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                    <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                    <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                  </div>
              </div>

            </div>
          </div>
          <div class="card-body">
            <p><?php echo $value->name;?></p>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-6">
                <a target="_blank" href="/panel/support/tikets/detail?id=<?php echo $value->id;?>" type="button" class="btn btn-sm btn-outline-info btn-block">Информация</a>
              </div>
              <div class="col-md-6">
                <button type="button" onclick="update_status(this,'<?php echo $value->id;?>', 'work');" class="btn btn-sm btn-outline-primary btn-block">В работу</button>
              </div>
            </div>
          </div>
        </div>

    <? } ?>
  <?php } else { ?>
      <div class="text-center">
        Нет заявок
      </div>
  <?php }?>

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
    <?php if ($tiket_work > 0) { ?>
        <?php foreach ($data_massiv_work_tikets as $key => $value) { ?>
                <div class="card mt-3 mb-3">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-sm-6">
                        <i data-feather="briefcase" class="text-primary" style="width: 1.2em;"></i>#<?php echo $value->id;?>
                      </div>
                      <div class="col-sm-6 text-right">
                        <?php echo $settings->date_time_rus($value->date_added,true);?>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <p><?php echo $value->name;?></p>
                  </div>
                  <div class="card-footer">
                    <div class="row mb-2">
                      <div class="col-md-12">
                        <a target="_blank" href="/panel/support/tikets/detail?id=<?php echo $value->id;?>" type="button" class="btn btn-xs btn-outline-info btn-block">Информация </a>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-block" onclick="update_status(this,'<?php echo $value->id;?>', 'open');">Приостановка</button>
                      </div>
                      <div class="col-md-6">
                        <button type="button" class="btn btn-sm btn-outline-success btn-block" onclick="update_status(this,'<?php echo $value->id;?>', 'close');">Завершить</button>
                      </div>
                    </div>
                  </div>
                </div>
          <?php } ?>
      <?php } else { ?>
          <div class="text-center">
            Нет заявок
          </div>
      <?php }?>

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
              <?php if ($tiket_close > 0) { ?>
                  <?php foreach ($data_massiv_close_tikets as $key => $value) { ?>

                    <div class="card mt-3 mb-3">
                      <div class="card-header">
                        <div class="row">
                          <div class="col-sm-6">
                            <i data-feather="briefcase" class="text-danger" style="width: 1.2em;"></i>#<?php echo $value->id;?>
                          </div>
                          <div class="col-sm-6 text-right">
                            <?php echo $settings->date_time_rus($value->date_added,true);?>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <p><?php echo $value->name;?></p>
                      </div>
                      <div class="card-footer">
                        <div class="row">
                          <div class="col-md-6">
                            <button type="button" onclick="update_status(this,'<?php echo $value->id;?>', 'work');" class="btn btn-sm btn-outline-primary btn-block">В работу</button>
                          </div>
                          <div class="col-md-6">
                            <a target="_blank" href="/panel/support/tikets/detail?id=<?php echo $value->id;?>" type="button" class="btn btn-sm btn-outline-info btn-block">Информация</a>
                          </div>
                        </div>
                      </div>
                    </div>

                  <? } ?>
            <?php } else { ?>
                        <div class="text-center">
                            Нет заявок
                        </div>
            <?php } ?>

  </div>
</div>

<script src="/assets/vendors/feather-icons/feather.min.js"></script>
