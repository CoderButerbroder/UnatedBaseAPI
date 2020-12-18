<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_all_roles_json = $settings->get_all_roles_sistem();
$default_user_photo = $settings->get_global_settings('default_user_photo');
$data_all_roles = json_decode($data_all_roles_json);

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
                              $users_in_role[] = $default_user_photo;
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
                            <a href="/panel/settings/view_rules" role="button" class="btn btn-sm btn-primary">Права</a>
                            <button type="button" onclick="delete_role(<?php echo $value->id;?>);" class="btn btn-sm btn-danger">Удалить</button>
                        <? } ?>
                      </td>
                  </tr>
              <? } ?>
            </tbody>
          </table>
        </div>

<?php } ?>

<script type="text/javascript">
  $(document).ready(function(){
    $('.table').DataTable({
          "language": {
            "url": "/assets/vendors/datatables.net/Russian.json"
          }
    });
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    });
  });
</script>
