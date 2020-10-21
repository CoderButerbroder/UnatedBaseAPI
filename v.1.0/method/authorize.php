<?php
$login = $_POST['login'];
$password = $_POST['password'];
$parsed = parse_url($_SERVER['HTTP_REFERER']);

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// // Установка заголовков для ответа
if ($_GET['resp'] == 'xml')  { header("Content-Type: text/xml;charset=utf-8");        $response_type = 'xml';  }
if ($_GET['resp'] == 'json') { header('Content-type:application/json;charset=utf-8'); $response_type = 'json'; }
if (!$_GET['resp'])          { header('Content-type:application/json;charset=utf-8'); $response_type = 'json'; }




// // Проверка зарегистрированого домена


// Проверка был ли пользователь авторизован ранее и выдан ли ему токен
if (!$token_user) {
    if ($response_type == 'json') { echo json_encode(array('error' => 'token not found')); exit; }
    if ($response_type == 'xml')  { echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><response><error>token not found</error></response>";}
}



// проверка токена на действительность (в базе данных выданных токенов)
// (php код тут)


// Выдача результата пользователю
// (php код тут)

// echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"\?\>";
// echo " <response> ";
// echo "<form>";
// echo "123";
// echo "</form>";
// echo "</response> ";


// $massiv = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
//
//
// echo $massiv[0];
// echo '<br>';
// echo $massiv[1];
// echo '<br>';
// echo $massiv[2];




?>
