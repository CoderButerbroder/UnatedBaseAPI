  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
  <?php /*тут метатеги*/?>
	<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');

  $arr_data_branch = $settings->get_count_entity_branch();


  $arr_result_branch = (object) array();
  $arr_result_branch->name = array_keys($arr_data_branch);
  $arr_result_branch->data = (array) $arr_result_branch->data;
  foreach($arr_data_branch as $key => $value){
    array_push($arr_result_branch->data, $value);
  }




  ?>

  <?php if (!$data_user_rules->dashboard->rule->view_dashboard->value) {?>

      <div class="container-fluid text-center">
          <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
      </div>

  <?php } else { ?>


    <div class="row">
        <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-body">
                    <div class="" id="div_chart_line">
                    </div>
              </div>
            </div>
        </div>
    </div>

<script type="text/javascript">

  $(document).ready(function() {

    $.getJSON('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/assets/vendors/apexcharts/ru.json', function(data) {
        var ru_loc = data
        var data_branch = JSON.parse('<?php echo json_encode($arr_result_branch ,JSON_UNESCAPED_UNICODE); ?>');

        // Apex Donut chart start
        var options = {
          chart: {
            height: 500,
            type: "donut",
            locales: [ru_loc],
            defaultLocale: 'ru',
          },
          stroke: {
            colors: ['rgba(0,0,0,0)']
          },
          //colors: ["#f77eb9", "#7ee5e5", "#4d8af0", "#fbbc06"],
          legend: {
            position: 'top',
            horizontalAlign: 'center'
          },
          dataLabels: {
            enabled: false
          },
          title: {
                text: 'Юр. лица по отрослям',
          },
          noData: {
             text: 'Загрузка...'
           },
          series: data_branch["data"],
           labels: data_branch["name"],

          //series: [44, 55, 13, 33],
          //labels: ['Apple', 'Mango', 'Orange', 'Watermelon']
        };

        var chart = new ApexCharts(document.querySelector("#div_chart_line"), options);
        chart.render();
    });
  });

</script>


  <?php } ?>


  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
