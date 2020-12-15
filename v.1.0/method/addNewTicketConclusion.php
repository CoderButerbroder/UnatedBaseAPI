<?
// Добавление нового решения тикета


include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (isset($id_ticket) && isset($id_user_tboil) && isset($description) &&  isset($action) &&  isset($links_add_files) ) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewTicketConclusion',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
          $check_id_referer = $settings->get_data_referer($resource);
          $data_refer = json_decode($check_id_referer);
          if ($data_refer->response) {
                $response_ticket_conclusion = $settings->add_new_support_conclusion($id_ticket, $id_user_tboil, $description, $action, $links_add_files);
                            $settings->recording_history($resource,'addNewTicketConclusion',$response);
                $response_ticket_conclusion;
          } else {
                echo $check_id_referer;
          }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для добавления тикета в единую базу данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}


?>
