function check_auth(form) {
  //console.log(form.elements.email);
  //console.log(form.elements.pass);
console.log(form.elements.email.value);
//console.log(form.elements.email.value.indexOf('@'));


}

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
