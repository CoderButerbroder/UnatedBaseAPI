<?php
$array = array(
	'login'   => 'admin',
	'password' => '1234'
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.kt-segment.ru/v.1.0/method/getUser');
curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $array);
$out = curl_exec($curl);
$admin_token = (json_decode($out));
curl_close($curl);

var_dump($admin_token);


?>
