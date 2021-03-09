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

  <div class="row mt-3 mb-3">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-header">
             <div class="row align-items-start mb-2 my-auto">
              <div class="col-md-7 my-auto">
                <h5 class="card-title my-auto">НАГРУЗКА ПО МЕТОДАМ по Времени</h5>
              </div>
              <div class="col-md-5 d-flex justify-content-md-end" style="display:none;">
                <div class="btn-group mb-3 mb-md-0" name_chart="date_method" role="group" aria-label="Basic example" id="btn_period_chart_line_date_method" >
                  <button type="button" class="btn_period_chart btn btn-outline-primary" value="day">День</button>
                  <button type="button" class="btn_period_chart btn btn-outline-primary" value="week">Неделя</button>
                  <button type="button" class="btn_period_chart btn btn-primary" value="month" active>Месяц</button>
                  <button type="button" class="btn_period_chart btn btn-outline-primary" value="year">Год</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body" style="min-height: 420px; /*max-height: 420;*/">
            <div class="" id="div_chart_line_date_method" >
              <div id="spinner_chart_line_date_method" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>

  <div class="row mt-3 mb-3">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-header">
             <div class="row align-items-start mb-2 my-auto">
              <div class="col-md-7 my-auto">
                <h5 class="card-title my-auto">НАГРУЗКА ПО МЕТОДАМ по Времени</h5>
              </div>
              <div class="col-md-5 d-flex justify-content-md-end" style="display:none;">
                <div class="btn-group mb-3 mb-md-0" name_chart="date_method_Scatter" role="group" aria-label="Basic example" id="btn_period_chart_line_date_method_Scatter" >
                  <button type="button" class="btn_period_chart btn btn-outline-primary" value="day">День</button>
                  <button type="button" class="btn_period_chart btn btn-outline-primary" value="week">Неделя</button>
                  <button type="button" class="btn_period_chart btn btn-primary" value="month" active>Месяц</button>
                  <button type="button" class="btn_period_chart btn btn-outline-primary" value="year">Год</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body" style="min-height: 420px; /*max-height: 420;*/">
            <div class="" id="div_chart_line_date_method_Scatter" >
              <div id="spinner_chart_line_date_method_Scatter" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>

  <div class="row">

    <div class="col-md-6 stretch-card">
        <div class="card">
          <div class="card-header">
             <div class="row align-items-start mb-2 my-auto">
              <div class="col-md-7 my-auto">
                <h5 class="card-title my-auto">ОБЩАЯ НАГРУЗКА ПО МЕТОДАМ</h5>
              </div>
            </div>
          </div>
          <div class="card-body" style="min-height: 420px; max-height: 420px;">
            <div class="" id="div_chart_line_load_method" >
              <div id="spinner_chart_line_load_method" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>

  <script type="text/javascript">

  var opt_chart_load = {
    series: [{
      data: []
    }],
    chart: {
      type: 'bar',
      height: 350,
      defaultLocale: 'ru',
      locales: [],
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

  var options_line_ur = {
    chart: {
        height: 350,
        type: "line",
        stacked: false,
        locales: [],
        defaultLocale: 'ru',
      },
      dataLabels: {
        enabled: false
      },
      series: [],
      stroke: {
        width: [4, 4]
      },
      plotOptions: {
        bar: {
          columnWidth: "20%"
        }
      },
      xaxis: {
        categories: []
      },
      legend: {
        horizontalAlign: "left",
        offsetX: 40
      }
  };

  var options_scatter = {
    series: [ ],
      chart: {
      height: 350,
      type: 'scatter',
      zoom: {
        type: 'xy'
      },
      locales: [],
      defaultLocale: 'ru',
    },
    dataLabels: {
      enabled: false,
    },
    grid: {
      xaxis: {
        lines: {
          show: true
        }
      },
      yaxis: {
        lines: {
          show: true
        }
      },
    },
    // markers:{
    //   colors: ['#F44336', '#E91E63', '#9C27B0']
    // },
    xaxis: {
      categories: [],
      // tickAmount: 10,
      // type: 'datetime',
    },
  };

  $(document).ready(function() {
    $.getJSON('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/assets/vendors/apexcharts/ru.json', function(data) {

      var arr_chart = ['load_method', 'date_method', 'date_method_Scatter'];

      arr_chart.forEach((element) => {
        var btn_act = $('#btn_period_chart_line_'+element+' [active]')[0];
        var btn_value = (btn_act != undefined) ? $(btn_act).val() : "month";
        activate_charts(element, btn_value, data);
      });

      $('.btn_period_chart').on('click', function() {
        if ($(this).hasClass('btn-primary')) {
          return 0;
        }
        var div_list_btn = $(this).parent();
        var btn_child = $(div_list_btn).children();
        var name_chart = $(div_list_btn).attr('name_chart');
        // console.log(name_chart);

        $(btn_child).each(function(elem) {
          var temp_btn = $(btn_child)[elem];
          // console.log(temp_btn);
          $(temp_btn).removeClass('btn-primary');
          if(!$(temp_btn).hasClass('btn-outline-primary')) $(temp_btn).addClass('btn-outline-primary');
          $(temp_btn).removeAttr('active');
        });
        if(!$(this).hasClass('btn-primary')) $(this).addClass('btn-primary');
        $(this).removeClass('btn-outline-primary');
        $(this).attr('active', 'active');

        var child_body_card = $('#div_chart_line_'+name_chart).children();
        $(child_body_card).each(block => {
          var temp_div = $(child_body_card)[block];
          if (!$(temp_div).hasClass('spinner-border')) {
            $(temp_div).remove();
          };
        })

        activate_charts(name_chart, $(this).val(), data);
      });

      // if (IsJsonString(data_method_str)){
      //   var data_method = JSON.parse(data_method_str);
      //   opt_chart_load["series"][0]["data"] = Object.values(data_method["data"]);
      //   opt_chart_load["xaxis"]["categories"] = Object.values(data_method["xaxis"]);
      //   opt_chart_load["locales"] = [data];
      //   var chart = new ApexCharts(document.querySelector('#apexBar'), opt_chart_load);
      //   chart.render();
      //   $('#spinner_apexBar').hide('fast');
      // } else {
      //   alerts('warning', 'Ошибка', 'Попробуйте позже');
      // }

    });
  });


  function activate_charts(element, period, ru_local) {
    $('#spinner_chart_line_'+ element).show("fast");
    $('#btn_period_chart_line_'+ element).hide("fast");
    $.ajax({
      async: true,
      cache: false,
      type: 'POST',
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/system/load/actions/get_data_chart',
      data: { "chart" : element, "period" : period },
      success: function(result, status, xhr) {
        if (IsJsonString(result)) {
          ar_data = JSON.parse(result);
          if(ar_data["response"] == false) {
            alerts('warning', ar_data["description"], 'Попробуйте позже');
          } else {
          $('#spinner_chart_line_'+ element).hide("fast");
          if (element == 'load_method') {
            var options_chart = opt_chart_load;
              options_chart["series"][0]["data"] = Object.values(ar_data["data"]);
              options_chart["xaxis"]["categories"] = Object.values(ar_data["xaxis"]);
          }
          if (element == 'date_method') {
            var options_chart = options_line_ur;
            options_chart["series"] = Object.values(ar_data["data"]);
            options_chart["xaxis"]["categories"] = Object.values(ar_data["time"]);
            // options_chart["colors"] = Object.values(ar_data["colors"]);
          }
          if (element == 'date_method_Scatter') {
            var options_chart = options_scatter;
            options_chart["series"] = Object.values(ar_data["data"]);
            options_chart["xaxis"]["categories"] = Object.values(ar_data["time"]);
            // options_chart["colors"] = Object.values(ar_data["colors"]);

          }

          options_chart["chart"]["locales"] = [ru_local];
          var chart = new ApexCharts(document.querySelector('#div_chart_line_'+element), options_chart);
          chart.render();
          $('#btn_period_chart_line_'+ element).show("fast");
          }
        }
      },
      error: function(jqXHR, textStatus) {
        alerts('error', 'Ошибка подключения', 'Попробуйте позже');
      }
    });
  }


  </script>


<?php }  ?>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
