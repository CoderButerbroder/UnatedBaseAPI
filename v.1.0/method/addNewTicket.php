<?
// Добавление нового тикета

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (isset($id_user_tboil) &&  isset($name) &&  isset($description) &&  isset($message) &&  isset($links_add_files) &&  isset($type_user) ) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewTicket',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
          $check_id_referer = $settings->get_data_referer($resource);
          $data_refer = json_decode($check_id_referer);
          if ($data_refer->response) {
              $response_ticket = $settings->add_new_support_ticket($id_user_tboil, $name, $description, 'open', $data_refer->data->id);
                         $settings->recording_history($resource,'addNewTicket',$response);
              $data_response_ticket = json_decode($response_ticket);
              if($data_response_ticket->response){
               $response_ticket_message = $settings->add_new_support_messages($data_response_ticket->data->id, $id_user_tboil, $message, $links_add_files, $data_refer->data->id, $type_user);
                           $settings->recording_history($resource,'addNewTicketMessage',$response);
               if(json_decode($response_ticket_message)->response && json_decode($response_ticket)->response){
                 echo json_encode(array('response' => true, 'description' => 'Тикет успешно открыт', 'data_ticket' => $response_ticket, 'data_message' => $response_ticket_message),JSON_UNESCAPED_UNICODE);
               } else {
                 echo $response_ticket;
               }
              }
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
