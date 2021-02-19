<?php


?>


<script type="text/javascript">

  $(document).ready(function() {
    $.getJSON('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/assets/vendors/apexcharts/ru.json', function(data) {
    var ru_loc = data;
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
    var options_line_user = {
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
            data: []
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
          categories: []
        },
        legend: {
          horizontalAlign: "left",
          offsetX: 40
        }
    };
    var options_line_ur = {
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

    var arr_chart = ['branch', 'user', 'company'];

    arr_chart.forEach((element) => {
      $.ajax({
        type: 'POST',
        url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/actions/get_data_chart',
        data: { "chart" : element },
        success: function(result, status, xhr) {
          if (IsJsonString(result)) {
            ar_data = JSON.parse(result);
            $('#div_chart_line_'+ element).html('');
            if (element == 'branch') {
              var options_chart = options_donut;
              options_chart["series"] = Object.values(ar_data["data"]);
              options_chart["labels"] = Object.values(ar_data["name"]);
            }
            if (element == 'company') {
              var options_chart = options_line_ur;
              options_chart["series"] = Object.values(ar_data["data"]);
              options_chart["xaxis"]["categories"] = Object.values(ar_data["time"]);
            }
            if (element == 'user') {
              var options_chart = options_line_user;
              options_chart["series"][0]["data"] = Object.values(ar_data["data"]);
              options_chart["xaxis"]["categories"] = Object.values(ar_data["name"]);
            }

            var chart = new ApexCharts(document.querySelector('#div_chart_line_'+element), options_chart);
            chart.render();
          }
        },
        error: function(jqXHR, textStatus) {
          // alerts('error', 'Ошибка подключения', 'Попробуйте позже');
        }
      });
    });

  });
});

</script>

<script type="text/javascript">

    // $.getJSON('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/assets/vendors/apexcharts/ru.json', function(data) {
    //     var ru_loc = data;
    //     console.log(data);
        // var data_branch = JSON.parse('<?php echo json_encode($arr_result_branch ,JSON_UNESCAPED_UNICODE); ?>');
        // var data_users = JSON.parse('<?php echo json_encode($arr_result_usersusers ,JSON_UNESCAPED_UNICODE); ?>');
        // var data_company = JSON.parse('<?php echo json_encode($arr_result_company_count ,JSON_UNESCAPED_UNICODE); ?>');


        // // Apex Donut chart start

        // var chart = new ApexCharts(document.querySelector("#div_chart_line_branch"), options_donut);
        // chart.render();
        //
        // var options2 = {
        //   chart: {
        //       height: 350,
        //       type: "line",
        //       stacked: false,
        //       locales: [ru_loc],
        //       defaultLocale: 'ru',
        //     },
        //     dataLabels: {
        //       enabled: false
        //     },
        //     series: [
        //       {
        //         name: "Физ. Лица",
        //         // data: data_users["data"]
        //         data: []
        //       },
        //     ],
        //     stroke: {
        //       width: [4, 4]
        //     },
        //     plotOptions: {
        //       bar: {
        //         columnWidth: "20%"
        //       }
        //     },
        //     xaxis: {
        //       // categories: data_users["name"]
        //       categories: []
        //     },
        //     legend: {
        //       horizontalAlign: "left",
        //       offsetX: 40
        //     }
        // };
        // var apexLineChart_user = new ApexCharts(document.querySelector("#div_chart_line_user"), options2);
        // apexLineChart_user.render();
        //
        // var options3 = {
        //   chart: {
        //       height: 350,
        //       type: "line",
        //       stacked: false,
        //       locales: [ru_loc],
        //       defaultLocale: 'ru',
        //     },
        //     dataLabels: {
        //       enabled: false
        //     },
        //     series: [],
        //     // series: data_company["data"],
        //     stroke: {
        //       width: [4, 4]
        //     },
        //     plotOptions: {
        //       bar: {
        //         columnWidth: "20%"
        //       }
        //     },
        //     xaxis: {
        //       // categories: data_company["time"]
        //       categories: []
        //     },
        //     legend: {
        //       horizontalAlign: "left",
        //       offsetX: 40
        //     }
        // };
        // var apexLineChart_company = new ApexCharts(document.querySelector("#div_chart_line_company"), options3);
        // apexLineChart_company.render();

  //
  //   });
  // });

</script>
