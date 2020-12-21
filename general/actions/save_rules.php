<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-type:application/json;charset=utf-8');
$role = (isset($_POST['role'])) ? $_POST['role'] : false;

if(!$role){
    echo json_encode(array('response' => false, 'description' => 'Роль не была распознана сервером, пожалуйста попробуйте позже'),JSON_UNESCAPED_UNICODE);
    exit;
}
require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_role_json = $settings->get_role_data_name($role);

$return_cur_rule_role = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $data_role_json);

$return_cur_rule_role = json_decode(json_decode($return_cur_rule_role)->data->rules);

$check_rules = $_POST["type_settings"];

// var_dump($return_cur_rule_role->$check_rules);

// var_dump($_POST);

unset($_POST['type_settings']);
unset($_POST['role']);

// var_dump($_POST);

$massiv_znash = $_POST;

$massiv_keys =  array_keys($_POST);

// var_dump($_POST);
// var_dump($massiv_keys);
// var_dump($massiv_keys[0]);
// var_dump($check_rules);


var_dump($return_cur_rule_role);

foreach ($return_cur_rule_role->$check_rules->rule as $key => $value) {

    $name_settings = $value->name;
    $key_serch = array_key_exists($value->name, $massiv_znash);

    if ($key_serch) {
      $return_cur_rule_role->$check_rules->rule->$name_settings->value = true;
    }
    else {
      $return_cur_rule_role->$check_rules->rule->$name_settings->value = false;
    }

}

for ($i=0; $i < count($massiv_keys); $i++) {
      $name_settings = $massiv_keys[$i];
      $return_cur_rule_role->$check_rules->rule->$name_settings->value;
}

var_dump($return_cur_rule_role);




exit;
?>
