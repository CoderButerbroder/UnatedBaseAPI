<?
/*
Обновление ответа на технологический запрос в единой  базе данных
$id_requests_on_referer = id тех запроса на рефере
$id_solution_on_referer = id решения на рефере
$id_entity = id компании в едниой базе данных
$id_user_tboil = id пользователя tboil добавившего решение
$name_project = название проекта
$description - описание проекта
$result_project = результат проекта
$readiness = готовность проекта
$period = период работы
$forms_of_support = формы поддержки проекта
$protection = защита проекта
$links_add_files = ссылки через заяпятую на добавленные файлы
$solutions_hash = хэш решения
$status = статус
$date_receiving = дата получения ответа

*/
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_requests_on_referer && $id_solution_on_referer && isset($id_entity) && $id_user_tboil && isset($name_project) && isset($description) && isset($result_project) && isset($readiness) && isset($period) && isset($forms_of_support) && isset($protection) && isset($links_add_files) && isset($solutions_hash) && isset($status) && isset($date_receiving)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'updNewTechRequestSolution',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
            $check_id_referer = $settings->get_data_referer($resource);
            if (json_decode($check_id_referer)->response) {
                $response = $settings->tech_requests_solutions($id_requests_on_referer,$id_solution_on_referer,$id_entity,$id_user_tboil,$name_project,$description,$result_project,$readiness,$period,$forms_of_support,$protection,$links_add_files,$solutions_hash,$status,$date_receiving,json_decode($check_id_referer)->data->id);
                            $settings->recording_history($resource,'updNewTechRequestSolution',$response);
                echo $response;
            } else {
                echo $check_id_referer;
            }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для обновления данных по ответу на технологический запрос в единой базе данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
