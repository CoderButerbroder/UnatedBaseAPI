<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_all_roles_json = $settings->get_all_roles_sistem();

$data_all_roles = json_decode($data_all_roles_json);

?>

<select class="js-example-basic-single" name="role_copy" id="select_input">
  <option default selected value="0">Не выбрана (задать по умолчанию)</option>
  <?php foreach ($data_all_roles->data as $key => $value) { ?>
      <option value="<?php echo $value->id;?>"><?php echo $value->alias;?></option>
  <?}?>
</select>


<script>
$(document).ready(function($) {
  $("#select_input").select2();
});
</script>
