<?php
session_start();

if (!isset($_SESSION["key_user"]) || !isset($_GET["value"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$temp_null = 0;
$data_message_ticket = json_decode($settings->get_data_support_ticket($_GET["value"],"conclusion"))->data->$temp_null;
$data_ticket_str = $settings->get_data_tiket($_GET["value"]);
$data_ticket = json_decode($data_ticket_str);
//$data_referer_ticket = json_decode($settings->get_data_referer_id($data_ticket->data->id_referer));
$data_user_request = json_decode($settings->get_user_data_id_boil($data_ticket->data->id_tboil));

foreach ($data_message_ticket->messages as $key => $value): ?>
  <li class="message-item <?php echo ( $value->id_tboil_or_id_support == $data_user_request->data->id_tboil ) ? 'friend' : 'me' ; ?>">
    <img src="<?php echo ( $value->id_tboil_or_id_support == $data_user_request->data->id_tboil ) ? 'https://via.placeholder.com/43x43' :  $data_user->data->photo ;?>" class="img-xs rounded-circle" alt="avatar">
    <div class="content">
      <div class="message">
        <div class="bubble">
          <p><?php echo $value->message; ?></p>
        </div>
        <span><?php echo date('H:i d.m.Y', strtotime($value->date_added)); ?></span>
      </div>
    </div>
  </li>
<?php endforeach;


?>
