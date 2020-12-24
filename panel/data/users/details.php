<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Данные по пользователю - пользователи FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php if (!$data_user_rules->users->rule->view_one_user->value) {?>

<div class="container-fluid text-center">
  <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i>
    <h4>Доступ запрещен</h4>
    <p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a>
  </div>
</div>

<?php } else { ?>

<?php
  $current_user_data_tboil_json = $settings->get_all_data_user_id_tboil($_GET["tboil"]);

  if (!json_decode($current_user_data_tboil_json)->response) { ?>

<div class="container-fluid text-center">
  <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i>
    <h4>Пользователь с таким номером id tboil не найден</h4>
    <p>Пожалуйста, введите корректный номер пользователя на tboil</p><a class="btn btn-primary m-4" href="/panel/data/users/">К списку пользователей</a>
  </div>
</div>

<? } else {
  $current_user_data_tboil = json_decode($current_user_data_tboil_json)->data;
  $current_user_entity_data_tboil = false;
  if (!$current_user_data_tboil) {
    $current_user_data_tboil = json_decode($current_user_data_tboil_json)->user;
    $current_user_entity_data_tboil = json_decode($current_user_data_tboil_json)->entity;
    $current_user_entity_data_tboil_fns = json_decode($current_user_entity_data_tboil->data_fns);
    $count_items_fns = (count($current_user_entity_data_tboil_fns)>1) ? count($current_user_entity_data_tboil_fns)-1 : 0;
    $current_user_entity_data_tboil_fns = ($current_user_entity_data_tboil_fns->items[$count_items_fns]->ЮЛ) ? $current_user_entity_data_tboil_fns->items[$count_items_fns]->ЮЛ : $current_user_entity_data_tboil_fns->items[$count_items_fns]->ИП;

  }
  ?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="/panel/data/users/">Все физ. лица</a></li>
    <li class="breadcrumb-item active" aria-current="page">Данные по физ. лицу <?php echo $current_user_data_tboil->last_name.' '.$current_user_data_tboil->name.' '.$current_user_data_tboil->second_name;?> (id tboil <?php echo $_GET["tboil"];?>)</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 text-center">
            <div class="row">
              <div class="col-md-12">
                <img src="/assets/images/custom/user.png" style="width: 200px; height: 200px;" alt="user">
              </div>
              <div class="col-md-12 mt-3">
                <p>Персональный QR-Код Tboil</p>
                <img src="https://chart.googleapis.com/chart?cht=qr&choe=UTF-8&chld=H&chs=250x250&chl=https://tboil.spb.ru/<?php echo $_GET["tboil"];?>/" alt="">
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="row">
              <div class="col-md-12 border-bottom">
                <h4>Личная информация<h4>
              </div>
              <div class="col-md-12 mt-3">
                <h4><?php echo $current_user_data_tboil->last_name.' '.$current_user_data_tboil->name.' '.$current_user_data_tboil->second_name;?></h4>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-3">
                id в FULLDATA:
              </div>
              <div class="col-9">
                <?php echo $current_user_data_tboil->id;?>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-3">
                id в Tboil:
              </div>
              <div class="col-9">
                <a href="https://tboil.spb.ru/<?php echo $current_user_data_tboil->id_tboil;?>"><?php echo $current_user_data_tboil->id_tboil;?></a>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-3">
                id в leaderID:
              </div>
              <div class="col-9">
                <?php
                          $leader_id = ($current_user_data_tboil->id_leader != 0) ? $current_user_data_tboil->id_leader : 'Не зарегистрирован';
                        ?>
                <a href="https://leader-id.ru/users/<?php echo $leader_id;?>"><?php echo $leader_id;?></a>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-3">
                E-mail:
              </div>
              <div class="col-9">
                <a href="mailto:<?php echo $current_user_data_tboil->email;?>"><?php echo $current_user_data_tboil->email;?></a>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-3">
                Телефон:
              </div>
              <div class="col-9">
                <?php
                        $phone = ($current_user_data_tboil->phone) ? mb_eregi_replace('[^0-9 ]', '', $current_user_data_tboil->phone) : 'Не указан';
                        if ($phone != 'Не указан') { ?>
                <a href="callto:<?php echo $phone;?>"><?php echo $phone;?></a>
                <? }
                        ?>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-3">
                Город:
              </div>
              <div class="col-9">
                <?php echo $current_user_data_tboil->adres;?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 mt-4 border-bottom">
                <h4>Опыт работы</h4>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-3">
                Организация:
              </div>
              <div class="col-9">
                <?php echo $current_user_data_tboil->company;?>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-3">
                Должность:
              </div>
              <div class="col-9">
                <?php echo $current_user_data_tboil->profession;?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 mt-4 border-bottom">
                <h4>Привязанное юридическое лицо</h4>
              </div>
            </div>



            <div class="row mt-2">
              <div class="col-12">
                <?php if ($current_user_entity_data_tboil) {?>
                <?php $inn = ($current_user_entity_data_tboil_fns->ИНН) ? $current_user_entity_data_tboil_fns->ИНН : $current_user_entity_data_tboil_fns->ИННФЛ;?>
                <a href="/panel/data/company/details?inn=<?php echo $inn;?>" class="btn btn-primary">
                  Смотреть данные по <?php echo $current_user_entity_data_tboil_fns->НаимСокрЮЛ;?>
                </a>
                <? } else { ?>
                Привязанных юридических лиц нет
                <?php } ?>
              </div>
            </div>

          </div>

        </div>

        <div class="row">
          <div class="col-md-12">
            <div id="accordion" class="accordion" role="tablist">
              <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                  <h6 class="mb-0">
                    <a data-toggle="collapse" href="#event_user" aria-expanded="false" aria-controls="event_user">
                      Посещенные мероприятия
                    </a>
                  </h6>
                </div>
                <div id="event_user" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                  <div class="card-body">

                    <div class="row">

                      <div class="col-md-4 p-2">


                      </div>

                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php }} ?>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
