<?
// Регистрация нового юридического лица и привязвка физического лица к ней
// после создания компании пользоватлю домабляется id_etity в строку записи


include($_SERVER['DOCUMENT_ROOT'].'/v.1.0/settings.php');

if (!$token) {
    echo json_encode(array('response' => false, 'description' => 'Для получнения статуса валидности токена, обязтельно требуется токен'),JSON_UNESCAPED_UNICODE);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$token_answer = $settings->validate_token($token,$resource);

echo $token_answer;








?>
