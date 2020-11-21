<?php
header('Content-type:application/json;charset=utf-8');

echo json_encode(array('response' => false, 'description' => 'Ошибка 500, внутренняя ошибка сервера'),JSON_UNESCAPED_UNICODE);
exit;
?>
