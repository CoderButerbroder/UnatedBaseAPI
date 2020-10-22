<?php
$token_user = $_POST['token'];

// Установка заголовков для ответа
if ($_POST['resp'] == 'xml')  { header("Content-Type: text/xml;charset=utf-8");        $response_type = 'xml';  }
if ($_POST['resp'] == 'json') { header('Content-type:application/json;charset=utf-8'); $response_type = 'json'; }
if (!$_POST['resp'])          { header('Content-type:application/json;charset=utf-8'); $response_type = 'json'; }
if (!headers_sent())          { header('Content-type:application/json;charset=utf-8'); $response_type = 'json'; }

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



$key = 'asdasdadasdweqwrq';
$asdasd = 'asdasd';
// например, с помощью openssl_random_pseudo_bytes
$plaintext = "данные для шифрования";
$cipher = "aes-128-gcm";
if (in_array($cipher, openssl_get_cipher_methods()))
{
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $asdasd);
    // сохраняем $cipher, $iv и $tag для дальнейшей расшифровки
    $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $asdasd);
    echo $original_plaintext."\n";
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
