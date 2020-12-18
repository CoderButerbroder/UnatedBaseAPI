<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

  session_start();
  if(!$_SESSION['key_user']) {
    echo json_encode(array('response' => false, 'desciption' => 'Необходимо авторизоваться'), JSON_UNESCAPED_UNICODE );
    exit();
  }

  require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
  $settings = new Settings;

  $data_user = json_decode($settings->get_cur_user($_SESSION["key_user"]));
  if (!$data_user->response) {
      header('Location: http://'.$_SERVER['SERVER_NAME'].'/general/actions/logout');
      exit;
  }

  $folder_upload = "/upload/".$_SESSION['key_user']."/";
  $target_path = $_SERVER['DOCUMENT_ROOT'].$folder_upload;

  if (file_exists($target_path)) {
      //echo "папка уже существует<br>";
  } else {
      mkdir("$target_path", 0777);
    //  echo 'папка была создана<br>';
  }

  if ($_POST["action"] == 'send_avatar'){

    $data = base64_decode($_POST["data_img"]);
             list($type, $data) = explode(';', $data);
             list(, $data)      = explode(',', $data);
             $data_image = base64_decode($data);
             $name = str_replace(" ", "_", $_POST["file_name"]);
             $time = $today = date("d_m_y_H_i_s");
             $path_img = $target_path.$time.'_'.$name.".png";
             file_put_contents($path_img, $data_image);
             $size = sprintf("%u",filesize($path_img));
             $size_final = round($size/1024);
             $check_bool_upload =  $settings->upload_file('user',$data_user->data->id,$name.'.png',$folder_upload.$time.'_'.$name.'.png','png',$size_final);
             //
             if ($check_bool_upload) {
                $obj_key = json_encode((object) array('photo' => $folder_upload.$time.'_'.$name.'.png'), JSON_UNESCAPED_UNICODE );
                 $check_update_user = $settings->mass_update_user_api_field($obj_key);
                 if(json_decode($check_update_user)->response){
                   echo json_encode(array('response' => true, 'description' => 'Файл успешно загружен'), JSON_UNESCAPED_UNICODE );
                 } else {
                   echo json_encode(array('response' => false, 'description' => 'Ошибка обновления данных', 'inf' => json_decode($check_update_user)->description) ,JSON_UNESCAPED_UNICODE );
                 }
             } else {
               echo json_encode(array('response' => false, 'description' => 'Ошибка загрузки файла'), JSON_UNESCAPED_UNICODE );
             }
             exit;
  }

?>
