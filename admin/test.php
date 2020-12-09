<?php

/*
СКРИПТ ОБНОВЛЕНИЯ ОТРОСЛЕЙ КОМПАНИЙ, СТАРЫЙХ НА НОВЫЕ С IPCHAINT+-


session_start();
require_once(__DIR__.'/../general/core.php');

  global $database;

  $statement = $database->prepare("SELECT * FROM `MAIN_entity` WHERE branch IS NOT NULL");
  $statement->execute();
  $data_select = $statement->fetchAll(PDO::FETCH_OBJ);

  $arr = [];

  array_push($arr, (object) [   "name" => 'Медицинская техника',
                                "code" => 'IND-004',
                                "name_new" => 'Здравоохранение']);

  array_push($arr, (object) [  "name" => "Биотехнологии",
                               "code" => "IND-009",
                              "name_new" => "Наука" ]);

  array_push($arr, (object) [  "name" => "Машиностроение",
                               "code" => "IND-020",
                              "name_new" => "Тяжелая промышленность" ]);

  array_push($arr, (object) [  "name" => "Нанотехнологии",
                               "code" => "IND-009",
                              "name_new" => "Наука" ]);

  array_push($arr, (object) [  "name" => "Фармацевтика",
                               "code" => "IND-004",
                              "name_new" => "Здравоохранение" ]);

  array_push($arr, (object) [  "name" => "Промышленное оборудование",
                               "code" => "IND-020",
                              "name_new" => "Тяжелая промышленность" ]);

  array_push($arr, (object) [  "name" => "Товары народного потребления",
                               "code" => "IND-015",
                              "name_new" => "Розничная и оптовая торговля" ]);

  array_push($arr, (object) [  "name" => "Транспортные услуги",
                               "code" => "IND-019",
                              "name_new" => "Транспорт и логистика" ]);

  array_push($arr, (object) [  "name" => "Электроника и приборостроение",
                               "code" => "IND-007",
                              "name_new" => "Легкая промышленность" ]);

  array_push($arr, (object) [  "name" => "Энергетика",
                               "code" => "IND-024",
                              "name_new" => "Энергетика" ]);

  array_push($arr, (object) [   "name" => 'Медицинская техника',
                                "code" => 'IND-004',
                                "name_new" => 'Здравоохранение']);

  array_push($arr, (object) [   "name" => 'Нефтегазовое',
                                  "code" => 'IND-003',
                                  "name_new" => 'Добыча полезных ископаемых']);

  array_push($arr, (object) [   "name" => 'Нефтегазовое оборудование',
                                  "code" => 'IND-003',
                                  "name_new" => 'Добыча полезных ископаемых']);

  array_push($arr, (object) [   "name" => 'Здравоохранение',
                                  "code" => 'IND-004',
                                  "name_new" => 'Здравоохранение']);

  array_push($arr, (object) [   "name" => 'Информационные технологии',
                                  "code" => 'IND-005',
                                  "name_new" => 'Информационные TECHологии']);

  array_push($arr, (object) [   "name" => 'Информационные и коммуникационные технологии',
                                  "code" => 'IND-005',
                                  "name_new" => 'Информационные TECHологии']);

  array_push($arr, (object) [   "name" => 'Инжиниринг',
                                  "code" => 'NFP-048',
                                  "name_new" => 'Развитие научной и научно-производственной кооперации']);

  array_push($arr, (object) [   "name" => 'Образование',
                                  "code" => 'IND-011',
                                  "name_new" => 'Образование']);

  array_push($arr, (object) [   "name" => 'Материалы и химия',
                                  "code" => 'TECH-035',
                                  "name_new" => 'TECHологии новых материалов и веществ']);

  //echo json_encode($arr, JSON_UNESCAPED_UNICODE);


  foreach ($data_select as $key_select => $value_select) {
    //echo $value_select->branch;

    if($value_select->branch == '[{"value":null}]') { continue; }
    $old_value_branch = json_decode($value_select->branch);
    $new_value_str = '';

    foreach ($old_value_branch as $key_branch => $value_branch) {
      foreach ($arr as $key_new => $value_new) {
        if($value_new->name == $value_branch->value){
          $new_value = (object) array( 'code' => $value_new->code, 'value' => $value_new->name_new);
          $new_value_str .= json_encode($new_value, JSON_UNESCAPED_UNICODE);
        }
      }
    }

    $upd_profile = $database->prepare("UPDATE `MAIN_entity` SET branch = :branch WHERE id = :id");
    $upd_profile->bindParam(':branch', $new_value_str, PDO::PARAM_STR);
    $upd_profile->bindParam(':id', $value_select->id, PDO::PARAM_INT);
    $temp = $upd_profile->execute();
    var_dump($temp);
    echo "</br>";
}

*/

?>
