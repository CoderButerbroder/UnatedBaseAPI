<?
// Добавление сообщения в тикет

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (isset($id_ticket) &&  isset($id_user_tboil) &&  isset($message) && isset($links_add_files) && isset($links_add_files) && isset($type_user))  {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewTicketMessage',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
          $check_id_referer = $settings->get_data_referer($resource);
          $data_refer = json_decode($check_id_referer);
          if ($data_refer->response) {
            $status = 'open';
              $response_ticket = $settings->add_new_support_messages($id_ticket, $id_user_tboil, $message, $links_add_files, $data_refer->data->id, $type_user);
                          $settings->recording_history($resource,'addNewTicketMessage',$response);
              echo $response_ticket;
          } else {
                echo $check_id_referer;
          }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для добавления сообщения в тикет единой базы данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}


?>
