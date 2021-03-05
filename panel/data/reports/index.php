<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Все отчеты - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="#">Отчеты</a></li>
    <li class="breadcrumb-item active" aria-current="page">Все отчеты</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div id="acc_report" class="accordion" role="tablist">
              <div class="card">
                <div class="card-header" role="tab" id="heading_report_ur">
                  <h6 class="mb-0">
                    <a data-toggle="collapse" href="#collapse_report_ur" aria-expanded="true" aria-controls="collapse_report_ur">
                      Отчет по Юр. лицам
                    </a>
                  </h6>
                </div>
                <div id="collapse_report_ur" class="collapse" role="tabpanel" aria-labelledby="heading_report_ur" data-parent="#acc_report">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_fiz_1').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Общие показатели</li>
                      <li style="cursor:pointer;" class="list-group-item"><a style="color:black" href="javascript:void(0)" onclick="window.open('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/data/reports/actions/report_users_count_period')"><i
                            class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Общая выгрузка с разделением на месяцы</a></li>
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_category').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Выгрузка по критерию
                      </li>
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_specific_category').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Выгрузка по
                        определенному критерию</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" role="tab" id="heading_report_fiz">
                  <h6 class="mb-0">
                    <a data-toggle="collapse" href="#collapse_report_fiz" aria-expanded="true" aria-controls="collapse_report_fiz">
                      Отчет по Физ. лицам
                    </a>
                  </h6>
                </div>
                <div id="collapse_report_fiz" class="collapse" role="tabpanel" aria-labelledby="heading_report_fiz" data-parent="#acc_report">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li style="cursor:pointer;" class="list-group-item"><a style="color:black" href="javascript:void(0)" onclick="window.open('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/data/reports/actions/report_users')"><i
                            class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Общая выгрузка Пользователей</a></li>
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_period_user').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>Количественные
                        показатели по периоду</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" role="tab" id="heading_report_fsi">
                  <h6 class="mb-0">
                    <a data-toggle="collapse" href="#collapse_report_fsi" aria-expanded="true" aria-controls="collapse_report_fsi">
                      Отчет по ФСИ
                    </a>
                  </h6>
                </div>
                <div id="collapse_report_fsi" class="collapse" role="tabpanel" aria-labelledby="heading_report_fsi" data-parent="#acc_report">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_FSI').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>
                        Фонды, Институты развития
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" role="tab" id="heading_report_cervices">
                  <h6 class="mb-0">
                    <a data-toggle="collapse" href="#collapse_report_cervices" aria-expanded="true" aria-controls="collapse_report_cervices">
                      Отчет по Сервисам
                    </a>
                  </h6>
                </div>
                <div id="collapse_report_cervices" class="collapse" role="tabpanel" aria-labelledby="heading_report_cervices" data-parent="#acc_report">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_cervices').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>
                        Количественные показатели сервисов</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" role="tab" id="heading_report_event">
                  <h6 class="mb-0">
                    <a data-toggle="collapse" href="#collapse_report_event" aria-expanded="true" aria-controls="collapse_report_event">
                      Мероприятия
                    </a>
                  </h6>
                </div>
                <div id="collapse_report_event" class="collapse" role="tabpanel" aria-labelledby="heading_report_event" data-parent="#acc_report">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_event').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>
                        Общие показатели</li>
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_events').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>
                        Выгрузка мероприятий</li>
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_event').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>
                        Данные по мероприятию</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" role="tab" id="heading_report_tboil">
                  <h6 class="mb-0">
                    <a data-toggle="collapse" href="#collapse_report_tboil" aria-expanded="true" aria-controls="collapse_report_tboil">
                      Точка Кипения (ТК СПБ)
                    </a>
                  </h6>
                </div>
                <div id="collapse_report_tboil" class="collapse" role="tabpanel" aria-labelledby="heading_report_tboil" data-parent="#acc_report">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_tboil').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>
                         Показатель работы ТК СПб</li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header" role="tab" id="heading_report_tboil_prom">
                  <h6 class="mb-0">
                    <a data-toggle="collapse" href="#collapse_report_tboil_prom" aria-expanded="true" aria-controls="collapse_report_tboil_prom">
                      Промышленная Точка Кипения (ПТК)
                    </a>
                  </h6>
                </div>
                <div id="collapse_report_tboil_prom" class="collapse" role="tabpanel" aria-labelledby="heading_report_tboil_prom" data-parent="#acc_report">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <div class="alert alert-info" role="alert">Отчеты по промышленной точке кипения, уже скоро!</div>
                      <!-- <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_tboil').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>
                         Показатель работы ТК СПб</li> -->
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
<script type="text/javascript">

  $(document).ready(function() {

    $('#select_specific_category2').on('select2:select', function (e) {
      var val = $(e.currentTarget).val();
      if (val == false || val == '') {
        alerts('warning', 'Ошибка', 'Укажите другой вариант');
        return false;
      } else {
        $('#button_generate_report').removeAttr('disabled');
      }
    });

    $('#select_specific_category1').on('select2:select', function (e) {
      var val = $(e.currentTarget).val();
      if (val == false || val == '') {
        alerts('warning', 'Ошибка', 'Укажите другой вариант');
        return false;
      } else {
        $('#spiner').removeClass('d-none');
        $.ajax({
          type: 'POST',
          url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>'+$(e.currentTarget).attr('action'),
          data: { "search" : val },
          success: function(result, status, xhr) {
            $('#spiner').addClass('d-none');
            if(IsJsonString(result)){
              arr = JSON.parse(result);
              alerts('warning', 'Ошибка', arr["description"]);
              $('#div_select_specific_category').slideUp();
              $('#button_generate_report').attr('disabled','disabled');
              return false;
            } else {
              $('#select_specific_category2').html(result);
              $('#div_select_specific_category').slideDown();
            }
          },
          error: function(jqXHR, textStatus) {
            $('#spiner').addClass('d-none');
            alerts('error', 'Ошибка подключения', 'Попробуйте позже');
          }
        });
      }
    });
  });


  function generate_report(form, modal_w, select_name) {
    var btn = form.elements["button"];
    var select = form.elements[select_name];
    if (!$(select).val()) {
      if (select_name == 'select_input_event') {
        alerts('warning', 'Укажите мероприятие', '');
        return false;
      }
      alerts('warning', 'Укажите временной диапозон', '');
      return false;
    }
    if($(form).attr('id') == 'modal_report_by_period_user' && $(form.elements["period_1"]).val().length() != 10 && $(form.elements["period_2"]).val().length() != 10) {
      alerts('warning', 'Укажите Диапозон дат', '');
      return false;
    }

    $('#spiner').removeClass('d-none');
    $(btn).attr('disabled','disabled');
    $.ajax({
      type: 'POST',
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>'+$(form).attr('action'),
      data: $(form).serialize(),
      dataType: 'binary',
      xhrFields: {
        'responseType': 'blob'
      },
      success: function(result, status, xhr) {
        $(btn).removeAttr('disabled');
        $('#spiner').addClass('d-none');
        response_h = xhr.getResponseHeader('Content-Type');
        if (response_h == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
          $('#'+modal_w).modal('hide');
          file_name = xhr.getResponseHeader('Content-Disposition').split("filename=")[1];
          file_name = file_name.substring(1, file_name.length - 1);
          file_name = file_name.replace(' ', '');
          var blob = new Blob([result], {type: xhr.getResponseHeader('Content-Type')});
          			var link = document.createElement('a');
          			link.href = window.URL.createObjectURL(result);
          			link.download = file_name;
          			link.click();
        } else {
          alerts('warning', 'Ошибка генерации', 'Попробуйте позже');
        }

      },
      error: function(jqXHR, textStatus) {
        $(btn).removeAttr('disabled');
        $('#spiner').addClass('d-none');
        alerts('error', 'Ошибка подключения', 'Попробуйте позже');
      }
    });
  };

