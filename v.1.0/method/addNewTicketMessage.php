<?
/* Добавление сообщения в тикет в единую базу данных

$id_support_ticket = id тикета поддержки
$id_tboil = id_tboil пользователя
$message = сообщение
$type_user = тип пользователя жестки выбор

*/

include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется токен'),JSON_UNESCAPED_UNICODE);exit;}
if (!$resource) {echo json_encode(array('response' => false, 'description' => 'Обязательно требуется ресурс с которого идет запрос'),JSON_UNESCAPED_UNICODE);exit;}

if (isset($id_support_ticket) && isset($id_tboil) && isset($message) && isset($type_user)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
      $settings = new Settings;
      $check_valid_token = $settings->validate_token($token,$resource);
                           $settings->recording_history($resource,'addNewTicketMessage',$check_valid_token);

      if (json_decode($check_valid_token)->response) {
          $check_id_referer = $settings->get_data_referer($resource);
          if (json_decode($check_id_referer)->response) {
              $response = $settings->add_new_support_messages($id_support_ticket, $id_tboil, $message, json_decode($check_id_referer)->data->id, $type_user);
                          $settings->recording_history($resource,'addNewTicketMessage',$response);
                echo $response;
          } else {
                echo $check_id_referer;
          }
      } else {
              echo $check_valid_token;
      }
} else {
      echo json_encode(array('response' => false, 'description' => 'Не все обязательные поля были заполнены для добавления мероприятия в единую базу данных', 'data_referer' => $_POST),JSON_UNESCAPED_UNICODE);
      exit;
}



?>
