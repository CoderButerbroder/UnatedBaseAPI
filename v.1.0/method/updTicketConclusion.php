<?
// Обновление решения тикета
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if ($id_ticket_conclusion && $description && $action)  {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'updTicketConclusion',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
          $check_id_referer = $settings->get_data_referer($resource);
          $data_refer = json_decode($check_id_referer);
          if ($data_refer->response) {
              $response_ticket = $settings->upd_support_conclusion($id_ticket_conclusion, $description, $action, $links_add_files);
                          $settings->recording_history($resource,'updTicketConclusion',$response_ticket);
              echo $response_ticket;
          } else {
                echo $check_id_referer;
          }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для редактирования решения тикета единой базы данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}


?>
