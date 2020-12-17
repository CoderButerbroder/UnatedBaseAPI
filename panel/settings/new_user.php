<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Новый пользователь - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">Добаление пользователя</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
                  <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-12 text-center">
                            <h5></h5>
                          </div>
                        </div>
                        <div class="container">
                          <form class="forms-sample">
                              <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                  <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label">Фамилия</label>
                                <div class="col-sm-9">
                                  <input type="text" name="lastname" class="form-control" id="lastname" autocomplete="off" placeholder="Фамилия">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Имя</label>
                                <div class="col-sm-9">
                                  <input type="text" name="name" class="form-control" id="name" placeholder="Имя">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="secondname" class="col-sm-3 col-form-label">Отчеcтво</label>
                                <div class="col-sm-9">
                                  <input type="text" name="second_name" class="form-control" id="secondname" placeholder="Отчетсво">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="phone" class="col-sm-3 col-form-label">Телефон</label>
                                <div class="col-sm-9">
                                  <input class="form-control" name="phone" id="secondname" data-inputmask-alias="+9(999)999-99-99">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="select_input" class="col-sm-3 col-form-label">Роль пользователя</label>
                                <div class="col-sm-9">
                                  <select class="js-example-basic-single" id="select_input">
                  										<option value="TX">Texas</option>
                  										<option value="WY">Wyoming</option>
                  										<option value="NY">New York</option>
                  										<option value="FL">Florida</option>
                  										<option value="KN">Kansas</option>
                  										<option value="HW">Hawaii</option>
                  									</select>
                                </div>

                              </div>
                              <div class="form-check form-check-flat form-check-primary mt-0" style="cursor: pointer;">
                                <label class="form-check-label">
                                  <input type="checkbox" name="send_email" class="form-check-input">
                                  Выслать данные на указанный email
                                </label>
                              </div>
                              <div class="container-fluid text-center">
                                <button type="submit" class="btn btn-primary mr-2">Зарегистрировать</button>
                                <button type="reset" class="btn btn-light">Сброс</button>
                              </div>

                            </form>
                          </div>


                      </div>
                  </div>
    </div>
</div>



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
