<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>

<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else {

  $id_yandex = $settings->get_global_settings('id_app_yandex_disk');
  $token_yandex = $settings->get_global_settings('token_app_yandex_disk');
  $path_yandex_disk = $settings->get_global_settings('path_yandex_disk');



  $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources?limit=9999&path=' . urlencode($path_yandex_disk));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token_yandex));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HEADER, false);
  $res = curl_exec($ch);
  curl_close($ch);
  $obj_app = json_decode($res);

  $used_space = 0;

  if( $obj_app->message == 'Не авторизован' || $obj_app->error == "UnauthorizedError") {
    $flag_unauth = true;
  } else {
  $flag_unauth = false;

  foreach ($obj_app->_embedded->items as $key => $value) {
    $used_space += $value->size;
  }

  $free_size = (30 - (round($used_space / 1024 / 1024 / 1024, 2)));
  $used_size = ((round($used_space / 1024 / 1024 / 1024, 2)) );

  $arr_result_disk = (object) [];
  $arr_result_disk->name =  [ '0' =>  'Свободно '.$free_size." Гб.", '1' => 'Занятно'.$used_size." Гб."  ];



  $arr_result_disk->data =  [ '0' => $free_size ,
                              '1' => $used_size ];

  }

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/panel/system/integration/">Настройки Интеграций</a></li>
    <li class="breadcrumb-item active" aria-current="page">Интеграция API YANDEX DISK</li>
  </ol>
</nav>

<!--  -->

<div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5><small><a href="https://oauth.yandex.ru/">Где получить?</a></small></h5>
          <div class="mt-2">
            <form method="post"  onsubmit="upd_key(this); return false;" class="form-inline" style="width: 100%">
              <div class="row" style="margin: 0; width: 100%; padding: 0;">
                <div class="col-md-12">
                  <label for="exampleInputEmail1">ID APP</label>
                  <input type="text" class="form-control" style="width: 100%" name="id_app" placeholder="Обязательное поле" required value="<?php echo $id_yandex;?>">
                </div>
                <div class="col-md-12 mt-2">
                  <label for="exampleInputEmail1">Token APP</label>
                  <input type="text" class="form-control" style="width: 100%" name="token_app" placeholder="Обязательное поле" required value="<?php echo $token_yandex;?>">
                </div>
              <div class="col-md-12 mt-2">
                  <button type="submit" name="submit" class="btn btn-success btn-block">Сохранить</button>
              </div>
            </div>
           </form>
         </div>
        </div>
      </div>

      <?php
            if (!$flag_unauth) { ?>

              <div class="card">
                <div class="card-body">
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Размер</th>
                        <th scope="col">Дата</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $temp_count = 0;
                        foreach ($obj_app->_embedded->items as $key => $value) {
                          $temp_count++;
                          echo "<tr>";
                            echo "<td>".$temp_count."</td>";
                            echo "<td>".$value->name."</td>";
                            echo "<td>".((round($value->size / 1024 / 1024, 2)) )." Мб.</td>";
                            echo "<td>".date('H:i d.m.Y', strtotime($value->created))."</td>";
                          echo "</tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

      <?php } ?>



    </div>
    <div class="col-md-6" >
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-10 my-auto">
              Загруженно <?php   echo count($obj_app->_embedded->items); ?> Бекапов
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="" id="div_YD">
            <?php
              if ($flag_unauth) {
                echo '<div class="alert alert-danger" role="alert">
                        Ошибка авторизации
                      </div>';
              }
            ?>
          </div>
        </div>
      </div>
    </div>
</div>

<?php }  ?>

<script type="text/javascript">
  var options_donut = {
    chart: {
      height: 400,
      type: "donut",
      defaultLocale: 'ru',
      parentHeightOffset: 0
    },
    grid: {
            borderColor: "rgba(77, 138, 240, .1)",
            padding: {
              bottom: -15
            }
          },
    stroke: {
      colors: ['rgba(0,0,0,0)']
    },
    legend: {
      position: 'top',
      horizontalAlign: 'center'
    },
    dataLabels: {
      enabled: false
    },
    noData: {
       text: 'Загрузка...'
     },
  };


  $(document).ready(function() {

    $.getJSON('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/assets/vendors/apexcharts/ru.json', function(data) {
      options_donut["chart"]["locales"] = [data];
      ar_data = '<?php if(!$flag_unauth) echo json_encode($arr_result_disk, JSON_UNESCAPED_UNICODE); ?>';
      if (IsJsonString(ar_data)) {
        ar_data = JSON.parse(ar_data);
        options_donut["series"] = ar_data["data"];
        options_donut["labels"] = ar_data["name"];
        var chart = new ApexCharts(document.querySelector('#div_YD'), options_donut);
        chart.render();
      }

    });

  });


  function upd_key(form) {
    $.ajax({
      async: true,
      cache: false,
      type: 'POST',
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/system/integration/yandex_disk/action/upd_key',
      data: $(form).serialize(),
      success: function(result, status, xhr) {
        if (IsJsonString(result)) {
          ar_data = JSON.parse(result);
          if (ar_data["response"]) {
            alerts('success', ar_data["description"], '');
            setTimeout(function (){location.reload()}, 1500);

          } else {
            alerts('warning', 'Ошибка', ar_data["description"]);
          }
        } else {
          alerts('warning', 'Ошибка', result);
        }
      },
      error: function(jqXHR, textStatus) {
        alerts('error', 'Ошибка подключения', 'Попробуйте позже');
      }
    });
  }

  function change_view_pass(el) {
      if ($(el.previousElementSibling).attr('type') == 'password'){
        $(el).removeClass();
        $(el).addClass("icon_pass far fa-eye-slash");
        $(el.previousElementSibling).attr('type', 'text');
      } else {
        $(el).removeClass();
        $(el).addClass("icon_pass far fa-eye");
        $(el.previousElementSibling).attr('type', 'password');
      }
    return false;
  };


</script>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
