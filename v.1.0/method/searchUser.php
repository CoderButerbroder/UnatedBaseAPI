<?php
$token_user = $_POST['token'];

// Установка заголовков для ответа
header('Content-type:application/json;charset=utf-8');
$response_type = 'json';

// проверка наличия токена авторизации пользователя

// if (!$token_user) {
//     if ($response_type == 'json') echo json_encode(array('error' => 'token not found')); exit;
//     if ($response_type == 'xml') echo '<response><error>token not found</error></response>'; exit;
// }


// проверка токена на действительность (в базе данных выданных токенов)
// (php код тут)

// if (!$token_valid) {
//     if ($response_type == 'json') echo json_encode(array('error' => 'token is not valid')); exit;
//     if ($response_type == 'xml') echo '<response><error>token is not valid</error></response>'; exit;
// }

// Выдача результата пользователю
// (php код тут)



$key = 'eJzbqfSooYm8P51dZ6sWnw5Oz786FGxZ';
$asdasd = 'asdasd';
// например, с помощью openssl_random_pseudo_bytes
//$plaintext = "данные для шифрования";

$obj_data = (object) [
                        "user" => 'lw0E1vLiW7BC4Db1HOdDMIrqzX4Vn99H',
                        "data_making" => date("Y-m-d H:i:s"),
                        "live" => "259200" // 3 дня в секундах
];

$plaintext = json_encode($obj_data, JSON_UNESCAPED_UNICODE);

if(json_last_error() != 0){
  echo "error";
  exit();
}

echo "\nОригинальный текст: ".$plaintext."\n";
foreach ($obj_data as $key => $value) {
  echo $key." = ".$value."\n";
}
echo "\n";


$method = "AES-128-CBC";

$ivlen = openssl_cipher_iv_length($method);
$iv = openssl_random_pseudo_bytes($ivlen);

$text_cript = openssl_encrypt($plaintext, $method, $key, $options=0, $iv);

// сохраняем $cipher, $iv и $tag для дальнейшей расшифровки
$text_encript = openssl_decrypt($text_cript, $method, $key, $options=0, $iv);
//echo $text_cript."\n";

echo "\n***************************************************************\n";
echo "\nЗашифрованный текст:".$text_cript."\n";
echo "\n***************************************************************\n";
echo "\nРасшифрованный текст:".$text_encript."\n";

foreach (json_decode($text_encript, false) as $key => $value) {
  echo $key." = ".$value."\n";
}

// $massiv = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
//
//
// echo $massiv[0];
// echo '<br>';
// echo $massiv[1];
// echo '<br>';
// echo $massiv[2];




?>
