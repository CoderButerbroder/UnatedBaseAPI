<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Настройки нагрузка - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>

<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else { ?>

  <nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Система</a></li>
      <li class="breadcrumb-item active" aria-current="page">Нагрузка</li>
    </ol>
  </nav>

  <div class="row">
      <div class="col-xl-12 stretch-card">
        <div class="col-xl-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body" style="min-height: 420px; max-height: 420px;">
              <h6 class="card-title">Общая нагрузка по методам (apex radar)</h6>
              <div id="apexBar"></div>
              <div id="spinner_apexBar" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
        </div>
            <!-- <?php // json_encode($settings->get_log_api_response_group_by(false,'year')); ?> -->

            <?php //echo json_encode($settings->get_log_api_response_group_by_method());?>
      </div>
  </div>

  <script type="text/javascript">

  var opt_chart_load = {
    series: [{
      data: []
    }],
    chart: {
      type: 'bar',
      height: 350
    },
    plotOptions: {
      bar: {
        horizontal: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    xaxis: {
      categories: [],
    }
  };

  $(document).ready(function() {
    $.getJSON('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/assets/vendors/apexcharts/ru.json', function(data) {
      <?php
        $arr_data_method = $settings->get_log_api_response_group_by_method();

        $arr_result = (object) [];
        $arr_result->data = [];
        $arr_result->xaxis = [];

        foreach($arr_data_method as $key => $value){
          array_push($arr_result->data, $value->sum);
          array_push($arr_result->xaxis, $value->method);
        }

        echo "var data_method_str = '".json_encode($arr_result, JSON_UNESCAPED_UNICODE)."';";
      ?>
      if (IsJsonString(data_method_str)){
        var data_method = JSON.parse(data_method_str);
        opt_chart_load["series"][0]["data"] = Object.values(data_method["data"]);
        opt_chart_load["xaxis"]["categories"] = Object.values(data_method["xaxis"]);
        opt_chart_load["locales"] = [data];
        var chart = new ApexCharts(document.querySelector('#apexBar'), opt_chart_load);
        chart.render();
        $('#spinner_apexBar').hide('fast');
      } else {
        alerts('warning', 'Ошибка', 'Попробуйте позже');
      }

    });
  });


  </script>


<?php }  ?>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
