
var capcha1,capcha2,capcha3,capcha4;
var onloadCallback = function() {
           var sitekey = '6Lc2muEZAAAAANkdNWL9ktrDN4jFng-kfR0x5vDx';
           if($('#form_auth').attr('id')) {
             capcha1 = grecaptcha.render('capcha_auth', {
                 'sitekey' : sitekey,
                 'callback': 'submit_auth',
                 'size':'invisible'
             });
           }
           if($('#form_reg').attr('id')) {
             capcha2 = grecaptcha.render('capcha_reg', {
                 'sitekey' : sitekey,
                 'callback': 'submit_reg',
                 'size':'invisible'
             });
           }
           if($('#form_rec').attr('id')) {
             capcha3 = grecaptcha.render('capcha_rec', {
                 'sitekey' : sitekey,
                 'callback': 'submit_rec',
                 'size':'invisible'
             });
           }
           if($('#form_rec_p').attr('id')) {
             capcha4 = grecaptcha.render('capcha_rec_p', {
                 'sitekey' : sitekey,
                 'callback': 'submit_rec_p',
                 'size':'invisible'
             });
          }
       };

$(document).ready(function() {

  // $("#phone_number").inputmask({
  //   mask: '+7 (999) 999 99 99',
  //   placeholder: ' ',
  //   showMaskOnHover: false,
  //   showMaskOnFocus: false,
  //   onBeforePaste: function(pastedValue, opts) {
  //     var processedValue = pastedValue;
  //     return processedValue;
  //   }
  // });

  if($('#form_auth').attr('id')) $('#form_auth').submit(function (event)   {   event.preventDefault();   grecaptcha.execute(capcha1); });
  if($('#form_reg').attr('id'))   $('#form_reg').submit(function (event)    {   event.preventDefault();   grecaptcha.execute(capcha2); });
  if($('#form_rec').attr('id')) $('#form_rec').submit(function (event)    {   event.preventDefault();   grecaptcha.execute(capcha3); });
  if($('#form_rec_p').attr('id')) $('#form_rec_p').submit(function (event)  {   event.preventDefault();   grecaptcha.execute(capcha4); });

});

function submit_auth(token)  {   check_auth('auth','form_auth'); };
function submit_reg(token)   {   check_auth('reg','form_reg'); };
function submit_rec(token)   {   check_auth('rec','form_rec'); };
function submit_rec_p(token) {   check_auth('rec_p','form_rec_p'); };

function check_auth(act,form_id) {
  form = document.getElementById(form_id);
  $(form["btn_sub"]).attr('disabled', 'disabled');
  $.ajax({
    url: $(form).attr('action'),
    method: 'POST',
    dataType: 'html',
    data: $(form).serialize(),
    success: function(result) {
      $(form["btn_sub"]).removeAttr('disabled');
      if (IsJsonString(result)) {
        arr = JSON.parse(result);
        if (arr["response"] == true) {
          if(act == 'auth' || act == 'reg') window.location.href = "https://" + window.location.host + "/panel";
          if(act == 'rec') {
            alerts('success', arr["description"], '');
            $('#recovery').modal('hide');
          }
          if(act == 'rec_p') {
            alerts('success', arr["description"], '');
            setTimeout('window.location.href = "https://" + window.location.host;', 1500);
          }
        } else {
          alerts('warning', 'Ошибка', arr["description"]);
        }
      }
    },
    error: function(jqXHR, exception) {
      $(form["btn_sub"]).removeAttr('disabled');
      alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
    }
  });

  if(act == 'auth')    grecaptcha.reset(capcha1);
  if(act == 'reg')     grecaptcha.reset(capcha2);
  if(act == 'rec')     grecaptcha.reset(capcha3);
  if(act == 'rec_p')   grecaptcha.reset(capcha4);
};

function IsJsonString(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
};

function alerts(v_icon, v_title, v_msg) {
  Swal.fire({
    scrollbarPadding: false,
    icon: v_icon,
    title: v_title,
    text: v_msg
  })
};

function check_spec_char(flag, value) {
    var pattern = (flag) ? new RegExp(/[0-9]/) : new RegExp(/[~`!#$@%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/);
    if (pattern.test(value)) {
        return true;
    }
    return false; //good user input
};

function check_char(flag,value) {
  var pattern = (flag) ? new RegExp(/[A-ZА-Я]/) : new RegExp(/[a-zа-я]/);
    if (pattern.test(value)) {
        return true;
    }
    return false; //good user input
};

function verification_passwords(el) {

  form = document.getElementById('form_rec_p');
  pass1 = form.elements.password;
  pass2 = form.elements.confirm_password;
  btn = form.elements.btn_sub;
  sm_txt = document.getElementById('small_text_rec_p');

  if(pass1.length <= 5 || pass2.length <= 5 || pass1.value != pass2.value || !check_char(false, el.value) || !check_char(true, el.value) || !check_spec_char(true,el.value) )  {
    $(btn).attr('disabled', 'disabled');
    $(btn).css('opacity', '0.5');
    $(sm_txt).css('opacity', '1');

    if (!check_char(false, el.value) || !check_char(true, el.value)) {
      msg = 'Укажите пароль из символов латинского или русского алфавита разного регистра';
      sm_txt.innerHTML = msg;
      return 0;
    }

    if (pass1.length <= 5 || pass2.length <= 5) {
      msg = 'Укажите пароль длинной не менее 6 символов';
      sm_txt.innerHTML = msg;
      return 0;
    }

    if ( pass1.value != pass2.value) {
      msg = 'Пароли не совпадают';
      sm_txt.innerHTML = msg;
      return 0;
    }

    msg = 'Укажите пароль от 6 символов используя цифры и буквы разного регистра';
    sm_txt.innerHTML = msg;
    return 0;

  } else {
    $(btn).removeAttr('disabled');
    $(btn).css('opacity', '1');
    $(sm_txt).css('opacity', '0');
    //sm_txt.innerHTML = '';
    return 0;
  }

  // console.log(pass1);
  // console.log(pass2);



};

function change_view_pass(el) {
    if ($(el.previousElementSibling).attr('type') == 'password'){
      $(el).removeClass();
      $(el).addClass("icon_pass far fa-eye-slash");
      $(el.previousElementSibling).attr('type', 'text');
    } else {
      $(el).removeClass();
      $(el).addClass("icon_pass far fa-eye");
      $(el.previousElementSibling).attr('type', 'password');
    }
  // if ($(el.parentNode.parentNode.childNodes[1]).attr('type') == 'password') {
  //   el.innerHTML = '<i style="color: #afc71e;" class="far fa-eye-slash"></i>';
  //   $(el.parentNode.parentNode.childNodes[1]).attr('type', 'text');
  // } else {
  //   el.innerHTML = '<i style="color: #afc71e;" class="far fa-eye"></i>';
  //   $(el.parentNode.parentNode.childNodes[1]).attr('type', 'password');
  // }
  return false;
};
