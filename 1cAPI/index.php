<?php
// header('Content-type:application/json;charset=utf-8');
// echo json_encode(array('response' => false, 'description' => 'Ошибка 404, не верный метод API'),JSON_UNESCAPED_UNICODE);
// exit;


?>


<!DOCTYPE html>
<html lang="ru" >
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="/assets/js/jquery-3.5.1.min.js"></script>

  </head>
  <body>
    <form class="" action="/1cAPI/method/downResidLPM?key=54ae3f5d9095dbbd97e8dacc2a3f51c8a98f2f92"  id="test" method="post" enctype="multipart/form-data">
      <input type="file" name="residents">
      <button type="submit" name="button" >send</button>
    </form>
  </body>
</html>
