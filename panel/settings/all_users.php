<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Пользователи - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php if (!$data_user_rules->emploe->rule->view_all_users->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else { ?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">Все пользователи</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <a href="/panel/settings/new_user" type="button" class="btn btn-success btn-icon-text">
                  <i class="btn-icon-prepend" data-feather="user-plus"></i>
                  Добавить
                </a>
              </div>
            </div>
              <div class="mt-4">
              <?php
              $users_data = $settings->get_all_api_users(true);
              $default_user_photo = $settings->get_global_settings('default_user_photo');
              if($users_data) {
              ?>
                      <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                          <thead>
                            <tr>
                              <th>ФИО</th>
                              <th>Роль</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $count_users_data = 0;
                              foreach ($users_data as $key_users_data => $value_users_data) {
                                $second_name = (isset($value_users_data->second_name)) ? $value_users_data->second_name : '';
                                $name = (isset($value_users_data->name)) ? $value_users_data->name : '';
                                $second_lastname = (isset($value_users_data->lastname)) ? $value_users_data->lastname : '';
                                $photo = isset($value_users_data->photo) ? $value_users_data->photo : $default_user_photo;
                                echo '<tr>';
                                  echo '<td><img src="'.$photo.'" /> '.$second_lastname.' '.$name.' '.$second_name.' '.'</td>';
                                  echo '<td>'.$value_users_data->alias.'</td>';
                                echo '</tr>';
                              }
                            ?>
                          </tbody>
                        </table>
                      </div>

              <?php } ?>

              </div>

          </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.table').DataTable({
          "language": {
            "url": "/assets/vendors/datatables.net/Russian.json"
          }
    });
  });
</script>

<? } ?>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
