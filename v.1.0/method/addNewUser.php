<?php
// Регистрация нового пользователя (физического лица) через тбоил и запись данных в базу данных

/*
 Регистрация пользователя с перенаправлением из письма:

регистрируем пользовател на tboil
получаем ответ и причину уже возвращаем если ошибка
или продолжаем регистрацию у себя в бд приязывая ид тбоил к себе..
*/
<<<<<<< HEAD

$key = $_POST['login'];
$login = $_POST['login'];
$pass = $_POST['password'];
$email = $_POST['email'];
$resource = $_POST['referer'];
$token = $_POST['token'];
$name = $_POST['name'];
$secondName = $_POST['secondname'];
$lastName = $_POST['lastname'];
$profession = $_POST['profession'];
$phone = $_POST['phone'];
$company = $_POST['company'];
$city = $_POST['Piter'];
$redirectUrl = $_POST['redirectUrl'];


$pass = $_POST['password'];
$email = $_POST['email'];
$name = $_POST['name'];
$secondName = $_POST['secondname'];
$lastName = $_POST['lastname'];
$profession = $_POST['profession'];
$phone = $_POST['phone'];
$company = $_POST['company'];
$city = $_POST['Piter'];
$redirectUrl = $_POST['redirectUrl'];





if (!$key || !$pass) {
    echo json_encode(array('error' => 'Обязательно требуется логин и пароль'),JSON_UNESCAPED_UNICODE);
    exit;
}

=======
include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');



if (!$email || !$pass || !$name || !$lastName) {
    echo json_encode(array('response' => false, 'description' => 'Обязательно требуется email, пароль, Фамилия и Имя'),JSON_UNESCAPED_UNICODE);
  exit;
} else {
  $secondName = (!$secondName) ? ' ' : $secondName;
  $profession = (!$profession) ? ' ' : $profession;
  $phone = (!$phone) ? ' ' : $phone;
  $company = (!$company) ? ' ' : $company;
  $city = (!$city) ? ' ' : $city;
  $redirectUrl = (!$redirectUrl) ? ' ' : $redirectUrl;


  include($_SERVER['DOCUMENT_ROOT'].'/general/core2.php');

  $settings2 = new Settings2;
  //некая функция регистрации на тбоил
  $response_tboil = $settings2->registerRedirect_tboil($email,$name,$secondName,$lastName,$profession,$phone,$company,$city,$redirectUrl,$pass));
  $arr_response_tboil = json_decode($response_tboil);
  //проверяем что при декодировании небыло ошибок, а результат будет массивом или обьектом
  if(json_last_error() == JSON_ERROR_NONE && (is_array($response_tboil) || is_object($response_tboil))) {
    //если tboil при регистрации вернул ошибку
    if($arr_response_tboil["response"] == false) {
        echo $response_tboil;
        exit;
      } else {
        //а тут пользователь регистрироваля на tboil а тперь у нас в бд
      }
    } else {
      echo json_encode(array('response' => false, 'description' => 'Ошибка промежуточного подключения к tboil'),JSON_UNESCAPED_UNICODE);
      exit();
    }

  }
>>>>>>> fe90e15be3657f0a6a42289b4aa197e9eecb6806


?>
