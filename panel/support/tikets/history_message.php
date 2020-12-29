<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION["key_user"]) || !isset($_GET["value"])) {
  //echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;
$temp_null = 0;
$data_message_ticket = json_decode($settings->get_data_support_ticket($_GET["value"],true))->data->$temp_null;
$data_ticket_str = $settings->get_data_tiket($_GET["value"]);
$data_ticket = json_decode($data_ticket_str);
//$data_referer_ticket = json_decode($settings->get_data_referer_id($data_ticket->data->id_referer));
$data_user_request = json_decode($settings->get_user_data_id_boil($data_ticket->data->id_tboil));
$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));

$arr = array($data_user->data->id => 'https://'.$_SERVER["SERVER_NAME"].$data_user->data->photo);


foreach ($data_message_ticket->messages as $key => $value): ?>
  <li class="message-item <?php echo ( $value->type_user != 'support' ) ? 'friend' : 'me' ; ?>">
    <?php
    //логика такая, если поддрежка достаем фотку или используем шаблон фотки пользвателя запрошивающего помощь
      if ( $value->type_user == 'support' ) {
        //если в массиве уже есть фотка с запрашивающем суппорт пользователем
        $temp_id_support = $value->id_tboil_or_id_support;
        if ($arr[$temp_id_support]) {
          $photo = $arr[$temp_id_support];
        } else {
          //поиск другого пользователя поддержки
          $data_user_support = $settings->get_data_user_api($temp_id_support);
          if ($data_user_support == false) {
            $photo = 'https://'.$_SERVER["SERVER_NAME"].'/assets/images/custom/uncknow_user.jpg';
            $arr[$temp_id_support] = $photo;
          } else {
            $photo = 'https://'.$_SERVER["SERVER_NAME"].$data_user_support->photo;
          }
        }
      } else {
        $photo = 'https://'.$_SERVER["SERVER_NAME"].'/assets/images/custom/uncknow_user.jpg';
      }
    ?>
    <img src="<?php echo $photo; ?>" class="img-xs rounded-circle" alt="avatar">
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
