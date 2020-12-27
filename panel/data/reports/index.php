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
                        <a data-toggle="collapse" href="#collapse_report_fiz" aria-expanded="true" aria-controls="collapse_report_fiz">
                          Отчет по Пользователям
                        </a>
                      </h6>
                    </div>
                    <div id="collapse_report_fiz" class="collapse" role="tabpanel" aria-labelledby="heading_report_fiz" data-parent="#acc_teport_fiz">
                      <div class="card-body">
                        <ul class="list-group list-group-flush">
                          <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_fiz_1').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i> Общие показатели</li>
                          <li style="cursor:pointer;" class="list-group-item" ><a style="color:black" href="https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/data/reports/actions/report_users_count_period" download><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>Общая выгрузка с разделением на месяцы</a></li>
                          <li style="cursor:pointer;" class="list-group-item" onclick="$('#modal_report_by_category').modal('show');"><i class="link-icon mr-1 text-primary" style="width: 25px; height: 25px;" data-feather="chevron-right"></i>Выгрузка по критерию</li>
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
  function generate_report(form, modal_w, select) {
    btn = form.elements["button"];
    select = form.elements[select];
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
          $(modal_w).modal('hide');
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
              <select class="js-example-basic-single" name="select" id="">
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



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
