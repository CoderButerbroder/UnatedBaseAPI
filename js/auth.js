function check_auth(form) {
  //console.log(form.elements.email);
  //console.log(form.elements.pass);
// index_dog = form.elements.email.value.indexOf('@');
// if (index_dog >= 0 && index_dog <= 1)
//     Swal.fire(
//     'Некорректно введен email',
//     '',
//     'warning');

var count_dog = (form.elements.email.value.split('@').length - 1);
if(count_dog >= 1 ) {
  var count_err = (form.elements.email.value.split('-_').length-1) + (form.elements.email.value.split('_-').length-1) + (form.elements.email.value.split('-@').length-1) + (form.elements.email.value.split('@-').length-1) + (form.elements.email.value.split('-.').length-1) + (form.elements.email.value.split('.-').length-1) + (form.elements.email.value.split('..').length-1) + (form.elements.email.value.split('.@').length-1) + (form.elements.email.value.split('@.').length-1);
  // var count_point = (form.elements.email.value.split('.').length - 1);

  if (count_err > 0 || form.elements.email.value[form.elements.email.value.length-1] == '.' || form.elements.email.value[form.elements.email.value.length-1] == '-' || form.elements.email.value[form.elements.email.value.length-1] == '_') {
      Swal.fire(
      'Некорректно введен email',
      '',
      'warning');
  } else {
      Swal.fire(
      'Продолжение авторизации',
      '',
      'warning');
  }
}

};

function check_log_input(el) {


  el.value=el.value.replace(/[^0-9A-Za-z\-\@\_\.]/g, '');

  if(el.value.length == 0 || el.value.length <= 1) {
  el.value=el.value.replace(/[^0-9A-Za-z]{1,}/g, '');
} else {
  el.value=el.value.replace(/[\@]{2,}/g, '@');
  el.value=el.value.replace(/[\_]{2,}/g, '_');
  el.value=el.value.replace(/[\-]{2,}/g, '-');
  var count_dog = (el.value.split('@').length - 1);

  count_err = count_dog+(el.value.split('-@').length-1) + (el.value.split('-_').length-1) + (el.value.split('_-').length-1) + (el.value.split('@-').length-1) + (el.value.split('-.').length-1) + (el.value.split('.-').length-1) + (el.value.split('..').length-1) + (el.value.split('.@').length-1) + (el.value.split('@.').length-1);

  if( count_err > 1 ) {
    el.value=el.value.slice(0,-1);
  }

}

};

function change_view_pass(el){
  if ($(el.parentNode.parentNode.childNodes[1]).attr('type') == 'password'){
    el.innerHTML='<i class="far fa-eye-slash"></i>';
    $(el.parentNode.parentNode.childNodes[1]).attr('type', 'text');
  } else {
    el.innerHTML='<i class="far fa-eye"></i>';
    $(el.parentNode.parentNode.childNodes[1]).attr('type', 'password');
  }
  return false;
};
