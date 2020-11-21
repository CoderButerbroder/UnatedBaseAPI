<?php
header('Content-type:application/json;charset=utf-8');

echo json_encode(array('response' => false, 'description' => 'Ошибка 503, сервер недоступен. Сервер, по техническим причинам, временно не может обрабатывать запросы.'),JSON_UNESCAPED_UNICODE);
exit;
?>
