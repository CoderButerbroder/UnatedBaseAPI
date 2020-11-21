<?php
header('Content-type:application/json;charset=utf-8');

echo json_encode(array('response' => false, 'description' => 'Ошибка 501, сервер не поддерживает технологий, которые необходимы для обработки запроса, либо не понимает, чего от него хотят'),JSON_UNESCAPED_UNICODE);
exit;
?>
