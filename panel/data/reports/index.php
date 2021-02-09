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

            <div id="acc_teport_fiz" class="accordion" role="tablist">
                  <div class="card">
                    <div class="card-header" role="tab" id="heading_report_fiz">
                      <h6 class="mb-0">
                        <a data-toggle="collapse" href="#collapse_report_ur" aria-expanded="true" aria-controls="collapse_report_ur">
                          Отчет по Юр. лицам
                        </a>
                      </h6>
                    </div>
                    <div id="collapse_report_ur" class="collapse" role="tabpanel" aria-labelledby="heading_report_fiz" data-parent="#acc_teport_fiz">
                      <div class="card-body">
                        <ul class="list-group list-group-flush">
                          <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_fiz_1').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Общие показатели</li>
                          <li style="cursor:pointer;" class="list-group-item" ><a style="color:black" href="javascript:void(0)" onclick="window.open('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/data/reports/actions/report_users_count_period')" ><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Общая выгрузка с разделением на месяцы</a></li>
                          <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_category').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Выгрузка по критерию</li>
                          <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_specific_category').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Выгрузка по определенному критерию</li>
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
                    <div id="collapse_report_fiz" class="collapse" role="tabpanel" aria-labelledby="heading_report_fiz" data-parent="#acc_teport_fiz">
                      <div class="card-body">
                        <ul class="list-group list-group-flush">
                          <li style="cursor:pointer;" class="list-group-item" ><a style="color:black" href="javascript:void(0)" onclick="window.open('https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/data/reports/actions/report_users')" ><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Общая выгрузка Пользователей</a></li>
                          <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_period_user').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> !Тест! Выгрузка по периоду</li>

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
  //    select_specific_category1 action
  //    div_select_specific_category
  //    select_specific_category2

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
      alerts('warning', 'Укажите временной диапозон', '');
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
</script>
<div class="modal fade" id="modal_report_fiz_1" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_users_overall" onsubmit="generate_report(this,'modal_report_fiz_1', 'period_count_company'); return false;" method="post" id="form_fiz_1">
          <!-- <div class="form-group">
            <label for="exampleInputUsername1">Укажите необходимый результирующий диапозон общих показателей</label>
            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Username">
          </div> -->

          <div class="form-group">
            <label for="select_input" class="col-form-label">Укажите необходимый результирующий диапозон общих показателей</label>
            <div class="">
              <select class="js-example-basic-single" name="period_count_company" id="select_input">
                <option default disabled selected value="false">Не выбрана</option>
                <option value="day">День</option>
                <option value="week">Неделя</option>
                <option value="month">Месяц</option>
                <option value="year">Год</option>
              </select>
            </div>
          </div>
          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_fiz_1')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_report_by_period_user" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_users_overall" onsubmit="generate_report(this,'modal_report_fiz_2', 'period'); return false;" method="post" id="form_fiz_2">
          <div class="form-group">
            <label for="select_input_period" class="col-form-label">Укажите необходимый результирующий диапозон</label>
            <div class="">
              <select class="js-example-basic-single" name="period" id="select_input_period">
                <option default disabled selected value="false">Не выбрана</option>
                <option value="day">День</option>
                <option value="week">Неделя</option>
                <option value="month">Месяц</option>
                <option value="year">Год</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="input-daterange">
              <div class="row ml-0 mr-0">
                <span class="col-md-2 my-2 text-center"> C </span> <input type="text" name="period_1" class="form-control col-md-4 text-center" >
                <div class="input-group-addon"> </div> <span class="col-md-2 my-2 text-center"> По </span>
                <input type="text" name="period_2" class="form-control col-md-4 text-center">
              </div>
            </div>
          </div>


          <!-- <label for="period_1" class="col-form-label">От</label>
          <div class="input-group date datepicker">
						<input type="text" name="period_1" class="form-control" id="period_1"><span class="input-group-addon"><i data-feather="calendar"></i></span>
					</div>
          <label for="period_1" class="col-form-label">До</label>
          <div class="input-group date datepicker">
						<input type="text" name="period_1" class="form-control" id="period_2"><span class="input-group-addon"><i data-feather="calendar"></i></span>
					</div> -->
          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <!-- <button type="button" class="btn btn-primary" onclick="$('#form_fiz_2')[0].elements['button'].click()">Сгенерировать</button> -->
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_report_by_category" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_users_by_category" onsubmit="generate_report(this,'modal_report_by_category', 'select'); return false;" method="post" id="form_by_category_2">

          <div class="form-group">
            <label for="select_input" class="col-form-label">Укажите необходимый критерий отбора</label>
            <div class="">
              <select class="js-example-basic-single" name="select"  >
                <option default disabled selected value="false">Не выбрана</option>
                <option value="мсп" >МСП</option>
                <option value="регион" >Регион</option>
                <option value="район" >Район</option>
                <option value="тип" >Тип инфроструктуры</option>
                <option value="отрасль" >Отрасль компании</option>
                <option value="УчасСколково" >Участник Сколково</option>
                <option value="УчасФСИ" >Участник ФСИ</option>
                <option value="экспорт" >Осуществляет экспорт</option>
              </select>
            </div>
          </div>
          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_by_category_2')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_report_by_specific_category" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form action="/panel/data/reports/actions/report_users_specific_category" onsubmit="generate_report(this,'modal_report_by_specific_category', 'select2'); return false;" method="post" id="form_by_specific_category">

          <div class="form-group">
            <label for="select_input" class="col-form-label">Укажите необходимый критерий отбора</label>
            <div class="">
              <select class="js-example-basic-single" name="select" id="select_specific_category1" action="/panel/data/reports/actions/get_parament_report_users_by_category" >
                <option default disabled selected value="false">Не выбрана</option>
                <option value="msp" >МСП</option>
                <option value="region" >Регион</option>
                <option value="staff" >Количество сотрудников</option>
                <option value="district" >Район</option>
                <option value="type_inf" >Тип инфроструктуры</option>
                <option value="export" >Экспорт</option>
                <option value="branch" >Отрасль</option>
                <option value="technology" >Технологии</option>
              </select>
            </div>
          </div>

          <div class="form-group" id="div_select_specific_category" style="display:none;">
            <label for="select_input" class="col-form-label">Укажите необходимый конкретный критерий отбора</label>
            <div class="">
              <select class="js-example-basic-single" name="select2" id="select_specific_category2">
                <option default disabled selected value="false">Не выбрана</option>
              </select>
            </div>
          </div>
          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" id="button_generate_report" disabled onclick="$('#form_by_specific_category')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  // $('.datepicker').datetimepicker({
  //   format: 'LT'
  // });
  // if($('.datepicker').length) {
  //   var date = new Date();
  //   var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
  //   $('.datepicker').datepicker({
  //     language: "ru",
  //     format: "mm/dd/yyyy",
  //     todayHighlight: true,
  //     autoclose: true
  //   });
  //   $('.datepicker').datepicker('setDate', today);
  // }
  $('.input-daterange input').each(function() {
      // $(this).datepicker('clearDates');
      var date = new Date();
      var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
      $(this).datepicker({
          format: "mm.dd.yyyy",
          todayHighlight: true,
          autoclose: true,
          language: "ru-RU",
          zIndexOffset: 1051
        });
      // $(this).datepicker('setDate', today);
  });
});

</script>



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
