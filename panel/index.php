  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
  <?php /*тут метатеги*/?>
	<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>

  <?php if (!$data_user_rules->dashboard->rule->view_dashboard->value) {?>

      <div class="container-fluid text-center">
          <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
      </div>

  <?php } else { ?>


    <div class="row">
        <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-header">
               <h5 class="card-title my-auto">Юр. лица по отрослям</h5>
              </div>
              <div class="card-body" style="min-height: 420px; max-height: 420px;">
                <div class="" id="div_chart_line_branch" >
                  <div id="spinner_chart_line_branch" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-header">
                 <div class="row align-items-start mb-2 my-auto">
                  <div class="col-md-7 my-auto">
                    <h5 class="card-title my-auto">Количественные показатели Юр. лиц</h5>
                  </div>
                  <div class="col-md-5 d-flex justify-content-md-end" style="display:none;">
                    <div class="btn-group mb-3 mb-md-0" name_chart="company" role="group" aria-label="Basic example" id="btn_period_chart_line_company" >
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="day">День</button>
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="week">Неделя</button>
                      <button type="button" class="btn_period_chart btn btn-primary" value="month" active>Месяц</button>
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="year">Год</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body" style="min-height: 420px; max-height: 420px;">
                <div class="" id="div_chart_line_company" >
                  <div id="spinner_chart_line_company" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
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
                  <h5 class="card-title my-auto">Физ Лица</h5>
                </div>
                <div class="col-md-5 d-flex justify-content-md-end" style="display:none;">
                  <div class="btn-group mb-3 mb-md-0" name_chart="user" role="group" aria-label="Basic example" id="btn_period_chart_line_user" >
                    <button type="button" class="btn_period_chart btn btn-outline-primary" value="day">День</button>
                    <button type="button" class="btn_period_chart btn btn-outline-primary" value="week">Неделя</button>
                    <button type="button" class="btn_period_chart btn btn-primary" value="month" active>Месяц</button>
                    <button type="button" class="btn_period_chart btn btn-outline-primary" value="year">Год</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body" style="min-height: 420px; /*max-height: 420;*/">
              <div class="" id="div_chart_line_user" >
                <div id="spinner_chart_line_user" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
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
                    <h5 class="card-title my-auto">ФСИ</h5>
                  </div>
                  <div class="col-md-5 d-flex justify-content-md-end" style="display:none;">
                    <div class="btn-group mb-3 mb-md-0" name_chart="FSI" role="group" aria-label="Basic example" id="btn_period_chart_line_FSI" >
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="day">День</button>
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="week">Неделя</button>
                      <button type="button" class="btn_period_chart btn btn-primary" value="month" active>Месяц</button>
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="year">Год</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body" style="min-height: 420px; max-height: 420px;">
                <div class="" id="div_chart_line_FSI" >
                  <div id="spinner_chart_line_FSI" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-header">
                 <div class="row align-items-start mb-2 my-auto">
                  <div class="col-md-7 my-auto">
                    <h5 class="card-title my-auto">Сколково</h5>
                  </div>
                  <div class="col-md-5 d-flex justify-content-md-end" style="display:none;">
                    <div class="btn-group mb-3 mb-md-0" name_chart="SK" role="group" aria-label="Basic example" id="btn_period_chart_line_SK" >
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="day">День</button>
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="week">Неделя</button>
                      <button type="button" class="btn_period_chart btn btn-primary" value="month" active>Месяц</button>
                      <button type="button" class="btn_period_chart btn btn-outline-primary" value="year">Год</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body" style="min-height: 420px; max-height: 420px;">
                <div class="" id="div_chart_line_SK" >
                  <div id="spinner_chart_line_SK" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>

  <?php include(__DIR__.'/actions/js_chart.php');?>

  <?php } ?>


  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
