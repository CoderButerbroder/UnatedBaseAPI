<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);
ignore_user_abort(true);
set_time_limit(0);

session_start();
if (!isset($_SESSION["key_user"])) {
  echo json_encode(array('response' => false, 'description' => 'Ошибка авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

include($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
// //
$settings = new Settings;

global $database;

$tboil_token = $settings->get_global_settings('tboil_token');

$data_events_users = json_decode(file_get_contents("https://tboil.spb.ru/api/v2/getEventsUsers/?token=".$tboil_token));
echo "events count: ".count(get_object_vars($data_events_users->data));
echo "</br>";
//
// $data_events =  json_decode(file_get_contents("https://tboil.spb.ru/api/v2/getEvents/?token=".$tboil_token));
// echo count($data_events->data);

$count = 0;
$count_iniq = 0;
$arr_users = [];
foreach ($data_events_users->data as $key => $value) {
  $count += count($value);
  if (count($value) > 0 ) {
    foreach ($value as $key2 => $value2) {
      array_push( $arr_users, $value2);
    }
  }
}

$arr_users_uniq = array_unique($arr_users);

echo "users visit evetns: ".$count;
echo "</br>";
echo "uniq visit users: ".count($arr_users_uniq);
echo "</br>";

$statement = $database->prepare("SELECT `id_tboil` FROM `MAIN_users` WHERE `activation` = 'Y' ");
$statement->execute();
$data_users_sql = $statement->fetchAll(PDO::FETCH_OBJ);

$data_users_sql_data = array_column($data_users_sql, 'id_tboil');
echo "me activation users ".count($data_users_sql_data);
echo "</br>";

$count_active_users_events = 0;

foreach ($arr_users_uniq as $key => $value) {
  if (in_array($value, $data_users_sql_data ) ) {
    $count_active_users_events++;
  }
}

echo "result count uniq activation users visit evetns: ".$count_active_users_events;



// $data_user_tboil = json_decode(file_get_contents("https://tboil.spb.ru/api/v2/getUsers/?token=".$tboil_token))->data;
//
// // echo "users count:";
//
// foreach ($data_user_tboil as $key => $value) {
//   echo " ".$value."</br>";
// }




//
// echo file_get_contents("https://tboil.spb.ru/api/v2/getEvent/1544/?token=".$tboil_token);
// echo "</br>";
// echo "</br>";
// echo "</br>";
// echo file_get_contents($tboil_site."/api/v2/getEvents/?token=".$tboil_token);
//

//
// $arr_data_event_summ = $settings->get_count_main_events_groupby_time_reg(false, 'data');

// echo json_encode($arr_data_event_summ, JSON_UNESCAPED_UNICODE);

// echo count($arr_data_event_summ);

// foreach($arr_data_event_summ as $key => $value ){
//   echo "status: ".$value->status_event."</br>";
// }

// $status_event = array_column($arr_data_event_summ, 'status_event');
// var_dump($status_event);
// foreach($status_event as $key => $value ){
//   echo "status: ".$value."</br>";
// }
//
// var_dump(array_unique($status_event));
// echo "</br>";
// echo "</br>";
// echo "</br>";
//
// $massiv_field_value = [ 'reg_date' => '06.12.2018 10:17:19'  ];
// $massiv_field_value["reg_date"] = date('Y-m-d H:i:s', strtotime( $massiv_field_value["reg_date"] ));
//
// var_dump($settings->cron_mass_update_user_field(json_encode($massiv_field_value, JSON_UNESCAPED_UNICODE), 110312));

// echo strtotime('0000-00-00 00:00:00');


?>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


<div class="row justify-content-center">
  <div class="col-md-4">
    <div class="form-group">
      <div class="input-group ">
        <input type="text" class="form-control" id="pwd" placeholder="Пароль" value="" name="password" required autocomplete="new-password">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary eye_button" type="button" onclick="change_view_pass(this);"><i class="far fa-eye"></i></button>
        </div>
        <div style="display:none;" class="valid-feedback">Пароль длинной не менее 6 символов</div>
        <div style="display:none;" class="valid-feedback">Содержит 1 или более цифр</div>
        <div style="display:none;" class="valid-feedback">Содержит символы Русского или Латинского алфавита</div>
        <div style="display:none;" class="valid-feedback">Разного регистра</div>
        <div style="display:none;" class="valid-feedback">Спец. символ</div>
        <div style="display:none;" class="valid-feedback">Пароль соответствует указанным критериям</div>
        <div style="display:none;" class="invalid-feedback">Укажите пароль длинной не менее 6 символов</div>
        <div style="display:none;" class="invalid-feedback">Содержит 1 или более цифр</div>
        <div style="display:none;" class="invalid-feedback">Содержит символы Русского или Латинского алфавита</div>
        <div style="display:none;" class="invalid-feedback">Разного регистра</div>
        <div style="display:none;" class="invalid-feedback">Спец. символ</div>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function() {

  var popover_pwd = $('#pwd').popover({
    trigger: 'focus',
    title: '',
    html: true,
    placement: 'top',
  });

  $('#pwd').on('input', function() {
    // var invalid_msg = $(this).find('.invalid-feedback');
    var invalid_msg = Object.values( $('#for_pwd').find(".invalid-feedback") );
    var valid_msg = Object.values( $('#for_pwd').find(".valid-feedback") );

    console.log(invalid_msg);
    // console.log(Object.values( invalid_msg ));

    var val = $(this).val();
    var check = 0;

    val = val.replace(' ', '');

    // if ( val.trim().length < 6
    //       || val.search(/\d/) == -1
    //       || val.search(/[A-Za-zА-Яа-яЁё]/) == -1
    //       || ( val.search(/[A-ZА-ЯЁ]/) == -1 || val.search(/[a-zа-яё]/) == -1 )
    //       || val.search(/[\.\\\$\%\|\?\*\+\(\)\<\>\!\&\/\\\-\@]/) == -1 ) {
    //   $($(valid_msg)[5]).slideUp('fast');
    // } else {
    //   if($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
    //   for (var i = 0; i < 5; i++) {
    //     $($(valid_msg)[i]).slideUp('fast');
    //   }
    //   $($(valid_msg)[5]).slideDown('fast');
    //   return true;
    // }


    if( val.trim().length < 6 ) {
      if(!$(this).hasClass('is-invalid')) $(this).addClass('is-invalid');
      $($(valid_msg)[0]).slideUp('fast')
      $($(invalid_msg)[0]).slideDown('fast')
      // $($(invalid_msg)[0].html('Укажите пароль длинной не менее 6 символов');
    } else {
      // if($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
      $($(valid_msg)[0]).slideDown('fast')
      $($(invalid_msg)[0]).slideUp('fast')
      // $($(invalid_msg)[0].html('');
      check++;
    }

    if( val.search(/\d/) == -1 ){
      if(!$(this).hasClass('is-invalid')) $(this).addClass('is-invalid');
      $($(invalid_msg)[1]).slideDown('fast')
      $($(valid_msg)[1]).slideUp('fast')
      // $($(invalid_msg)[1].html('Содержит 1 или более цифр');
    } else {
      // if($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
      $($(valid_msg)[1]).slideDown('fast')
      $($(invalid_msg)[1]).slideUp('fast')
      // $($(invalid_msg)[1].html('');
      check++;
    }

    if( val.search(/[A-Za-zА-Яа-яЁё]/) == -1 ){
      if(!$(this).hasClass('is-invalid')) $(this).addClass('is-invalid');
      $($(valid_msg)[2]).slideUp('fast')
      $($(invalid_msg)[2]).slideDown('fast')
      // $($(invalid_msg)[2].html('Содержит символы Русского или Латинского алфавита');
    } else {
      // if($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
      $($(valid_msg)[2]).slideDown('fast')
      $($(invalid_msg)[2]).slideUp('fast')
      // $($(invalid_msg)[2].html('');
      check++;
    }

    if( val.search(/[A-ZА-ЯЁ]/) == -1 || val.search(/[a-zа-яё]/) == -1 ){
      if(!$(this).hasClass('is-invalid')) $(this).addClass('is-invalid');
      $($(valid_msg)[3]).slideUp('fast')
      $($(invalid_msg)[3]).slideDown('fast')
      // $($(invalid_msg)[3].html('Разного регистра');
    } else {
      // if($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
      $($(valid_msg)[3]).slideDown('fast')
      $($(invalid_msg)[3]).slideUp('fast')
      // $($(invalid_msg)[3].html('');
      check++;
    }

    if( val.search(/[\.\\\$\%\|\?\*\+\(\)\<\>\!\&\/\\\-\@]/) == -1 ){
      if(!$(this).hasClass('is-invalid')) $(this).addClass('is-invalid');
      $($(valid_msg)[4]).slideUp('fast')
      $($(invalid_msg)[4]).slideDown('fast')
      // $($(invalid_msg)[4].html('Разного регистра');
    } else {
      // if($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
      $($(valid_msg)[4]).slideDown('fast')
      $($(invalid_msg)[4]).slideUp('fast')
      // $($(invalid_msg)[4].html('');
      check++;
    }

    $('#pwd').attr('data-content', 'hello');
    var popover = $('#pwd').data('popover');
    popover.setContent();


  });
});

</script> -->
