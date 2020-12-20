function switch_new_user_css_style(new_style) {

    $.ajax({
          method: 'POST',
          url: 'https://'+ window.location.host+'/general/actions/switch_css_style_user',
          data: 'css_style='+new_style,
              success: function(result) {
                if (IsJsonString(result)) {
                  arr = JSON.parse(result);
                  if (arr["response"] == true) {
                      window.location.reload();
                  } else {
                      alerts('warning', 'Внимание', arr["description"]);
                  }
                } else {
                    alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
                }
              },
              error: function(jqXHR, exception) {
                  alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
              }
        });
}
