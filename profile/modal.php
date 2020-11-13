
<script type="text/javascript">

  $msg = '<?php echo json_encode($user_data, JSON_UNESCAPED_UNICODE); ?>';
  console.log($msg);

</script>

<?php
  if($user_data->data->status == 'not active') { //открываю модульное окно подтвержения почты ?>
    <style media="screen">
      body.modal-open .site-wrapper{
        -webkit-filter: blur(5px);
        -moz-filter: blur(5px);
        -o-filter: blur(5px);
        -ms-filter: blur(5px);
        filter: blur(5px);
      }
    </style>

    <div class="modal fade modal_backdrop" id="confirmation_mail" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="recoveryLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header" style=" color: #000; padding: 0rem; border-bottom: none;">
            <img src="../img/lpm-connect3.png" width="50" style="margin: 10px 10px;">
          </div>
          <div class="modal-body" >
              <div class="row justify-content-center">
                  <div class="col-md-12 col-sm-12" >
                    <center>
                      <img src="/img/paper-plane.png" alt="" width="42">
                      <h4>Подтверждение почты</h4>
                      <div class="">
                        <h6>Мы отправили письмо на Ваш email <?php echo $user_data->data->email; ?> указанный при регистрации.</h6>
                      </div>
                    </center>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
             <a href="#" class="text-right" onclick="resend_mail(this);" style="color:#afc71e;">Повторить отправку</a>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">

    function resend_mail(el) {
      $.ajax({
        url: '/general/actions/based_confirmation?action=resend_mail',
        method: 'POST',
        dataType: 'html',
        success: function(result) {
          if (IsJsonString(result)) {
            arr = JSON.parse(result);
            if (arr["response"] == true) {
              alerts('success', '', arr["description"]);
            } else {
              alerts('warning', 'Ошибка', arr["description"]);
            }
          }
        },
        error: function(jqXHR, exception) {
          alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
        }
      });
    }


    $(document).ready(function() {
    	<?php if($user_data->data->status == 'not active') echo "$('#confirmation_mail').modal('show');" ?>
    });
    </script>
  <?
  }
?>

<?php
  if($user_data->data->status == 'active' && $user_data->data->phone == '') { //открываю модульное окно подтвержения номера телефона ?>
    <style media="screen">
      body.modal-open .site-wrapper{
        -webkit-filter: blur(5px);
        -moz-filter: blur(5px);
        -o-filter: blur(5px);
        -ms-filter: blur(5px);
        filter: blur(5px);
      }
      .signin-sms__wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin: 0 auto;
        padding: 0;
        /* background: #000; */
        /* padding: 5em; */
        transition: opacity 0.3s ease;
      }
      .signin-sms__wrap input {
        display: inline-block;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        width: 0.45em;
        height: 50px;
        padding: 10px 20px;
        margin: 5px;

        border: 0 solid #ffffff;
        border-bottom: 4px solid #ffff00;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        text-align: center;
        font: normal 38px/0 "Times New Roman", Times, serif;
        color: rgba(0,142,198,1);
        -o-text-overflow: clip;
        text-overflow: clip;
        -webkit-box-shadow: 0 2px 2px 0 rgba(180,180,180,1) ;
        box-shadow: 0 2px 2px 0 rgba(180,180,180,1) ;
        outline: none;
        -webkit-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1);
        -moz-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1);
        -o-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1);
        transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1);
      }
      /* .signin-sms__wrap input {
        width: 100%;
        flex: 0 0 23%;
        padding: 0;
        margin: 0;
        text-align: center;
        min-height: 80px;
        font-size: 2em;
        box-shadow: none;
        outline: none;
      } */
      /* .signin-sms__wrap input:not(:first-child) {
        margin-left: 1%;
      } */

      .signin-sms__wrap.done {
        opacity: 0;
      }
    </style>
    <script src="../js/jquery.inputmask.bundle.js"></script>
    <div class="modal fade modal_backdrop" id="confirmation_phone" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="recoveryLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header" style=" color: #000; padding: 0rem; border-bottom: none;">
            <img src="../img/lpm-connect3.png" width="50" style="margin: 10px 10px;">
          </div>
          <div class="modal-body" >
              <div class="row justify-content-center">
                  <div class="col-md-12 col-sm-12" style="position:relative; min-height:15em;">
                    <div class="col-12" id="div_confirmation_phone" style="position:absolute; left:0; right:0; z-index:5; opacity:1;">
                      <center>
                        <img src="/img/paper-plane.png" alt="" width="42">
                        <h4>Подтверждение телефона</h4>
                        <div class="">
                          <form class="" onsubmit="form_send_cod();" id="form_confirmation_phone" style="z-index:5; opacity:1;" action="/general/actions/based_confirmation_phone?action=phone" method="POST">
                            <div class="form-group" style="margin-top: 4%;">
                              <input style="border-radius: 5px;" type="text" class="form-control"  id="phone_number" oninput="check_phone(this);" title="Введите телефон для связи"  placeholder="+7 (999) 000 00 00" data-inputmask="'alias': 'phonebe'" value="" name="phone" required>
                            </div>
                            <button class="btn btn-block" type="submit" name="submit" disabled style="opacity:0.5;">Отправить код</button>
                          </form>
                          <form class="" id="form_confirmation_cod_phone" style="z-index:2; opacity:0;" action="/general/actions/based_confirmation_phone?action=cod" method="POST">
                            <div class="form-group" style="margin-top: 4%;">
                              <input style="border-radius: 5px;" type="text" class="form-control"  id="phone_code" oninput="" value="" name="code" required>
                            </div>
                            <!-- <button class="btn btn-block" type="submit" name="submit" disabled style="opacity:0.5;">Подтвердить</button> -->
                          </form>
                        </div>
                      </center>
                    </div>
                    <div class="col-12" id="div_confirmation_code" style="position:absolute; left:0; right:0; z-index:2; opacity:0;">
                      <center>
                        <img src="/img/paper-plane.png" alt="" width="42">
                        <h4>Укажите код поддтвержения</h4>
                        <div class="">
                          <form class="" id="form_confirmation_cod_phone" action="/general/actions/based_confirmation_phone?action=cod" method="POST">
                            <!-- <div class="form-group" style="margin-top: 4%;">
                              <input style="border-radius: 5px;" type="text" class="form-control"  id="phone_code" oninput="" value="" name="code" required>
                            </div> -->

                            <!-- <button class="btn btn-block" type="submit" name="submit" disabled style="opacity:0.5;">Подтвердить</button> -->
                            <div class="signin-sms__wrap">
                              <input class="sms-input" type="number" maxlength="1" tabindex="1">
                              <input class="sms-input" type="number" maxlength="1" tabindex="2">
                              <input class="sms-input" type="number" maxlength="1" tabindex="3">
                              <input class="sms-input" type="number" maxlength="1" tabindex="4">
                            </div>

                          </form>
                        </div>
                      </center>
                    </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>

