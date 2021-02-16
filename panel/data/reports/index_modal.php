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
                <option value="day">День</option>
                <option value="week">Неделя</option>
                <option selected value="month">Месяц</option>
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
        <form action="/panel/data/reports/actions/report_users_period" onsubmit="generate_report(this,'modal_report_fiz_2', 'select_input_period'); return false;" method="post" id="form_fiz_2">
          <div class="form-group">
            <label for="select_input_period" class="col-form-label">Укажите необходимый результирующий диапозон</label>
            <div class="">
              <select class="js-example-basic-single" name="period" id="select_input_period">
                <option value="day">День</option>
                <option value="week">Неделя</option>
                <option selected value="month">Месяц</option>
                <option value="year">Год</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="input-daterange">
              <div class="row ml-0 mr-0">
                <span class="col-md-2 my-2 text-center"> C </span>
                <input type="text" name="period_1" id="data_period_1" class="form-control col-md-4 text-center" >
                <div class="input-group-addon"> </div> <span class="col-md-2 my-2 text-center"> По </span>
                <input type="text" name="period_2" id="data_period_2" class="form-control col-md-4 text-center">
              </div>
            </div>
          </div>

          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_fiz_2')[0].elements['button'].click()">Сгенерировать</button>
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


<div class="modal fade" id="modal_report_event" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Данные по мероприятияю</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_select_event" onsubmit="generate_report(this,'modal_report_event', 'select_input_event'); return false;" method="post" id="form_report_event">
          <div class="form-group">
            <label for="select_input_evevnt" class="col-form-label">Выберите мероприятие</label>
            <div class="">
              <select class="js-example-basic-single" name="event" id="select_input_event">
                <option default disabled selected value="false">-</option>
              </select>
            </div>
          </div>

          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_report_event')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_report_by_FSI" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_FSI" onsubmit="generate_report(this,'modal_report_by_FSI', 'period'); return false;" method="post" id="form_FSI">
          <div class="form-group">
            <label for="select_input_period" class="col-form-label">Укажите необходимый результирующий диапозон</label>
            <div class="">
              <select class="js-example-basic-single" name="period" id="select_period">
                <option value="week">Неделя</option>
                <option selected value="month">Месяц</option>
                <option value="year">Год</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="input-daterange">
              <div class="row ml-0 mr-0">
                <span class="col-md-2 my-2 text-center"> C </span>
                <input type="text" name="start" id="data_period_3" class="form-control col-md-4 text-center" >
                <div class="input-group-addon"> </div> <span class="col-md-2 my-2 text-center"> По </span>
                <input type="text" name="end" id="data_period_4" class="form-control col-md-4 text-center">
              </div>
            </div>
          </div>

          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_FSI')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_report_by_events" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_events_period" onsubmit="generate_report(this,'modal_report_by_events', 'start'); return false;" method="post" id="form_events">

          <div class="form-group">
            <div class="input-daterange">
              <div class="row ml-0 mr-0">
                <span class="col-md-2 my-2 text-center"> C </span>
                <input type="text" name="start" id="data_period_7" class="form-control col-md-4 text-center" >
                <div class="input-group-addon"> </div> <span class="col-md-2 my-2 text-center"> По </span>
                <input type="text" name="end" id="data_period_8" class="form-control col-md-4 text-center">
              </div>
            </div>
          </div>

          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_events')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_report_by_event" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_event" onsubmit="generate_report(this,'modal_report_by_event', 'period'); return false;" method="post" id="form_event">
          <div class="form-group">
            <label for="select_input_period" class="col-form-label">Укажите необходимый результирующий диапозон</label>
            <div class="">
              <select class="js-example-basic-single" name="period" >
                <option value="week">Неделя</option>
                <option selected value="month">Месяц</option>
                <option value="year">Год</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="input-daterange">
              <div class="row ml-0 mr-0">
                <span class="col-md-2 my-2 text-center"> C </span>
                <input type="text" name="start" id="data_period_5" class="form-control col-md-4 text-center" >
                <div class="input-group-addon"> </div> <span class="col-md-2 my-2 text-center"> По </span>
                <input type="text" name="end" id="data_period_6" class="form-control col-md-4 text-center">
              </div>
            </div>
          </div>

          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_event')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_report_by_tboil" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_point_boil" onsubmit="generate_report(this,'modal_report_by_tboil', 'period'); return false;" method="post" id="form_tboil">
          <div class="form-group">
            <label for="select_input_period" class="col-form-label">Укажите необходимый результирующий диапозон</label>
            <div class="">
              <select class="js-example-basic-single" name="period" >
                <option value="week">Неделя</option>
                <option selected value="month">Месяц</option>
                <option value="year">Год</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="input-daterange">
              <div class="row ml-0 mr-0">
                <span class="col-md-2 my-2 text-center"> C </span>
                <input type="text" name="start" id="data_period_9" class="form-control col-md-4 text-center" >
                <div class="input-group-addon"> </div> <span class="col-md-2 my-2 text-center"> По </span>
                <input type="text" name="end" id="data_period_10" class="form-control col-md-4 text-center">
              </div>
            </div>
          </div>

          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_tboil')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_report_by_cervices" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Количественные показатели</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/panel/data/reports/actions/report_cervices" onsubmit="generate_report(this,'modal_report_by_cervices', 'period'); return false;" method="post" id="form_cervices">
          <div class="form-group">
            <label for="select_input_period" class="col-form-label">Укажите необходимый результирующий диапозон</label>
            <div class="">
              <select class="js-example-basic-single" name="period" >
                <option value="week">Неделя</option>
                <option selected value="month">Месяц</option>
                <option value="year">Год</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="input-daterange">
              <div class="row ml-0 mr-0">
                <span class="col-md-2 my-2 text-center"> C </span>
                <input type="text" name="start" id="data_period_11" class="form-control col-md-4 text-center" >
                <div class="input-group-addon"> </div> <span class="col-md-2 my-2 text-center"> По </span>
                <input type="text" name="end" id="data_period_12" class="form-control col-md-4 text-center">
              </div>
            </div>
          </div>

          <button style="display:none;" type="submit" name="button"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="$('#form_cervices')[0].elements['button'].click()">Сгенерировать</button>
      </div>
    </div>
  </div>
</div>
