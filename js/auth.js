$(document).ready(function() {
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

function check_auth(act,form) {

  $.ajax({
    url: $(form).attr('action'),
    method: 'POST',
    dataType: 'html',
    data: $(form).serialize(),
    success: function(result) {
      if (IsJsonString(result)) {
        arr = JSON.parse(result);
        if (arr["response"] == true) {
          if(act == 'auth' || act == 'reg') window.location.href = "https://" + window.location.host + "/profile";
          if(act == 'rec') {
            alerts('success', arr["description"], '');
            $('#recovery').modal('hide');
          }
          if(act == 'rec_p') {
            alerts('success', arr["description"], '');
            $('#recovery_pass').modal('hide');
          }


        } else {
          alerts('warning', arr["description"], '');
        }
      }
    },
    error: function(jqXHR, exception) {
      alerts('error', 'Ошибка подключения', '');
    }
  });
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
        return false;
    }
    return true; //good user input
};

function check_char(flag,value) {
  var pattern = (flag) ? new RegExp(/[A-Z]/) : new RegExp(/[a-z]/);
    if (pattern.test(value)) {
        return false;
    }
    return true; //good user input
};

function verification_passwords(el) {

  form = document.getElementById('form_rec_p');
  pass1 = form.elements.password;
  pass2 = form.elements.confirm_password;
  btn = form.elements.btn_sub;
  sm_txt = document.getElementById('small_text_rec_p');

  if(pass1.length <= 5 || pass2.length <= 5 || pass1.value != pass2.value || check_char(false, el.value) || check_char(true, el.value) || check_spec_char(true,el.value) || check_spec_char(false,el.value) )  {
    $(btn).attr('disabled', 'disabled');
    $(btn).css('opacity', '0.5');
    sm_txt.innerHTML = 'Укажите пароль от 6 символов используя спец. символы, цифры и буквы разного регистра';
    return 0;
  } else {
    $(btn).removeAttr('disabled');
    $(btn).css('opacity', '1');
    sm_txt.innerHTML = '';
    return 0;
  }

  // console.log(pass1);
  // console.log(pass2);



};

function change_view_pass(el) {
  if ($(el.parentNode.parentNode.childNodes[1]).attr('type') == 'password') {
    el.innerHTML = '<i style="color: #afc71e;" class="far fa-eye-slash"></i>';
    $(el.parentNode.parentNode.childNodes[1]).attr('type', 'text');
  } else {
    el.innerHTML = '<i style="color: #afc71e;" class="far fa-eye"></i>';
    $(el.parentNode.parentNode.childNodes[1]).attr('type', 'password');
  }
  return false;
};
