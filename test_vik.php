<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
include($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

$settings = new Settings;

$massiv_field_value = [ 'reg_date' => '06.12.2018 10:17:19'  ];
$massiv_field_value["reg_date"] = date('Y-m-d H:i:s', strtotime( $massiv_field_value["reg_date"] ));

var_dump($settings->cron_mass_update_user_field(json_encode($massiv_field_value, JSON_UNESCAPED_UNICODE), 110312));

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
