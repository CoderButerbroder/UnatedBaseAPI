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

  $arr_data_users = $settings->get_count_all_users();
  $arr_result_usersusers = (object) array();
  $arr_result_usersusers->name = array_keys($arr_data_users);
  $arr_result_usersusers->data = (array) $arr_result_usersusers->data;
  foreach($arr_data_users as $key => $value){
    array_push($arr_result_usersusers->data, $value);
  }



  $data_count_company_period = $settings->get_count_entity_groupby_time_reg('month');
  $data_count_company_SK_period = $settings->get_count_entity_skolkovo_groupby_time_reg('month');
  $data_count_company_FSI_period = $settings->get_count_entity_fci_groupby_time_reg('month');
  $data_count_company_EXPORT_period = $settings->get_count_entity_export_groupby_time_reg('month');

  function period($a, $b) {
    if(isset($a->dayd)){
      $date1 = $a->dayd.'.'.$a->monthd.'.'.$a->yeard;
      $date2 = $a->dayd.'.'.$b->monthd.'.'.$b->yeard;
    } else {
      $date1 = '00.'.$a->monthd.'.'.$a->yeard;
      $date2 = '00.'.$b->monthd.'.'.$b->yeard;
    }
      if (( strtotime( $date1 ) == strtotime( $date2 ) )) {
          return 0;
      }
      return ( strtotime( $date1 ) < strtotime( $date2 ) ) ? -1 : 1;
  }

  $arr_select_month = array('1' => (object) array('name' => 'Январь', ),
                            '2' => (object) array('name' => 'Февраль', ),
                            '3' => (object) array('name' => 'Март', ),
                            '4' => (object) array('name' => 'Апрель', ),
                            '5' => (object) array('name' => 'Май', ),
                            '6' => (object) array('name' => 'Июнь', ),
                            '7' => (object) array('name' => 'Июль', ),
                            '8' => (object) array('name' => 'Август', ),
                            '9' => (object) array('name' => 'Сентябрь', ),
                            '10' => (object) array('name' => 'Октябрь', ),
                            '11' => (object) array('name' => 'Ноябрь', ),
                            '12' => (object) array('name' => 'Декабрь' ) );

  $arr_merge_count_company = array_merge($data_count_company_period, $data_count_company_SK_period, $data_count_company_FSI_period, $data_count_company_EXPORT_period);
  usort($arr_merge_count_company, 'period');

  $temp_data_foreach_count = 0;
  $temp_data_arr_foerch = (object) array();

  foreach ($arr_merge_count_company as $key => $value) {
    $temp_name_to_obj = $value->monthd.$value->yeard;
    if(!isset($temp_data_arr_foerch->$temp_name_to_obj)) {
      $temp_data_arr_foerch->$temp_name_to_obj =$arr_select_month[$value->monthd]->name.' '.$value->yeard;
      $temp_data_foreach_count++;
    }
  }

  $arr_result_company_count = (object) [];
  $arr_result_company_count->data = [];
  $arr_result_company_count->time = [];

  function search_value($value_arr, $value_search){
    foreach ($value_arr as $key2 => $value2) {
      if ($value_search == $value2->monthd.$value2->yeard) {
        return (object) array ( "response" => true , "sum" => $value2->sum);
      }
    }
    return array ( "response" => false );
  }

  $temp_data = (object)  array();
  $temp_data->name = 'Компании';
  $temp_data->data = [];
  $temp_data->time = [];

  foreach ($temp_data_arr_foerch as $key => $value) {
    $temp_search = search_value($data_count_company_period,$key);
    if($temp_search->response){
       array_push( $temp_data->data, $temp_search->sum );
    } else {
       array_push( $temp_data->data, "0" );
    }
  }
  array_push( $arr_result_company_count->data, $temp_data );

  $temp_data = (object)  array();
  $temp_data->name = 'Компании Сколково';
  $temp_data->data = [];

  foreach ($temp_data_arr_foerch as $key => $value) {
    $temp_search = search_value($data_count_company_SK_period,$key);
    if($temp_search->response){
       array_push( $temp_data->data, $temp_search->sum );
    } else {
       array_push( $temp_data->data, "0" );
    }
  }
  array_push( $arr_result_company_count->data, $temp_data );

  $temp_data = (object)  array();
  $temp_data->name = 'Компании ФСИ';
  $temp_data->data = [];

  foreach ($temp_data_arr_foerch as $key => $value) {
    $temp_search = search_value($data_count_company_FSI_period,$key);
    if($temp_search->response){
       array_push( $temp_data->data, $temp_search->sum );
    } else {
       array_push( $temp_data->data, "0" );
    }
  }

  array_push( $arr_result_company_count->data, $temp_data );


  $temp_data = (object)  array();
  $temp_data->name = 'Компании Экспорт';
  $temp_data->data = [];

  foreach ($temp_data_arr_foerch as $key => $value) {
    $temp_search = search_value($data_count_company_EXPORT_period,$key);
    if($temp_search->response){
       array_push( $temp_data->data, $temp_search->sum );
    } else {
       array_push( $temp_data->data, "0" );
    }
  }

  array_push( $arr_result_company_count->data, $temp_data );


  $temp_data = [];
  foreach ($temp_data_arr_foerch as $key => $value) {
    array_push( $arr_result_company_count->time, $value);
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
              <div class="card-body" style="max-height: 420px;">
                    <div class="" id="div_chart_line_branch" >
                    </div>
              </div>
            </div>
        </div>
        <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-body" style="/*max-height: 420px;*/">
                    <div class="" id="div_chart_line_user" >
                    </div>
              </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
      <div class="offset-6 col-md-6 stretch-card">
          <div class="card">
            <div class="card-body" style="/*max-height: 420px;*/">
                  <div class="" id="div_chart_line_company" >
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
        var data_users = JSON.parse('<?php echo json_encode($arr_result_usersusers ,JSON_UNESCAPED_UNICODE); ?>');
        var data_company = JSON.parse('<?php echo json_encode($arr_result_company_count ,JSON_UNESCAPED_UNICODE); ?>');

        // Apex Donut chart start
        var options_donut = {
          chart: {
            height: 400,
            type: "donut",
            locales: [ru_loc],
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
        var chart = new ApexCharts(document.querySelector("#div_chart_line_branch"), options_donut);
        chart.render();

        var options2 = {
          chart: {
              height: 350,
              type: "line",
              stacked: false,
              locales: [ru_loc],
              defaultLocale: 'ru',
            },
            dataLabels: {
              enabled: false
            },
            series: [
              {
                name: "Физ. Лица",
                data: data_users["data"]
              },
            ],
            stroke: {
              width: [4, 4]
            },
            plotOptions: {
              bar: {
                columnWidth: "20%"
              }
            },
            xaxis: {
              categories: data_users["name"]
            },
            legend: {
              horizontalAlign: "left",
              offsetX: 40
            }
        };
        var apexLineChart_user = new ApexCharts(document.querySelector("#div_chart_line_user"), options2);
        apexLineChart_user.render();

        var options3 = {
          chart: {
              height: 350,
              type: "line",
              stacked: false,
              locales: [ru_loc],
              defaultLocale: 'ru',
            },
            dataLabels: {
              enabled: false
            },
            series: data_company["data"],
            stroke: {
              width: [4, 4]
            },
            plotOptions: {
              bar: {
                columnWidth: "20%"
              }
            },
            xaxis: {
              categories: data_company["time"]
            },
            legend: {
              horizontalAlign: "left",
              offsetX: 40
            }
        };
        var apexLineChart_company = new ApexCharts(document.querySelector("#div_chart_line_company"), options3);
        apexLineChart_company.render();




    });
  });

</script>


  <?php } ?>


  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
