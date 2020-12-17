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

                        <div class="container">
                          <form class="forms-sample">
                              <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                  <input type="email" class="form-control" id="exampleInputUsername2" placeholder="Email">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Фамилия</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" id="exampleInputEmail2" autocomplete="off" placeholder="Фамилия">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputMobile" class="col-sm-3 col-form-label">Имя</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" id="exampleInputMobile" placeholder="Имя">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputMobile" class="col-sm-3 col-form-label">Отчетсво</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" id="exampleInputMobile" placeholder="Отчетсво">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputMobile" class="col-sm-3 col-form-label">Телефон</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" id="exampleInputMobile" placeholder="Телефон">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                  <input type="password" class="form-control" id="exampleInputPassword2" autocomplete="off" placeholder="Password">
                                </div>
                              </div>
                              <div class="form-check form-check-flat form-check-primary mt-0">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input">
                                  Remember me
                                </label>
                              </div>
                              <button type="submit" class="btn btn-primary mr-2">Submit</button>
                              <button class="btn btn-light">Cancel</button>
                            </form>
                          </div>


                      </div>
                  </div>
    </div>
</div>



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
