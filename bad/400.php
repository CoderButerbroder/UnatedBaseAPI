<?php

header('Content-type:application/json;charset=utf-8');
echo json_encode(array('response' => false, 'description' => 'Ошибка 400, в запросе присутствует синтаксическая ошибка'),JSON_UNESCAPED_UNICODE);
exit;

?>
