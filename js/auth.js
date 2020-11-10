function check_auth(form) {



};

function change_view_pass(el){
  if ($(el.parentNode.parentNode.childNodes[1]).attr('type') == 'password'){
    el.innerHTML='<i style="color: #afc71e;" class="far fa-eye-slash"></i>';
    $(el.parentNode.parentNode.childNodes[1]).attr('type', 'text');
  } else {
    el.innerHTML='<i style="color: #afc71e;" class="far fa-eye"></i>';
    $(el.parentNode.parentNode.childNodes[1]).attr('type', 'password');
  }
  return false;
};