<script type = "text/javascript" >
  $(document).ready(function() {

    <?php if($user_data->data->status == 'active' && $user_data->data->phone == '') echo "$('#confirmation_phone').modal('show');" ?>

    $("#phone_number").inputmask({
        mask: '+7 (999) 999 99 99',
        placeholder: ' ',
        showMaskOnHover: false,
        showMaskOnFocus: false,
        onBeforePaste: function(pastedValue, opts) {
          var processedValue = pastedValue;
          return processedValue;
        }
    });
  });

  function check_phone(el) {
    form = document.getElementById('form_confirmation_phone');
    if (el.value.replace(/[\+\-\(\)\ ]/g, '').length == 11) {
      $(form.elements.submit).removeAttr('disabled');
      $(form.elements.submit).css('opacity', '1');
    } else {
      $(form.elements.submit).attr('disabled', 'disabled');
      $(form.elements.submit).css('opacity', '0.5');
    }
  }

  function form_send_cod() {
    event.preventDefault();
    div_phone = document.getElementById('div_confirmation_phone');
    div_cod = document.getElementById('div_confirmation_code');
    $(div_phone).css('z-index', '2');
    $(div_phone).css('opacity', '0');
    $(div_cod).css('z-index', '5');
    $(div_cod).css('opacity', '1');

    $('.sms-input:first-child').focus();
  }

  $('.sms-input').on('keydown', function(e) {
    let value = $(this).val();
    let len = value.length;
    let curTabIndex = parseInt($(this).attr('tabindex'));
    let nextTabIndex = curTabIndex + 1;
    let prevTabIndex = curTabIndex - 1;
    if (len > 0) {
      $(this).val(value.substr(0, 1));
      $('[tabindex=' + nextTabIndex + ']').focus();
    } else if (len == 0 && prevTabIndex !== 0) {
      $('[tabindex=' + prevTabIndex + ']').focus();
    }
  });

  $('.sms-input:last-child').on('keyup', function(e) {
    if($(this).val() != '') {
      $('.signin-sms__wrap').addClass('done');
      alert('некая отправка кода и тп тд');
    }
  })

</script>

  <?
  }
?>


<script type="text/javascript">




</script>