$(document).ready(function() {

  $('#select_input_event').load('actions/get_parament_event');
  $('.input-daterange input').each(function() {
      <?php
       $js_timepicker = date('m/d/Y', strtotime($settings->get_min_max_users_time_reg('min') ));
       $arr_start_end_events = $settings->get_min_max_events_time_reg();
       $arr_js_timepicker = explode('/', $js_timepicker);
       // var_dump($arr_start_end_events);
       echo "var startDate = new Date('".$arr_js_timepicker[2]."', '".($arr_js_timepicker[0]-1)."', '".$arr_js_timepicker[1]."');";
       echo "var startDate2 = new Date('".$arr_js_timepicker[2]."', '".($arr_js_timepicker[0]-1)."', '".($arr_js_timepicker[1]+1)."');";
       echo "var endDate = new Date('".date('Y')."', '".(date('m')-1)."', '".(date('d')-1)."');";
       echo "var endDate2 = new Date('".date('Y')."', '".(date('m')-1)."', '".date('d')."');";
       echo "var start_event_date = new Date('".date('Y', strtotime(strtotime($arr_start_end_events->start)))."','".(date('m', strtotime($arr_start_end_events->start))-1)."','".(date('d', strtotime($arr_start_end_events->start))-1)."');";
       echo "var end_event_date = new Date('".date('Y', strtotime($arr_start_end_events->end))."','".(date('m', strtotime($arr_start_end_events->end))-1)."','".(date('d', strtotime($arr_start_end_events->end))-1)."');";
      ?>
      $(this).datepicker({
          format: "dd.mm.yyyy",
          todayHighlight: true,
          autoclose: true,
          language: "ru-RU",
          zIndexOffset: 1051
        });
      if($(this).attr('id') == 'data_period_2' || $(this).attr('id') == 'data_period_4' || $(this).attr('id') == 'data_period_10' || $(this).attr('id') == 'data_period_12'){
        $(this).datepicker('setStartDate', startDate2);
        $(this).datepicker('setEndDate', endDate2);
        $(this).datepicker('setDate', endDate2);
      }
      if($(this).attr('id') == 'data_period_1' || $(this).attr('id') == 'data_period_3' || $(this).attr('id') == 'data_period_9' || $(this).attr('id') == 'data_period_11'){
        $(this).datepicker('setStartDate', startDate);
        $(this).datepicker('setEndDate', endDate);
        $(this).datepicker('setDate', startDate);
      }
      if($(this).attr('id') == 'data_period_5' || $(this).attr('id') == 'data_period_7'){
        $(this).datepicker('setStartDate', start_event_date);
        $(this).datepicker('setEndDate', end_event_date);
        $(this).datepicker('setDate', start_event_date);
      }
      if($(this).attr('id') == 'data_period_6' || $(this).attr('id') == 'data_period_8'){
        $(this).datepicker('setStartDate', start_event_date);
        $(this).datepicker('setEndDate', end_event_date);
        $(this).datepicker('setDate', end_event_date);
      }
  });
});

</script>

<?php include(__DIR__.'/index_modal.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
