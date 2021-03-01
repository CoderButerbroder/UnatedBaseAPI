<?php

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

session_start();
if (!isset($_SESSION["key_user"])) {
  // echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}
if (!isset($_POST["query"])) {
  // echo json_encode(array('response' => false, 'description' => 'Ошибка проверки авторизации'), JSON_UNESCAPED_UNICODE);
  exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
$settings = new Settings;

$data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
session_write_close();
$data_user_rules = json_decode($settings->get_user_rules($data_user->data->role))->rules;
if (!$data_user_rules->sistem->rule->settings->value) {
  // echo json_encode(array('response' => false, 'description' => 'Ошибка, Недостаточно прав'), JSON_UNESCAPED_UNICODE);
  exit();
}

$api_yandex_map_key = $settings->get_global_settings('api_yandex_map_key');

$format = 'json';
//$query = urlencode("Санкт-Петербург ".$_POST["query"]);
$query = urlencode($_POST["query"]);

$return = file_get_contents('https://geocode-maps.yandex.ru/1.x/?apikey='.$api_yandex_map_key.'&format='.$format.'&geocode='.$query);

$arr_return = json_decode($return);
if(($arr_return == NULL) || ($arr_return == false)){
  echo NULL;
  exit();
}


function get_param_street($arr, $search)
{
  foreach ($arr as $key => $value) {
    if ($value->kind == $search) {
      return $value->name;
    }
  }
}


$arr_result = (object)["results" => [], "pagination" => (object) ["more" => true]];
$i = 1;

foreach ($arr_return->response->GeoObjectCollection->featureMember as $key) {
  // $temp_str = (object) ["pos" => $key->GeoObject->Point->pos,
  //                        "adr" => $key->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->Locality->LocalityName." "
  //                        .$key->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->Locality->Thoroughfare->ThoroughfareName,
  //                        "house" => $key->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->Locality->Thoroughfare->Premise->PremiseNumber,
  //                        "post_kod" => $key->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->Locality->Thoroughfare->Premise->PostalCode->PostalCodeNumber];
  $temp_long_param = $key->GeoObject->metaDataProperty->GeocoderMetaData->Address->Components;
  $temp_adr = get_param_street($temp_long_param, 'country').' '.get_param_street($temp_long_param, 'country').' '.$key->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->AdministrativeAreaName.' '.get_param_street($temp_long_param, 'locality').' '.get_param_street($temp_long_param, 'street');
  $temp_str = (object) [ "pos" => $key->GeoObject->Point->pos,
                         "adr" => $temp_adr,
                         "house" => get_param_street($key->GeoObject->metaDataProperty->GeocoderMetaData->Address->Components, 'house'),
                         "post_kod" => $key->GeoObject->metaDataProperty->GeocoderMetaData->Address->postal_code ];

  $temp_arr = (object) ["id" => json_encode($temp_str, JSON_UNESCAPED_UNICODE ), "text" =>  $key->GeoObject->description." ".$key->GeoObject->name];
  array_push($arr_result->results, $temp_arr);
  $i++;
}

echo json_encode($arr_result, JSON_UNESCAPED_UNICODE );

?>
