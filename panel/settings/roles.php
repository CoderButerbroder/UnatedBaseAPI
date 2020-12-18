<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Роли - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>
<?php
$data_all_roles_json = $settings->get_all_roles_sistem();

$data_all_roles = json_decode($data_all_roles_json);

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">Роли и права</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <a href="/panel/settings/new_role" type="button" class="btn btn-success btn-icon-text">
                  <i class="btn-icon-prepend" data-feather="plus"></i>
                  Добавить роль
                </a>
              </div>
            </div>
              <div class="mt-4">
              <?php
              if($data_all_roles) {
              ?>
                      <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                          <thead>
                            <tr>
                              <th>Название</th>
                              <th>Пользователи</th>
                              <th>Права</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              foreach ($data_all_roles->data as $key => $value) {
                                $check_users = $settings->get_data_role_user($value->id);
                                if (json_decode($check_users)->response) {
                                    $users_in_role = array();
                                    $users_fio = array();
                                    foreach (json_decode($check_users)->data as $key2 => $value2) {
                                        if ($value2->photo) {
                                            $users_in_role[] = $value2->photo;
                                            $users_fio[] = $value2->name.' '.$value2->lastname;
                                        }
                                        else {
                                            $users_in_role[] = '/assets/images/custom/avatar.png';
                                            $users_fio[] = $value2->name.' '.$value2->lastname;
                                        }
                                    }
                                }
                                else {
                                    $users_in_role = 'Пользователей нет';
                                }
                                $count = (count($users_in_role) > 5) ? 5 : count($users_in_role);
                                ?>

                                <tr>
                                    <td><?php echo $value->alias; ?></td>
                                    <td>
                                      <?
                                      if (is_array($users_in_role)) {
                                        for ($i=0; $i < $count; $i++) { ?>
                                          <img  data-toggle="tooltip" data-placement="top" title="<?php echo $users_fio[$i];?>" src="<?php echo $users_in_role[$i]; ?>" />
                                        <? }
                                        if (count($users_in_role) > 5) { ?>
                                          <img style="border: 1px solid #6b7677;" data-toggle="tooltip" data-placement="right" title="Смотреть всех" src="/assets/images/custom/troetoch.jpg" />
                                        <?
                                        }}
                                        else { echo $users_in_role; } ?>
                                    </td>
                                    <td>
                                      <?php if ($value->name != 'admin'){ ?>
                                      <a href="/panel/settings/view_rules" type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="bottom" title="Права"><i data-feather="star"></i></a>
                                      <? } ?>
                                    </td>
                                </tr>
                            <? } ?>
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

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
