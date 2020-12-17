<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

  require_once($_SERVER['DOCUMENT_ROOT'].'/general/core.php');
  $settings = new Settings;

  $folder_upload = "/upload/".$_SESSION['cur_user_hash']."/";
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
             $time = $today = date("d.m.y_H:i:s");
             $path_img = $target_path.$time.'_'.$name.".png";
             file_put_contents($path_img, $data_image);
             $size = sprintf("%u",filesize($path_img));
             $size_final = round($size/1024);
             $check_bool_upload =  $settings->upload_file('user',$_SESSION['cur_user_id'],$name.'.png',$folder_upload.$time.'_'.$name.'.png','png',$size_final);
             //
             if ($check_bool_upload) {
                 $check_update_user = $settings->update_data_user($_SESSION['cur_user_id'],'photo',$folder_upload.$time.'_'.$name.'.png');
                 if($check_update_user){
                   echo 'true';
                 } else {
                   echo 'error';
                 }
             } else {
                 echo 'error';
             }
             exit;
  }

?>
