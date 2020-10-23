<?php
header('Content-type:application/json;charset=utf-8');

echo json_encode(array('success' => false, 'description' => 'Ошибка 401, нет авторизации'),JSON_UNESCAPED_UNICODE);
exit;
?>
