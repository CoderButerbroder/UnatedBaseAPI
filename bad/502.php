<?php
header('Content-type:application/json;charset=utf-8');

echo json_encode(array('success' => false, 'description' => 'Ошибка 502,  сервер в данный момент перегружен, и необходимо обратиться позже.'),JSON_UNESCAPED_UNICODE);
exit;
?>
