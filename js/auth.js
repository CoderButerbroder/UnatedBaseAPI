function check_auth(form) {

  $.ajax({
  	url: '/general/actions/based_auth.php',
  	method: 'POST',
  	dataType: 'html',
    data: form.serialize(),
  	success: function(result){
      Swal.fire(
        'что то ',
        ''+result,
        'success'
      );
  	},
    error: function (jqXHR, exception) {
      Swal.fire(
        'Ошибка подключения',
        '',
        'error'
      );
	}
  });

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
