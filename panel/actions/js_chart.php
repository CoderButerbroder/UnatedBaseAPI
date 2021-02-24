<?php


?>


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
var options_line_user = {
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
var ru_loc = [];

  $(document).ready(function() {
    $.getJSON('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/assets/vendors/apexcharts/ru.json', function(data) {
      ru_loc = data;

    var arr_chart = ['branch', 'user', 'company', 'FSI', 'SK', 'event'];

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
        // console.log(temp_div);
        if (!$(temp_div).hasClass('spinner-border')) {
          $(temp_div).remove();
        };
      })


      // console.log(ru_loc);
      // console.log(name_chart);
      // console.log($(this).val());
      // console.log(ru_loc);

      activate_charts(name_chart, $(this).val(), ru_loc);

      // console.log(this);
    });

  });



});

// function new_period(list_btn, btn, name) {
//   // $(btn).index()
//   console.log(btn);
//   // $('#div_chart_line_'+name).children().each(function(elem) {
//   //   if (!$(this).hasClass('spinner-border')) {
//   //     !$(this).remove()
//   //   });
//   // });
//   //
//   //
//   //
//   // var btn_act = $('#btn_period_chart_line_'+element+' [active]')[0];
//   // var btn_value = (btn_act != undefined) ? $(btn_act).val() : "mounth";
//   // activate_charts(element, btn_value, data);
//
// }


function activate_charts(element, period, ru_local) {
  $('#spinner_chart_line_'+ element).show("fast");
  $('#btn_period_chart_line_'+ element).hide("fast");
  $.ajax({
    async: true,
    cache: false,
    type: 'POST',
    url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/actions/get_data_chart',
    data: { "chart" : element, "period" : period },
    success: function(result, status, xhr) {
      if (IsJsonString(result)) {
        ar_data = JSON.parse(result);
        if(ar_data["response"] == false) {
          alerts('warning', ar_data["description"], 'Попробуйте позже');
        } else {
        $('#spinner_chart_line_'+ element).hide("fast");
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
        if (element == 'FSI' || element == 'SK' || element == 'event') {
          var options_chart = options_line_ur;
          options_chart["series"] = Object.values(ar_data["data"]);
          options_chart["xaxis"]["categories"] = Object.values(ar_data["time"]);
        }
        if (element == 'user') {
          var options_chart = options_line_user;
          options_chart["series"][0]["data"] = Object.values(ar_data["data"]);
          options_chart["xaxis"]["categories"] = Object.values(ar_data["name"]);
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
