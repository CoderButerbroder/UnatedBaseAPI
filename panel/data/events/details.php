<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Детали Мероприятия - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php


$data_event = json_decode($settings->get_data_one_event(trim($_GET["event"])));

if(!$data_event->response){  ?>
  <div class="container-fluid text-center">
    <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i>
      <h4>Мероприятия не существует</h4>
      <p>Пожалуйста, введите корректный id события</p><a class="btn btn-primary m-4" href="/panel/data/events/">К списку пользователей</a>
    </div>
  </div>
<?php } else {

$data_user_org = json_decode($settings->get_user_data_id_boil($data_event->data->id_tboil_organizer));


?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="/panel/data/events/">Мероприятия</a></li>
    <li class="breadcrumb-item active" aria-current="page">Детали Мероприятия «<?php echo trim($data_event->data->name); ?>»</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <?php
        //https://demos.creative-tim.com/argon-dashboard-pro/pages/widgets.html
        //сверху 4ре карточки понравились а получилось >_>
        ?>
        <div class="row ">
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
              <div class="card-body mx-auto">
                <i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="target"></i>
                <?php
                echo $data_event->data->individ;
                if ($data_event->data->type_event == "individ") echo "Для Физ лиц";
                if ($data_event->data->type_event == "entity") echo "Для Юр лиц";
                if ($data_event->data->type_event == "all") echo "Для Всех";
                ?>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
              <div class="card-body mx-auto">
                <i class="link-icon mr-1 text-success" style="width: 25px; height: 25px;" data-feather="user"></i>
                 <?php
                 if($data_user_org->response) { ?>
                   <span style="cursor:pointer;"  onclick="window.open('<?php echo 'https://'.$_SERVER["SERVER_NAME"];?>/panel/data/users/details?tboil=<?php echo $data_user_org->data->id_tboil; ?>')">
                     <?php
                     echo $data_user_org->data->last_name.' '.$data_user_org->data->name.' '.$data_user_org->data->second_name;
                     ?>
                    </span>
                     <?php
                     } else {
                       echo 'Пользователь не найден';
                     }
                 ?>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
              <div class="card-body mx-auto">
                <i class="link-icon mr-1 text-warning" style="width: 25px; height: 25px;" data-feather="map-pin"></i> <?php echo ($data_event->data->place && trim($data_event->data->place)) ? $data_event->data->place : 'Место не указано'; ?>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
              <div class="card-body mx-auto">
                <i class="link-icon mr-1 text-info" style="width: 25px; height: 25px;" data-feather="calendar"></i> <?php echo date('H:s d.m.Y', strtotime($data_event->data->start_datetime_event)); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="h3 mb-0"><?php echo $data_event->data->name; ?></h5>
              </div>
              <div class="card-body">
                  <?php

                  $data_description = $data_event->data->description;
                  // $data_description = str_replace('<script', '', $data_event->data->description);
                  // $data_description = str_replace('</script', '', $data_event->data->description);
                  // $data_description = str_replace('[DATA', '', $data_event->data->description);

                  while (strrpos($data_description, "<script")) {
                      if(strrpos($data_description, "</script")){
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "<script"), (strrpos($data_description, "</script") - strrpos($data_description, "<script")+9));
                      } else {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "<script"), 8);
                      }
                  }

                  while (strrpos($data_description, "</script>")) {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "</script>"), 9);
                  }
                  while (strrpos($data_description, "[DATA")) {
                      $data_description = substr_replace($data_description, '', strrpos($data_description, "[DATA"), 5);
                  }
                  while (strrpos($data_description, "<?xml")) {
                      if(strrpos($data_description, "?>")){
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "<?xml"), (strrpos($data_description, "?>") - strrpos($data_description, "<?xml")+2));
                      } else {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "<?xml"), 5);
                      }
                  }
                  while (strrpos($data_description, "<xml>")) {
                      if(strrpos($data_description, "</xml>")){
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "<xml>"), (strrpos($data_description, "</xml>") - strrpos($data_description, "<xml>")+6));
                      } else {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "<xml>"), 5);
                      }
                  }
                  while (strrpos($data_description, "?>")) {
                      $data_description = substr_replace($data_description, '', strrpos($data_description, "?>"), 2);
                  }
                  while (strrpos($data_description, "</xml>")) {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "</xml>"), 6);
                  }
                  while (strrpos($data_description, "StartFragment")) {
                      if(strrpos($data_description, "EndFragment")){
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "StartFragment"), (strrpos($data_description, "EndFragment") - strrpos($data_description, "StartFragment")+13));
                      } else {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "StartFragment"), 13);
                      }
                  }
                  while (strrpos($data_description, "EndFragment")) {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "EndFragment"), 11);
                  }
                  while (strrpos($data_description, "[endif]")) {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "[endif]"), 7);
                  }
                  while (strrpos($data_description, "<--")) {
                      if(strrpos($data_description, "-->")){
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "<--"), (strrpos($data_description, "<--") - strrpos($data_description, "-->")+3));
                      } else {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "<--"), 3);
                      }
                  }
                  while (strrpos($data_description, "-->")) {
                        $data_description = substr_replace($data_description, '', strrpos($data_description, "[endif]"), 3);
                  }

                  echo $data_description;

                  ?>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php } ?>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
