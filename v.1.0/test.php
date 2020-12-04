<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Базовая разметка HTML</title>
</head>
<!-- jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<body>
    <h1>Code Basics</h1>
    <input type="checkbox" id="checkbox" checked>
</body>

<script type="text/javascript">
var flag = true;
  $(document).ready(function(){

    let timerId = setTimeout(function tick() {

      if ($('#checkbox').is(':checked') && flag) {
        console.log('<?php echo $_SERVER["SERVER_NAME"];?>/admin/help/sinc_tboil_user_reverse.php');
        flag = false;
        $.ajax({
        	url: 'https://<?php echo $_SERVER["SERVER_NAME"];?>/admin/help/sinc_tboil_user_reverse.php',
        	method: 'get',
          async : false,
          timeout: 0,
        	success: function(data){
            flag = true;
        	},
          error: function(x, t, e){
            if( t === 'timeout') {
                 // Произошел тайм-аут
                } else {
                     flag = true;
                }
            },
        });
      }

      timerId = setTimeout(tick, 2000); // (*)
    }, 2000);

  });
</script>
</html>
