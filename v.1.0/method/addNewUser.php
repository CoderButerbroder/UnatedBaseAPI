<?php
// Регистрация нового пользователя (физического лица) через тбоил и запись данных в базу данных

/*
 Регистрация пользователя с перенаправлением из письма:

 POST /api/v2/registerRedirect/?token=токен
 поля формы (соответствуют полям при регистрации, тут после двоеточия пример значения):
 email:te22st2tes2t@test.ru
 name:Test
 secondName:TestTest
 lastName:Testest
 profession:test
 phone:+79180169656
 company:test
 city:Piter
 redirectUrl:http://vk.com
 password:PasswordD1

регистрируем пользовател на tboil
получаем ответ и причину уже возвращаем если ошибка
или продолжаем регистрацию у себя в бд приязывая ид тбоил к себе..
*/




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



?>
