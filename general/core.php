<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
require_once(__DIR__.'/plugins/smtp/PHPMailer.php');
require_once(__DIR__.'/plugins/smtp/SMTP.php');
require_once(__DIR__.'/plugins/smtp/Exception.php');
require_once(__DIR__.'/DATAroot.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/dadata/DadataClient.php');

class Settings {

  private $gen_settings = 'API_SETTINGS';
  private $users = 'API_USERS';
  private $tokens = 'API_TOKENS';
  private $api_referer = 'API_REFERER';
  private $history = 'API_HISTORY';
  private $API_USERS_SOCIAL = 'API_USERS_SOCIAL';
  private $API_UPLOAD_FILES = 'API_UPLOAD_FILES';
  private $user_referer = 'TIME_user_referer';
  private $main_users = 'MAIN_users';
  private $main_users_social = 'MAIN_users_social';
  private $MAIN_entity = 'MAIN_entity';
  private $MAIN_entity_additionally = 'MAIN_entity_additionally';
  private $MAIN_entity_tech_requests = 'MAIN_entity_tech_requests';
  private $MAIN_entity_tech_requests_solutions = 'MAIN_entity_tech_requests_solutions';
  private $MAIN_entity_tech_services = 'MAIN_entity_tech_services';
  private $MAIN_entity_tech_services_comments = 'MAIN_entity_tech_services_comments';
  private $MAIN_entity_tech_services_rating = 'MAIN_entity_tech_services_rating';
  private $MAIN_entity_tech_services_view = 'MAIN_entity_tech_services_view';
  private $MAIN_users_accounts = 'MAIN_users_accounts';
  private $errors_migrate = 'errors_migrate';
  private $MAIN_events = 'MAIN_events';
  private $MAIN_users_events = 'MAIN_events_users';
  private $MAIN_entity_events = 'MAIN_events_entity';
  private $IPCHAIN_entity = 'IPCHAIN_entity';
  private $IPCHAIN_StateSupport = 'IPCHAIN_StateSupport';
  private $IPCHAIN_Project = 'IPCHAIN_Project';
  private $IPCHAIN_IpObjects = 'IPCHAIN_IpObjects';
  private $MAIN_support_ticket = 'MAIN_support_ticket';
  private $MAIN_support_ticket_messages = 'MAIN_support_ticket_messages';
  private $MAIN_support_ticket_conclusion = 'MAIN_support_ticket_conclusion';
  private $API_USERS_ROLE = 'API_USERS_ROLE';
  private $MAIN_support_ticket_status_history = 'MAIN_support_ticket_status_history';

  // проверка json на валидность
  public function isJSON($string) {
      return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
  }

  // Отправка email любому пользователю с любой темой и текстом из системы
  public function send_email_user($user_email,$tema,$content) {
      global $database;

          $mail = new PHPMailer\PHPMailer\PHPMailer();
          try {
              $msg = "OK";
              $mail->isSMTP();
              $mail->CharSet = "UTF-8";
              $mail->SMTPAuth   = true;

              $email_host2 = $this->get_global_settings('email_host');
              $email_username2 = $this->get_global_settings('email_username');
              $email_pass2 = $this->get_global_settings('email_pass');
              $email_secure2 = $this->get_global_settings('email_secure');
              $email_port2 = $this->get_global_settings('email_port');
              $email_name2 = $this->get_global_settings('email_name');

              $mail->Host       = $email_host2;
              $mail->Username   = $email_username2;
              $mail->Password   = $email_pass2;
              $mail->SMTPSecure = $email_secure2;
              $mail->Port       = $email_port2;
              $mail->setFrom($email_username2,$email_name2);

              // Получатель письма
              $mail->addAddress($user_email);

                  $mail->isHTML(true);

                  $mail->Subject = $tema;
                  $mail->Body    = $content;
                  $mail->IsHTML(true);

                if ($mail->send()) {
                      return json_encode(array('response' => true, 'description' => 'Письмо успешно отправлено на адрес '.$user_email),JSON_UNESCAPED_UNICODE);
                } else {
                     return json_encode(array('response' => false, 'description' => 'Ошибка отправки письма на адрес '.$user_email.', пожалуйста, попробуйте позже'),JSON_UNESCAPED_UNICODE);
                }

          } catch (Exception $e) {
                return json_encode(array('response' => false, 'description' => 'Системная ошибка отправки письма, пожалуйста попробуйте позже'),JSON_UNESCAPED_UNICODE);
          }

  }

  // получение глобального парметра настройки
  public function get_global_settings($meta_key) {
      global $database;

      $check_user_data = $database->prepare("SELECT * FROM $this->gen_settings WHERE meta_key = :meta_key");
      $check_user_data->bindParam(':meta_key', $meta_key, PDO::PARAM_STR);
      $check_user_data->execute();
      $user = $check_user_data->fetch(PDO::FETCH_OBJ);
      if ($user) {
        return $user->meta_value;
      }
      else {
        return false;
      }

  }

  // обновление глобального параметра настройки
  public function update_global_settings($meta_key,$new_value) {
      global $database;

      $upd_profile = $database->prepare("UPDATE $this->gen_settings SET meta_value = :newvalue WHERE meta_key = :meta_key");
      $upd_profile->bindParam(':meta_key', $meta_key, PDO::PARAM_STR);
      $upd_profile->bindParam(':newvalue', $new_value, PDO::PARAM_STR);
      $temp = $upd_profile->execute();
      $count = $upd_profile->rowCount();

      if ($count) {
          return true;
      }
      else {
          return false;
      }


  }

  // Получение всех пользователей базы данных
  public function get_all_bd_users() {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->users");
      $statement->execute();
      $user = $statement->fetchAll(PDO::FETCH_OBJ);

      if ($user){
         return $user;
      }
      else {
         return false;
      }

  }

  // Регитсрация пользователя базы данных
  public function register_bd_user($email,$name,$lastname,$password) {
    global $database;

      $check_user = $this->get_all_bd_users();

      $user_already_exists = false;

      for ($i=0; $i < count($check_user); $i++) {
        if (in_array($email,$check_user[$i])) {
            $user_already_exists = true;
        }
      }

      if ($user_already_exists == false) {

          $password = password_hash($password, PASSWORD_DEFAULT);
          $today = date("Y-m-d H:i:s");
          $hash = md5($email.$name.$lastname.$password.rand(0,90000).$today);
          $status = 'active';

          $new_user = $database->prepare("INSERT INTO $this->users (email,name,lastname,password,hash,status) VALUES (:email,:name,:lastname,:password,:hash,:status)");
          $new_user->bindParam(':email', $email, PDO::PARAM_STR);
          $new_user->bindParam(':name', $name, PDO::PARAM_STR);
          $new_user->bindParam(':lastname', $lastname, PDO::PARAM_STR);
          $new_user->bindParam(':password', $password, PDO::PARAM_STR);
          $new_user->bindParam(':hash', $hash, PDO::PARAM_STR);
          $new_user->bindParam(':status', $status, PDO::PARAM_STR);
          $check_new_user = $new_user->execute();
          $count = $new_user->rowCount();
          if($count > 0) {
             return json_encode(array('response' => true, 'description' => 'Пользователь БД успешно зарегистрирован', 'user_key' => $hash),JSON_UNESCAPED_UNICODE);
          } else {
             return json_encode(array('response' => false, 'description' => 'Ошибка регистрации пользователя, пожалуйста попробуйте позже'),JSON_UNESCAPED_UNICODE);
          }
      }
      else {
        return json_encode(array('response' => false, 'description' => 'Пользователь с данным email уже зарегистрирован'),JSON_UNESCAPED_UNICODE);
      }


  }

  // поиск пользователя базы данных  перед
  public function search_user($key) {
      global $database;

      $status = 'active';

      $statement = $database->prepare("SELECT * FROM $this->users WHERE hash = :hash AND status = :status");
      $statement->bindParam(':hash', $key, PDO::PARAM_STR);
      $statement->bindParam(':status', $status, PDO::PARAM_STR);
      $statement->execute();
      $user = $statement->fetch(PDO::FETCH_OBJ);

      if ($user){
         return $user;
      }
      else {
         return false;
      }

  }

  // Получение данных по польователю из расшифровки хэша пользователя
  public function get_data_user($key) {
      global $database;



  }

  // получение данных по реферу и бд
  public function get_data_referer($link) {
      global $database;

      $resource = parse_url($link, PHP_URL_HOST);

      $statement = $database->prepare("SELECT * FROM $this->api_referer WHERE resourse = :resource");
      $statement->bindParam(':resource', $resource, PDO::PARAM_STR);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data){
         if ($data->status == 'active') {
           return json_encode(array('response' => true, 'data' => $data, 'description' => 'Рефер успешно найден'),JSON_UNESCAPED_UNICODE);
         } else {
           return json_encode(array('response' => false, 'description' => 'Данный ресурс не активирован'),JSON_UNESCAPED_UNICODE);
         }
      }
      else {
         return json_encode(array('response' => false, 'description' => 'Отказ в доступе, данный ресурс не зарегистрирован'),JSON_UNESCAPED_UNICODE);
      }

  }

  // получение данных по реферу и бд
  public function get_data_referer_id($id_referer) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->api_referer WHERE id = :id");
      $statement->bindParam(':id', $id_referer, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data){
         if ($data->status == 'active') {
           return json_encode(array('response' => true, 'data' => $data, 'description' => 'Рефер успешно найден'),JSON_UNESCAPED_UNICODE);
         } else {
           return json_encode(array('response' => false, 'description' => 'Данный ресурс не активирован'),JSON_UNESCAPED_UNICODE);
         }
      }
      else {
         return json_encode(array('response' => false, 'description' => 'Отказ в доступе, данный ресурс не зарегистрирован'),JSON_UNESCAPED_UNICODE);
      }

  }

  // Запсиь в базу данных истории
  public function recording_history($referer,$method,$big_data) {
      global $database;

        // return parse_url($referer, PHP_URL_HOST);

        $data_referer = $this->get_data_referer($referer);

        if (json_decode($data_referer)->response == true) {

            $today = date("Y-m-d H:i:s");

            $new_user = $database->prepare("INSERT INTO $this->history (id_referer,method,bigdata,date_request) VALUES (:id_referer,:method,:bigdata,:date_request)");
            $new_user->bindParam(':id_referer', json_decode($data_referer)->data->id, PDO::PARAM_INT);
            $new_user->bindParam(':method', $method, PDO::PARAM_STR);
            $new_user->bindParam(':bigdata', $big_data, PDO::PARAM_STR);
            $new_user->bindParam(':date_request', $today, PDO::PARAM_STR);
            $check_new_user = $new_user->execute();
            $count = $new_user->rowCount();
            if($count > 0) {
                  return json_encode(array('response' => true, 'description' => 'Запись в истории успешно добавлена'),JSON_UNESCAPED_UNICODE);
            } else {
                  return json_encode(array('response' => false, 'description' => 'Не удалось записать в историю'),JSON_UNESCAPED_UNICODE);
            }
        } else {
              return $data_referer;
        }

  }

  // проверка пользователя на доступность емуданного ресурса
  public function user_verification_referer($id_user,$resource) {
      global $database;

      $resource = parse_url($resource, PHP_URL_HOST);

      $statement = $database->prepare("SELECT * FROM $this->api_referer WHERE id_user = :id_user AND resourse = :resource");
      $statement->bindParam(':id_user', $id_user, PDO::PARAM_INT);
      $statement->bindParam(':resource', $resource, PDO::PARAM_STR);

      $statement->execute();
      $user = $statement->fetch(PDO::FETCH_OBJ);

      if ($user) {
          return json_encode(array('response' => true, 'data' => $user),JSON_UNESCAPED_UNICODE);
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Вы не имеете прав на данный ресурс', 'resource' => $resource),JSON_UNESCAPED_UNICODE);
      }

  }

  // Запись токена в базу данных
  public function recording_token($id_user,$token,$pseudo_bytes,$resource) {
      global $database;

        $check_user = $this->user_verification_referer($id_user,$resource);

        if (json_decode($check_user)->response == true) {
            $new_user = $database->prepare("INSERT INTO $this->tokens (id_user,token,pseudo_bytes,resource) VALUES (:id_user,:token,:pseudo_bytes,:resource)");
            $new_user->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $new_user->bindParam(':token', $token, PDO::PARAM_STR);
            $new_user->bindParam(':pseudo_bytes', $pseudo_bytes, PDO::PARAM_STR);
            $new_user->bindParam(':resource', $resource, PDO::PARAM_STR);
            $check_new_user = $new_user->execute();
            $count = $new_user->rowCount();
            if($count > 0) {
               return json_encode(array('response' => true, 'description' =>  'Токен успешно выдан пользователю на его ресурс'),JSON_UNESCAPED_UNICODE);
            } else {
               return json_encode(array('response' => false, 'description' => 'Ошибка выдачи токена пользователю, попробуйте позже'),JSON_UNESCAPED_UNICODE);
            }
        }
        else {
          return $check_user;
        }

  }

  // Выдача токена пользователю
  public function get_user_token($key,$password,$resource) {
       global $database;

       $data_user = $this->search_user($key);

       if ($data_user) {
                if (password_verify($password, $data_user->password)) {

                  $date = new DateTime(date("Y-m-d H:i:s"));
                  $date->modify('+1 day');
                  $date_die = $date->format("Y-m-d H:i:s");

                  $obj_data = (object) [
                                          "user_id" => $data_user->id,
                                          "referer" => parse_url($resource, PHP_URL_HOST),
                                          "data_making" => date("Y-m-d H:i:s"),
                                          "data_die" => $date_die
                  ];

                  $plaintext = json_encode($obj_data, JSON_UNESCAPED_UNICODE);

                    if(json_last_error() != 0){
                      return json_encode(array('response' => false, 'description' => 'Внутренная ошибка шифрования, попробуйте чуть позже'));
                      exit;
                    }

                  $key = $this->get_global_settings('key_sistem');
                  $method = $this->get_global_settings('crypt_method');

                  $ivlen = openssl_cipher_iv_length($method);
                  $pseudo_bytes = openssl_random_pseudo_bytes($ivlen);

                  $token = openssl_encrypt($plaintext, $method, $key, $options=0, $pseudo_bytes);

                  // // сохраняем $method, $iv и $tag для дальнейшей расшифровки
                  // $text_encript = openssl_decrypt($text_cript, $method, $key, $options=0, $pseudo_bytes);

                  $recording_token = $this->recording_token($data_user->id,$token,bin2hex($pseudo_bytes),$resource);

                    if (json_decode($recording_token)->response == true) {
                        return json_encode(array('response' => true, 'token' => $token),JSON_UNESCAPED_UNICODE);
                    } else {
                        return $recording_token;
                    }

                } else {
                    return json_encode(array('response' => false, 'description' => 'Не верный логин или пароль'),JSON_UNESCAPED_UNICODE);
                }
      } else {
          return json_encode(array('response' => false, 'description' => 'Отказ в доступе, пользователь незарегистрирвован'),JSON_UNESCAPED_UNICODE);
      }
  }

  // Получение данных по токену
  public function get_data_token($token) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->tokens WHERE token = :token");
      $statement->bindParam(':token', $token, PDO::PARAM_STR);
      $statement->execute();
      $token = $statement->fetch(PDO::FETCH_OBJ);

      if ($token) {
          return $token;
      }
      else {
          return false;
      }

  }

  // Зашифровка данных в токен
  public function encode_token($data) {

      $key = $this->get_global_settings('key_sistem');
      $method = $this->get_global_settings('crypt_method');

      $ivlen = openssl_cipher_iv_length($method);
      $pseudo_bytes = openssl_random_pseudo_bytes($ivlen);

      $token = openssl_encrypt($data, $method, $key, $options=0, $pseudo_bytes);

      $resource = 'without resource';

      $recording_token = $this->recording_token(1,$token,bin2hex($pseudo_bytes),$resource);

        if (json_decode($recording_token)->response == true) {
            if ($token) {
                return json_encode(array('response' => true, 'token' => $token),JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(array('response' => false, 'description' => 'Не верный токен'),JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode(array('response' => false, 'description' => 'Не удалось зашифровать токен'),JSON_UNESCAPED_UNICODE);
        }



  }

  // Расшифровка токена и получение данных
  public function decode_token($token) {
      global $database;

      $data_token = $this->get_data_token($token);

      $key = $this->get_global_settings('key_sistem');
      $method = $this->get_global_settings('crypt_method');

      $text_encript = openssl_decrypt($data_token->token, $method, $key, $options=0, hex2bin($data_token->pseudo_bytes));

      if ($text_encript) {
          return json_encode(array('response' => true, 'data' => array(json_decode($text_encript))),JSON_UNESCAPED_UNICODE);
      } else {
          return json_encode(array('response' => false, 'description' => 'Не верный токен'),JSON_UNESCAPED_UNICODE);
      }

  }

  // проверка токена на годность
  public function token_expiration_check($token) {
      $data_token = $this->decode_token($token);

      if (!json_decode($data_token)->response) {
          return $data_token;
      }
      else {

          $today = date("Y-m-d H:i:s");
          $date_die = json_decode($data_token)->data[0]->data_die;
          $result = (strtotime($date_die)>strtotime($today));

          if ($result) {
              return json_encode(array('response' => true, 'description' => 'Токен годен '.$date_die),JSON_UNESCAPED_UNICODE);
          } else {
              return json_encode(array('response' => false, 'description' => 'Токен был просрочен '.$date_die),JSON_UNESCAPED_UNICODE);
          }

      }

  }

  // Проверка токена на существование в базе данных
  public function check_token_base($token) {
    global $database;



  }

  // полная проверка токена на валидность
  public function validate_token($token,$resource) {
      global $database;

      $data_token = $this->decode_token($token);

        if (json_decode($data_token)->response) {
            $data_exp = $this->token_expiration_check($token);
            if (json_decode($data_exp)->response) {
                $data_refer = $this->user_verification_referer(json_decode($data_token)->data[0]->user_id,$resource);

                if (json_decode($data_refer)->response) {
                    return json_encode(array('response' => true, 'description' => 'Токен валидный на данный ресурс'),JSON_UNESCAPED_UNICODE);
                } else {
                    return $data_refer;
                }

            } else {
                return $data_exp;
            }

        } else {
            return $data_token;
        }

  }

  // Получение ip
  public function get_ip() {
      if (!empty($_SERVER['HTTP_CLIENT_IP']))
      {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
      }
      elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
      {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
      }
      else
      {
          $ip=$_SERVER['REMOTE_ADDR'];
      }
      return $ip;
  }

  // Проверка корректности ввода ИНН
  public function is_valid_inn($inn) {
     if ( preg_match('/\D/', $inn) ) return false;

     $inn = (string) $inn;
     $len = strlen($inn);

     if ( $len === 10 )
     {
         return $inn[9] === (string) (((
             2*$inn[0] + 4*$inn[1] + 10*$inn[2] +
             3*$inn[3] + 5*$inn[4] +  9*$inn[5] +
             4*$inn[6] + 6*$inn[7] +  8*$inn[8]
         ) % 11) % 10);
     }
     elseif ( $len === 12 )
     {
         $num10 = (string) (((
              7*$inn[0] + 2*$inn[1] + 4*$inn[2] +
             10*$inn[3] + 3*$inn[4] + 5*$inn[5] +
              9*$inn[6] + 4*$inn[7] + 6*$inn[8] +
              8*$inn[9]
         ) % 11) % 10);

         $num11 = (string) (((
             3*$inn[0] +  7*$inn[1] + 2*$inn[2] +
             4*$inn[3] + 10*$inn[4] + 3*$inn[5] +
             5*$inn[6] +  9*$inn[7] + 4*$inn[8] +
             6*$inn[9] +  8*$inn[10]
         ) % 11) % 10);

         return $inn[11] === $num11 && $inn[10] === $num10;
     }

       return json_encode(array('response' => false, 'description' => 'Ошибка инн не валидный'),JSON_UNESCAPED_UNICODE);
   }

  // Функция полячения данных пользователя из единой базы данных
  public function get_user_data($id_user) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->main_users WHERE id = :id");
      $statement->bindParam(':id', $id_user, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data) {
            return json_encode(array('response' => true, 'data' => $data, 'description' => 'Пользотваль найден'),JSON_UNESCAPED_UNICODE);
      } else {
            return json_encode(array('response' => false, 'description' => 'Пользотваль c данным id не найден'),JSON_UNESCAPED_UNICODE);
      }

  }

  // Функция полячения данных пользователя из единой базы данных по id_tboil
  public function get_user_data_id_boil($id_user_id_tboil) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->main_users WHERE id_tboil = :id_tboil");
      $statement->bindParam(':id_tboil', $id_user_id_tboil, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data) {
            return json_encode(array('response' => true, 'data' => $data, 'user_id_in_ebd' => $data->id, 'description' => 'Пользотваль найден'),JSON_UNESCAPED_UNICODE);
      } else {
            return json_encode(array('response' => false, 'description' => 'Пользотваль c данным id не найден'),JSON_UNESCAPED_UNICODE);
      }

  }

  // функция регистрации физического лица
  public function register_user($email,$name,$secondName,$lastName,$position,$phone,$company,$city,$redirectUrl,$password,$resource) {
    global $database;

      $resource = parse_url($resource, PHP_URL_HOST);

      $check_reg_tboil = json_decode($this->registerRedirect_tboil($email,$name,$secondName,$lastName,$position,$phone,$company,$city,$redirectUrl,$password,$resource));

      // Регистрация пользователя на tboil прошла успешно
      if ($check_reg_tboil->response) {

                $data_user = json_decode($this->getUser_tboil($check_reg_tboil->data->userId))->data;
                $today = date("Y-m-d H:i:s");

                $hash = md5($email.$name.$secondName.$lastName.$position.$phone.$company.$city.$redirectUrl.$password.$resource.$today);
                $phone = trim($phone);
                $vowels = array("(", ")", "+", "_", "-", " ");
                $phone = str_replace($vowels, "", $phone);

                $dob = "0000-00-00";
                $default = '';
                $default_int = 0;
                $role = 'user';
                $leaderId = (isset($data_user->data->leaderId)) ? $data_user->data->leaderId : 0;

                $new_user = $database->prepare("INSERT INTO $this->main_users (id_tboil,id_leader,email,phone,name,last_name,second_name,DOB,photo,adres,inn,passport_id,id_entity,company,position,profession,hash,first_referer,reg_date,role) VALUES (:id_tboil,:id_leader,:email,:phone,:name,:last_name,:second_name,:DOB,:photo,:adres,:inn,:passport_id,:id_entity,:company,:position,:profession,:hash,:first_referer,:reg_date,:role)");
                $new_user->bindParam(':id_tboil', $check_reg_tboil->data->userId, PDO::PARAM_INT);
                $new_user->bindParam(':id_leader', $leaderId, PDO::PARAM_INT);
                $new_user->bindParam(':email', $email, PDO::PARAM_STR);
                $new_user->bindParam(':phone', $phone, PDO::PARAM_STR);
                $new_user->bindParam(':name', $name, PDO::PARAM_STR);
                $new_user->bindParam(':last_name', $lastName, PDO::PARAM_STR);
                $new_user->bindParam(':second_name', $secondName, PDO::PARAM_STR);
                $new_user->bindParam(':DOB', $dob, PDO::PARAM_STR);
                $new_user->bindParam(':photo', $default, PDO::PARAM_STR);
                $new_user->bindParam(':adres', $city, PDO::PARAM_STR);
                $new_user->bindParam(':inn', $default_int,  PDO::PARAM_INT);
                $new_user->bindParam(':passport_id', $default_int, PDO::PARAM_INT);
                $new_user->bindParam(':id_entity', $default_int, PDO::PARAM_INT);
                $new_user->bindParam(':company', $company, PDO::PARAM_STR);
                $new_user->bindParam(':position', $position, PDO::PARAM_STR);
                $new_user->bindParam(':profession', $default, PDO::PARAM_STR);
                $new_user->bindParam(':hash', $hash, PDO::PARAM_STR);
                $new_user->bindParam(':first_referer', $resource, PDO::PARAM_STR);
                $new_user->bindParam(':reg_date', $today, PDO::PARAM_STR);
                $new_user->bindParam(':role', $role, PDO::PARAM_STR);

                $check_new_user = $new_user->execute();
                $count = $new_user->rowCount();

                if($count > 0) {
                      $id_last_user = $database->lastInsertId();
                      $data_user = json_decode($this->get_user_data($id_last_user));
                      return json_encode(array('response' => true, 'data' => $data_user->data, 'user_id_in_ebd' => $id_last_user,'description' => 'Пользотваль успешно зарегистрирован в единой базе данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                } else {
                      return json_encode(array('response' => false, 'description' => 'Пользотваля не удалось зарегистрировать в единой базе данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                }

      } else {
          return json_encode($check_reg_tboil);
          exit;
      }

  }

  // авторизация пользотваеля через tboil (сюда приходят декодированые данные полученные данные с платформы по методу /api/v2/getUser/?token=)
  public function auth_from_tboil($data_user_tboil,$resource) {
      global $database;

      $data_user_tboil = json_decode($data_user_tboil);

      if ($data_user_tboil->success) {
              // проверяем есть ли данный пользователь с таким id_tboil уже в базе даных
              $check_reg_user_in_base = json_decode($this->get_user_data_id_boil($data_user_tboil->data->userId));
              if ($check_reg_user_in_base->response) {
                      return json_encode(array('response' => true, 'data' => $check_reg_user_in_base->data, 'description' => 'Пользотваль найден в единой базе данных'),JSON_UNESCAPED_UNICODE);
                      exit;
              } else {
                      $data_user = json_decode($this->getUser_tboil($data_user_tboil->data->userId))->data;

                      $today = date("Y-m-d H:i:s");
                      $resource = parse_url($resource, PHP_URL_HOST);
                      $hash = md5($data_user->phone.$resource.$today);
                      $phone = trim($data_user->phone);

                      $vowels = array("(", ")", "+", "_", "-", " ");
                      $phone = str_replace($vowels, "", $phone);
                      $default = '-';
                      $default_int = 0;
                      $role = 'user';


                      if (!$data_user->lastName) {$secondName = '-';} else {$secondName = $data_user->lastName;}
                      if (!$data_user->birthday) {$dob = "0000-00-00";} else {$dob = $data_user->birthday;}
                      if (!$data_user->leaderId) {$leaderId = 0;} else {$leaderId = $data_user->leaderId;}
                      if (!$data_user->secondName) {$secondName = '';} else {$secondName = $data_user->secondName;}
                      if (!$data_user->city) {$city = '-';} else {$city = $data_user->city;}
                      if (!$data_user->company) {$company = '-';} else {$company = $data_user->company;}
                      if (!$data_user->position) {$position = '-';} else {$position = $data_user->position;}
                      if (!$data_user->profession) {$profession = '-';} else {$profession = $data_user->profession;}

                      $userId = $data_user->userId;
                      $email = $data_user->email;
                      $name = $data_user->name;
                      $lastName = $data_user->lastName;


                      $new_user = $database->prepare("INSERT INTO $this->main_users (id_tboil,id_leader,email,phone,name,last_name,second_name,DOB,photo,adres,inn,passport_id,id_entity,company,position,profession,hash,first_referer,reg_date,role)
                                                                             VALUES (:id_tboil,:id_leader,:email,:phone,:name,:last_name,:second_name,:DOB,:photo,:adres,:inn,:passport_id,:id_entity,:company,:position,:profession,:hash,:first_referer,:reg_date,:role)");
                      $new_user->bindParam(':id_tboil', $userId, PDO::PARAM_INT);
                      $new_user->bindParam(':id_leader', $leaderId, PDO::PARAM_INT);
                      $new_user->bindParam(':email', $email, PDO::PARAM_STR);
                      $new_user->bindParam(':phone', $phone, PDO::PARAM_STR);
                      $new_user->bindParam(':name', $name, PDO::PARAM_STR);
                      $new_user->bindParam(':last_name', $lastName, PDO::PARAM_STR);
                      $new_user->bindParam(':second_name', $secondName, PDO::PARAM_STR);
                      $new_user->bindParam(':DOB', $dob, PDO::PARAM_STR);
                      $new_user->bindParam(':photo', $default, PDO::PARAM_STR);
                      $new_user->bindParam(':adres', $city, PDO::PARAM_STR);
                      $new_user->bindParam(':inn', $default_int,  PDO::PARAM_INT);
                      $new_user->bindParam(':passport_id', $default_int, PDO::PARAM_INT);
                      $new_user->bindParam(':id_entity', $default_int, PDO::PARAM_INT);
                      $new_user->bindParam(':company', $company, PDO::PARAM_STR);
                      $new_user->bindParam(':position', $position, PDO::PARAM_STR);
                      $new_user->bindParam(':profession', $profession, PDO::PARAM_STR);
                      $new_user->bindParam(':hash', $hash, PDO::PARAM_STR);
                      $new_user->bindParam(':first_referer', $resource, PDO::PARAM_STR);
                      $new_user->bindParam(':reg_date', $today, PDO::PARAM_STR);
                      $new_user->bindParam(':role', $role, PDO::PARAM_STR);

                      $check_new_user = $new_user->execute();
                      $count = $database->lastInsertId();

                      if($count > 0) {
                            $data_user = json_decode($this->get_user_data($count));
                            return json_encode(array('response' => true, 'data' => $data_user->data, 'user_id_in_ebd' => $count, 'description' => 'Пользотваль успешно зарегистрирован в единой базе данных'),JSON_UNESCAPED_UNICODE);
                            exit;
                      } else {
                            return json_encode(array('response' => false, 'description' => 'Пользотваля не удалось зарегистрировать в единой базе данных'),JSON_UNESCAPED_UNICODE);
                            exit;
                      }
              }
      } else {
          return json_encode($data_user_tboil);
          exit;
      }

  }

  // получение данных по юридичесокму лицу по id в ебд
  public function get_data_entity($id_entity) {
      global $database;

        $statement = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE id = :id");
        $statement->bindParam(':id', $id_entity, PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_OBJ);

        if ($data) {
              return json_encode(array('response' => true, 'data' => $data, 'description' => 'Данные о юридическом лице'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Компания с даннам id не найдена'),JSON_UNESCAPED_UNICODE);
        }

  }

  // поиск компании по инн в единой базе данных
  public function get_data_entity_inn($inn) {
        global $database;

        $check_inn =  $this->is_valid_inn($inn);
        $check_false_inn = $this->isJSON($check_inn);

        if ($check_false_inn) {
            return $check_inn;
            exit;
        }

        $statement = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE inn = :inn");
        $statement->bindParam(':inn', $inn, PDO::PARAM_STR);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_OBJ);

        if ($data) {
              return json_encode(array('response' => true, 'data' => $data, 'description' => 'Данные о юридическом лице'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Компания с даннам id не найдена'),JSON_UNESCAPED_UNICODE);
        }
  }

  // Получение всех данных по пользователю и его юридическому лицу из ЕБД по id tboil
  public function get_all_data_user_id_tboil($id_user_tboil) {
        global $database;

        $data_user = $this->get_user_data_id_boil($id_user_tboil);

        if (json_decode($data_user)->response) {
            if (json_decode($data_user)->data->id_entity != 0) {
                $data_entity = $this->get_data_entity(json_decode($data_user)->data->id_entity);
                if (json_decode($data_entity)->response) {
                    return json_encode(array('response' => true, 'user' => json_decode($data_user)->data, 'user_id_in_ebd' => json_decode($data_user)->data->id, 'entity' => json_decode($data_entity)->data, 'description' => 'Данные о физическом и юридическом лице'),JSON_UNESCAPED_UNICODE);
                } else {
                    return $data_user;
                    exit;
                }
            } else {
              return $data_user;
              exit;
            }
        } else {
            return $data_user;
            exit;
        }

  }

  // Получение всех данных по пользователю и его юридическому лицу из ЕБД
  public function get_all_data_user_id($id_user) {
        global $database;

        $data_user = $this->get_user_data($id_user);

        if (json_decode($data_user)->response) {
            if (json_decode($data_user)->data->id_entity != 0) {
                $data_entity = $this->get_data_entity($id_entity);
                if (json_decode($data_entity)->response) {
                    return json_encode(array('response' => true, 'user' => json_decode($data_user)->data, '' => json_decode($data_entity)->data, 'description' => 'Данные о физическом и юридическом лице'),JSON_UNESCAPED_UNICODE);
                } else {
                    return $data_user;
                    exit;
                }
            } else {
              return $data_user;
              exit;
            }
        } else {
            return $data_user;
            exit;
        }

  }

  // перепривязка юридического лица к физическому
  public function update_entity_user($id_user_tboil,$id_entity) {
      global $database;

          $add_fns_database = $database->prepare("UPDATE $this->main_users SET id_entity = :id_entity WHERE id_tboil = :id_tboil");
          $add_fns_database->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
          $add_fns_database->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
          $check_add = $add_fns_database->execute();
          $count = $add_fns_database->rowCount();

          if ($count) {
              return json_encode(array('response' => true, 'description' => 'Компания пользователя успешно обновлена'),JSON_UNESCAPED_UNICODE);
              exit;
          } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка обновления компании пользователя'),JSON_UNESCAPED_UNICODE);
              exit;
          }

  }

  //Обновление данных пользователя в единой базе данных
  public function update_user_field($field,$value_field,$id_user_tboil) {
      global $database;

      $validFields = array('email', 'phone', 'name', 'last_name', 'second_name', 'DOB', 'photo', 'adres', 'inn', 'passport_id', 'company', 'position', 'profession');

      if (!in_array($field, $validFields)) {
          return json_encode(array('response' => false, 'description' => 'Не верное указанное поле'),JSON_UNESCAPED_UNICODE);
          exit;
      }

      $check_user = $this->get_all_data_user_id_tboil($id_user_tboil);

      if (!json_decode($check_user)->response) {
          return json_encode(array('response' => false, 'description' => 'Пользователь с данным id_tboil не найден в едной базе данных'),JSON_UNESCAPED_UNICODE);
          exit;
      }

      $field_integer = array('email' => 'str',
                      'phone' => 'str',
                      'name' => 'str',
                      'last_name' => 'str',
                      'second_name' => 'str',
                      'DOB' => 'str',
                      'photo' => 'str',
                      'adres' => 'str',
                      'inn' => 'int',
                      'passport_id' => 'int',
                      'company' => 'str',
                      'position' => 'str',
                      'profession' => 'str');

      $statement = $database->prepare("UPDATE $this->main_users SET {$field} = :value WHERE id_tboil = :id_tboil");
      if ($field_integer[$field] == 'int') { $statement->bindParam(':value', $value_field, PDO::PARAM_INT);}
      else                               { $statement->bindParam(':value', $value_field, PDO::PARAM_STR);}
      $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
      $statement->execute();
      $count = $statement->rowCount();

      if($count > 0) {
            return json_encode(array('response' => true, 'description' => 'Поле '.$field.' успешно было обновлено у пользователя в единой базе данных'),JSON_UNESCAPED_UNICODE);
            exit;
      } else {
            return json_encode(array('response' => false, 'description' => 'Ошибка обновления поля '.$field.' в единой базе данных'),JSON_UNESCAPED_UNICODE);
            exit;
      }

  }

  // Массовое обноление данных пользователя в единой базе данных
  public function mass_update_user_field($massiv_field_value2,$id_user_tboil) {
      global $database;

      $massiv_field_value = json_decode($massiv_field_value2,true);


      if (!is_array($massiv_field_value)) {
          return json_encode(array('response' => false, 'description' => 'Значение не является массивом "ключ" => "значение"'),JSON_UNESCAPED_UNICODE);
          exit;
      }

      $validFields = array('email', 'phone', 'name', 'last_name', 'second_name', 'DOB', 'photo', 'adres', 'inn', 'passport_id', 'company', 'position', 'profession');

      foreach ($massiv_field_value as $key => $value) {
          if (!in_array($key, $validFields)) {
              return json_encode(array('response' => false, 'description' => 'Поля '.$key.' нет в единой базе данных'),JSON_UNESCAPED_UNICODE);
              exit;
          }
      }

      $check_user = $this->get_all_data_user_id_tboil($id_user_tboil);

      if (!json_decode($check_user)->response) {
          return json_encode(array('response' => false, 'description' => 'Пользователь с данным id_tboil не найден в едной базе данных'),JSON_UNESCAPED_UNICODE);
          exit;
      }

      $field_type = array('email' => PDO::PARAM_STR,
                      'phone' => PDO::PARAM_STR,
                      'name' => PDO::PARAM_STR,
                      'last_name' => PDO::PARAM_STR,
                      'second_name' => PDO::PARAM_STR,
                      'DOB' => PDO::PARAM_STR,
                      'photo' => PDO::PARAM_STR,
                      'adres' => PDO::PARAM_STR,
                      'inn' => PDO::PARAM_INT,
                      'passport_id' => PDO::PARAM_INT,
                      'company' => PDO::PARAM_STR,
                      'position' => PDO::PARAM_STR,
                      'profession' => PDO::PARAM_STR);

      $sql_string = 'UPDATE '.$this->main_users.' SET ';

      $count_zap = 0;
      foreach ($massiv_field_value as $key => $value) {
          if ($count_zap == 0) {
            $sql_string .= $key.' = :'.$key;
          } else {
            $sql_string .= ', '.$key.' = :'.$key;
          }
          $count_zap++;
      }

      $sql_string .= ' WHERE id_tboil = :id_tboil';

      $statement = $database->prepare($sql_string);

      foreach($massiv_field_value as $key => $value) {
                $statement->bindValue(':'.$key, $value, $field_type[$key]);
      }

      $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
      $statement->execute();
      $count = $statement->rowCount();

      if($count > 0) {
            return json_encode(array('response' => true, 'description' => 'Все поля были успешно было обновлены у пользователя в единой базе данных'),JSON_UNESCAPED_UNICODE);
            exit;
      } else {
            return json_encode(array('response' => false, 'description' => 'Ошибка обновления полей в единой базе данных.'),JSON_UNESCAPED_UNICODE);
            exit;
      }

  }

  // Массовое обноление данных пользователя в единой базе данных
  public function mass_update_user_api_field($massiv_field_value2) {
      global $database;

      $massiv_field_value = json_decode($massiv_field_value2,true);


      if (!is_array($massiv_field_value)) {
          return json_encode(array('response' => false, 'description' => 'Значение не является массивом "ключ" => "значение"'),JSON_UNESCAPED_UNICODE);
          exit;
      }

      $validFields = array('email', 'phone', 'name', 'lastname', 'second_name', 'photo', 'role', 'status' );

      foreach ($massiv_field_value as $key => $value) {
          if (!in_array($key, $validFields)) {
              return json_encode(array('response' => false, 'description' => 'Поля '.$key.' нет в bd api'),JSON_UNESCAPED_UNICODE);
              exit;
          }
      }

      $check_user = json_decode($this->get_cur_user($_SESSION["key_user"]));

      if (!json_decode($check_user)->response) {
          return json_encode(array('response' => false, 'description' => 'Пользователь с данным id не найден в bd api'),JSON_UNESCAPED_UNICODE);
          exit;
      }

      $field_type = array('email' => PDO::PARAM_STR,
                          'phone' => PDO::PARAM_STR,
                          'name' => PDO::PARAM_STR,
                          'lastname' => PDO::PARAM_STR,
                          'second_name' => PDO::PARAM_STR,
                          'photo' => PDO::PARAM_STR,
                          'role' => PDO::PARAM_STR,
                          'status' => PDO::PARAM_STR);

      $sql_string = 'UPDATE '.$this->users.' SET ';

      $count_zap = 0;
      foreach ($massiv_field_value as $key => $value) {
          if ($count_zap == 0) {
            $sql_string .= $key.' = :'.$key;
          } else {
            $sql_string .= ', '.$key.' = :'.$key;
          }
          $count_zap++;
      }

      $sql_string .= ' WHERE id = :id';

      $statement = $database->prepare($sql_string);

      foreach($massiv_field_value as $key => $value) {
                $statement->bindValue(':'.$key, $value, $field_type[$key]);
      }

      $statement->bindParam(':id', $id_user->data->id, PDO::PARAM_INT);
      $statement->execute();
      $count = $statement->rowCount();

      if($count > 0) {
            return json_encode(array('response' => true, 'description' => 'Все поля были успешно было обновлены у пользователя в единой базе данных'),JSON_UNESCAPED_UNICODE);
            exit;
      } else {
            return json_encode(array('response' => false, 'description' => 'Ошибка обновления полей в единой базе данных.'),JSON_UNESCAPED_UNICODE);
            exit;
      }

  }

  // //Обновление данных юридических лиц в единой базе данных
  public function update_entity_field($field,$value_field,$id_entity) {
    global $database;

          $validFields = array('msp','site','region','staff','district','street','house','type_inf','additionally');

          if (!in_array($field, $validFields)) {
              return json_encode(array('response' => false, 'description' => 'Не верное указанное поле'),JSON_UNESCAPED_UNICODE);
              exit;
          }

          $check_entity = $this->get_data_entity($id_entity);

          if (!json_decode($check_entity)->response) {
              return json_encode(array('response' => false, 'description' => 'Компания с таким id = '.$id_entity.' не найдена в едной базе данных'),JSON_UNESCAPED_UNICODE);
              exit;
          }

          $field_ineger = array('inn' => 'int',
                                'data_fns' => 'str',
                                'data_dadata' => 'str',
                                'msp' => 'str',
                                'site' => 'str',
                                'region' => 'str',
                                'staff' => 'str',
                                'district' => 'str',
                                'street' => 'str',
                                'house' => 'str',
                                'type_inf' => 'str',
                                'additionally' => 'str');

          $statement = $database->prepare("UPDATE $this->MAIN_entity SET {$field} = :value WHERE id = :id_entity");
          if ($field_ineger[$field] == 'int') { $statement->bindParam(':value', $value_field, PDO::PARAM_INT);}
          else                               { $statement->bindParam(':value', $value_field, PDO::PARAM_STR);}
          $statement->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
          $statement->execute();
          $count = $statement->rowCount();

          if($count > 0) {
                return json_encode(array('response' => true, 'description' => 'Поле '.$field.' успешно было обновлено у пользователя в единой базе данных'),JSON_UNESCAPED_UNICODE);
                exit;
          } else {
                return json_encode(array('response' => false, 'description' => 'Ошибка обновления поля '.$field.' в единой базе данных'),JSON_UNESCAPED_UNICODE);
                exit;
          }

  }

  // Массовое обноление данных пользователя в единой базе данных
  public function mass_update_entity_field($massiv_field_value,$id_entity) {
      global $database;

      $massiv_field_value = json_decode($massiv_field_value,true);

      if (!is_array($massiv_field_value)) {
          return json_encode(array('response' => false, 'description' => 'Значение не является массивом "ключ" => "значение"'),JSON_UNESCAPED_UNICODE);
          exit;
      }

      $validFields = array('msp','site','region','staff','district','street','house','type_inf','additionally','export','branch','technology');

      foreach ($massiv_field_value as $key => $value) {
          if (!in_array($key, $validFields)) {
              return json_encode(array('response' => false, 'description' => 'Поля '.$key.' нет в единой базе данных'),JSON_UNESCAPED_UNICODE);
              exit;
          }
      }

      $check_entity = $this->get_data_entity($id_entity);

      if (!json_decode($check_entity)->response) {
          return json_encode(array('response' => false, 'description' => 'Юридическое лицо с таким id = '.$id_entity.' не найдено в едной базе данных'),JSON_UNESCAPED_UNICODE);
          exit;
      }

      $field_type = array(
                      'msp' => PDO::PARAM_STR,
                      'site' => PDO::PARAM_STR,
                      'region' => PDO::PARAM_STR,
                      'staff' => PDO::PARAM_STR,
                      'district' => PDO::PARAM_STR,
                      'street' => PDO::PARAM_STR,
                      'house' => PDO::PARAM_STR,
                      'type_inf' => PDO::PARAM_STR,
                      'additionally' => PDO::PARAM_STR,
                      'export' => PDO::PARAM_STR,
                      'branch' => PDO::PARAM_STR,
                      'technology' => PDO::PARAM_STR );

      $sql_string = 'UPDATE '.$this->MAIN_entity.' SET ';

      $count_zap = 0;
      foreach ($massiv_field_value as $key => $value) {
          if ($count_zap == 0) {
            $sql_string .= $key.' = :'.$key;
          } else {
            $sql_string .= ', '.$key.' = :'.$key;
          }
          $count_zap++;
      }

      $sql_string .= ' WHERE id = :id';

      $statement = $database->prepare($sql_string);

      foreach($massiv_field_value as $key => $value) {
                $statement->bindValue(':'.$key, $value, $field_type[$key]);
      }

      $statement->bindValue(':id', $id_entity, PDO::PARAM_INT);
      $statement->execute();
      $count = $statement->rowCount();

      if($count > 0) {
            return json_encode(array('response' => true, 'description' => 'Все поля были успешно обновлены у юридического лица в единой базе данных'),JSON_UNESCAPED_UNICODE);
            exit;
      } else {
            return json_encode(array('response' => false, 'description' => 'Ошибка обновления полей в единой базе данных.'),JSON_UNESCAPED_UNICODE);
            exit;
      }

  }

  // Добавление компании и привязка ее к физическому лицу
  public function register_entity($id_user_tboil,$inn,$msp,$site,$region,$staff,$district,$street,$house,$type_inf,$additionally,$export,$branch,$technology){
      global $database;

      $today = date("Y-m-d H:i:s");

      $check_company = $this->get_data_entity_inn($inn);

      if (json_decode($check_company)->response) {

              $statement = $database->prepare("SELECT * FROM $this->main_users WHERE id_entity = :id_entity");
              $statement->bindParam(':id_entity', json_decode($check_company)->data->id, PDO::PARAM_INT);
              $statement->execute();
              $data = $statement->fetch(PDO::FETCH_OBJ);

              if ($data) {
                        if ($data->id_tboil != $id_user_tboil) {
                            return json_encode(array('response' => false, 'description' => 'Данное юридическое лицо привязано к другой учетной записи Tboil '.$data->id_tboil.'****'.$id_user_tboil),JSON_UNESCAPED_UNICODE);
                            exit;
                        } else {

                              $array_entity = array();

                              if (trim($msp)) { $array_entity['msp'] = $msp;}
                              if (trim($site)) { $array_entity['site'] = $site; }
                              if (trim($region)) { $array_entity['region'] = $region; }
                              if (trim($staff)) { $array_entity['staff'] = $staff; }
                              if (trim($district)) { $array_entity['district'] = $district; }
                              if (trim($street)) { $array_entity['street'] = $street; }
                              if (trim($house)) { $array_entity['house'] = $house; }
                              if (trim($type_inf)) { $array_entity['type_inf'] = $type_inf; }
                              if (trim($additionally)) { $array_entity['additionally'] = $additionally; }
                              if (trim($export)) { $array_entity['export'] = $export; }
                              if (trim($branch)) { $array_entity['branch'] = $branch; }
                              if (trim($technology)) { $array_entity['technology'] = $technology; }

                              // $array_entity = array(
                              //                       'msp' => $msp,
                              //                       'site' => $site,
                              //                       'region' => $region,
                              //                       'staff' => $staff,
                              //                       'district' => $district,
                              //                       'street' => $street,
                              //                       'house' => $house,
                              //                       'type_inf' => $type_inf,
                              //                       'additionally' => $additionally,
                              //                       'export' => $export,
                              //                       'branch' => $branch
                              //                     );

                              $array_entity = json_encode($array_entity,JSON_UNESCAPED_UNICODE);

                              $check_update_entity = $this->mass_update_entity_field($array_entity,json_decode($check_company)->data->id);
                              $data_user_new = $this->get_all_data_user_id_tboil($id_user_tboil);
                              if (json_decode($check_update_entity)->response) {
                                    return json_encode(array('response' => true, 'user' => json_decode($data_user_new)->user, 'entity' => json_decode($data_user_new)->entity, 'description' => 'Все поля вашего юридеского лица были успешно обновлены'),JSON_UNESCAPED_UNICODE);
                                    exit;
                              } else {
                                    return json_encode(array('response' => false, 'user' => json_decode($data_user_new)->user, 'entity' => json_decode($data_user_new)->entity, 'description' => 'Ошибка обновления полей в единой базе данных.'),JSON_UNESCAPED_UNICODE);
                                    exit;
                              }
                        }
              } else {
                    $statement = $database->prepare("UPDATE $this->main_users SET id_entity = :id_entity WHERE id_tboil = :id_tboil");
                    $statement->bindParam(':id_entity', json_decode($check_company)->data->id, PDO::PARAM_INT);
                    $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
                    $statement->execute();
                    $count = $statement->rowCount();


                    $array_entity = array('msp' => $msp,
                                          'site' => $site,
                                          'region' => $region,
                                          'staff' => $staff,
                                          'district' => $district,
                                          'street' => $street,
                                          'house' => $house,
                                          'type_inf' => $type_inf,
                                          'additionally' => $additionally,
                                          'export' => $export,
                                          'branch' => $branch,
                                          'technology' => $technology);

                    $array_entity = json_encode($array_entity);

                    $check_update_entity = $this->mass_update_entity_field($array_entity,json_decode($check_company)->data->id);
                    $data_user_new = $this->get_all_data_user_id_tboil($id_user_tboil);
                    if (json_decode($check_update_entity)->response) {
                            return json_encode(array('response' => true, 'user' => json_decode($data_user_new)->user, 'entity' => json_decode($data_user_new)->entity, 'description' => 'Юридическое лицо успешно привязано к вашему аккаунту и все поля юридеского лица были успешно обновлены'),JSON_UNESCAPED_UNICODE);
                            exit;
                    } else {
                            return json_encode(array('response' => true, 'user' => json_decode($data_user_new)->user, 'entity' => json_decode($data_user_new)->entity, 'description' => 'Юридическое лицо успешно привязано к вашему аккаунту, но не все поля были успешно сохранены'),JSON_UNESCAPED_UNICODE);
                            exit;
                    }

              }
      } else {

            $date_pickup = date("Y-m-d H:i:s");
            $default = '';

            $hash = md5($id_user_tboil.$inn.$msp.$site.$region.$staff.$district.$street.$house.$type_inf.$additionally.$date_pickup);

            $request = $database->prepare("INSERT INTO $this->MAIN_entity (inn,data_fns,data_dadata,msp,site,region,staff,district,street,house,type_inf,additionally,export,branch,technology,hash,date_pickup,date_register)
                                                  VALUES (:inn,:data_fns,:data_dadata,:msp,:site,:region,:staff,:district,:street,:house,:type_inf,:additionally,:export,:branch,:technology,:hash,:date_pickup,:date_register)");
            $request->bindParam(':inn', $inn, PDO::PARAM_INT);
            $request->bindParam(':data_fns', $default, PDO::PARAM_STR);
            $request->bindParam(':data_dadata', $default, PDO::PARAM_STR);
            $request->bindParam(':msp', $msp, PDO::PARAM_STR);
            $request->bindParam(':site', $site, PDO::PARAM_STR);
            $request->bindParam(':region', $region, PDO::PARAM_STR);
            $request->bindParam(':staff', $staff, PDO::PARAM_STR);
            $request->bindParam(':district', $district, PDO::PARAM_STR);
            $request->bindParam(':street', $street, PDO::PARAM_STR);
            $request->bindParam(':house', $house, PDO::PARAM_STR);
            $request->bindParam(':type_inf', $type_inf, PDO::PARAM_STR);
            $request->bindParam(':additionally', $additionally, PDO::PARAM_STR);
            $request->bindParam(':export', $export, PDO::PARAM_STR);
            $request->bindParam(':branch', $branch, PDO::PARAM_STR);
            $request->bindParam(':technology', $technology, PDO::PARAM_STR);
            $request->bindParam(':hash', $hash, PDO::PARAM_STR);
            $request->bindParam(':date_pickup', $date_pickup, PDO::PARAM_STR);
            $request->bindParam(':date_register', $today, PDO::PARAM_STR);

            $check_request = $request->execute();
            //$id_request = $request->rowCount();
            $id_request = $database->lastInsertId();

            if($id_request > 0) {

                  $check_fns = $this->fns_base($inn);

                  if (json_decode($check_fns)->response) {

                      $check_update_user = $this->update_entity_user($id_user_tboil,$id_request);

                      if (json_decode($check_update_user)->response) {

                            $check_dadata = $this->find_entity($inn);

                            $add_fns_database = $database->prepare("UPDATE $this->MAIN_entity SET data_dadata = :data_dadata, date_pickup = :date_pickup  WHERE inn = :inn");
                            $add_fns_database->bindParam(':inn', $inn, PDO::PARAM_INT);
                            $add_fns_database->bindParam(':data_dadata', $check_dadata, PDO::PARAM_STR);
                            $add_fns_database->bindParam(':date_pickup', $date_pickup, PDO::PARAM_STR);
                            $check_add = $add_fns_database->execute();

                            $check_data_entity = $this->get_data_entity_inn($inn);

                            $check_data_user = $this->get_user_data_id_boil($id_user_tboil);

                            return json_encode(array('response' => true,  'user' => json_decode($check_data_user)->data, 'entity' => json_decode($check_data_entity)->data, 'description' => 'Юридическое лицо успешно зарегиcтрировано и привязано к вашему аккаунту'),JSON_UNESCAPED_UNICODE);
                            exit;
                      } else {
                          return $check_update_user;
                          exit;
                      }

                  } else {
                       return $check_fns;
                       exit;
                  }

            } else {
                return json_encode(array('response' => false, 'description' => 'Неудалось зарегистрировать и приявзять компанию к акксунту'),JSON_UNESCAPED_UNICODE);
                exit;
            }
      }

    }

  // функция добавления и обновления данных по технологическому запросу
  public function tech_requests($id_requests_on_referer,$id_entity,$id_user_tboil,$name_request,$description,$demand,$collection_time,$links_to_logos,$type_request,$links_add_files,$request_hash,$status,$date_added,$id_referer){
      global $database;


      $statement = $database->prepare("SELECT * FROM $this->MAIN_entity_tech_requests WHERE id_referer = :id_referer AND id_requests_on_referer = :id_requests_on_referer AND id_entity = :id_entity");
      $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
      $statement->bindParam(':id_requests_on_referer', $id_requests_on_referer, PDO::PARAM_INT);
      $statement->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data) {

          $statement = $database->prepare("UPDATE $this->MAIN_entity_tech_requests SET name_request = :name_request, id_tboil = :id_tboil, description = :description, demand = :demand, collection_time = :collection_time, links_to_logos = :links_to_logos, type_request = :type_request, links_add_files = :links_add_files, request_hash = :request_hash, status = :status, date_added = :date_added WHERE id_referer = :id_referer AND id_requests_on_referer = :id_requests_on_referer AND id_entity = :id_entity");
          $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
          $statement->bindParam(':id_requests_on_referer', $id_requests_on_referer, PDO::PARAM_INT);
          $statement->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
          $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
          $statement->bindParam(':name_request', $name_request, PDO::PARAM_STR);
          $statement->bindParam(':description', $description, PDO::PARAM_STR);
          $statement->bindParam(':demand', $demand, PDO::PARAM_STR);
          $statement->bindParam(':type_request', $type_request, PDO::PARAM_STR);
          $statement->bindParam(':collection_time', $collection_time, PDO::PARAM_STR);
          $statement->bindParam(':links_to_logos', $links_to_logos, PDO::PARAM_STR);
          $statement->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
          $statement->bindParam(':request_hash', $request_hash, PDO::PARAM_STR);
          $statement->bindParam(':status', $status, PDO::PARAM_STR);
          $statement->bindParam(':date_added', $date_added, PDO::PARAM_STR);
          $check_add = $statement->execute();
          $count = $statement->rowCount();

          if($count > 0) {
                return json_encode(array('response' => true, 'description' => 'Технологический запрос успешно обновлен в единой базе данных'),JSON_UNESCAPED_UNICODE);
                exit;
          } else {
                return json_encode(array('response' => false, 'description' => 'Ошибка обновления данных по технологическому запросу в единой базе данных'),JSON_UNESCAPED_UNICODE);
                exit;
          }

      } else {

          $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_requests (id_requests_on_referer,id_entity,id_tboil,name_request,description,demand,collection_time,links_to_logos,type_request,links_add_files,request_hash,status,date_added,id_referer)
                                                VALUES (:id_requests_on_referer,:id_entity,:id_tboil,:name_request,:description,:demand,:collection_time,:links_to_logos,:type_request,:links_add_files,:request_hash,:status,:date_added,:id_referer)");

          $request->bindParam(':id_requests_on_referer', $id_requests_on_referer, PDO::PARAM_INT);
          $request->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
          $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
          $request->bindParam(':name_request', $name_request, PDO::PARAM_STR);
          $request->bindParam(':description', $description, PDO::PARAM_STR);
          $request->bindParam(':demand', $demand, PDO::PARAM_STR);
          $request->bindParam(':collection_time', $collection_time, PDO::PARAM_STR);
          $request->bindParam(':links_to_logos', $links_to_logos, PDO::PARAM_STR);
          $request->bindParam(':type_request', $type_request, PDO::PARAM_STR);
          $request->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
          $request->bindParam(':request_hash', $request_hash, PDO::PARAM_STR);
          $request->bindParam(':status', $status, PDO::PARAM_STR);
          $request->bindParam(':date_added', $date_added, PDO::PARAM_STR);
          $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

          $check_request = $request->execute();
          $count_request = $request->rowCount();
          if($count_request > 0) {
                return json_encode(array('response' => true, 'description' => 'Технологического запрос успешно добавлен в единую базу данных'),JSON_UNESCAPED_UNICODE);
                exit;
          } else {
                return json_encode(array('response' => false, 'description' => 'Ошибка добавления данных по технологическому запросу в единую базу данных'),JSON_UNESCAPED_UNICODE);
                exit;
          }

      }

    }

  // функция добавления и обновления данных по ответу на технологический запрос
  public function tech_requests_solutions($id_requests_on_referer,$id_solution_on_referer,$id_entity,$id_user_tboil,$name_project,$description,$result_project,$readiness,$period,$forms_of_support,$protection,$links_add_files,$solutions_hash,$status,$date_receiving,$id_referer){
      global $database;

            $statement = $database->prepare("SELECT * FROM $this->MAIN_entity_tech_requests_solutions WHERE id_referer = :id_referer AND id_requests_on_referer = :id_requests_on_referer AND id_solution_on_referer = :id_solution_on_referer AND id_tboil = :id_tboil");
            $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_requests_on_referer', $id_requests_on_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_solution_on_referer', $id_solution_on_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
            $statement->execute();
            $data = $statement->fetch(PDO::FETCH_OBJ);

            if ($data) {

                $statement = $database->prepare("UPDATE $this->MAIN_entity_tech_requests_solutions SET id_entity = :id_entity, name_project = :name_project, description = :description, result_project = :result_project, readiness = :readiness, period = :period, forms_of_support = :forms_of_support, protection = :protection, links_add_files = :links_add_files, solutions_hash = :solutions_hash, status = :status, date_receiving = :date_receiving, id_referer = :id_referer WHERE id_referer = :id_referer AND id_requests_on_referer = :id_requests_on_referer AND id_solution_on_referer = :id_solution_on_referer AND id_tboil = :id_tboil");
                $statement->bindParam(':id_requests_on_referer', $id_requests_on_referer, PDO::PARAM_INT);
                $statement->bindParam(':id_solution_on_referer', $id_solution_on_referer, PDO::PARAM_INT);
                $statement->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
                $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
                $statement->bindParam(':name_project', $name_project, PDO::PARAM_STR);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':result_project', $result_project, PDO::PARAM_STR);
                $statement->bindParam(':readiness', $readiness, PDO::PARAM_STR);
                $statement->bindParam(':period', $period, PDO::PARAM_STR);
                $statement->bindParam(':forms_of_support', $forms_of_support, PDO::PARAM_STR);
                $statement->bindParam(':protection', $protection, PDO::PARAM_STR);
                $statement->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
                $statement->bindParam(':solutions_hash', $solutions_hash, PDO::PARAM_STR);
                $statement->bindParam(':status', $status, PDO::PARAM_STR);
                $statement->bindParam(':date_receiving', $date_receiving, PDO::PARAM_STR);
                $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
                $check_add = $statement->execute();
                $count = $statement->rowCount();

                if($count > 0) {
                      return json_encode(array('response' => true, 'description' => ' Ответ на технологический запрос успешно обновлен в единой базе данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                } else {
                      return json_encode(array('response' => false, 'description' => 'Ошибка обновления данных ответа на технологический запрос в единой базе данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                }

            } else {

                $statement = $database->prepare("INSERT INTO $this->MAIN_entity_tech_requests_solutions (id_requests_on_referer,id_solution_on_referer,id_entity,id_tboil,name_project,description,result_project,readiness,period,forms_of_support,protection,links_add_files,solutions_hash,status,date_receiving,id_referer)
                                                      VALUES (:id_requests_on_referer,:id_solution_on_referer,:id_entity,:id_user_tboil,:name_project,:description,:result_project,:readiness,:period,:forms_of_support,:protection,:links_add_files,:solutions_hash,:status,:date_receiving,:id_referer)");

                $statement->bindParam(':id_requests_on_referer', $id_requests_on_referer, PDO::PARAM_INT);
                $statement->bindParam(':id_solution_on_referer', $id_solution_on_referer, PDO::PARAM_INT);
                $statement->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
                $statement->bindParam(':id_user_tboil', $id_user_tboil, PDO::PARAM_INT);
                $statement->bindParam(':name_project', $name_project, PDO::PARAM_STR);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':result_project', $result_project, PDO::PARAM_STR);
                $statement->bindParam(':readiness', $readiness, PDO::PARAM_STR);
                $statement->bindParam(':period', $period, PDO::PARAM_STR);
                $statement->bindParam(':forms_of_support', $forms_of_support, PDO::PARAM_STR);
                $statement->bindParam(':protection', $protection, PDO::PARAM_STR);
                $statement->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
                $statement->bindParam(':solutions_hash', $solutions_hash, PDO::PARAM_STR);
                $statement->bindParam(':status', $status, PDO::PARAM_STR);
                $statement->bindParam(':date_receiving', $date_receiving, PDO::PARAM_STR);
                $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

                $check_request = $statement->execute();
                $count_request = $statement->rowCount();
                if($count_request > 0) {
                      return json_encode(array('response' => true, 'description' => ' Ответ на технологический запрос успешно добавлен в единую базу данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                } else {
                      return json_encode(array('response' => false, 'description' => 'Ошибка добавления данных ответа на технологический запрос в единую базу данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                }

            }

    }

  // функция добавления и обновления данных по сервису в базу данных
  public function tech_services($id_service_on_referer,$id_entity,$id_user_tboil,$name,$category,$object_type,$description,$district,$street,$link_preview,$links_add_files,$status,$additionally,$data_added,$service_hash,$id_referer){
      global $database;


      $statement = $database->prepare("SELECT * FROM $this->MAIN_entity_tech_services WHERE id_referer = :id_referer AND id_service_on_referer = :id_service_on_referer AND id_tboil = :id_tboil");
      $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
      $statement->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
      $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data) {

          $request = $database->prepare("UPDATE $this->MAIN_entity_tech_services SET id_entity = :id_entity, name = :name, category = :category, object_type = :object_type, description = :description, district = :district, street = :street, link_preview = :link_preview, links_add_files = :links_add_files, status = :status, additionally = :additionally, data_added = :data_added, service_hash = :service_hash WHERE id_referer = :id_referer AND id_service_on_referer = :id_service_on_referer AND id_tboil = :id_tboil");

          $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
          $request->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
          $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
          $request->bindParam(':name', $name, PDO::PARAM_STR);
          $request->bindParam(':category', $category, PDO::PARAM_STR);
          $request->bindParam(':object_type', $object_type, PDO::PARAM_STR);
          $request->bindParam(':description', $description, PDO::PARAM_STR);
          $request->bindParam(':district', $district, PDO::PARAM_STR);
          $request->bindParam(':street', $street, PDO::PARAM_STR);
          $request->bindParam(':link_preview', $link_preview, PDO::PARAM_STR);
          $request->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
          $request->bindParam(':status', $status, PDO::PARAM_STR);
          $request->bindParam(':additionally', $additionally, PDO::PARAM_STR);
          $request->bindParam(':data_added', $data_added, PDO::PARAM_STR);
          $request->bindParam(':service_hash', $service_hash, PDO::PARAM_STR);
          $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
          $check_add = $request->execute();
          $count = $request->rowCount();

          if($count > 0) {
                return json_encode(array('response' => true, 'description' => 'Сервис успешно обновлен в единой базе данных'),JSON_UNESCAPED_UNICODE);
                exit;
          } else {
                return json_encode(array('response' => false, 'description' => 'Ошибка обновления сервиса в единой базе данных'),JSON_UNESCAPED_UNICODE);
                exit;
          }

      } else {

            $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_services (id_service_on_referer,id_entity,id_tboil,name,category,object_type,description,district,street,link_preview,links_add_files,status,additionally,data_added,service_hash,id_referer)
                                                  VALUES (:id_service_on_referer,:id_entity,:id_tboil,:name,:category,:object_type,:description,:district,:street,:link_preview,:links_add_files,:status,:additionally,:data_added,:service_hash,:id_referer)");
            $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
            $request->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
            $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
            $request->bindParam(':name', $name, PDO::PARAM_STR);
            $request->bindParam(':category', $category, PDO::PARAM_STR);
            $request->bindParam(':object_type', $object_type, PDO::PARAM_STR);
            $request->bindParam(':description', $description, PDO::PARAM_STR);
            $request->bindParam(':district', $district, PDO::PARAM_STR);
            $request->bindParam(':street', $street, PDO::PARAM_STR);
            $request->bindParam(':link_preview', $link_preview, PDO::PARAM_STR);
            $request->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
            $request->bindParam(':status', $status, PDO::PARAM_STR);
            $request->bindParam(':additionally', $additionally, PDO::PARAM_STR);
            $request->bindParam(':data_added', $data_added, PDO::PARAM_STR);
            $request->bindParam(':service_hash', $service_hash, PDO::PARAM_STR);
            $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

            $check_request = $request->execute();
            $count_request = $request->rowCount();
            if($count_request > 0) {
                  return json_encode(array('response' => true, 'description' => 'Сервис успешно добавлен в единую базу данных'),JSON_UNESCAPED_UNICODE);
                  exit;
            } else {
                  return json_encode(array('response' => false, 'description' => 'Ошибка добавления сервиса в единую базу данных'),JSON_UNESCAPED_UNICODE);
                  exit;
            }

      }


    }

  // Добавление и обновление данных по комментарию к сервису
  public function tech_services_comments($id_services_comments_on_referer,$id_service_on_referer,$id_user_tboil,$comment,$status,$date_update,$comments_hash,$id_referer){
      global $database;



            $statement = $database->prepare("SELECT * FROM $this->MAIN_entity_tech_services_comments WHERE id_referer = :id_referer AND id_services_comments_on_referer = :id_services_comments_on_referer AND id_service_on_referer = :id_service_on_referer AND id_tboil = :id_tboil");
            $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_services_comments_on_referer', $id_services_comments_on_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
            $statement->execute();
            $data = $statement->fetch(PDO::FETCH_OBJ);

            if ($data) {

                $request = $database->prepare("UPDATE $this->MAIN_entity_tech_services_comments SET comment = :comment, status = :status, date_update = :date_update, comments_hash = :comments_hash WHERE id_referer = :id_referer AND id_services_comments_on_referer = :id_services_comments_on_referer AND id_service_on_referer = :id_service_on_referer AND id_tboil = :id_tboil");

                $request->bindParam(':id_services_comments_on_referer', $id_services_comments_on_referer, PDO::PARAM_INT);
                $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
                $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
                $request->bindParam(':comment', $comment, PDO::PARAM_STR);
                $request->bindParam(':status', $status, PDO::PARAM_STR);
                $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
                $request->bindParam(':comments_hash', $comments_hash, PDO::PARAM_STR);
                $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
                $check_add = $request->execute();
                $count = $request->rowCount();

                if($count > 0) {
                      return json_encode(array('response' => true, 'description' => 'Комментарий к сервису успешно обновлен в единой базе данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                } else {
                      return json_encode(array('response' => false, 'description' => 'Ошибка обновления комментаря к сервису по технологическому запросу в единой базе данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                }

            } else {

                  $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_services_comments (id_services_comments_on_referer,id_service_on_referer,id_tboil,comment,status,date_update,comments_hash,id_referer)
                                                        VALUES (:id_services_comments_on_referer,:id_service_on_referer,:id_tboil,:comment,:status,:date_update,:comments_hash,:id_referer)");

                  $request->bindParam(':id_services_comments_on_referer', $id_services_comments_on_referer, PDO::PARAM_INT);
                  $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
                  $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
                  $request->bindParam(':comment', $comment, PDO::PARAM_STR);
                  $request->bindParam(':status', $status, PDO::PARAM_STR);
                  $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
                  $request->bindParam(':comments_hash', $comments_hash, PDO::PARAM_STR);
                  $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

                  $check_request = $request->execute();
                  $count_request = $request->rowCount();
                  if($count_request > 0) {
                        return json_encode(array('response' => true, 'description' => 'Комментарий к сервису успешно добавлен в единую базу данных'),JSON_UNESCAPED_UNICODE);
                        exit;
                  } else {
                        return json_encode(array('response' => false, 'description' => 'Ошибка добавления комментария к сервису в единую базу данных'),JSON_UNESCAPED_UNICODE);
                        exit;
                  }

            }


    }

  // Добавление и обновление данных по рейтингу к сервису
  public function tech_services_rating($id_services_rating_on_referer,$id_service_on_referer,$id_comment,$id_user_tboil,$rating,$date_update,$id_referer){
      global $database;



      $statement = $database->prepare("SELECT * FROM $this->MAIN_entity_tech_services_rating WHERE id_referer = :id_referer AND id_services_rating_on_referer = :id_services_rating_on_referer AND id_service_on_referer = :id_service_on_referer AND id_tboil = :id_tboil");
      $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
      $statement->bindParam(':id_services_rating_on_referer', $id_services_rating_on_referer, PDO::PARAM_INT);
      $statement->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
      $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data) {

            $request = $database->prepare("UPDATE $this->MAIN_entity_tech_services_rating SET id_services_rating_on_referer = :id_services_rating_on_referer, id_service_on_referer = :id_service_on_referer, id_comment = :id_comment, id_tboil = :id_tboil, rating = :rating, date_update = :date_update, id_referer = :id_referer WHERE id_referer = :id_referer AND id_services_comments_on_referer = :id_services_comments_on_referer AND id_service_on_referer = :id_service_on_referer AND id_tboil = :id_tboil");

            $request->bindParam(':id_services_rating_on_referer', $id_services_rating_on_referer, PDO::PARAM_INT);
            $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
            $request->bindParam(':id_comment', $id_comment, PDO::PARAM_INT);
            $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
            $request->bindParam(':rating', $rating, PDO::PARAM_INT);
            $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
            $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
            $check_add = $request->execute();
            $count = $request->rowCount();

            if($count > 0) {
                  return json_encode(array('response' => true, 'description' => 'Технологический запрос успешно обновлен в единой базе данных'),JSON_UNESCAPED_UNICODE);
                  exit;
            } else {
                  return json_encode(array('response' => false, 'description' => 'Ошибка обновления данных по технологическому запросу в единой базе данных'),JSON_UNESCAPED_UNICODE);
                  exit;
            }

      } else {

            $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_services_rating (id_services_rating_on_referer,id_service_on_referer,id_comment,id_tboil,rating,date_update,id_referer)
                                                  VALUES (:id_services_rating_on_referer,:id_service_on_referer,:id_comment,:id_tboil,:rating,:date_update,:id_referer)");

            $request->bindParam(':id_services_rating_on_referer', $id_services_rating_on_referer, PDO::PARAM_INT);
            $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
            $request->bindParam(':id_comment', $id_comment, PDO::PARAM_INT);
            $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
            $request->bindParam(':rating', $rating, PDO::PARAM_INT);
            $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
            $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

            $check_request = $request->execute();
            $count_request = $request->rowCount();
            if($count_request > 0) {
                  return json_encode(array('response' => true, 'description' => 'Комментарий к сервису успешно добавлен в единую базу данных'),JSON_UNESCAPED_UNICODE);
                  exit;
            } else {
                  return json_encode(array('response' => false, 'description' => 'Ошибка добавления комментария к сервису в единую базу данных'),JSON_UNESCAPED_UNICODE);
                  exit;
            }

      }




    }

  // добавление просмотра сервиса
  public function tech_services_view($id_services_view_on_referer,$id_service_on_referer,$id_user_tboil,$view,$date_update,$id_referer){
      global $database;

            $statement = $database->prepare("SELECT * FROM $this->MAIN_entity_tech_services_view WHERE id_referer = :id_referer AND id_services_view_on_referer = :id_services_view_on_referer AND id_service_on_referer = :id_service_on_referer AND id_tboil = :id_tboil");
            $statement->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_services_view_on_referer', $id_services_view_on_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
            $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
            $statement->execute();
            $data = $statement->fetch(PDO::FETCH_OBJ);

            if ($data) {

                  $request = $database->prepare("UPDATE $this->MAIN_entity_tech_services_view SET id_services_view_on_referer = :id_services_view_on_referer, id_service_on_referer = :id_service_on_referer, id_user_tboil = :id_user_tboil, view = :view, date_update = :date_update, id_referer = :id_referer WHERE id_referer = :id_referer AND id_services_view_on_referer = :id_services_view_on_referer AND id_service_on_referer = :id_service_on_referer AND id_tboil = :id_tboil");

                  $request->bindParam(':id_services_view_on_referer', $id_services_view_on_referer, PDO::PARAM_INT);
                  $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
                  $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
                  $request->bindParam(':view', $view, PDO::PARAM_INT);
                  $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
                  $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
                  $check_add = $request->execute();
                  $count = $request->rowCount();

                  if($count > 0) {
                        return json_encode(array('response' => true, 'description' => 'Просмотр сервиса успешно обновлен в единой базе данных'),JSON_UNESCAPED_UNICODE);
                        exit;
                  } else {
                        return json_encode(array('response' => false, 'description' => 'Ошибка обновления просмотра к сервису по технологическому запросу в единой базе данных'),JSON_UNESCAPED_UNICODE);
                        exit;
                  }

            } else {


                  $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_services_view (id_services_view_on_referer,id_service_on_referer,	id_tboil,view,date_update,id_referer)
                                                        VALUES (:id_services_view_on_referer,:id_service_on_referer,:id_tboil,:view,:date_update,:id_referer)");

                  $request->bindParam(':id_services_view_on_referer', $id_services_view_on_referer, PDO::PARAM_INT);
                  $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
                  $request->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
                  $request->bindParam(':view', $view, PDO::PARAM_INT);
                  $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
                  $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

                  $check_request = $request->execute();
                  $count_request = $request->rowCount();
                  if($count_request > 0) {
                        return json_encode(array('response' => true, 'description' => 'Просмотр сервиса успешно добавлен в единую базу данных'),JSON_UNESCAPED_UNICODE);
                        exit;
                  } else {
                        return json_encode(array('response' => false, 'description' => 'Ошибка добавления просмотра к сервису в единую базу данных'),JSON_UNESCAPED_UNICODE);
                        exit;
                  }

            }

    }

  // добавление акаунта пользователя
  public function add_user_accounts($id_user_in_ebd,$resource) {
    global $database;

    $today = date("Y-m-d H:i:s");

    $statement = $database->prepare("SELECT * FROM $this->MAIN_users_accounts WHERE id_user = :id_user AND id_referer = :id_referer");
    $statement->bindParam(':id_user', $id_user_in_ebd, PDO::PARAM_INT);
    $statement->bindParam(':id_referer', $resource, PDO::PARAM_INT);
    $statement->execute();
    $data = $statement->fetch(PDO::FETCH_OBJ);

    if (!$data) {

        $add_user_accs = $database->prepare("INSERT INTO $this->MAIN_users_accounts (id_user,id_referer,date_record) VALUES (:id_user,:id_referer,:date_record)");
        $add_user_accs->bindParam(':id_user', $id_user_in_ebd, PDO::PARAM_INT);
        $add_user_accs->bindParam(':id_referer', $resource, PDO::PARAM_INT);
        $add_user_accs->bindParam(':date_record', $today, PDO::PARAM_STR);
        $check_new_user = $add_user_accs->execute();
        $count = $database->lastInsertId();

        if ($count) {
              return json_encode(array('response' => true, 'description' => 'Аккаунт пользоватля успешно зарегистрирвоан в системе'),JSON_UNESCAPED_UNICODE);
              exit;
        }
        else {
              return json_encode(array('response' => false, 'description' => 'Ошибка добавления аккаунта для обновления данных, обратитесь к администратору', 'data_referer' => 'id_user = '.$id_user_in_ebd.', id_referer='.$id_referer),JSON_UNESCAPED_UNICODE);
              exit;
        }
    }
    else {
      return json_encode(array('response' => true, 'description' => 'Аккаунт пользоватля успешно зарегистрирвоан в системе'),JSON_UNESCAPED_UNICODE);
      exit;
    }


  }

  // Получение id реферов  аккаунтов  пользотвателя
  public function get_user_accounts_referer($id_user) {
    global $database;

    $statement = $database->prepare("SELECT AP_RE.link_update_trigger FROM $this->MAIN_users_accounts as US_AC, $this->api_referer as AP_RE WHERE US_AC.id_user = :id_user AND US_AC.id_referer = AP_RE.id");
    $statement->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_OBJ);

    if ($data) {
          return $data;
          exit;
    } else {
          return json_encode(array('response' => false, 'description' => 'У пользователя нет аккаунтов на реферах'),JSON_UNESCAPED_UNICODE);
          exit;
    }


  }

  // получение данных по пользователю API
  public function get_data_user_api($id_user_api) {
    global $database;

    $statement = $database->prepare("SELECT * FROM $this->users WHERE id = :id");
    $statement->bindParam(':id', $id_user_api, PDO::PARAM_INT);
    $statement->execute();
    $data = $statement->fetch(PDO::FETCH_OBJ);
    if ($data) {
          return $data;
          exit;
    } else {
          return false;
          exit;
    }


  }

  // Функция обновления всех платформ
  public function update_all_platform_referer($id_tboil,$id_entity = 0){
    global $database;

    $check_user = json_decode($this->get_user_data_id_boil($id_tboil));
    $check_referer = $this->get_user_accounts_referer($check_user->data->id);

    foreach ($check_referer as $key => $value) {
          $data_referer = $this->get_data_referer($value->link_update_trigger);
          $data_user_api = $this->get_data_user_api(json_decode($data_referer)->data->id_user);
          $arr = (object) array('id_tboil' => $id_tboil, 'id_entity' => $id_entity);
          $plaintext = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
          $key = $data_user_api->hash;
          $method = $this->get_global_settings('crypt_method');
          $ivlen = openssl_cipher_iv_length($method);
          $pseudo_bytes = openssl_random_pseudo_bytes($ivlen);
          $token = openssl_encrypt($plaintext, $method, $key, $options=0, $pseudo_bytes);
          $res_str = bin2hex($pseudo_bytes).$token;

          // $data = file_get_contents('https://test.e-spb.tech/test_cript.php');

          if( $curl = curl_init() ) {
                  $data_post = array( 'token' => $res_str );
                  curl_setopt($curl, CURLOPT_URL, $value->link_update_trigger);
                  curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                  curl_setopt($curl, CURLOPT_POST, true);
                  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
                  curl_setopt($curl, CURLOPT_USERAGENT, 'UNATED_BASE');
                  curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                  $check_return = curl_exec($curl);
                  curl_close($curl);
          }
    }

    return true;


  }

  // поиск данных юиридческого лица по ИНН компании
  public function serch_and_record_fns_data($inn) {
    global $database;

        $statement = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE inn = :inn");
        $statement->bindParam(':inn', $inn, PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_OBJ);

        if ($data) {
            return json_encode(array('response' => true, 'data' => json_decode($data->data_fns), 'description' => 'Данные успешно загружены из ЕБД'),JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $check_fns_data = $this->fns_base($inn);
            if (json_decode($check_fns_data)->response) {
                return json_encode(array('response' => true, 'data' => json_decode($check_fns_data)->data, 'description' => 'Данные успешно загружены из федеральной налоговой службы'),JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                return $check_fns_data;
                exit;
            }
        }
  }

  // Получение текущего списка айдишников tboil для синхронизации данных
  public function get_mass_id_tboil() {
    global $database;

    $statement = $database->prepare("SELECT * FROM $this->main_users");
    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_OBJ);

    $array_mass = array();
    foreach ($data as $key) {
          array_push($array_mass, $key->id_tboil);
    }

    return $array_mass;

  }

  // временное получние данных из lpmtech.ru
  public function get_all_lpmtech_user() {
      global $database;

      $statement = $database->prepare("SELECT * FROM `TEMP_entity_lpmtech`");
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_OBJ);

      return $data;

  }

  // Поиск физического лица по email
  public function search_user_email($email) {
    global $database;

    $statement = $database->prepare("SELECT * FROM $this->main_users WHERE email = :email");
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $data = $statement->fetch(PDO::FETCH_OBJ);

    if ($data) {
        return json_encode(array('response' => true, 'data' => $data, 'description' => 'Пользователь найден'),JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        return json_encode(array('response' => false, 'description' => 'Пользователь c таким email не найден'),JSON_UNESCAPED_UNICODE);
        exit;
    }

  }

  // добавление мероприятия в единую базу данных
  public function add_update_new_event($id_event_on_referer,$type_event,$name,$description,$organizer,$status,$start_datetime_event,$end_datetime_event,$place,$interest,$resource) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_events WHERE id_event_on_referer = :id_event_on_referer AND id_referer = :id_referer");
      $statement->bindParam(':id_event_on_referer', $id_event_on_referer, PDO::PARAM_INT);
      $statement->bindParam(':id_referer', $resource, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);
      $date_update = date("Y-m-d H:i:s");
      if (!$data) {

            $add_user_accs = $database->prepare("INSERT INTO $this->MAIN_events (id_event_on_referer,type_event,name,description,id_tboil_organizer,status,start_datetime_event,end_datetime_event,place,interest,date_update,id_referer) VALUES (:id_event_on_referer,:type_event,:name,:description,:id_tboil_organizer,:status,:start_datetime_event,:end_datetime_event,:place,:interest,:date_update,:id_referer)");
            $add_user_accs->bindParam(':id_event_on_referer', $id_event_on_referer, PDO::PARAM_INT);
            $add_user_accs->bindParam(':type_event', $type_event, PDO::PARAM_STR);
            $add_user_accs->bindParam(':name', $name, PDO::PARAM_STR);
            $add_user_accs->bindParam(':description', $description, PDO::PARAM_STR);
            $add_user_accs->bindParam(':id_tboil_organizer', $organizer, PDO::PARAM_INT);
            $add_user_accs->bindParam(':status', $status, PDO::PARAM_STR);
            $add_user_accs->bindParam(':start_datetime_event', $start_datetime_event, PDO::PARAM_STR);
            $add_user_accs->bindParam(':end_datetime_event', $end_datetime_event, PDO::PARAM_STR);
            $add_user_accs->bindParam(':place', $place, PDO::PARAM_STR);
            $add_user_accs->bindParam(':interest', $interest, PDO::PARAM_STR);
            $add_user_accs->bindParam(':date_update', $date_update, PDO::PARAM_STR);
            $add_user_accs->bindParam(':id_referer', $resource, PDO::PARAM_INT);
            $check_new_user = $add_user_accs->execute();
            $count = $database->lastInsertId();

            if ($count) {
                return json_encode(array('response' => true, 'id_event_in_ebd' => $count, 'description' => 'Мероприятие успешно добавлено в единую базу данных'),JSON_UNESCAPED_UNICODE);
                exit;
            }
            else {
                return json_encode(array('response' => false, 'description' => 'Ошибка добавления мероприятия в базу данных'),JSON_UNESCAPED_UNICODE);
                exit;
            }
      }
      else {

          $statement = $database->prepare("UPDATE $this->MAIN_events SET type_event = :type_event, name = :name, description = :description, id_tboil_organizer = :id_tboil_organizer, status = :status, start_datetime_event = :start_datetime_event, end_datetime_event = :end_datetime_event, place = :place, interest = :interest, date_update = :date_update  WHERE id_referer = :id_referer AND id_event_on_referer = :id_event_on_referer");
          $statement->bindParam(':id_event_on_referer', $id_event_on_referer, PDO::PARAM_INT);
          $statement->bindParam(':type_event', $type_event, PDO::PARAM_STR);
          $statement->bindParam(':name', $name, PDO::PARAM_STR);
          $statement->bindParam(':description', $description, PDO::PARAM_STR);
          $statement->bindParam(':id_tboil_organizer', $organizer, PDO::PARAM_INT);
          $statement->bindParam(':status', $status, PDO::PARAM_STR);
          $statement->bindParam(':start_datetime_event', $start_datetime_event, PDO::PARAM_STR);
          $statement->bindParam(':end_datetime_event', $end_datetime_event, PDO::PARAM_STR);
          $statement->bindParam(':place', $place, PDO::PARAM_STR);
          $statement->bindParam(':interest', $interest, PDO::PARAM_STR);
          $statement->bindParam(':date_update', $date_update, PDO::PARAM_STR);
          $statement->bindParam(':id_referer', $resource, PDO::PARAM_INT);
          $check_add = $statement->execute();
          $count = $statement->rowCount();

          if ($count) {
                return json_encode(array('response' => true, 'id_event_in_ebd' => $data->id, 'description' => 'Мероприятие успешно обновлено в единой базе данных'),JSON_UNESCAPED_UNICODE);
                exit;
          }
          else {
              return json_encode(array('response' => false, 'description' => 'Ошибка обновления мероприятия в единой базе данных'),JSON_UNESCAPED_UNICODE);
              exit;
          }

      }

  }

  // добавление посещения мероприятия пользователем tboil
  public function add_user_visit_events($id_event_on_referer,$id_tboil,$status,$resource) {
    global $database;

        $statement = $database->prepare("SELECT * FROM $this->MAIN_users_events WHERE id_event_on_referer = :id_event_on_referer AND id_referer = :id_referer AND id_tboil = :id_tboil");
        $statement->bindParam(':id_event_on_referer', $id_event_on_referer, PDO::PARAM_INT);
        $statement->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
        $statement->bindParam(':id_referer', $resource, PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_OBJ);
        $date_update = date("Y-m-d H:i:s");
        if (!$data) {

              $statement = $database->prepare("INSERT INTO $this->MAIN_users_events (id_event_on_referer,id_tboil,id_referer,date_added,status) VALUES (:id_event_on_referer,:id_tboil,:id_referer,:date_added,:status)");
              $statement->bindParam(':id_event_on_referer', $id_event_on_referer, PDO::PARAM_INT);
              $statement->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
              $statement->bindParam(':id_referer', $resource, PDO::PARAM_INT);
              $statement->bindParam(':date_added', $date_update, PDO::PARAM_STR);
              $statement->bindParam(':status', $status, PDO::PARAM_STR);
              $check_new_user = $statement->execute();
              $count = $database->lastInsertId();

              if ($count) {
                  return json_encode(array('response' => true, 'description' => 'Посещение пользователем мероприятия успешно записано в единую базу данных'),JSON_UNESCAPED_UNICODE);
                  exit;
              }
              else {
                  return json_encode(array('response' => false, 'description' => 'Ошибка добавления посещения пользователя мероприятия в единую базу данных'),JSON_UNESCAPED_UNICODE);
                  exit;
              }
        }
        else {
            return json_encode(array('response' => true, 'description' => 'Посещение данным пользователем данного мероприятия было ранее записано в единую базу данных'),JSON_UNESCAPED_UNICODE);
            exit;
        }

  }

  // добавление посещения мероприятия юридическим лицом
  public function add_entity_visit_events($id_event_on_referer,$id_entity,$status,$resource) {
    global $database;

        $statement = $database->prepare("SELECT * FROM $this->MAIN_entity_events WHERE id_event_on_referer = :id_event_on_referer AND id_referer = :id_referer AND id_entity = :id_entity");
        $statement->bindParam(':id_event_on_referer', $id_event_on_referer, PDO::PARAM_INT);
        $statement->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
        $statement->bindParam(':id_referer', $resource, PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_OBJ);
        $date_update = date("Y-m-d H:i:s");
        if (!$data) {

              $statement = $database->prepare("INSERT INTO $this->MAIN_entity_events (id_event_on_referer,id_entity,id_referer,date_added,status) VALUES (:id_event_on_referer,:id_entity,:id_referer,:date_added,:status)");
              $statement->bindParam(':id_event_on_referer', $id_event_on_referer, PDO::PARAM_INT);
              $statement->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
              $statement->bindParam(':id_referer', $resource, PDO::PARAM_INT);
              $statement->bindParam(':date_added', $date_update, PDO::PARAM_STR);
              $statement->bindParam(':status', $status, PDO::PARAM_STR);
              $check_new_user = $statement->execute();
              $count = $database->lastInsertId();

              if ($count) {
                  return json_encode(array('response' => true, 'description' => 'Посещение юридическим лицом мероприятия успешно записано в единую базу данных'),JSON_UNESCAPED_UNICODE);
                  exit;
              }
              else {
                  return json_encode(array('response' => false, 'description' => 'Ошибка добавления посещения юридическим лицом мероприятия в единую базу данных'),JSON_UNESCAPED_UNICODE);
                  exit;
              }
        }
        else {
            return json_encode(array('response' => true, 'description' => 'Посещение данным юридическим лицом данного мероприятия было ранее записано в единую базу данных'),JSON_UNESCAPED_UNICODE);
            exit;
        }


  }

  // получение всех мероприятий из единой базы данных или по типу
  public function get_all_events($type_event = 'all') {
      global $database;

      if ($type_event == 'all') {
            $statement = $database->prepare("SELECT * FROM $this->MAIN_events");
            $statement->execute();
            $data = $statement->fetchAll(PDO::FETCH_OBJ);
      }
      else {
            $all = 'all';
            $statement = $database->prepare("SELECT * FROM $this->MAIN_events WHERE type_event = :type_event AND type_event = :type_eventall");
            $statement->bindParam(':type_event', $type_event, PDO::PARAM_STR);
            $statement->bindParam(':type_eventall', $all, PDO::PARAM_STR);
            $statement->execute();
            $data = $statement->fetchAll(PDO::FETCH_OBJ);
      }

      if ($data) {
            return json_encode(array('response' => true, 'data' => $data, 'description' => 'Мероприятия по вашему запросу найдены'),JSON_UNESCAPED_UNICODE);
            exit;
      }
      else {
            return json_encode(array('response' => false, 'description' => 'Мероприятий в базе данных не найдено, по данному запросу'),JSON_UNESCAPED_UNICODE);
            exit;
      }


  }

  // получение мероприятий отдельного пользователя по id_tboil
  public function get_user_event($id_user_tboil) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_users_events INNER JOIN $this->MAIN_events ON $this->MAIN_users_events.`id_event_on_referer` = $this->MAIN_events.`id_event_on_referer` AND $this->MAIN_users_events.`id_referer` = $this->MAIN_events.`id_referer` WHERE id_tboil = :id_tboil ORDER BY start_datetime_event DESC");
      $statement->bindParam(':id_tboil', $id_user_tboil, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_OBJ);

      if ($data) {
            return json_encode(array('response' => true, 'data' => $data, 'description' => 'Мероприятия на которые ходил данный пользователь найдены'),JSON_UNESCAPED_UNICODE);
            exit;
      }
      else {
            return json_encode(array('response' => false, 'description' => 'Мероприятий в базе данных не найдено, по данному запросу'),JSON_UNESCAPED_UNICODE);
            exit;
      }


  }

  // вспомогающая функция для
  public function date_time_rus($string_data,$time = true) {
        if ($string_data) {
          if ($time == false) {
              $myDateTime = DateTime::createFromFormat('Y-m-d', $string_data);
              $newDateString = $myDateTime->format('d.m.Y');
          }
          else {
              $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $string_data);
              $newDateString = $myDateTime->format('d.m.Y H:i');
          }
          return $newDateString;
        }
        else {
          return false;
        }
  }

  // получение данных по отдельному мероприятияю
  public function get_data_one_event($id_event){
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_events WHERE id = :id");
      $statement->bindParam(':id', $id_event, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data) {
          return json_encode(array('response' => true, 'data' => $data, 'description' => 'Данныt по мероприятияю успешно найдены'),JSON_UNESCAPED_UNICODE);
          exit;
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Данных по данному мероприятию не найдено'),JSON_UNESCAPED_UNICODE);
          exit;
      }

  }

  // провекра статуса сколково по инн и API Сколково
  public function check_status_skolkovo_entity($inn) {

                $skolkovo_fond = file_get_contents("https://crmapi.sk.ru/api/Public/GetMembers");
                $test_json = json_decode($skolkovo_fond);
                $massiv_skolkovo = array();
                foreach ($test_json as $key) {
                  array_push($massiv_skolkovo,$key->Inn);
                }

                if (in_array($inn, $massiv_skolkovo)) {
                    return true;
                } else {
                    return false;
                }

  }

  // получение пользователя по инн компании (привязанного пользователя)
  public function get_id_user_tboil_entity($inn_entity) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE inn = :inn");
      $statement->bindParam(':inn', $inn_entity, PDO::PARAM_STR);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data->id) {
            $id_entity = $data->id;
            $statement = $database->prepare("SELECT * FROM $this->main_users WHERE id_entity = :id_entity");
            $statement->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
            $statement->execute();
            $data_user = $statement->fetch(PDO::FETCH_OBJ);

            if ($data_user) {
                return $data_user->id_tboil;
                exit;
            }
            else {
                return false;
                exit;
            }

      } else {
          return false;
          exit;
      }

  }

  // получение данных по тикету поддержки
  public function get_data_tiket($id_ticket){
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE id = :id");
      $statement->bindParam(':id', $id_ticket, PDO::PARAM_INT);
      $statement->execute();
      $data = $statement->fetch(PDO::FETCH_OBJ);

      if ($data->id) {
            return json_encode(array('response' => true, 'data' => $data, 'description' => 'Данные по тикету успешно найдены'),JSON_UNESCAPED_UNICODE);
            exit;
      }
      else {
            return json_encode(array('response' => false, 'description' => 'Данные по тикету не найдены'),JSON_UNESCAPED_UNICODE);
            exit;
      }

  }

  // Смена статуса тикета
  public function update_status_support_tiket($id_ticket,$new_status) {
        global $database;

        $date_update = date("Y-m-d H:i:s");

        $update_status_ticket = $database->prepare("UPDATE $this->MAIN_support_ticket SET status = :status, date_added = :date_added  WHERE id =:id");
        $update_status_ticket->bindParam(':id', $id_ticket, PDO::PARAM_INT);
        $update_status_ticket->bindParam(':date_added', $date_update, PDO::PARAM_STR);
        $update_status_ticket->bindParam(':status', $new_status, PDO::PARAM_STR);
        $temp = $update_status_ticket->execute();
        $check = $update_status_ticket->rowCount();

        if ($check) {

            $data_tiket_support = json_decode($this->get_data_tiket($id_ticket))->data;
            $data_user_tiket = json_decode($this->get_user_data_id_boil($data_tiket_support->id_tboil))->data;
            $data_referer_ticket = json_decode($this->get_data_referer_id($data_tiket_support->id_referer))->data;

            $array_status = array('work' => 'в работе',
                                  'close' => 'закрыта',
                                  'open' => 'открыта'
                                );

            $content =  'Здравствуйте, '.$data_user_tiket->name.' '.$data_user_tiket->second_name.'<br>';
            $content .= 'Статус вашей заявки был изменен на «'.$array_status[$new_status].'».<br>';
            $content .= 'Посмотреть подробности заявки Вы можете в личном кабинете.';

            $tema = 'Статус заявки #'.$id_ticket;

            $today = date("d.m.Y H:i");

            $maildata =
                  array(
                    'title' => $tema,
                    'description' => $content,
                    'link_to_server' => 'https://'.$data_referer_ticket->resourse,
                    'text_button' => 'Личный кабинет',
                    'link_button' => $data_referer_ticket->link_to_support,
                    'link_to_logo' => $data_referer_ticket->link_to_logo,
                    'alt_link_to_logo' => $data_referer_ticket->resourse,
                    'color_button1' => $data_referer_ticket->color_button1,
                    'text_color_button1' =>$data_referer_ticket->color_text_button1,
                    'name_host' => $data_referer_ticket->resourse,
                    'date' => $today
                  );

            $template_email = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/assets/template/mail/support_tikcet_status.php');

            foreach ($maildata as $key => $value) {
              $template_email = str_replace('['.$key.']', $value, $template_email);
            }

            $check_mail = $this->send_email_user($data_user_tiket->email,$tema,$template_email);

            $chek_history_status = $this->add_status_in_history($data_tiket_support->id,$new_status);

            return json_encode(array('response' => true, 'description' => 'Статус заявки изменен'), JSON_UNESCAPED_UNICODE);
            exit;
        }
        else {
            return json_encode(array('response' => false, 'description' => 'Ошибка изменения статуса заявки'), JSON_UNESCAPED_UNICODE);
            exit;
        }

  }

  // добавление истории обновления статусов тикетов поддержки
  public function add_status_in_history($id_ticket,$status) {
        global $database;

        $date = date("Y-m-d H:i:s");

        $d_data = $database->prepare("INSERT INTO $this->MAIN_support_ticket_status_history (id_support_ticket,status,date_update)
                                      VALUES (:id_support_ticket,:status,:date_update)");
        $d_data->bindParam(':id_support_ticket', $id_ticket, PDO::PARAM_INT);
        $d_data->bindParam(':status', $status, PDO::PARAM_STR);
        $d_data->bindParam(':date_update', $date, PDO::PARAM_STR);
        $temp = $d_data->execute();
        $id_new_ticket = $database->lastInsertId();

        if ($id_new_ticket) {
              return json_encode(array('response' => true, 'description' => 'Статус заявки успешно записан в историю статусов запроса'), JSON_UNESCAPED_UNICODE);
              exit;
        }
        else {
              return json_encode(array('response' => false, 'description' => 'Ошибка записи истории'), JSON_UNESCAPED_UNICODE);
              exit;
        }

  }

  // получить данные по истории статусов
  public function get_ticket_status_history($id_ticket) {
        global $database;

        $statement = $database->prepare("SELECT * FROM $this->MAIN_support_ticket_status_history WHERE id_support_ticket = :id");
        $statement->bindParam(':id', $id_ticket, PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_OBJ);

        if ($data) {
              return json_encode(array('response' => true, 'data' => $data, 'description' => 'История статусов заявки успешно найдена'), JSON_UNESCAPED_UNICODE);
              exit;
        }
        else {
              return json_encode(array('response' => false, 'description' => 'Ошибка, история статусов заявки не найдена'), JSON_UNESCAPED_UNICODE);
              exit;
        }

  }

  // функция подсчета заявок
  public function count_support_tickets($status){
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE status = :status");
      $statement->bindParam(':status', $status, PDO::PARAM_STR);
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_OBJ);

      if ($data) {
            $data_count = count($data);
            return $data_count;
            exit;
      }
      else {
            return 0;
            exit;
      }

  }

  // получение тикетов заявок по статусом
  public function get_support_tiket_status($status) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE status = :status");
      $statement->bindParam(':status', $status, PDO::PARAM_STR);
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_OBJ);

      if ($data) {
          return json_encode(array('response' => true, 'data' => $data, 'description' => 'Заявки со статусом успешно найдены'), JSON_UNESCAPED_UNICODE);
          exit;
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Нет заявок'), JSON_UNESCAPED_UNICODE);
          exit;
      }
  }


  // Функция
  public function entity_additionally($id_entity) {
        global $database;

        //
        $check_data_с = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE id = :id_entity");
        $check_data_с->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
        $check_data_с->execute();
        $check_data_result_с = $check_data_с->fetch(PDO::FETCH_OBJ);

        $check_data = $database->prepare("SELECT * FROM $this->MAIN_entity_additionally WHERE id_entity = :id_entity");
        $check_data->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
        $check_data_с->execute();
        $check_data_result = $check_data->fetch(PDO::FETCH_OBJ);

        $fns_data_company = $this->fns_base($check_data_result_с->inn);


        $category = (!empty($category)) ? $category : ' ';
        $proceeds = (isset($proceeds)) ? $proceeds : ' ';
        $proceeds_import = (!empty($proceeds_import)) ? $proceeds_import : ' ';
        $proceeds_export = (!empty($proceeds_export)) ? $proceeds_export : ' ';
        $count_staff = (isset($count_staff)) ? $count_staff : ' ';
        $volume_fund = (!empty($volume_fund)) ? $volume_fund : ' ';
        $volume_tax_customs = (!empty($volume_tax_customs)) ? $volume_tax_customs : ' ';
        $volume_investments = (!empty($volume_investments)) ? $volume_investments : ' ';
        $volume_credits = (!empty($volume_credits)) ? $volume_credits : ' ';
        $count_patent = (!empty($count_patent)) ? $count_patent : ' ';
        $pay_research = (!empty($pay_research)) ? $pay_research : ' ';
        $count_export_contracts = (!empty($count_export_contracts)) ? $count_export_contracts : ' ';
        $land = (!empty($land)) ? $land : ' ';
        $land_area = (!empty($land_area)) ? $land_area : ' ';
        $room_area = (!empty($room_area)) ? $room_area : ' ';

        if(is_Object($check_data_result)) {
          //нашли прошлую запись, обновляем ее

          $d_data = $database->prepare("UPDATE $this->gen_settings SET category = :category, proceeds = :proceeds, proceeds_import = :proceeds_import,
            proceeds_export = :proceeds_export, count_staff = :count_staff, volume_fund = :volume_fund, volume_tax_customs = :volume_tax_customs,
            volume_investments = :volume_investments, volume_credits = :volume_credits, count_patent = :count_patent, pay_research = :pay_research,
            count_export_contracts = :count_export_contracts, land = :land, land_area = :land_area, room_area = :room_area  WHERE id_entity = :id_entity,");
        } else {
          $d_data = $database->prepare("INSERT INTO $this->errors_migrate (id_entity, category, proceeds, proceeds_import, proceeds_export, count_staff, volume_fund, volume_tax_customs, volume_investments, volume_credits, count_patent, pay_research, count_export_contracts, land, land_area, room_area)
          VALUES (:id_entity, :category, :proceeds, :proceeds_import, :proceeds_export, :count_staff, :volume_fund, :volume_tax_customs, :volume_investments, :volume_credits, :count_patent, :pay_research, :count_export_contracts, :land, :land_area, :room_area)");
        }

        $d_data->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
        $d_data->bindParam(':category', $category, PDO::PARAM_STR);
        $d_data->bindParam(':proceeds', $proceeds, PDO::PARAM_STR);
        $d_data->bindParam(':proceeds_import', $proceeds_import, PDO::PARAM_STR);
        $d_data->bindParam(':proceeds_export', $proceeds_export, PDO::PARAM_STR);
        $d_data->bindParam(':count_staff', $count_staff, PDO::PARAM_STR);
        $d_data->bindParam(':volume_fund', $volume_fund, PDO::PARAM_STR);
        $d_data->bindParam(':volume_tax_customs', $volume_tax_customs, PDO::PARAM_STR);
        $d_data->bindParam(':volume_investments', $volume_investments, PDO::PARAM_STR);
        $d_data->bindParam(':volume_credits', $volume_credits, PDO::PARAM_STR);
        $d_data->bindParam(':count_patent', $count_patent, PDO::PARAM_STR);
        $d_data->bindParam(':pay_research', $pay_research, PDO::PARAM_STR);
        $d_data->bindParam(':count_export_contracts', $count_export_contracts, PDO::PARAM_STR);
        $d_data->bindParam(':land', $land, PDO::PARAM_STR);
        $d_data->bindParam(':land_area', $land_area, PDO::PARAM_STR);
        $d_data->bindParam(':room_area', $room_area, PDO::PARAM_STR);

        $temp = $d_data->execute();
        $count = $d_data->rowCount();

        if($count > 0){
          return json_encode(array('response' => true, 'description' => 'Данные добавлены/обновлены'), JSON_UNESCAPED_UNICODE);
        } else {
          return json_encode(array('response' => false, 'description' => 'Ошибка добавление/обновления данных'), JSON_UNESCAPED_UNICODE);
        }

    }

  // добавлени нового тикета в поддержку
  public function add_new_support_ticket($type_support,$id_tboil,$name,$short_description,$full_description,$target,$question_desc,$links_add_files,$link_to_photo,$programma_fci,$contact_face,$contacts,$id_referer) {

    global $database;

    if(!isset($id_tboil) || !isset($name)  || !isset($contact_face) || !isset($contacts) || !isset($id_referer)) {
      return json_encode(array('response' => false, 'description' => 'Не все обезательные поля были указаны'), JSON_UNESCAPED_UNICODE);
    }

    $type_support = trim($type_support);
    $name = trim($name);
    $short_description = trim($short_description);
    $full_description = trim($full_description);
    $target = trim($target);
    $question_desc = trim($question_desc);
    $links_add_files = trim($links_add_files);
    $link_to_photo = trim($link_to_photo);
    $programma_fci = trim($programma_fci);
    $contact_face = trim($contact_face);
    $contacts = trim($contacts);

    $type_support = (isset($type_support)) ? $type_support : NULL;
    $name = (isset($name)) ? $name : NULL;
    $short_description = (isset($short_description)) ? $short_description : NULL;
    $full_description = (isset($full_description)) ? $full_description : NULL;
    $target = (isset($target)) ? $target : NULL;
    $question_desc = (isset($question_desc)) ? $question_desc : NULL;
    $links_add_files = (isset($links_add_files)) ? $links_add_files : NULL;
    $link_to_photo = (isset($link_to_photo)) ? $link_to_photo : NULL;
    $programma_fci = (isset($programma_fci)) ? $programma_fci : NULL;
    $contact_face = (isset($contact_face)) ? $contact_face : NULL;
    $contacts = (isset($contacts)) ? $contacts : NULL;

    $date_added = date("Y-m-d H:i:s");
    $date_update_status = date("Y-m-d H:i:s");
    $status = 'open';
    $hash_tiket_support = md5($date_added.$date_update_status.$id_tboil.$name);

      $d_data = $database->prepare("INSERT INTO $this->MAIN_support_ticket (type_support, id_tboil, name, short_description, full_description, target, question_desc, date_added, status, links_add_files, link_to_photo, programma_fci, contact_face, contacts, id_referer, hash_tiket_support, date_update_status)
                                    VALUES (:type_support,:id_tboil,:name,:short_description,:full_description,:target,:question_desc,:date_added,:status,:links_add_files,:link_to_photo,:programma_fci,:contact_face,:contacts,:id_referer,:hash_tiket_support,:date_update_status)");

      $d_data->bindParam(':type_support', $type_support, PDO::PARAM_STR);
      $d_data->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
      $d_data->bindParam(':name', $name, PDO::PARAM_STR);
      $d_data->bindParam(':short_description', $short_description, PDO::PARAM_STR);
      $d_data->bindParam(':full_description', $full_description, PDO::PARAM_STR);
      $d_data->bindParam(':target', $target, PDO::PARAM_STR);
      $d_data->bindParam(':question_desc', $question_desc, PDO::PARAM_STR);
      $d_data->bindParam(':date_added', $date_added, PDO::PARAM_STR);
      $d_data->bindParam(':status', $status, PDO::PARAM_STR);
      $d_data->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
      $d_data->bindParam(':link_to_photo', $link_to_photo, PDO::PARAM_STR);
      $d_data->bindParam(':programma_fci', $programma_fci, PDO::PARAM_STR);
      $d_data->bindParam(':contact_face', $contact_face, PDO::PARAM_STR);
      $d_data->bindParam(':contacts', $contacts, PDO::PARAM_STR);
      $d_data->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
      $d_data->bindParam(':hash_tiket_support', $hash_tiket_support, PDO::PARAM_STR);
      $d_data->bindParam(':date_update_status', $date_update_status, PDO::PARAM_STR);

      $temp = $d_data->execute();
      $id_new_ticket = $database->lastInsertId();
      if($id_new_ticket === false){
        return json_encode(array('response' => false, 'description' => 'Ошибка добавления новой заявки'), JSON_UNESCAPED_UNICODE);
      } else {
        return json_encode(array('response' => true, 'description' => 'Заявка успешно добавлена'), JSON_UNESCAPED_UNICODE);
      }

  }

  //получение данных по тикиту
    //type_search поиск по id tiket = true
    //или по user_tboil = false создавшего тикет для получения всех его тикетов и истории переписки
  public function get_data_support_ticket($value_search, $type_search = true) {
      if(!isset($value_search)){
        return json_encode(array('response' => false, 'description' => 'Не указаны все обходимые данные'), JSON_UNESCAPED_UNICODE);
      }
      global $database;

      $arr_result = (object) array();

      if($type_search){
        $ticket_data = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE id = :id");
        $ticket_data->bindParam(':id', $value_search, PDO::PARAM_INT);
      } else {
        $ticket_data = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE id_tboil = :id_tboil");
        $ticket_data->bindParam(':id_tboil', $value_search, PDO::PARAM_INT);
      }
      $ticket_data->execute();
      $result_data_ticket = $ticket_data->fetchAll(PDO::FETCH_OBJ);

      if (empty($result_data_ticket)) {
        if($type_search){
          return json_encode(array('response' => false, 'description' => 'Тикет с указаным id не найден'), JSON_UNESCAPED_UNICODE);
        } else {
          return json_encode(array('response' => false, 'description' => 'У Пользователя небыло тикетов'), JSON_UNESCAPED_UNICODE);
        }
      }

      foreach ($result_data_ticket as $key_tiket => $value_tiket) {
        $result_data_message = [];
        $message_data = $database->prepare("SELECT * FROM $this->MAIN_support_ticket_messages WHERE id_support_ticket = :id_support_ticket");
        $message_data->bindParam(':id_support_ticket', $value_tiket->id, PDO::PARAM_INT);
        $message_data->execute();
        $result_data_message = $message_data->fetchAll(PDO::FETCH_OBJ);

        if(empty($result_data_message)){
          $arr_result->$key_tiket->messages = 'Ошибка получения переписки в тиките';
        } else {
          $arr_result->$key_tiket->messages = $result_data_message;
        }

        $arr_result->$key_tiket->ticket = $value_tiket;

        $conclusion_data = $database->prepare("SELECT * FROM $this->MAIN_support_ticket_conclusion WHERE id_support_ticket = :id_support_ticket");
        $conclusion_data->bindParam(':id_support_ticket', $value_tiket->id, PDO::PARAM_INT);
        $conclusion_data->execute();
        $result_data_conclusion = $conclusion_data->fetchAll(PDO::FETCH_OBJ);

          if(empty($result_data_conclusion)){
              $arr_result->$key_tiket->conclusion = 'Ошибка получения решения в тиките';
          } else {
            $arr_result->$key_tiket->conclusion = $result_data_conclusion;
          }
        }

      return json_encode(array('response' => true, 'data' => $arr_result,'description' => 'Получение данных о тиките/ах'), JSON_UNESCAPED_UNICODE);
    }

  // добавление нового сообщения в тикет поддержки
  public function add_new_support_messages($hash_tiket, $id_tboil, $message, $id_referer, $type_user) {
      global $database;

      $count_users = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE hash_tiket_support = :hash_tiket_support");
      $count_users->bindParam(':hash_tiket_support', $hash_tiket, PDO::PARAM_STR);
      $count_users->execute();
      $data_count_users = $count_users->fetch(PDO::FETCH_OBJ);

      if ($data_count_users) {
          $id_support_ticket = $data_count_users->id;
      }
      else {
          return $data_tickkets;
          exit;
      }

      $date_added = date("Y-m-d H:i:s");

      $d_data = $database->prepare("INSERT INTO $this->MAIN_support_ticket_messages (id_support_ticket, id_tboil_or_id_support, message, date_added, id_referer, type_user)
                                    VALUES (:id_support_ticket, :id_tboil_or_id_support, :message, :date_added, :id_referer, :type_user)");
      $d_data->bindParam(':id_support_ticket', $id_support_ticket, PDO::PARAM_INT);
      $d_data->bindParam(':id_tboil_or_id_support', $id_tboil, PDO::PARAM_INT);
      $d_data->bindParam(':message', $message, PDO::PARAM_STR);
      $d_data->bindParam(':date_added', $date_added, PDO::PARAM_STR);
      $d_data->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);
      $d_data->bindParam(':type_user', $type_user, PDO::PARAM_STR);
      $temp = $d_data->execute();
      $id_new_messages = $database->lastInsertId();
      if($id_new_messages === false){
        return json_encode(array('response' => false, 'description' => 'Ошибка добавления нового сообщения'), JSON_UNESCAPED_UNICODE);
      } else {

        if ($type_user == 'support') {

              $data_tiket_support = json_decode($this->get_data_tiket($id_support_ticket))->data;
              $data_user_tiket = json_decode($this->get_user_data_id_boil($data_tiket_support->id_tboil))->data;
              $data_referer_ticket = json_decode($this->get_data_referer_id($data_tiket_support->id_referer))->data;

              $array_status = array('work' => 'в работе',
                                    'close' => 'закрыта',
                                    'open' => 'открыта'
                                  );

              $content =  $data_user_tiket->name.' '.$data_user_tiket->second_name.', ';
              $content .= 'на Вашу заявку поступило новое сообщение от технической поддержки.<br>';
              $content .= 'Посмотреть подробности Вы можете в личном кабинете.';

              $tema = 'Сообщение по заявке #'.$id_support_ticket;

              $today = date("d.m.Y H:i");

              $maildata =
                    array(
                      'title' => $tema,
                      'description' => $content,
                      'link_to_server' => 'https://'.$data_referer_ticket->resourse,
                      'text_button' => 'Личный кабинет',
                      'link_button' => $data_referer_ticket->link_to_support,
                      'link_to_logo' => $data_referer_ticket->link_to_logo,
                      'alt_link_to_logo' => $data_referer_ticket->resourse,
                      'color_button1' => $data_referer_ticket->color_button1,
                      'text_color_button1' =>$data_referer_ticket->color_text_button1,
                      'name_host' => $data_referer_ticket->resourse,
                      'date' => $today
                    );

              $template_email = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/assets/template/mail/support_tikcet_status.php');

              foreach ($maildata as $key => $value) {
                    $template_email = str_replace('['.$key.']', $value, $template_email);
              }

              $check_mail = $this->send_email_user($data_user_tiket->email,$tema,$template_email);

        }
        return json_encode(array('response' => true, 'description' => 'Сообщение успешно добавлено', 'data' => (object) array('id' => $id_new_messages)), JSON_UNESCAPED_UNICODE);
      }

    }

  // обновление сообщения в тикете поддержки
  // public function upd_support_messages($id_support_ticket_msg, $message, $links_add_files) {
  //     global $database;
  //
  //     if(empty($id_support_ticket_msg) && empty($message)) {
  //       return json_encode(array('response' => false, 'description' => 'Не все обезательные поля были указаны'), JSON_UNESCAPED_UNICODE);
  //     }
  //     if($links_add_files) $links_add_files = trim($links_add_files);
  //     $links_add_files = ($links_add_files) ? $links_add_files : NULL;
  //
  //     $d_data = $database->prepare("UPDATE $this->MAIN_support_ticket_messages SET message =:message, links_add_files =:links_add_files WHERE id =:id");
  //     $d_data->bindParam(':id', $id_support_ticket_msg, PDO::PARAM_INT);
  //     $d_data->bindParam(':message', $message, PDO::PARAM_STR);
  //     $d_data->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
  //     $temp = $d_data->execute();
  //     if(!$d_data->rowCount()){
  //       return json_encode(array('response' => false, 'description' => 'Ошибка редактирования сообщения'), JSON_UNESCAPED_UNICODE);
  //     } else {
  //       return json_encode(array('response' => true, 'description' => 'Сообщение успешно отредактировано'), JSON_UNESCAPED_UNICODE);
  //     }
  //
  //   }

  // добавление решения по тикету поддержку
  // public function add_new_support_conclusion($id_support_ticket, $id_tboil, $description, $action, $links_add_files) {
  //     global $database;
  //
  //     if(empty($id_support_ticket) && empty($id_tboil) && empty($description) && empty($action) ) {
  //       return json_encode(array('response' => false, 'description' => 'Не все обезательные поля были указаны'), JSON_UNESCAPED_UNICODE);
  //     }
  //     if($links_add_files) $links_add_files = trim($links_add_files);
  //     $links_add_files = ($links_add_files) ? $links_add_files : NULL;
  //     $date_added = date("Y-m-d H:i:s");
  //
  //     $d_data = $database->prepare("INSERT INTO $this->MAIN_support_ticket_conclusion (id_support_ticket, id_tboil, description, action, links_add_files, date_added)
  //                                   VALUES (:id_support_ticket, :id_tboil, :description, :action, :links_add_files, :date_added)");
  //     $d_data->bindParam(':id_support_ticket', $id_support_ticket, PDO::PARAM_INT);
  //     $d_data->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
  //     $d_data->bindParam(':description', $description, PDO::PARAM_STR);
  //     $d_data->bindParam(':action', $action, PDO::PARAM_STR);
  //     $d_data->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
  //     $d_data->bindParam(':date_added', $date_added, PDO::PARAM_STR);
  //
  //     $temp = $d_data->execute();
  //     $id_new_conclusion = $database->lastInsertId();
  //     if($id_new_conclusion === false){
  //       return json_encode(array('response' => false, 'description' => 'Ошибка добавления новой записи'), JSON_UNESCAPED_UNICODE);
  //     } else {
  //       return json_encode(array('response' => true, 'description' => 'Решение успешно добавлено', 'data' => (object) array('id' => $id_new_conclusion)), JSON_UNESCAPED_UNICODE);
  //     }
  //
  //   }

  // обновление данных по решению
  // public function upd_support_conclusion($id_ticket_conclusion, $description, $action, $links_add_files) {
  //     global $database;
  //
  //     if(empty($id_ticket_conclusion) && empty($description) && empty($action) ) {
  //       return json_encode(array('response' => false, 'description' => 'Не все обезательные поля были указаны'), JSON_UNESCAPED_UNICODE);
  //     }
  //     if($links_add_files) $links_add_files = trim($links_add_files);
  //     $links_add_files = ($links_add_files != '') ? $links_add_files : NULL;
  //     $date_added = date("Y-m-d H:i:s");
  //
  //     $d_data = $database->prepare("UPDATE $this->MAIN_support_ticket_conclusion SET description =:description, action =:action, links_add_files =:links_add_files WHERE id =:id");
  //     $d_data->bindParam(':id', $id_ticket_conclusion, PDO::PARAM_INT);
  //     $d_data->bindParam(':description', $description, PDO::PARAM_STR);
  //     $d_data->bindParam(':action', $action, PDO::PARAM_STR);
  //     $d_data->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
  //     $temp = $d_data->execute();
  //     if(!$d_data->rowCount()){
  //       return json_encode(array('response' => false, 'description' => 'Ошибка редактирования решения'), JSON_UNESCAPED_UNICODE);
  //     } else {
  //       return json_encode(array('response' => true, 'description' => 'Решение успешно отредактировано'), JSON_UNESCAPED_UNICODE);
  //     }
  //
  //   }


  // получение списка заявок пользователя
  public function get_support_tikets_list($id_tboil) {
    global $database;

    $count_users = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE id_tboil = :id_tboil ORDER BY id DESC");
    $count_users->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
    $count_users->execute();
    $data_count_users = $count_users->fetchAll(PDO::FETCH_OBJ);

    if (count($data_count_users)) {
        return json_encode(array('response' => true, 'data'=> $data_count_users, 'description' => 'Данные по заявкам пользователя успешно найдены'),JSON_UNESCAPED_UNICODE);
        exit;
    }
    else {
        return json_encode(array('response' => false, 'description' => 'Ошибка, данных по заявкам поддержки пользователя не найдено'),JSON_UNESCAPED_UNICODE);
        exit;
    }

  }

  // Получение данных по хэшу заявки
  public function get_support_data_tiket($hash_tiket,$id_tboil) {
      global $database;

      $count_users = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE hash_tiket_support = :hash_tiket_support AND id_tboil = :id_tboil");
      $count_users->bindParam(':hash_tiket_support', $hash_tiket, PDO::PARAM_STR);
      $count_users->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
      $count_users->execute();
      $data_count_users = $count_users->fetch(PDO::FETCH_OBJ);

      if ($data_count_users) {
          return json_encode(array('response' => true, 'data'=> $data_count_users, 'description' => 'Данные по заявке пользователя успешно найдены'),JSON_UNESCAPED_UNICODE);
          exit;
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Ошибка, данных по данному тикету не найдено или тикет не принадлежит данному пользователю'),JSON_UNESCAPED_UNICODE);
          exit;
      }
  }

  // получениt сообщений по тикету
  public function get_support_messages_tiket($hash_tiket,$id_tboil) {
      global $database;

      $chek_ticket = $this->get_support_data_tiket($hash_tiket,$id_tboil);

      if (json_decode($chek_ticket)->response) {
          $id_ticket_support = json_decode($chek_ticket)->data->id;
          $count_users = $database->prepare("SELECT * FROM $this->MAIN_support_ticket_messages WHERE id_support_ticket = :id_support_ticket");
          $count_users->bindParam(':id_support_ticket', $id_ticket_support, PDO::PARAM_INT);
          $count_users->execute();
          $data_count_users = $count_users->fetchAll(PDO::FETCH_OBJ);


          if ($data_count_users) {
              return json_encode(array('response' => true, 'data'=> $data_count_users, 'description' => 'Сообщения по тикету найдены'),JSON_UNESCAPED_UNICODE);
              exit;
          }
          else {
              return json_encode(array('response' => false, 'description' => 'Нет сообщений по данному тикету'),JSON_UNESCAPED_UNICODE);
              exit;
          }

      }
      else {
          return $chek_ticket;
          exit;
      }

  }







  /* API функции - ipchain */


  // получить токен ipchain
  public function ipchain_token() {
    global $database;

        $curl = curl_init();
        $username = $this->get_global_settings('login_ipchain');
        $password = $this->get_global_settings('password_ipchain');
        $domen_ipchain = $this->get_global_settings('domen_ipchain');
        $data_post = array('grant_type' => 'password',
                           'username' => $username,
                           'password' => $password
                          );
        curl_setopt($curl, CURLOPT_URL, 'https://'.$domen_ipchain.'/token');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
        $out1 = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($out1);
        if ($response->access_token) {
            $check_update = $this->update_global_settings('token_ipchain',$response->access_token);
            $check_update2 = $this->update_global_settings('token_type_ipchain',$response->token_type);
            return json_encode(array('response' => true, 'token' => $response->access_token, 'description' => 'Токен ipchain успешно обновлен в единой базе данных'),JSON_UNESCAPED_UNICODE);
            exit;
        }
        else {
              return json_encode(array('response' => false, 'description' => 'Ошибка обновления токена ipchain'),JSON_UNESCAPED_UNICODE);
              exit;
        }
  }

  // Получение всех данных по компаниям из ipchain GetDigitalPlatformDataFast
  public function ipchain_GetDigitalPlatformDataFast() {
    global $database;
        $this->ipchain_token();
        $token_type_ipchain = $this->get_global_settings('token_type_ipchain');
        $token_ipchain = $this->get_global_settings('token_ipchain');
        $domen_ipchain = $this->get_global_settings('domen_ipchain');
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: '.lcfirst($token_type_ipchain).' '.$token_ipchain
                ],
                'content' => ''
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://'.$domen_ipchain.'/api/Company/GetDigitalPlatformDataFast', false, $builder);
        $output = json_decode($document);

        return $document;

  }

  // синхронизация данных с ipchain
  public function sinc_data_entity_ipchain() {
      global $database;

      $this->ipchain_token();

      $mass_all_comany = $this->ipchain_GetDigitalPlatformDataFast();

      $data_mass_for_cicle = json_decode($mass_all_comany);

      foreach ($data_mass_for_cicle as $key => $value) {

              $Ogrn = $value->Company->Ogrn;
              $Inn = $value->Company->Inn;

              $Name = isset($value->Company->Name) ? $value->Company->Name : $Name = ' ';
              $FullName = isset($value->Company->FullName) ? $value->Company->FullName : $FullName = ' ';
              $FoundationDate = isset($value->Company->FoundationDate) ? $value->Company->FoundationDate : $FoundationDate = ' ';
              $LawAddress = isset($value->Company->LawAddress) ? $value->Company->LawAddress : $LawAddress = ' ';
              $Industries = is_array($value->Company->Industries) ? json_encode($value->Company->Industries) : $Industries = ' ';
              $Technologies = is_array($value->Company->Technologies) ? json_encode($value->Company->Technologies) : $Technologies = ' ';
              $LeaderId = isset($value->Company->LeaderId) ? $value->Company->LeaderId : $LeaderId = 0;
              $Website = isset($value->Company->Website) ? $value->Company->Website : $Website = ' ';
              $Okved = isset($value->Company->Okved) ? $value->Company->Okved : $Okved = ' ';
              $Okveds = is_array($value->Company->Okveds) ? json_encode($value->Company->Okveds) : $Okveds = ' ';
              $Region = is_array($value->Company->Region) ? json_encode($value->Company->Region) : $Region = ' ';
              $Email = isset($value->Company->Email) ? $value->Company->Email : $Email = ' ';
              $Notes = isset($value->Company->Notes) ? $value->Company->Notes : $Notes = ' ';
              $AnnualIndicators = isset($value->Company->AnnualIndicators) ? json_encode($value->Company->AnnualIndicators) : $AnnualIndicators = ' ';

              $Fund_Name = isset($value->Fund->Name) ? $value->Fund->Name : $Fund_Name = ' ';
              $Fund_Ogrn = isset($value->Fund->Ogrn) ? $value->Fund->Ogrn : $Fund_Ogrn = 0;

              $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_entity WHERE Inn = :Inn AND Ogrn = :Ogrn");
              $statement->bindParam(':Inn', $Inn, PDO::PARAM_STR);
              $statement->bindParam(':Ogrn', $Ogrn, PDO::PARAM_STR);
              $statement->execute();
              $data = $statement->fetch(PDO::FETCH_OBJ);

              if (!$data) {
                    // добавление недостающей компании
                    $statement = $database->prepare("INSERT INTO $this->IPCHAIN_entity (Name,FullName,Ogrn,Inn,FoundationDate,LawAddress,Industries,Technologies,LeaderId,Website,Okved,Okveds,Region,Email,Notes,AnnualIndicators,Fund_Name,Fund_Ogrn) VALUES (:Name,:FullName,:Ogrn,:Inn,:FoundationDate,:LawAddress,:Industries,:Technologies,:LeaderId,:Website,:Okved,:Okveds,:Region,:Email,:Notes,:AnnualIndicators,:Fund_Name,:Fund_Ogrn)");
                    $statement->bindParam(':Name', $Name, PDO::PARAM_STR);
                    $statement->bindParam(':FullName', $FullName, PDO::PARAM_STR);
                    $statement->bindParam(':Ogrn', $Ogrn, PDO::PARAM_STR);
                    $statement->bindParam(':Inn', $Inn, PDO::PARAM_STR);
                    $statement->bindParam(':FoundationDate', $FoundationDate, PDO::PARAM_STR);
                    $statement->bindParam(':LawAddress', $LawAddress, PDO::PARAM_STR);
                    $statement->bindParam(':Industries', $Industries, PDO::PARAM_STR);
                    $statement->bindParam(':Technologies', $Technologies, PDO::PARAM_STR);
                    $statement->bindParam(':LeaderId', $LeaderId, PDO::PARAM_STR);
                    $statement->bindParam(':Website', $Website, PDO::PARAM_STR);
                    $statement->bindParam(':Okved', $Okved, PDO::PARAM_STR);
                    $statement->bindParam(':Okveds', $Okveds, PDO::PARAM_STR);
                    $statement->bindParam(':Region', $Region, PDO::PARAM_STR);
                    $statement->bindParam(':Email', $Email, PDO::PARAM_STR);
                    $statement->bindParam(':Notes', $Notes, PDO::PARAM_STR);
                    $statement->bindParam(':AnnualIndicators', $AnnualIndicators, PDO::PARAM_STR);
                    $statement->bindParam(':Fund_Name', $Fund_Name, PDO::PARAM_STR);
                    $statement->bindParam(':Fund_Ogrn', $Fund_Ogrn, PDO::PARAM_INT);
                    $check_new_user = $statement->execute();
                    $count = $database->lastInsertId();

                    if (!$count && !$check_new_user) {
                        $message = '[CRON] - Компания из ipchain '.$Name.' (ОГРН:'.$Ogrn.') не была добавлена';
                        $id_chat_error = $this->get_global_settings('telega_chat_error');
                        $this->telega_send($id_chat_error, $message);
                    }

              }
              else {

                    // обновление компании в нашей базе данных
                    $statement = $database->prepare("UPDATE $this->IPCHAIN_entity SET LawAddress = :LawAddress, Industries = :Industries, Technologies = :Technologies, LeaderId = :LeaderId, Website = :Website, Okved = :Okved, Okveds = :Okveds, Region = :Region, Email = :Email, Notes = :Notes, AnnualIndicators = :AnnualIndicators WHERE id = :id");
                    $statement->bindParam(':id', $data->id, PDO::PARAM_INT);
                    $statement->bindParam(':LawAddress', $LawAddress, PDO::PARAM_STR);
                    $statement->bindParam(':Industries', $Industries, PDO::PARAM_STR);
                    $statement->bindParam(':Technologies', $Technologies, PDO::PARAM_STR);
                    $statement->bindParam(':LeaderId', $LeaderId, PDO::PARAM_STR);
                    $statement->bindParam(':Website', $Website, PDO::PARAM_STR);
                    $statement->bindParam(':Okved', $Okved, PDO::PARAM_STR);
                    $statement->bindParam(':Okveds', $Okveds, PDO::PARAM_STR);
                    $statement->bindParam(':Region', $Region, PDO::PARAM_STR);
                    $statement->bindParam(':Email', $Email, PDO::PARAM_STR);
                    $statement->bindParam(':Notes', $Notes, PDO::PARAM_STR);
                    $statement->bindParam(':AnnualIndicators', $AnnualIndicators, PDO::PARAM_STR);
                    $check_new_user = $statement->execute();
                    $count = $statement->rowCount();

                    if (!$count && !$check_new_user) {
                        $message = '[CRON] - Компания из ipchain (id:'.$data->id.') '.$data->Name.' не была обновлена';
                        $id_chat_error = $this->get_global_settings('telega_chat_error');
                        $this->telega_send($id_chat_error, $message);
                    }

              }
        }
        return true;

  }

  // реверс синхронизация данных с ipchain
  public function reverse_sinc_data_entity_ipchain() {
      global $database;

      $this->ipchain_token();

      $mass_all_comany = $this->ipchain_GetDigitalPlatformDataFast();

      $data_mass_for_cicle = json_decode($mass_all_comany);

      foreach ($data_mass_for_cicle as $key => $value) {

              $Ogrn = $value->Company->Ogrn;
              $Inn = $value->Company->Inn;

              $Name = isset($value->Company->Name) ? $value->Company->Name : $Name = ' ';
              $FullName = isset($value->Company->FullName) ? $value->Company->FullName : $FullName = ' ';
              $FoundationDate = isset($value->Company->FoundationDate) ? $value->Company->FoundationDate : $FoundationDate = ' ';
              $LawAddress = isset($value->Company->LawAddress) ? $value->Company->LawAddress : $LawAddress = ' ';
              $Industries = is_array($value->Company->Industries) ? json_encode($value->Company->Industries) : $Industries = ' ';
              $Technologies = is_array($value->Company->Technologies) ? json_encode($value->Company->Technologies) : $Technologies = ' ';
              $LeaderId = isset($value->Company->LeaderId) ? $value->Company->LeaderId : $LeaderId = 0;
              $Website = isset($value->Company->Website) ? $value->Company->Website : $Website = ' ';
              $Okved = isset($value->Company->Okved) ? $value->Company->Okved : $Okved = ' ';
              $Okveds = is_array($value->Company->Okveds) ? json_encode($value->Company->Okveds) : $Okveds = ' ';
              $Region = is_array($value->Company->Region) ? json_encode($value->Company->Region) : $Region = ' ';
              $Email = isset($value->Company->Email) ? $value->Company->Email : $Email = ' ';
              $Notes = isset($value->Company->Notes) ? $value->Company->Notes : $Notes = ' ';
              $AnnualIndicators = isset($value->Company->AnnualIndicators) ? json_encode($value->Company->AnnualIndicators) : $AnnualIndicators = ' ';

              $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_entity WHERE Name = :Name");
              $statement->bindParam(':Name', $Name, PDO::PARAM_STR);
              $statement->execute();
              $data = $statement->fetch(PDO::FETCH_OBJ);

              if (!$data) {

                  $statement = $database->prepare("INSERT INTO $this->IPCHAIN_entity (Name,FullName,Ogrn,Inn,FoundationDate,LawAddress,Industries,Technologies,LeaderId,Website,Okved,Okveds,Region,Email,Notes,AnnualIndicators) VALUES (:Name,:FullName,:Ogrn,:Inn,:FoundationDate,:LawAddress,:Industries,:Technologies,:LeaderId,:Website,:Okved,:Okveds,:Region,:Email,:Notes,:AnnualIndicators)");
                  $statement->bindParam(':Name', $Name, PDO::PARAM_STR);
                  $statement->bindParam(':FullName', $FullName, PDO::PARAM_STR);
                  $statement->bindParam(':Ogrn', $Ogrn, PDO::PARAM_STR);
                  $statement->bindParam(':Inn', $Inn, PDO::PARAM_STR);
                  $statement->bindParam(':FoundationDate', $FoundationDate, PDO::PARAM_STR);
                  $statement->bindParam(':LawAddress', $LawAddress, PDO::PARAM_STR);
                  $statement->bindParam(':Industries', $Industries, PDO::PARAM_STR);
                  $statement->bindParam(':Technologies', $Technologies, PDO::PARAM_STR);
                  $statement->bindParam(':LeaderId', $LeaderId, PDO::PARAM_STR);
                  $statement->bindParam(':Website', $Website, PDO::PARAM_STR);
                  $statement->bindParam(':Okved', $Okved, PDO::PARAM_STR);
                  $statement->bindParam(':Okveds', $Okveds, PDO::PARAM_STR);
                  $statement->bindParam(':Region', $Region, PDO::PARAM_STR);
                  $statement->bindParam(':Email', $Email, PDO::PARAM_STR);
                  $statement->bindParam(':Notes', $Notes, PDO::PARAM_STR);
                  $statement->bindParam(':AnnualIndicators', $AnnualIndicators, PDO::PARAM_STR);
                  $check_new_user = $statement->execute();
                  $count = $database->lastInsertId();
              }
              else {}
        }
        return true;

  }

  // получение всех компании которые были загружены ipchain
  public function get_all_entity_ipchain() {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_entity");
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_OBJ);

      return $data;

  }

  // добавление фондов в компанию из ipchain
  public function sinc_data_support_ipchain() {
      global $database;

      $this->ipchain_token();
      $mass_entity_ebd = $this->get_all_entity_ipchain();
      $token_type_ipchain = $this->get_global_settings('token_type_ipchain');
      $token_ipchain = $this->get_global_settings('token_ipchain');
      $domen_ipchain = $this->get_global_settings('domen_ipchain');

      $count = 0;

      foreach ($mass_entity_ebd as $key => $value) {

           $curl = curl_init();
           $data_post = array('ogrn' => $value->Ogrn);
           $headers = array(
                 'Content-Type: application/json',
                 'Accept: application/json',
                 'Authorization: '.lcfirst($token_type_ipchain).' '.$token_ipchain
           );
           curl_setopt($curl, CURLOPT_URL, 'https://dfptest.sk.ru/api/Company/GetStateSupport?ogrn='.$value->Ogrn);
           curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
           curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
           curl_setopt($curl, CURLOPT_POST, false);
           // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
           $out1 = curl_exec($curl);
           curl_close($curl);

           $out1 = json_decode($out1);

           if (count($out1)) {

                foreach ($out1 as $key => $value2) {

                  $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_StateSupport WHERE id_Support = :id_Support");
                  $statement->bindParam(':id_Support', $value2->Id, PDO::PARAM_STR);
                  $statement->execute();
                  $data = $statement->fetch(PDO::FETCH_OBJ);

                  $id_Support = isset($value2->Id) ? trim($value2->Id) : $id_Support = ' ';
                  $typeId = isset($value2->TypeId) ? $value2->TypeId : $typeId = ' ';
                  $date_support = isset($value2->Date) ? substr($value2->Date, 0, 10) : $date_support = '0000-00-00 00:00:00';
                  $Sum = isset($value2->Sum) ? $value2->Sum : $Sum = ' ';
                  $id_entity = isset($value->id) ? $value->id : $id_entity = 0;

                  if (!$data) {
                        // добавление поддержки из ipchain
                        $statement = $database->prepare("INSERT INTO $this->IPCHAIN_StateSupport (ipchain_id_entity,id_Support,typeId,date_support,Sum) VALUES (:ipchain_id_entity,:id_Support,:typeId,:date_support,:Sum)");
                        $statement->bindValue(':ipchain_id_entity', $id_entity, PDO::PARAM_INT);
                        $statement->bindValue(':id_Support', $id_Support, PDO::PARAM_STR);
                        $statement->bindValue(':typeId', $typeId, PDO::PARAM_STR);
                        $statement->bindValue(':date_support', $date_support, PDO::PARAM_STR);
                        $statement->bindValue(':Sum', $Sum, PDO::PARAM_STR);
                        $check_new_user = $statement->execute();
                        $count = $database->lastInsertId();

                        if (!$count && !$check_new_user) {
                            $message = '[CRON] - Поддержка из ipchain '.$data->Name.' у компании id '.$id_entity.' не был добавлен в базу данных';
                            $id_chat_error = $this->get_global_settings('telega_chat_error');
                            $this->telega_send($id_chat_error, $message);
                        }

                  }
                  else  {
                        // обновление поддержки из ipchain
                        $statement = $database->prepare("UPDATE $this->IPCHAIN_StateSupport id_Support = :id_Support, typeId = :typeId, date_support = :date_support, Sum = :Sum WHERE id = :id");
                        $statement->bindValue(':id', $data->id, PDO::PARAM_INT);
                        $statement->bindValue(':id_Support', $id_Support, PDO::PARAM_STR);
                        $statement->bindValue(':typeId', $typeId, PDO::PARAM_STR);
                        $statement->bindValue(':date_support', $date_support, PDO::PARAM_STR);
                        $statement->bindValue(':Sum', $Sum, PDO::PARAM_STR);
                        $check_new_user = $statement->execute();
                        $count = $statement->rowCount();

                        if (!$count && !$check_new_user) {
                            $message = '[CRON] - Поддержка из ipchain (id:'.$data->id.') '.$data->Name.' не был обновлена';
                            $id_chat_error = $this->get_global_settings('telega_chat_error');
                            $this->telega_send($id_chat_error, $message);
                        }

                  }

                }

           }

      }

  }

  // добавление проектов в компанию из ipchain
  public function sinc_data_project_ipchain() {
      global $database;

      $this->ipchain_token();
      $mass_entity_ebd = $this->get_all_entity_ipchain();
      $token_type_ipchain = $this->get_global_settings('token_type_ipchain');
      $token_ipchain = $this->get_global_settings('token_ipchain');
      $domen_ipchain = $this->get_global_settings('domen_ipchain');

      $count = 0;

      foreach ($mass_entity_ebd as $key => $value) {

           $curl = curl_init();
           $data_post = array('ogrn' => $value->Ogrn);
           $headers = array(
                 'Content-Type: application/json',
                 'Accept: application/json',
                 'Authorization: '.lcfirst($token_type_ipchain).' '.$token_ipchain
           );
           curl_setopt($curl, CURLOPT_URL, 'https://dfptest.sk.ru/api/Company/GetProjects?ogrn='.$value->Ogrn);
           curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
           curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
           curl_setopt($curl, CURLOPT_POST, false);
           // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
           $out1 = curl_exec($curl);
           curl_close($curl);

           $out1 = json_decode($out1);

           if (count($out1)) {

                foreach ($out1 as $key => $value2) {

                  $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_Project WHERE ipchain_id_project = :ipchain_id_project");
                  $statement->bindParam(':ipchain_id_project', $value2->Id, PDO::PARAM_STR);
                  $statement->execute();
                  $data = $statement->fetch(PDO::FETCH_OBJ);

                  $Name = isset($value2->Name) ? $value2->Name : $Name = ' ';
                  $Description = isset($value2->Description) ? $value2->Description : $Description = ' ';
                  $StartDate = isset($value2->StartDate) ? $value2->StartDate : $StartDate = '0000-00-00 00:00:00';
                  $EndDate = isset($value2->EndDate) ? $value2->EndDate : $EndDate = '0000-00-00 00:00:00';
                  $ipchain_id_project = isset($value2->Id) ? $value2->Id : $ipchain_id_project = ' ';
                  $id_entity = isset($value->id) ? $value->id : $id_entity = 0;

                  if (!$data) {

                      $statement = $database->prepare("INSERT INTO $this->IPCHAIN_Project (ipchain_id_entity,Name,Description,StartDate,EndDate,ipchain_id_project) VALUES (:ipchain_id_entity,:Name,:Description,:StartDate,:EndDate,:ipchain_id_project)");
                      $statement->bindParam(':ipchain_id_entity', $id_entity, PDO::PARAM_INT);
                      $statement->bindParam(':Name', $Name, PDO::PARAM_STR);
                      $statement->bindParam(':Description', $Description, PDO::PARAM_STR);
                      $statement->bindParam(':StartDate', $StartDate, PDO::PARAM_STR);
                      $statement->bindParam(':EndDate', $EndDate, PDO::PARAM_STR);
                      $statement->bindParam(':ipchain_id_project', $ipchain_id_project, PDO::PARAM_STR);
                      $check_new_user = $statement->execute();
                      $count = $database->lastInsertId();

                      if (!$count && !$check_new_user) {
                          $message = '[CRON]  - Проект из ipchain '.$data->Name.' у компании id '.$id_entity.' не был добавлен в базу данных';
                          $id_chat_error = $this->get_global_settings('telega_chat_error');
                          $this->telega_send($id_chat_error, $message);
                      }

                  }
                  else {
                      // обновление проектов из ipchain
                      $statement = $database->prepare("UPDATE $this->IPCHAIN_Project SET Name = :Name, Description = :Description, StartDate = :StartDate, EndDate = :EndDate, ipchain_id_project = :ipchain_id_project WHERE id = :id");
                      $statement->bindParam(':id', $data->id, PDO::PARAM_INT);
                      $statement->bindParam(':Name', $Name, PDO::PARAM_STR);
                      $statement->bindParam(':Description', $Description, PDO::PARAM_STR);
                      $statement->bindParam(':StartDate', $StartDate, PDO::PARAM_STR);
                      $statement->bindParam(':EndDate', $EndDate, PDO::PARAM_STR);
                      $statement->bindParam(':ipchain_id_project', $ipchain_id_project, PDO::PARAM_STR);
                      $check_new_user = $statement->execute();
                      $count = $statement->rowCount();

                      if (!$count && !$check_new_user) {
                          $message = '[CRON] - Проект из ipchain (id: '.$data->id.') '.$data->Name.' не был обновлен';
                          $id_chat_error = $this->get_global_settings('telega_chat_error');
                          $this->telega_send($id_chat_error, $message);
                      }

                  }

                }

           }

      }

  }

  // добавление объектов в компанию из ipchain
  public function sinc_data_ipobject_ipchain() {
      global $database;

      $this->ipchain_token();
      $mass_entity_ebd = $this->get_all_entity_ipchain();
      $token_type_ipchain = $this->get_global_settings('token_type_ipchain');
      $token_ipchain = $this->get_global_settings('token_ipchain');
      $domen_ipchain = $this->get_global_settings('domen_ipchain');

      $count = 0;

      foreach ($mass_entity_ebd as $key => $value) {

           $curl = curl_init();
           $data_post = array('ogrn' => $value->Ogrn);
           $headers = array(
                 'Content-Type: application/json',
                 'Accept: application/json',
                 'Authorization: '.lcfirst($token_type_ipchain).' '.$token_ipchain
           );
           curl_setopt($curl, CURLOPT_URL, 'https://dfptest.sk.ru/api/Company/GetIpObjects?ogrn='.$value->Ogrn);
           curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
           curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
           curl_setopt($curl, CURLOPT_POST, false);
           // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
           $out1 = curl_exec($curl);
           curl_close($curl);


           $out1 = json_decode($out1);

           if (count($out1)) {


                foreach ($out1 as $key => $value2) {


                  $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_IpObjects WHERE Number_Objects = :Number_Objects");
                  $statement->bindParam(':Number_Objects', $value2->Number, PDO::PARAM_STR);
                  $statement->execute();
                  $data = $statement->fetch(PDO::FETCH_OBJ);

                  $Type = isset($value2->Type) ? $value2->Type : ' ';
                  $Country = isset($value2->Country) ? $value2->Country : ' ';
                  $Name = isset($value2->Name) ? $value2->Name : ' ';
                  $RegistrationDate = isset($value2->RegistrationDate) ? $value2->RegistrationDate : '0000-00-00 00:00:00';
                  $Number_Objects = isset($value2->Number) ? $value2->Number : ' ';
                  $Url = isset($value2->Url) ? $value2->Url : ' ';
                  $id_entity = isset($value->id) ? $value->id : $id_entity = 0;

                  if (!$data) {

                      $statement = $database->prepare("INSERT INTO $this->IPCHAIN_IpObjects (ipchain_id_entity,Type,Country,Name,RegistrationDate,Number_Objects,Url) VALUES (:ipchain_id_entity,:Type,:Country,:Name,:RegistrationDate,:Number_Objects,:Url)");
                      $statement->bindParam(':ipchain_id_entity', $id_entity, PDO::PARAM_INT);
                      $statement->bindParam(':Type', $Type, PDO::PARAM_STR);
                      $statement->bindParam(':Country', $Country, PDO::PARAM_STR);
                      $statement->bindParam(':Name', $Name, PDO::PARAM_STR);
                      $statement->bindParam(':RegistrationDate', $RegistrationDate, PDO::PARAM_STR);
                      $statement->bindParam(':Number_Objects', $Number_Objects, PDO::PARAM_STR);
                      $statement->bindParam(':Url', $Url, PDO::PARAM_STR);
                      $check_new_user = $statement->execute();
                      $count = $database->lastInsertId();

                      if (!$count && !$check_new_user) {
                          $message = '[CRON] - Патент из ipchain '.$data->Name.' у компании id '.$id_entity.' не был добавлен';
                          $id_chat_error = $this->get_global_settings('telega_chat_error');
                          $this->telega_send($id_chat_error, $message);
                      }
                  }
                  else {

                      $statement = $database->prepare("UPDATE $this->IPCHAIN_IpObjects SET Type = :Type, Country = :Country, Name = :Name, RegistrationDate = :RegistrationDate, Number_Objects = :Number_Objects, Url = :Url WHERE id = :id");
                      $statement->bindParam(':id', $data->id, PDO::PARAM_INT);
                      $statement->bindParam(':Type', $Type, PDO::PARAM_STR);
                      $statement->bindParam(':Country', $Country, PDO::PARAM_STR);
                      $statement->bindParam(':Name', $Name, PDO::PARAM_STR);
                      $statement->bindParam(':RegistrationDate', $RegistrationDate, PDO::PARAM_STR);
                      $statement->bindParam(':Number_Objects', $Number_Objects, PDO::PARAM_STR);
                      $statement->bindParam(':Url', $Url, PDO::PARAM_STR);
                      $check_new_user = $statement->execute();
                      $count = $statement->rowCount();

                      if (!$count && !$check_new_user) {
                          $message = '[CRON] - Патент из ipchain (id:'.$data->id.') '.$data->Name.' не был обновлен';
                          $id_chat_error = $this->get_global_settings('telega_chat_error');
                          $this->telega_send($id_chat_error, $message);
                      }

                  }

                }

           }

      }

  }

  // получение данных по компании из ipchain
  public function ipchain_get_data_entity($type,$number) {
        global $database;

        if ($type == 'inn') {
              $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_entity WHERE Inn = :Inn");
              $statement->bindParam(':Inn', $number, PDO::PARAM_INT);
        }
        else {
            $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_entity WHERE Ogrn = :Ogrn");
            $statement->bindParam(':Ogrn', $number, PDO::PARAM_INT);
        }
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_OBJ);

        if ($data) {
            return json_encode(array('response' => true, 'data' => $data, 'description' => 'Данные найдены в ipchain'),JSON_UNESCAPED_UNICODE);
            exit;
        }
        else {
            return json_encode(array('response' => false, 'description' => 'Ошибка данные не найдены в ipchain по данному юридическому лицу'),JSON_UNESCAPED_UNICODE);
            exit;
        }

  }

  // создание выгрузки в ipchain в csv
  public function get_data_for_ipchain() {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->main_users WHERE id_entity > 0 AND id_leader > 0");
      $statement->execute();
      $data_users = $statement->fetchAll(PDO::FETCH_OBJ);

      foreach ($data_users as $key => $value) {
            $statement = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE id = :id");
            $statement->bindParam(':id', $value->id_entity, PDO::PARAM_INT);
            $statement->execute();
            $data_entity = $statement->fetchAll(PDO::FETCH_OBJ);


            foreach ($data_entity as $key2 => $value2) {

              $data_fns = json_decode($value2->data_fns);

              $Ogrn = isset($data_fns->items[0]->ЮЛ->ОГРН) ? $data_fns->items[0]->ЮЛ->ОГРН : $data_fns->items[0]->ИП->ОГРНИП;

                  if (isset($value2->technology)) {
                      $TechnologyType = array();
                      $mass = json_decode($value2->technology);
                        for ($i=0; $i < count($mass); $i++) {
                              $TechnologyType[$i] = $mass[$i]->Code;
                        }
                      $TechnologyType = implode(";", $TechnologyType);
                  }
                  else {$TechnologyType = '';}

                  if (isset($value2->branch)) {
                      $Industry = array();
                      $mass = json_decode($value2->branch);
                        for ($i=0; $i < count($mass); $i++) {
                              $Industry[$i] = $mass[$i]->Code;
                        }
                      $Industry = implode(";", $Industry);
                  }
                  else {$Industry = '';}

                  $Website = isset($value2->site) ? $value2->site : '';
                  $LeaderId = isset($value->id_leader) ? $value->id_leader : '';
                  $ExportSales = 0;
                  $Notes = '';

              $statement = $database->prepare("SELECT * FROM `TEMP_entity_for_ipchain` WHERE Ogrn = :Ogrn");
              $statement->bindParam(':Ogrn', $Ogrn, PDO::PARAM_STR);
              $statement->execute();
              $data_otvet = $statement->fetch(PDO::FETCH_OBJ);

              if (!$data_otvet) {
                    // // добавление поддержки из ipchain
                    $statement = $database->prepare("INSERT INTO `TEMP_entity_for_ipchain` (Ogrn,TechnologyType,Industry,Website,LeaderId,ExportSales,Notes) VALUES (:Ogrn,:TechnologyType,:Industry,:Website,:LeaderId,:ExportSales,:Notes)");
                    $statement->bindParam(':Ogrn', $Ogrn, PDO::PARAM_STR);
                    $statement->bindParam(':TechnologyType', $TechnologyType, PDO::PARAM_STR);
                    $statement->bindParam(':Industry', $Industry, PDO::PARAM_STR);
                    $statement->bindParam(':Website', $Website, PDO::PARAM_STR);
                    $statement->bindParam(':LeaderId', $LeaderId, PDO::PARAM_STR);
                    $statement->bindParam(':ExportSales', $ExportSales, PDO::PARAM_STR);
                    $statement->bindParam(':Notes', $Notes, PDO::PARAM_STR);
                    $check_new_user = $statement->execute();
                    $count = $database->lastInsertId();
              }
              else {
                    $statement = $database->prepare("UPDATE `TEMP_entity_for_ipchain` SET TechnologyType = :TechnologyType, Industry = :Industry, Website = :Website, LeaderId = :LeaderId, ExportSales = :ExportSales, Notes = :Notes WHERE Ogrn = :Ogrn");
                    $statement->bindParam(':Ogrn', $Ogrn, PDO::PARAM_STR);
                    $statement->bindParam(':TechnologyType', $TechnologyType, PDO::PARAM_STR);
                    $statement->bindParam(':Industry', $Industry, PDO::PARAM_STR);
                    $statement->bindParam(':Website', $Website, PDO::PARAM_STR);
                    $statement->bindParam(':LeaderId', $LeaderId, PDO::PARAM_STR);
                    $statement->bindParam(':ExportSales', $ExportSales, PDO::PARAM_STR);
                    $statement->bindParam(':Notes', $Notes, PDO::PARAM_STR);
                    $check_new_user = $statement->execute();
                    $count = $database->rowCount();
              }

            }

      }

      return true;
  }

  // получение типов поддержки из ipcain
  public function get_state_support_types_ipchain() {

      $curl = curl_init();
      $headers = array(
            'Content-Type: application/json',
            'Accept: application/json'
      );
      curl_setopt($curl, CURLOPT_URL, 'https://dfptest.sk.ru/api/Common/GetStateSupportTypes');
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
      curl_setopt($curl, CURLOPT_POST, false);
      $out1 = curl_exec($curl);
      curl_close($curl);

      $out1 = json_decode($out1);

      if (count($out1)) {
          return $out1;
      }
      else {
          return false;
      }

  }

  // соединие таблицы поддержки из ipchain и компании из ipchain
  public function IPCHAIN_entity_inner_join($inn) {
      global $database;

      $data_с = $database->prepare("SELECT * FROM IPCHAIN_StateSupport INNER JOIN IPCHAIN_entity WHERE IPCHAIN_StateSupport.ipchain_id_entity = IPCHAIN_entity.id AND inn =:inn");
      $data_с->bindParam(':inn', $inn, PDO::PARAM_STR);
      $data_с->execute();
      $data_с_result = $data_с->fetchAll(PDO::FETCH_OBJ);

      if($data_с_result){
        return json_encode(array('response' => true, 'data' => $data_с_result, 'description' => 'Данные сформированы'), JSON_UNESCAPED_UNICODE);
      } else {
        return json_encode(array('response' => false, 'description' => 'Ошибка выгрузки данных'), JSON_UNESCAPED_UNICODE);
      }
    }

  //получение всех ридов из ЕБД
  public function get_EBD_IPCHAIN_IpObjects($type = false, $value = 0) {
      global $database;

      if($type){
        $data_с = $database->prepare("SELECT * FROM $this->IPCHAIN_IpObjects WHERE ipchain_id_entity =:ipchain_id_entity");
        $data_с->bindParam(':ipchain_id_entity', $value, PDO::PARAM_INT);
      } else {
        $data_с = $database->prepare("SELECT * FROM $this->IPCHAIN_IpObjects ");
      }
      $data_с->execute();
      $data_с_result = $data_с->fetchAll(PDO::FETCH_OBJ);

      if($data_с_result){
        return json_encode(array('response' => true, 'data' => $data_с_result, 'description' => 'Данные успешно сформированы из IPChain'), JSON_UNESCAPED_UNICODE);
      } else {
        return json_encode(array('response' => false, 'description' => 'Ошибка выгрузки патентов из IPChain'), JSON_UNESCAPED_UNICODE);
      }
    }

  //получение всех проекты компаний в ipchain из ЕБД
  public function get_EBD_IPCHAIN_Project($type = false, $value = 0) {
      global $database;
      if($type){
        $data_с = $database->prepare("SELECT * FROM $this->IPCHAIN_Project WHERE ipchain_id_entity =:ipchain_id_entity");
        $data_с->bindParam(':ipchain_id_entity', $value, PDO::PARAM_INT);
      } else {
        $data_с = $database->prepare("SELECT * FROM $this->IPCHAIN_Project ");
      }
      $data_с->execute();
      $data_с_result = $data_с->fetchAll(PDO::FETCH_OBJ);

      if($data_с_result){
        return json_encode(array('response' => true, 'data' => $data_с_result, 'description' => 'Данные успешно сформированы из IPChain'), JSON_UNESCAPED_UNICODE);
      } else {
        return json_encode(array('response' => false, 'description' => 'Ошибка выгрузки проектов компаний из IPChain'), JSON_UNESCAPED_UNICODE);
      }
    }

  //получение всех форм поддержки компании
  public function get_EBD_IPCHAIN_StateSupport($type = false, $value = 0) {
      global $database;
      if($type){
        $data_с = $database->prepare("SELECT * FROM $this->IPCHAIN_StateSupport WHERE ipchain_id_entity =:ipchain_id_entity");
        $data_с->bindParam(':ipchain_id_entity', $value, PDO::PARAM_INT);
      } else {
        $data_с = $database->prepare("SELECT * FROM $this->IPCHAIN_StateSupport ");
      }
      $data_с->execute();
      $data_с_result = $data_с->fetchAll(PDO::FETCH_OBJ);

      if($data_с_result){
        return json_encode(array('response' => true, 'data' => $data_с_result, 'description' => 'Данные успешно сформированы из IPChain'), JSON_UNESCAPED_UNICODE);
      } else {
        return json_encode(array('response' => false, 'description' => 'Ошибка получения форм поддержки компании из IPChain'), JSON_UNESCAPED_UNICODE);
      }
    }

  // получение всех документов подддержки компаний
  public function get_all_statesupport_ipchain() {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_StateSupport INNER JOIN $this->IPCHAIN_entity ON $this->IPCHAIN_StateSupport.`ipchain_id_entity` = $this->IPCHAIN_entity.`id` GROUP BY $this->IPCHAIN_entity.`inn`");
      $statement->execute();
      $data_users = $statement->fetchAll(PDO::FETCH_OBJ);

      if ($data_users) {
          return json_encode(array('response' => true, 'data' => $data_users, 'description' => 'Данные по поддержке из ipchain успешно получены'), JSON_UNESCAPED_UNICODE);
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Ошибка получения данных по поддержке из ipchain'), JSON_UNESCAPED_UNICODE);
      }

  }

  // полчучение отраслей из ipchain
  public function ipchain_GetIndustries() {
      global $database;

      if( $curl = curl_init() ) {
          curl_setopt($curl, CURLOPT_URL, 'https://dfptest.sk.ru/api/Common/GetIndustries');
          curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
          curl_setopt($curl, CURLOPT_POST, false);
          $out_branch = curl_exec($curl);
          curl_close($curl);
          $arr_val_branch = json_decode($out_branch);

          if ($arr_val_branch) {
                return json_encode(array('response' => true, 'data' => $arr_val_branch, 'description' => 'Данные по типам отрослей из ipchain успешно получены'), JSON_UNESCAPED_UNICODE);
                exit;
          }
          else {
                return json_encode(array('response' => false, 'description' => 'Ошибка получения данных из ipchain'), JSON_UNESCAPED_UNICODE);
                exit;
          }
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Ошибка CURL получения данных из ipchain'), JSON_UNESCAPED_UNICODE);
          exit;
      }




  }






  /* API ФУНКЦИИ - TBOIL  */

  // обновление токена Tboil id
  public function refresh_token_tboil() {

        $login = $this->get_global_settings('tboil_admin_login');
        $password = $this->get_global_settings('tboil_admin_password');
        $tboil_domen = $this->get_global_settings('tboil_domen');


        $data_post = array('login' => $login,
                           'password' => $password
                          );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://'.$tboil_domen.'/api/v2/auth/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
        $out = curl_exec($curl);
        $admin_token = json_decode($out);
        curl_close($curl);

        $token = $this->update_global_settings('tboil_token',$admin_token->data->token);
        if ($token) {
            return json_encode(array('response' => true, 'description' => 'Обновление токена tboil прошло успешно', 'token' => $admin_token->data->token),JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(array('response' => false, 'description' => 'Ошибка Обновления токена tboil '.$admin_token->error ),JSON_UNESCAPED_UNICODE);
        }


  }

  // Получение информации о пользователе c tboil
  public function getUser_tboil($id_user_tboil) {

        $token = $this->get_global_settings('tboil_token');
        $tboil_domen = $this->get_global_settings('tboil_domen');
        $one_user = file_get_contents('https://'.$tboil_domen.'/api/v2/getUser/?token='.$token.'&userId='.$id_user_tboil);
        $data_one_user = json_decode($one_user);

        if (!$data_one_user->success) {
            $data_refresh = json_decode($this->refresh_token_tboil());
            $one_user = file_get_contents('https://'.$tboil_domen.'/api/v2/getUser/?token='.$data_refresh->token.'&userId='.$id_user_tboil);
            $data_one_user = json_decode($one_user);
        }

        if ($data_one_user->success) {
              return json_encode(array('response' => true, 'data' => $data_one_user->data, 'description' => 'Данные пользователя успешно забраны с tboil'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => $data_one_user->error),JSON_UNESCAPED_UNICODE);
        }


  }

  // Регситрация пользователя на Tboil
  public function registerRedirect_tboil($email,$name,$secondName,$lastName,$position,$phone,$company,$city,$redirectUrl,$password,$resource) {

          $token = $this->get_global_settings('tboil_token');
          $tboil_domen = $this->get_global_settings('tboil_domen');

          $data_post = array(
                             'email' => $email,
                             'name' => $name,
                             'secondName' => $secondName,
                             'lastName' => $lastName,
                             'profession' => $position,
                             'phone' => $phone,
                             'company' => $company,
                             'city' => $city,
                             'redirectUrl' => $redirectUrl,
                             'password' => $password,
                             'UrlLid' => $resource
                            );

          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, 'https://'.$tboil_domen.'/api/v2/registerRedirect/?token='.$token);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
          $out = curl_exec($curl);
          $data_one_user = json_decode($out);
          curl_close($curl);

          if (!$data_one_user->success) {
                $admin_token = json_decode($this->refresh_token_tboil());
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://'.$tboil_domen.'/api/v2/registerRedirect/?token='.$admin_token->token);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_post));
                $out = curl_exec($curl);
                $data_one_user = json_decode($out);
                curl_close($curl);
          }

          if ($data_one_user->success) {
                return json_encode(array('response' => true, 'data' => $data_one_user->data, 'description' => 'Пользователь был успешно зарегистрирован на Tboil, Пожалуйста подтвердите свой email для последущих входов в аккаунт'),JSON_UNESCAPED_UNICODE);
          } else {
                return json_encode(array('response' => false, 'description' => $data_one_user->error),JSON_UNESCAPED_UNICODE);
          }

  }












  /* API ФУНКЦИИ - ФЕДЕРАЛЬНАЯ НАЛОГОВАЯ СЛУЖБА  */


  // Забор данных из ФНС
  public function fns_base($inn) {
       global $database;

            $valid_inn = $this->is_valid_inn($inn);
            $check_false_inn = $this->isJSON($valid_inn);
            $date_pickup = date("Y-m-d H:i:s");
            $token_fns = $this->get_global_settings('api_fns_key');


            if ($check_false_inn) {
                 return json_encode(array('response' => false, 'description' => 'ИНН не прошел проверку на корректность'),JSON_UNESCAPED_UNICODE);
             }

             $statement = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE inn = :inn");
             $statement->bindParam(':inn', $inn, PDO::PARAM_INT);
             $statement->execute();
             $data = $statement->fetch(PDO::FETCH_OBJ);

             if ($data) {

                 $data_fnc = file_get_contents("https://api-fns.ru/api/egr?req=".$inn."&key=".$token_fns);
                 $fnc = json_decode($data_fnc);

                 $chek_inn = $fnc->items[0]->ЮЛ->ИНН;
                 $chek_inn2 = $fnc->items[0]->ИП->ИННФЛ;

                 if ($chek_inn == '') {
                           if($chek_inn2 == '') {
                                return json_encode(array('response' => false, 'description' => 'ИНН не найден в базе ФНС'),JSON_UNESCAPED_UNICODE);
                                exit;
                           }
                 }

                 $add_fns_database = $database->prepare("UPDATE $this->MAIN_entity SET data_fns = :data_fns, date_pickup = :date_pickup  WHERE inn = :inn");
                 $add_fns_database->bindParam(':inn', $inn, PDO::PARAM_INT);
                 $add_fns_database->bindParam(':data_fns', $data_fnc, PDO::PARAM_STR);
                 $add_fns_database->bindParam(':date_pickup', $date_pickup, PDO::PARAM_STR);
                 $check_add = $add_fns_database->execute();
                 $count = $add_fns_database->rowCount();
                 if (!$count) {
                       return json_encode(array('response' => false, 'description' => 'Внутреняя ошибка обновления данных из ФНС, попробуйте позже'),JSON_UNESCAPED_UNICODE);
                       exit;
                 } else {
                       return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Данные о компании обновленны в базе данных'),JSON_UNESCAPED_UNICODE);
                       exit;
                 }
           } else {

                 $data_fnc = file_get_contents("https://api-fns.ru/api/egr?req=".$inn."&key=".$token_fns);
                 $fnc = json_decode($data_fnc);

                 $chek_inn = $fnc->items[0]->ЮЛ->ИНН;
                 $chek_inn2 = $fnc->items[0]->ИП->ИННФЛ;

                 if ($chek_inn == '') {
                           if($chek_inn2 == '') {
                                return json_encode(array('response' => false, 'description' => 'ИНН не найден в базе ФНС'),JSON_UNESCAPED_UNICODE);
                                exit;
                           }
                 }

                 $request = $database->prepare("INSERT INTO $this->MAIN_entity (inn,data_fns,date_pickup)
                                                       VALUES (:inn,:data_fns,:date_pickup)");
                 $request->bindParam(':inn', $inn, PDO::PARAM_INT);
                 $request->bindParam(':data_fns', $data_fnc, PDO::PARAM_STR);
                 $request->bindParam(':date_pickup', $date_pickup, PDO::PARAM_STR);
                 $check_request = $request->execute();
                 $count_request = $request->rowCount();

                 if ($count_request) {
                      return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Данные о компании загружены в базу данных'),JSON_UNESCAPED_UNICODE);
                      exit;
                 } else {
                      return json_encode(array('response' => false, 'description' => 'Внутреняя ошибка записи данных из ФНС, попробуйте позже'),JSON_UNESCAPED_UNICODE);
                      exit;
                 }
           }
     }

  // Загрузка данных из ФНС
  public function get_fns_base($inn,$json = false) {
       global $database,$unated_database,$UNATED_BASE_PREFIX__;

       $valid_inn = $this->is_valid_inn($inn);

       if ($this->isJSON($valid_inn)) {
           return $valid_inn;
       }

       $chek_reg_uruser = $database->prepare("SELECT * FROM $this->company WHERE inn = :inn");
       $chek_reg_uruser->bindParam(':inn', $inn, PDO::PARAM_STR);
       $chek_reg_uruser->execute();
       $data_chek_reg_uruser = $chek_reg_uruser->fetch(PDO::FETCH_OBJ);

       $chek_reg_inobj = $database->prepare("SELECT * FROM $this->inobject WHERE inn = :inn");
       $chek_reg_inobj->bindParam(':inn', $inn, PDO::PARAM_STR);
       $chek_reg_inobj->execute();
       $data_chek_reg_inobj = $chek_reg_inobj->fetch(PDO::FETCH_OBJ);


         //Выполняем поиск по ИНН уже ранее запрошенных из ФНС ИНН
         $chek_fns_database = $database->prepare("SELECT * FROM $this->fns_database WHERE inn = :inn");
         $chek_fns_database->bindParam(':inn', $inn, PDO::PARAM_STR);
         $chek_fns_database->execute();
         $data_fns_database = $chek_fns_database->fetch(PDO::FETCH_OBJ);

         if ($data_fns_database) {
                 if (!$json){$fnc = json_decode($data_fns_database->info);}
                 else {$fnc = $data_fns_database->info;}
                 return $fnc;
         }
         else {

             $data_fnc = file_get_contents("https://api-fns.ru/api/egr?req=".$inn."&key=".$this->get_global_settings('api_fns_key'));
             $fnc = json_decode($data_fnc);

             $chek_inn = $fnc->items[0]->ЮЛ->ИНН;
             $chek_inn2 = $fnc->items[0]->ИП->ИННФЛ;


             if ($chek_inn == '') {
                   if ($chek_inn2 == '') {
                       return '613';
                   }
                   else {

                     $add_fns_database = $database->prepare("INSERT INTO $this->fns_database (inn,info) VALUES (:inn,:info)");
                     $add_fns_database->bindParam(':inn', $inn, PDO::PARAM_STR);
                     $add_fns_database->bindParam(':info', $data_fnc, PDO::PARAM_STR);
                     $check_add = $add_fns_database->execute();
                     if (!$check_add) {
                           return '614';
                     }
                     else {
                           $add_fns_database = $unated_database->prepare("INSERT INTO $UNATED_BASE_PREFIX__$this->fns_database (inn,info) VALUES (:inn,:info)");
                           $add_fns_database->bindParam(':inn', $inn, PDO::PARAM_STR);
                           $add_fns_database->bindParam(':info', $data_fnc, PDO::PARAM_STR);
                           $check_add = $add_fns_database->execute();
                           if (!$json) {return $fnc;}
                           else {return $data_fnc;}
                     }

                   }
             }
             else {

                   $add_fns_database = $database->prepare("INSERT INTO $this->fns_database (inn,info) VALUES (:inn,:info)");
                   $add_fns_database->bindParam(':inn', $inn, PDO::PARAM_STR);
                   $add_fns_database->bindParam(':info', $data_fnc, PDO::PARAM_STR);
                   $check_add = $add_fns_database->execute();
                   if (!$check_add) {
                       return '614';
                   }
                   else {
                       $add_fns_database = $unated_database->prepare("INSERT INTO $UNATED_BASE_PREFIX__$this->fns_database (inn,info) VALUES (:inn,:info)");
                       $add_fns_database->bindParam(':inn', $inn, PDO::PARAM_STR);
                       $add_fns_database->bindParam(':info', $data_fnc, PDO::PARAM_STR);
                       $check_add = $add_fns_database->execute();
                       if (!$json) {return $fnc;}
                       else {return $data_fnc;}
                   }

             }
         }
      }

  // Проверка контрагента
  public function check_contragent($inn) {

        $valid_inn = $this->is_valid_inn($inn);

        if ($this->isJSON($valid_inn)) {
            return $valid_inn;
        }

        $data_fnc = file_get_contents("https://api-fns.ru/api/check?req=".$inn."&key=".$this->get_global_settings('api_fns_key'));
        $fnc = json_decode($data_fnc);

        if ($fnc->items) {
              return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Запрос в фнс по проверке конрагента'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка запроса в ФНС'),JSON_UNESCAPED_UNICODE);
        }

    }

  // Позволяет получить изменения данных о компании в ЕГРЮЛ или ЕГРИП
  public function check_change_egrl($inn) {

        $valid_inn = $this->is_valid_inn($inn);

        if ($this->isJSON($valid_inn)) {
            return $valid_inn;
        }

        $data_fnc = file_get_contents("https://api-fns.ru/api/changes?req=".$inn."&key=".$this->get_global_settings('api_fns_key'));
        $fnc = json_decode($data_fnc);

        if ($fnc->items) {
              return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Запрос в фнс по проверке конрагента'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка запроса в ФНС'),JSON_UNESCAPED_UNICODE);
        }

    }

  // Позволяет получить официальную выписку ФНС из ЕГРЮЛ или ЕГРИП
  public function check_excerpt_egrl($inn) {

        $valid_inn = $this->is_valid_inn($inn);

        if ($this->isJSON($valid_inn)) {
            return $valid_inn;
        }

        $data_fnc = file_get_contents("https://api-fns.ru/api/vyp?req=".$inn."&key=".$this->get_global_settings('api_fns_key'));
        $fnc = json_decode($data_fnc);

        if ($fnc->items) {
              return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Запрос в фнс по проверке конрагента'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка запроса в ФНС'),JSON_UNESCAPED_UNICODE);
        }

    }

  // Бухгалтерская отчетность организации по данным ФНС (только юридические лица).
  public function check_financ_stat($inn) {

        $valid_inn = $this->is_valid_inn($inn);

        if ($this->isJSON($valid_inn)) {
            return $valid_inn;
        }

        $data_fnc = file_get_contents("https://api-fns.ru/api/bo?req=".$inn."&key=".$this->get_global_settings('api_fns_key'));
        $fnc = json_decode($data_fnc);

        if ($fnc->items) {
              return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Запрос в фнс по проверке конрагента'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка запроса в ФНС'),JSON_UNESCAPED_UNICODE);
        }

    }

  // Бухгалтерская отчетность организации в виде файла по данным ФНС (только юридические лица).
  public function check_financ_stat_file($inn,$year,$type=null) {

        $valid_inn = $this->is_valid_inn($inn);

        if ($this->isJSON($valid_inn)) {
            return $valid_inn;
        }

        if(!$type) {
              $data_fnc = file_get_contents("https://api-fns.ru/api/bo_file?req=".$inn."&year=".intval($year)."&key=".$this->get_global_settings('api_fns_key'));
        } else {
              $data_fnc = file_get_contents("https://api-fns.ru/api/bo_file?req=".$inn."&year=".intval($year)."&xls=1&key=".$this->get_global_settings('api_fns_key'));
        }


        $fnc = json_decode($data_fnc);

        if ($fnc->items) {
              return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Запрос в фнс по проверке конрагента'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка запроса в ФНС'),JSON_UNESCAPED_UNICODE);
        }

    }

  // Узнать ИНН физического лица
  public function check_fiz_inn($name,$last_name,$second_name,$DOB,$type_document,$number_document) {

        $array = array('Паспорт гражданина СССР' => '01',
                       'Свидетельство о рождении' => '03',
                       'Паспорт иностранного гражданина' => '10',
                       'Вид на жительство в Российской Федерации' => '12',
                       'Разрешение на временное проживание в Российской Федерации' => '15',
                       'Свидетельство о предоставлении временного убежища на территории Российской Федерации' => '19',
                       'Паспорт гражданина Российской Федерации' => '21',
                       'Свидетельство о рождении, выданное уполномоченным органом иностранного государства' => '23');

        $data_fnc = file_get_contents("https://api-fns.ru/api/innfl?fam=".$last_name."&nam=".$name."&otch=".$second_name."&bdate=".$DOB."&doctype=".$array[$type_document]."&docno=".$number_document."&key=".$this->get_global_settings('api_fns_key'));

        $fnc = json_decode($data_fnc);

        if ($fnc->items) {
              return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Запрос в фнс ИНН физического лица'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка запроса в ФНС'),JSON_UNESCAPED_UNICODE);
        }

    }

  // Проверка паспорта на недействительность
  public function check_passport($number_passport) {

        $data_fnc = file_get_contents("https://api-fns.ru/api/mvdpass?docno=".$number_passport."&key=".$this->get_global_settings('api_fns_key'));
        $fnc = json_decode($data_fnc);

        if ($fnc->items) {
              return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Запрос в фнс по проверке конрагента'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка запроса в ФНС'),JSON_UNESCAPED_UNICODE);
        }

    }

  // Лицензии Федеральной службы по регулированию алкогольного рынка (ФСРАР)
  public function check_fsar($inn) {

        $valid_inn = $this->is_valid_inn($inn);

        if ($this->isJSON($valid_inn)) {
            return $valid_inn;
        }

        $data_fnc = file_get_contents("https://api-fns.ru/api/fsrar?inn=".$inn."&key=".$this->get_global_settings('api_fns_key'));
        $fnc = json_decode($data_fnc);

        if ($fnc->items) {
              return json_encode(array('response' => true, 'data' => $fnc, 'description' => 'Запрос в фнс по проверке конрагента'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка запроса в ФНС'),JSON_UNESCAPED_UNICODE);
        }

    }












  /* API ФУНКЦИИ - DADATA  */


  // Поиск местоположения по ip
  public function iplocate($client_ip) {

          $ch = curl_init('https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip='.$client_ip);
          curl_setopt($ch, CURLOPT_POST, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Accept: application/json',
          'Content-Type: application/json',
          'Authorization: Token '.$this->get_global_settings('dadata_api_key')
          ));
          $html = curl_exec($ch);
          curl_close($ch);
          return $html;

     }

  // поиск компании по dadata
  public function find_entity($inn) {
          $data = [
              'query' => $inn
           ];
          $options = [
              'http' => [
                  'method' => 'POST',
                  'header' => [
                      'Content-Type: application/json',
                      'Accept: application/json',
                      'Authorization: Token '.$this->get_global_settings('dadata_api_key')
                  ],
                  'content' => json_encode($data)
              ]
           ];
          $builder = stream_context_create($options);
          $document = file_get_contents('https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', false, $builder);
          $output = json_decode($document);

          return $document;

    }

  // проверка самогозанятого по ИНН
  public function checkStatus($inn, $date = null) {
        if (!$date) {
            $date = new DateTime("now");
        }
        $dateStr = $date->format("Y-m-d");
        $url = "https://statusnpd.nalog.ru/api/v1/tracker/taxpayer_status";
        $data = array(
            "inn" => $inn,
            "requestDate" => $dateStr
        );
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => array(
                    'Content-type: application/json',
                ),
                'content' => json_encode($data)
            ),
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

  // Адрес в ФИАС по идентификатору
  public function check_adres_fias($fias) {
        $data = [
            'query' => $fias
         ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Token '.$this->get_global_settings('dadata_api_key')
                ],
                'content' => json_encode($data)
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/fias', false, $builder);
        $output = json_decode($document);

        return $document;
    }

  // Геокодирование (координаты по адресу)
  public function check_geo_adres($adres) {
        $data = json_encode([$adres]);
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => [
                      'Content-Type: application/json',
                      'Accept: application/json',
                      'Authorization: Token '.$this->get_global_settings('dadata_api_key'),
                      'X-Secret: '.$this->get_global_settings('dadata_key_standard')
                ],
                'content' => $data
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://cleaner.dadata.ru/api/v1/clean/address', false, $builder);
        $output = json_decode($document);

        $return = json_encode($output[0]);

        return $return;


    }

  // Адрес по коду КЛАДР или ФИАС
  public function check_adres_kladr_or_fias($kod) {
      $data = [
          'query' => $kod
       ];
      $options = [
          'http' => [
              'method' => 'POST',
              'header' => [
                  'Content-Type: application/json',
                  'Accept: application/json',
                  'Authorization: Token '.$this->get_global_settings('dadata_api_key')
              ],
              'content' => json_encode($data)
          ]
       ];
      $builder = stream_context_create($options);
      $document = file_get_contents('https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/address', false, $builder);
      $output = json_decode($document);

      return $document;
    }

  // Поиск аффилированных компаний
  public function find_afiling_entity($inn) {
        $data = [
            'query' => $inn
         ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Token '.$this->get_global_settings('dadata_api_key')
                ],
                'content' => json_encode($data)
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://suggestions.dadata.ru/suggestions/api/4_1/rs/findAffiliated/party', false, $builder);
        $output = json_decode($document);

        return $document;
    }

  // Банк по БИК, SWIFT, ИНН или регистрационному номеру
  public function check_bank($bik_swift_inn_regnum) {
        $data = [
          'query' => $bik_swift_inn_regnum
         ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Token '.$this->get_global_settings('dadata_api_key')
                ],
                'content' => json_encode($data)
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/bank', false, $builder);
        $output = json_decode($document);

        return $document;
    }

  // API стандартизации телефонов
  public function standart_phone($phone) {
        $data = json_encode([$phone]);
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Token '.$this->get_global_settings('dadata_api_key'),
                    'X-Secret: '.$this->get_global_settings('dadata_key_standard')
                ],
                'content' => $data
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://cleaner.dadata.ru/api/v1/clean/phone', false, $builder);
        $output = json_decode($document);

        $return = json_encode($output[0]);

        return $return;
  }

  // Проверка паспорта по справочнику недействительных паспортов МВД.
  public function standart_passport($passport_id) {
        $data = json_encode([$passport_id]);
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Token '.$this->get_global_settings('dadata_api_key'),
                    'X-Secret: '.$this->get_global_settings('dadata_key_standard')
                ],
                'content' => $data
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://cleaner.dadata.ru/api/v1/clean/passport', false, $builder);
        $output = json_decode($document);

        $return = json_encode($output[0]);

        return $return;
    }

  // кем выдан паспорт
  public function who_get_passport($kod_podrazdel) {
        $data = [
          'query' => $kod_podrazdel
        ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Token '.$this->get_global_settings('dadata_api_key')
                ],
                'content' => json_encode($data)
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/fms_unit', false, $builder);
        $output = json_decode($document);

        return $document;

    }

  // API стандартизации email
  public function check_email($email) {
        $data = json_encode([$email]);
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Token '.$this->get_global_settings('dadata_api_key'),
                    'X-Secret: '.$this->get_global_settings('dadata_key_standard')
                ],
                'content' => $data
            ]
         ];
        $builder = stream_context_create($options);
        $document = file_get_contents('https://cleaner.dadata.ru/api/v1/clean/email', false, $builder);
        $output = json_decode($document);

        $return = json_encode($output[0]);

        return $return;

  }









  /* Методы для работы с телеграмм чатами */


  // отправка сообщений в чат телеграм
  public function telega_send($id, $message) {   //Задаём публичную функцию send для отправки сообщений
        //Заполняем массив $data инфой, которую мы через api отправим до телеграмма
        $data = array(
            'chat_id'      => $id,
            'text'     => $message,
        );
        //Получаем ответ через функцию отправки до апи, которую создадим ниже
        $out = $this->telega_request('sendMessage', $data);
        //И пусть функция вернёт ответ. Правда в данном примере мы это никак не будем использовать, пусть будет задаток на будущее
        return $out;
  }

  // метод для отправки в телегу
  public  function telega_request($method, $data = array()) {
        $curl = curl_init(); //мутим курл-мурл в переменную. Для отправки предпочтительнее использовать курл, но можно и через file_get_contents если сервер не поддерживает
        $token = $this->get_global_settings('telega_token');

        curl_setopt($curl, CURLOPT_URL, 'https://api.telegram.org/bot' . $token .  '/' . $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //Отправляем через POST
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); //Сами данные отправляемые

        $out = json_decode(curl_exec($curl), true); //Получаем результат выполнения, который сразу расшифровываем из JSON'a в массив для удобства

        curl_close($curl); //Закрываем курл

        return $out; //Отправляем ответ в виде массива
    }

  // Функция полячения данных пользователя из единой базы данных по id_tboil
  public function add_errors_migrate($id_tboil, $type) {
        global $database;

        $today = date("Y-m-d H:i:s");

        $new_erorr = $database->prepare("INSERT INTO $this->errors_migrate (id_tboil,type,date_record) VALUES (:id_tboil,:type,:date_record)");
        $new_erorr->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
        $new_erorr->bindParam(':type', $type, PDO::PARAM_STR);
        $new_erorr->bindParam(':date_record', $today, PDO::PARAM_STR);
        $check_new_erorr = $new_erorr->execute();
        $check_id = $database->lastInsertId();

        $this->telega_send($this->get_global_settings('telega_chat_error'), '[CRON] '.$type.' '.$id_tboil);
  }












  /* ФУНКЦИИ КОТОРЫЕ БЫЛИ УДАЛЕНЫ  */


  // регистрация пользователя в системе
  public function regiter_user_in_sistem($email,$phone,$name,$lastname,$second_name,$id_role,$mail) {
      global $database;

        $check_user_data = $database->prepare("SELECT * FROM $this->users WHERE email = :email");
        $check_user_data->bindParam(':email', $email, PDO::PARAM_STR);
        $check_user_data->execute();
        $user = $check_user_data->fetch(PDO::FETCH_OBJ);


        if (!$user) {


                $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
                $max=8;
                $size=StrLen($chars)-1;
                $password=null;
                while($max--)
                $password.=$chars[rand(0,$size)];

                $hash_password = password_hash($password, PASSWORD_DEFAULT);


                $email = (isset($email)) ? $email : ' ';
                $name = (isset($name)) ? $name : ' ';
                $lastname = (isset($lastname)) ? $lastname : ' ';
                $second_name = (isset($second_name)) ? $second_name : ' ';
                $phone = (isset($phone)) ? $phone : '79999999999';
                $photo = $this->get_global_settings('default_user_photo');
                $id_role = (isset($id_role)) ? $id_role : 0;
                $status = 'active';
                $last_activity = date("Y-m-d H:i:s");
                $css_style = 'demo_1';
                $recovery_link = md5($email.$name.$lastname.$second_name.$phone.$last_activity.$status.$css_style);
                $key_user = md5($recovery_link);
                $hash = md5(md5($recovery_link));

                $new_uruser = $database->prepare("INSERT INTO $this->users (email,name,lastname,second_name,phone,password,hash,key_user,recovery_link,photo,role,status,last_activity,css_style) VALUES (:email,:name,:lastname,:second_name,:phone,:password,:hash,:key_user,:recovery_link,:photo,:role,:status,:last_activity,:css_style)");
                $new_uruser->bindParam(':email', $email, PDO::PARAM_STR);
                $new_uruser->bindParam(':name', $name, PDO::PARAM_STR);
                $new_uruser->bindParam(':lastname', $lastname, PDO::PARAM_STR);
                $new_uruser->bindParam(':second_name', $second_name, PDO::PARAM_STR);
                $new_uruser->bindParam(':phone', $phone, PDO::PARAM_STR);
                $new_uruser->bindParam(':password', $hash_password, PDO::PARAM_STR);
                $new_uruser->bindParam(':hash', $hash, PDO::PARAM_STR);
                $new_uruser->bindParam(':key_user', $key_user, PDO::PARAM_STR);
                $new_uruser->bindParam(':recovery_link', $recovery_link, PDO::PARAM_STR);
                $new_uruser->bindParam(':photo', $photo, PDO::PARAM_STR);
                $new_uruser->bindParam(':role', $id_role, PDO::PARAM_STR);
                $new_uruser->bindParam(':status', $status, PDO::PARAM_STR);
                $new_uruser->bindParam(':last_activity', $last_activity, PDO::PARAM_STR);
                $new_uruser->bindParam(':css_style', $css_style, PDO::PARAM_STR);
                $new_uruser->execute();
                $check_add_user = $database->lastInsertId();

                if ($check_add_user) {

                      if ($mail) {

                            $content =  'Здравствуйте, '.$name.' '.$second_name.'<br>';
                            $content .= 'Для Вас был зарегистирован аккаунт в системе FULLDATA ЛЕНПОЛИГРАФМАШ.<br>';
                            $content .= 'Ваши авторизационные данные расположены ниже.<br>';

                            $tema = 'Регистрация аккаунта';

                            $today = date("d.m.Y H:i");

                            $maildata =
                                  array(
                                    'title' => $tema,
                                    'description' => $content,
                                    'link_to_server' => 'https://'.$_SERVER['SERVER_NAME'],
                                    'text_button' => 'Начать работу',
                                    'link_button' => 'https://'.$_SERVER['SERVER_NAME'].'/',
                                    'name_host' => $_SERVER['SERVER_NAME'],
                                    'date' => $today,
                                    'text_login' => $email,
                                    'text_password' => $password
                                  );

                            $template_email = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/assets/template/mail/pass_reg_user.php');

                            foreach ($maildata as $key => $value) {
                              $template_email = str_replace('['.$key.']', $value, $template_email);
                            }

                            $check_mail = $this->send_email_user($email,$tema,$template_email);

                            if (json_decode($check_mail)->response) {
                                  return json_encode(array('response' => true, 'description' => 'Пользователь успешно зарегистрирован письмо было выслано на адрес '.$email),JSON_UNESCAPED_UNICODE);
                                  exit;
                            }
                            else {
                                  return json_encode(array('response' => true, 'description' => 'Пользователь успешно зарегистрирован, но письмо не было выслано на адрес '.$email),JSON_UNESCAPED_UNICODE);
                                  exit;
                            }
                      }
                      else {
                          return json_encode(array('response' => true, 'description' => 'Пользователь успешно зарегистрирован'),JSON_UNESCAPED_UNICODE);
                          exit;
                      }

                  }
                  else {
                      return json_encode(array('response' => true, 'description' => 'Ошибка создания пользователя, попробуйте позже'),JSON_UNESCAPED_UNICODE);
                      exit;
                  }

        }
        else {
              return json_encode(array('response' => false, 'description' => 'Ошибка, пользователь с таким email уже существует'),JSON_UNESCAPED_UNICODE);
              exit;
        }

  }

  // Запись переходов реферов
  public function record_user_referer($session_id,$ip_user,$refer) {
      global $database;

          $today = date("Y-m-d H:i:s");

          $resource = parse_url($refer, PHP_URL_HOST);
          $resource2 = $this->get_global_settings('hosting_name');
          if ($resource2 != $resource) {

            $add_user_referer = $database->prepare("UPDATE $this->user_referer SET ip = :ip, referer = :referer, date_record = :date_record WHERE session_id = :session_id");
            $add_user_referer->bindParam(':ip', $ip_user, PDO::PARAM_STR);
            $add_user_referer->bindParam(':referer', $refer, PDO::PARAM_STR);
            $add_user_referer->bindParam(':date_record', $today, PDO::PARAM_STR);
            $add_user_referer->bindParam(':session_id', $session_id, PDO::PARAM_STR);
            $temp = $add_user_referer->execute();
            $count = $add_user_referer->rowCount();

            if ($count) {
                  return json_encode(array('response' => true, 'description' => 'Рефер пользователя успешно обновлен'),JSON_UNESCAPED_UNICODE);
            } else {

                $add_user_referer = $database->prepare("INSERT INTO $this->user_referer (session_id,ip,referer,date_record) VALUES (:session_id,:ip,:referer,:date_record)");
                $add_user_referer->bindParam(':session_id', $session_id, PDO::PARAM_STR);
                $add_user_referer->bindParam(':ip', $ip_user, PDO::PARAM_STR);
                $add_user_referer->bindParam(':referer', $refer, PDO::PARAM_STR);
                $add_user_referer->bindParam(':date_record', $today, PDO::PARAM_STR);
                $check_referer = $add_user_referer->execute();
                if ($check_referer) {
                  return json_encode(array('response' => true, 'description' => 'Рефер пользователя успешно добавлен'),JSON_UNESCAPED_UNICODE);
                } else {
                  return json_encode(array('response' => false, 'description' => 'Рефер пользователя не был добавлен, попробуйте позже'),JSON_UNESCAPED_UNICODE);
                }

            }

          } else {
             return json_encode(array('response' => false, 'description' => 'Этот же хост'),JSON_UNESCAPED_UNICODE);
          }

   }

  // Получение данных пользователя
  public function get_cur_user($hash_or_id) {
      global $database;

      $check_activiti = $this->update_activity($hash_or_id);

          if (is_int($hash_or_id)) {
              $check_user_data = $database->prepare("SELECT * FROM $this->users WHERE id = :id");
              $check_user_data->bindParam(':id', $hash_or_id, PDO::PARAM_INT);
          } else {
              $check_user_data = $database->prepare("SELECT * FROM $this->users WHERE key_user = :hash");
              $check_user_data->bindParam(':hash', $hash_or_id, PDO::PARAM_STR);
          }
          $check_user_data->execute();
          $user = $check_user_data->fetch(PDO::FETCH_OBJ);

          if ($user) {
               return json_encode(array('response' => true, 'data' => $user),JSON_UNESCAPED_UNICODE);
               exit;
          }
          else {
               return json_encode(array('response' => false, 'description' => 'Нет данных по пользователю с данным ключем'),JSON_UNESCAPED_UNICODE);
               exit;
          }

  }

  // Авторизация пользователя
  public function auth_user($login,$password,$session_id,$ip,$type) {
   global $database;

       if ($type == 'phone') {
            $login = preg_replace('![^0-9]+!', '', $login);
            $login = trim($login);
            if (mb_strlen($login) <= 9 || mb_strlen($login) > 11) {
                  return json_encode(array('response' => false, 'description' => 'Неверный формат номера'),JSON_UNESCAPED_UNICODE);
            }
            if (mb_strlen($login) == 11) {
                if ($login[0] != '7') {
                  $login = substr($login, 1);
                  $login = '7'.$login;
                }
            }
            if (mb_strlen($login) == 10) {
                $login = '7'.$login;
            }
       }

       $check_email = $database->prepare("SELECT * FROM $this->users WHERE email = :email OR phone = :email");
       $check_email->bindParam(':email', $login, PDO::PARAM_STR);
       $check_email->execute();
       $user_email = $check_email->fetch(PDO::FETCH_OBJ);

       if (password_verify($password, $user_email->password)) {
                  $_SESSION["key_user"] = $user_email->key_user;
                  return json_encode(array('response' => true, 'description' => 'Пользователь успешно авторизован'),JSON_UNESCAPED_UNICODE);
                  exit;
       } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка, логин или пароль не верный'),JSON_UNESCAPED_UNICODE);
              exit;
       }

  }

  // получение социальных сетей пользователя
  public function get_user_social($id_user) {
      global $database;

      $check_social = $database->prepare("SELECT * FROM $this->API_USERS_SOCIAL WHERE id_user = :iduser");
      $check_social->bindParam(':iduser', $id_user, PDO::PARAM_INT);
      $check_social->execute();
      $user_check_social = $check_social->fetchAll(PDO::FETCH_OBJ);

      if ($user_check_social) {
          return  $user_check_social;
      } else {
          return false;
      }
  }

  // функция связки аккаунта с соц. сетью
  public function add_user_social($id_user,$data) {
        global $database;

        $data_user = json_decode($data);

        $check_data_social = $this->get_user_social($id_user);


        if ($check_data_social) {
          foreach ($check_data_social as $key) {
              if ($key->network == $data_user->network) {
                  if ($key->network_id == $data_user->uid) {
                     return json_encode(array('response' => false, 'description' => 'Данный аккаунт социальной сети уже привязан к аккаунту'),JSON_UNESCAPED_UNICODE);
                     exit;
                  }
              }
          }
        }

        $today = date("Y-m-d H:i:s");

        $date = new DateTime($data_user->bdate);
        $date_birth = $date->format('Y-m-d');

        // if (!$data_user->token) {$token = '';} else {$token = $data_user->token;}
        if (!$data_user->photo) {$photo = '';} else {$photo = $data_user->photo;}
        if (!$data_user->photo_big) {$photo_big = '';} else {$photo_big = $data_user->photo_big;}
        if (!$data_user->profile) {$profile = '';} else {$profile = $data_user->profile;}
        if (!$data_user->first_name) {$first_name = '';} else {$first_name = $data_user->first_name;}
        if (!$data_user->last_name) {$last_name = '';} else {$last_name = $data_user->last_name;}
        if (!$data_user->country) {$country = '';} else {$country = $data_user->country;}
        if (!$data_user->city) {$city = '';} else {$city = $data_user->city;}

        $hash = md5($id_user.$data_user->network.$data_user->uid.$profile.$data_user->email.$today);

        $create_company = $database->prepare("INSERT INTO $this->API_USERS_SOCIAL (id_user,network,network_id,profile,email,first_name,last_name,date_binding,photo,photo_big,country,city,date_birth,hash) VALUES (:id_user,:network,:network_id,:profile,:email,:first_name,:last_name,:date_binding,:photo,:photo_big,:country,:city,:date_birth,:hash)");
        $create_company->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $create_company->bindParam(':network', $data_user->network, PDO::PARAM_STR);
        $create_company->bindParam(':network_id', $data_user->uid, PDO::PARAM_STR);
        $create_company->bindParam(':profile', $profile, PDO::PARAM_STR);
        $create_company->bindParam(':email', $data_user->email, PDO::PARAM_STR);
        $create_company->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $create_company->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $create_company->bindParam(':date_binding', $today, PDO::PARAM_STR);
        $create_company->bindParam(':photo', $photo, PDO::PARAM_STR);
        $create_company->bindParam(':photo_big', $photo_big, PDO::PARAM_STR);
        $create_company->bindParam(':country', $country, PDO::PARAM_STR);
        $create_company->bindParam(':city', $city, PDO::PARAM_STR);
        $create_company->bindParam(':date_birth', $date_birth, PDO::PARAM_STR);
        $create_company->bindParam(':hash', $hash, PDO::PARAM_STR);

        $check_create_company = $create_company->execute();

        if ($check_create_company) {
            return json_encode(array('response' => true, 'description' => 'Социальная сеть '.$data_user->network.' была успешно прявзана к аккаунту, теперь вы можете авторизовываться через данную социальную сеть.'),JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            return json_encode(array('response' => false, 'description' => 'Внутренняя ошибка привязки аккаунта социальной сети, пожалуйста попробуйте позже'),JSON_UNESCAPED_UNICODE);
            exit;
        }
  }

  // функция отвязки аккаунта социальных сетей
  public function delete_social($id_user,$hash) {
      global $database;

      $check_social = $database->prepare("DELETE FROM $this->API_USERS_SOCIAL WHERE hash = :hash AND id_user = :id_user");
      $check_social->bindParam(':hash', $hash, PDO::PARAM_STR);
      $check_social->bindParam(':id_user', $id_user, PDO::PARAM_INT);
      $check_delete = $check_social->execute();

      if ($check_delete) {
          return json_encode(array('response' => true, 'description' => 'Аккаунт социальной сети был отвязан'),JSON_UNESCAPED_UNICODE);
          exit;
      } else {
          return json_encode(array('response' => false, 'description' => 'Неудалось отвязать аккаунт социальной сети, попробуйте позже'),JSON_UNESCAPED_UNICODE);
          exit;
      }
  }

  // Авторизация пользотвателя через социальную сеть
  public function auth_user_social($data) {
      global $database;

      $data_user = json_decode($data);

      $check_social = $database->prepare("SELECT AU.id, AU.key_user FROM $this->API_USERS_SOCIAL AS AUS, $this->users AS AU WHERE AUS.network = :network AND AUS.network_id = :network_id AND AU.id = AUS.id_user");
      $check_social->bindParam(':network', $data_user->network, PDO::PARAM_STR);
      $check_social->bindParam(':network_id', $data_user->uid, PDO::PARAM_INT);
      $check_social->execute();
      $user_check_social = $check_social->fetch(PDO::FETCH_OBJ);

            if ($user_check_social) {
                  $_SESSION["key_user"] = $user_check_social->key_user;
                  return json_encode(array('response' => true, 'description' => 'Пользотватель авторизован', 'check' => $user_check_social),JSON_UNESCAPED_UNICODE);
            } else {
                  return json_encode(array('response' => false, 'description' => 'Данная социальная сеть не привязана к аккаунту'),JSON_UNESCAPED_UNICODE);
            }
  }

  // Воссстановление достпа пользователя
  public function recovery_user($email) {
      global $database;

              if ((strripos($email, '@')) && strripos($email, '.')) {

              }
              else {
                    return json_encode(array('response' => false, 'description' => 'Данный email не валидный'),JSON_UNESCAPED_UNICODE);
              }

              $statement = $database->prepare("SELECT * FROM $this->users WHERE email = :email");
              $statement->bindParam(':email', $email, PDO::PARAM_STR);
              $statement->execute();
              $user = $statement->fetch(PDO::FETCH_OBJ);

              if (!$user) {
                    return json_encode(array('response' => false, 'description' => 'Пользователь с данным email не найден'),JSON_UNESCAPED_UNICODE);
              }

              $content =  'Здравствуйте, '.$user->name.' '.$user->second_name.'<br>';
              $content .= 'Нажмите на кнопку ниже для начала заверешения восстановления доступа к вашему аккаунту.<br>';
              $content .= 'Если Вы не делали запрос на восстановления доступа, просто проигнориуйте данное письмо.';

              $tema = 'Восстановление пароля';

              $today = date("d.m.Y H:i");

              $maildata =
                    array(
                      'title' => $tema,
                      'description' => $content,
                      'link_to_server' => 'https://'.$_SERVER['SERVER_NAME'],
                      'text_button' => 'Восстановить доступ',
                      'link_button' => 'https://'.$_SERVER['SERVER_NAME'].'/recovery?link='.$user->recovery_link,
                      'name_host' => $_SERVER['SERVER_NAME'],
                      'date' => $today
                    );

              $template_email = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/assets/template/mail/one_button.php');

              foreach ($maildata as $key => $value) {
                $template_email = str_replace('['.$key.']', $value, $template_email);
              }

              $check_mail = $this->send_email_user($email,$tema,$template_email);

              return $check_mail;

  }

  // Задание нового пароля пользователя после восстановления доступа
  public function new_pass_user($recovery_link,$password) {
      global $database;

          $statement = $database->prepare("SELECT * FROM $this->users WHERE recovery_link = :recovery_link");
          $statement->bindParam(':recovery_link', $recovery_link, PDO::PARAM_STR);
          $statement->execute();
          $user = $statement->fetch(PDO::FETCH_OBJ);

          if (!$user) {
                return json_encode(array('response' => false, 'description' => 'Ссылка для восстановления пароля недействительна'),JSON_UNESCAPED_UNICODE);
          }


          $hash_password = password_hash($password, PASSWORD_DEFAULT);
          $today = date("Y-m-d H:i:s");
          $hash_new_link = md5($hash_password.$password.$today.$recovery_link);

          $new_password_user = $database->prepare("UPDATE $this->users SET password = :hash_password, recovery_link = :new_recovery_link WHERE recovery_link = :recovery_link");
          $new_password_user->bindParam(':hash_password', $hash_password, PDO::PARAM_STR);
          $new_password_user->bindParam(':new_recovery_link', $hash_new_link, PDO::PARAM_STR);
          $new_password_user->bindParam(':recovery_link', $recovery_link, PDO::PARAM_STR);
          $check_new_password_user = $new_password_user->execute();
          $count = $new_password_user->rowCount();

          if ($count) {
                return json_encode(array('response' => true, 'description' => 'Новый пароль успешно задан'),JSON_UNESCAPED_UNICODE);
          }
          else {
                return json_encode(array('response' => false, 'description' => 'Ошибка задания нового пароля'),JSON_UNESCAPED_UNICODE);
          }

  }

  // Функция разофторизации пользотвателя
  public function logout() {

      $_SESSION = array();
      if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
      }
      session_destroy();
      return true;
  }

  // проверка логина и телефона
  public function check_login_valid($login) {

    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        return json_encode(array('response' => true, 'description' => 'Пользотватель использовал email', 'type' => 'email'),JSON_UNESCAPED_UNICODE);
    }
    else {

        $phoneNumber = preg_replace('![^0-9]+!', '', $login); // удалим пробелы, и прочие не нужные знаки

      	if(is_numeric($phoneNumber))
      	{
      		if(strlen($phoneNumber) < 10) {
      			  return json_encode(array('response' => false, 'description' => 'Слишком короткий телефон'),JSON_UNESCAPED_UNICODE);
      		} else {
              return json_encode(array('response' => true, 'description' => 'Пользотватель использовал телефон', 'type' => 'phone'),JSON_UNESCAPED_UNICODE);
      		}
        } else {
      		    return json_encode(array('response' => false, 'description' => 'Не верный формат телефона, присутсвуют посторонние символы'),JSON_UNESCAPED_UNICODE);
      	}

    }


  }

  // Обновление активности аккаунта
  public function update_activity($hash_user_or_id) {
      global $database;

      $today = date("Y-m-d H:i:s");

      if (is_int($hash_user_or_id)) {
        $upd_activity_user = $database->prepare("UPDATE $this->users SET last_activity = :last_activity WHERE id = :id");
        $upd_activity_user->bindParam(':id', $hash_user_or_id, PDO::PARAM_INT);
      } else {
        $upd_activity_user = $database->prepare("UPDATE $this->users SET last_activity = :last_activity WHERE hash = :hash");
        $upd_activity_user->bindParam(':hash', $hash_user_or_id, PDO::PARAM_STR);
      }
      $upd_activity_user->bindParam(':last_activity', $today, PDO::PARAM_STR);
      $check_upd_activity_user = $upd_activity_user->execute();
      $count = $upd_activity_user->rowCount();

      if ($count) {
          return json_encode(array('response' => true, 'description' => 'Активность пользователя обновлена'),JSON_UNESCAPED_UNICODE);
      } else {
          return json_encode(array('response' => false, 'description' => 'Ошибка обновления активности пользователя'),JSON_UNESCAPED_UNICODE);
      }

  }

  // отправка смс СНЯТЬ ТЕСТОВЫЙ РЕЖИМ ПРИ СОЗДАНИИ ИНТЕРФЕЙСА ДЛЯ АДМИНОВ
  public function sistem_sms($phone,$text) {
    global $database;

    $code_sms = $this->get_global_settings('api_smsru_key');
    $vowels = array("(", ")", "+", "_", "-", " ");
    $phone = str_replace($vowels, "", $phone);
    $body = file_get_contents("https://sms.ru/sms/send?api_id=".$code_sms."&to=".$phone."&msg=".urlencode(iconv("windows-1251","utf-8",$text))."&json=1");
    $json = json_decode($body);

    if ($json) {
        if ($json->status == "OK") {
            return json_encode(array('response' => true, 'description' => 'СМС сообщении успешно отпралено', 'data' => $json),JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(array('response' => false, 'description' => 'Ошибка отправки смс, пожалуйста попробуйте чуть позже', 'data' => $json),JSON_UNESCAPED_UNICODE);
        }
    } else {
            return json_encode(array('response' => false, 'description' => 'Системная ошибка отправки СМС сообщения', 'data' => $json),JSON_UNESCAPED_UNICODE);

    }

  }

  // функция отсылки письма об активации аккаунта
  public function send_email_activation($hash) {
      global $database;


      $statement = $database->prepare("SELECT * FROM $this->users WHERE hash = :hash");
      $statement->bindParam(':hash', $hash, PDO::PARAM_STR);
      $statement->execute();
      $user = $statement->fetch(PDO::FETCH_OBJ);

      if (!$user) {
          return json_encode(array('response' => false, 'description' => 'Пользователь не найден'),JSON_UNESCAPED_UNICODE);
      }

      $content =  'Здравствуйте, '.$user->name.' '.$user->second_name.'<br>';
      $content .= 'Активация аккаунта на сайте LPM-connect<br>';
      $content .= '<a href="https://'.$_SERVER['SERVER_NAME'].'/general/actions/activate_account?link='.$user->hash.'">https://'.$_SERVER['SERVER_NAME'].'/general/actions/activate_account?link='.$user->hash.'</a>';
      $content .= '<br></br> После активации аккаунта Вы сможете пользоваться всеми доступными Вам функциями';

      $tema = 'Активация аккаунта на сайте';

      $check_mail = $this->send_email_user($user->email,$tema,$content);

      if (json_decode($check_mail)->response) {
          return json_encode(array('response' => true, 'description' => 'Письмо для активации аккаунта успешно выслано на email '.$user->email),JSON_UNESCAPED_UNICODE);
      } else {
          return $check_mail;
      }

  }

  // функция активации аккаунта
  public function email_activation($hash) {
      global $database;

      $old_status = 'not active';

      $statement = $database->prepare("SELECT * FROM $this->main_users WHERE hash = :hash AND status = :status");
      $statement->bindParam(':hash', $hash, PDO::PARAM_STR);
      $statement->bindParam(':status', $old_status, PDO::PARAM_STR);
      $statement->execute();
      $user = $statement->fetch(PDO::FETCH_OBJ);

      if (!$user) {
          return json_encode(array('response' => false, 'description' => 'Пользователь не зарегистрирован'),JSON_UNESCAPED_UNICODE);
          exit();
      }

      $status = 'active';

      $activate_new_user = $database->prepare("UPDATE $this->main_users SET status = :status WHERE id = :id_user");
      $activate_new_user->bindParam(':status', $status, PDO::PARAM_STR);
      $activate_new_user->bindParam(':id_user', $user->id, PDO::PARAM_INT);
      $check_activate_new_user = $activate_new_user->execute();
      $count = $activate_new_user->rowCount();

      if ($count) {
            return json_encode(array('response' => true, 'description' => 'Аккаунт пользователя активирован'),JSON_UNESCAPED_UNICODE);
      }
      else {
            return json_encode(array('response' => false, 'description' => 'Не удалось активировать аккаунт, попробуйте позже'),JSON_UNESCAPED_UNICODE);
      }

  }

  // Функция прохождения капчи
  public function validate_recaptcha($captcha) {

        $secretKey = $this->get_global_settings('google_recaptacha_secret');
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretKey).'&response='.urlencode($captcha);
        $response = file_get_contents($url);
        $responseKeys = json_decode($response,true);
        if($responseKeys["success"]) {
              return json_encode(array('response' => true, 'description' => 'Капча успешно пройдена'),JSON_UNESCAPED_UNICODE);
        } else {
              return json_encode(array('response' => false, 'description' => 'Ошибка, капча не была пройдена'),JSON_UNESCAPED_UNICODE);
        }

    }

    public function get_all_api_users($bool_result = false) {
      global $database;

      if ($bool_result){
      $get_users_data = $database->prepare("SELECT AU.`lastname`, AU.`name`, AU.`lastname`, AUR.`alias` FROM `API_USERS` AS AU, `API_USERS_ROLE` AS AUR WHERE AU.`role` = AUR.`id` AND NOT AU.`role` = 1");
      } else {
        $get_users_data = $database->prepare("SELECT * FROM $this->users AS AU WHERE NOT AU.role = 1");
      }
      $get_users_data->execute();
      $users_data = $get_users_data->fetchAll(PDO::FETCH_OBJ);

      return $users_data;
  }

  // получение всех ролей системы
  public function get_all_roles_sistem() {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->API_USERS_ROLE WHERE root > 0");
      $statement->execute();
      $all_roles = $statement->fetchAll(PDO::FETCH_OBJ);

      if ($all_roles) {
          return json_encode(array('response' => true, 'data' => $all_roles, 'description' => 'Данные о ролях успешно получены'),JSON_UNESCAPED_UNICODE);
          exit;
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Ошибка роли не обнаружены'),JSON_UNESCAPED_UNICODE);
          exit;
      }

  }

  // получение данных роли по id
  public function get_role_data($id){
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->API_USERS_ROLE WHERE id = :id");
      $statement->bindParam(':id', $id, PDO::PARAM_INT);
      $statement->execute();
      $role = $statement->fetch(PDO::FETCH_OBJ);

      if ($role) {
          return json_encode(array('response' => true, 'data' => $role, 'description' => 'Роль успешно найдена'),JSON_UNESCAPED_UNICODE);
          exit;
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Роль c таким id ненайдена'),JSON_UNESCAPED_UNICODE);
          exit;
      }

  }

  // добавление новой роли
  public function add_role_in_sistem($alias,$copy_role = 0) {
      global $database;


      $name = $this->translit_sef($alias);

      $statement = $database->prepare("SELECT * FROM $this->API_USERS_ROLE WHERE alias = :alias OR name = :name");
      $statement->bindParam(':name', $name, PDO::PARAM_STR);
      $statement->bindParam(':alias', $alias, PDO::PARAM_STR);
      $statement->execute();
      $all_roles = $statement->fetch(PDO::FETCH_OBJ);

      if (!$all_roles) {

            if ($copy_role != 0) {
                $statement = $database->prepare("SELECT * FROM $this->API_USERS_ROLE WHERE id = :id");
                $statement->bindParam(':id', $copy_role, PDO::PARAM_INT);
                $statement->execute();
                $rules_copy = $statement->fetch(PDO::FETCH_OBJ);
                $rules = $rules_copy->rules;
            }
            else {
                $rules = json_encode(json_decode($this->get_global_settings('default_rules')));
            }

            $root = 1;
            $statement = $database->prepare("INSERT INTO $this->API_USERS_ROLE (name,alias,rules,root) VALUES (:name,:alias,:rules,:root)");
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
            $statement->bindParam(':alias', $alias, PDO::PARAM_STR);
            $statement->bindParam(':rules', $rules, PDO::PARAM_STR);
            $statement->bindParam(':root', $root, PDO::PARAM_INT);
            $check_exec = $statement->execute();
            $id_new_role = $database->lastInsertId();

            if ($id_new_role) {
                $data_last_role = json_decode($this->get_role_data($id_new_role))->data;
                return json_encode(array('response' => true, 'data' => $data_last_role, 'description' => 'Роль успешно добавлена'),JSON_UNESCAPED_UNICODE);
                exit;
            }
            else {
                return json_encode(array('response' => false, 'description' => 'Ошибка, роль не была добавлена'),JSON_UNESCAPED_UNICODE);
                exit;
            }
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Роль стаким назвнием уже существутет, пожалуйста измените название роли'),JSON_UNESCAPED_UNICODE);
          exit;
      }

  }

  // Удаление роли пользователя
  public function delete_role_in_sistem($id_role) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->users WHERE role = :id");
      $statement->bindParam(':id', $id_role, PDO::PARAM_INT);
      $statement->execute();
      $role = $statement->fetchAll(PDO::FETCH_OBJ);

      if (!$role) {
            $statement = $database->prepare("DELETE FROM $this->API_USERS_ROLE WHERE id = :id");
            $statement->bindParam(':id', $id_role, PDO::PARAM_INT);
            $check_delete = $statement->execute();

            if ($check_delete > 0) {
                return json_encode(array('response' => true, 'description' => 'Роль успешно удалена'),JSON_UNESCAPED_UNICODE);
                exit;
            }
            else {
                return json_encode(array('response' => false, 'description' => 'Ошибка удаления роли, попробуйте чуть позже'),JSON_UNESCAPED_UNICODE);
                exit;
            }
      }
      else {
            $count = count($role);
            return json_encode(array('response' => false, 'description' => 'Ошибка, данная роль распространяется на '.$count.' пользователей системы, освободите роль от всех пользователей'),JSON_UNESCAPED_UNICODE);
            exit;
      }

  }

  // транслит
  public function translit_sef($value){
      	$converter = array(
      		'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
      		'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
      		'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
      		'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
      		'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
      		'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
      		'э' => 'e',    'ю' => 'yu',   'я' => 'ya',   ' ' => '_',
      	);

      	$value = mb_strtolower($value);
      	$value = strtr($value, $converter);
      	$value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
      	$value = mb_ereg_replace('[-]+', '-', $value);
      	$value = trim($value, '-');

      	return $value;
  }

  // Добаление файлов к проекту
  public function upload_file($type_father,$id_father,$name,$path_file,$ext,$size) {
      global $database;

      $hash = md5(date("Y-m-d H:i:s").$path_file.$name.$type_father.$id_father.$ext.rand(0, 90000));
      $upload_date = date("Y-m-d H:i:s");
      $status = 0;

      $add_file_project = $database->prepare("INSERT INTO $this->API_UPLOAD_FILES (id_user,type_father,id_father,name,link,upload_date,hash,status,ext,size) VALUES (:id_user,:type_father,:id_father,:name,:link,:upload_date,:hash,:status,:ext,:size)");

      $add_file_project->bindParam(':id_user', $id_father, PDO::PARAM_INT);
      $add_file_project->bindParam(':type_father', $type_father, PDO::PARAM_STR);
      $add_file_project->bindParam(':id_father', $id_father, PDO::PARAM_INT);
      $add_file_project->bindParam(':name', $name, PDO::PARAM_STR);
      $add_file_project->bindParam(':link', $path_file, PDO::PARAM_STR);
      $add_file_project->bindParam(':upload_date', $upload_date, PDO::PARAM_STR);
      $add_file_project->bindParam(':hash', $hash, PDO::PARAM_STR);
      $add_file_project->bindParam(':status', $status, PDO::PARAM_STR);
      $add_file_project->bindParam(':ext', $ext, PDO::PARAM_STR);
      $add_file_project->bindParam(':size', $size, PDO::PARAM_INT);

      $checkadd = $add_file_project->execute();

      if ($checkadd) {
          return true;
      }
      else {
          return false;
      }
  }

  // получение пользователей по роли
  public function get_data_role_user($id_role) {
       global $database;

       $statement = $database->prepare("SELECT * FROM $this->users WHERE role = :role");
       $statement->bindParam(':role', $id_role, PDO::PARAM_INT);
       $statement->execute();
       $all_users = $statement->fetchAll(PDO::FETCH_OBJ);

       if ($all_users) {
           return json_encode(array('response' => true, 'data' => $all_users, 'description' => 'Пользователи с такой ролью успешно найдены'),JSON_UNESCAPED_UNICODE);
           exit;
       }
       else {
           return json_encode(array('response' => false, 'description' => 'Пользоатели с такой ролью не найдены'),JSON_UNESCAPED_UNICODE);
           exit;
       }

   }

  // получение даных по названию роли
  public function get_role_data_name($name_role) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->API_USERS_ROLE WHERE name = :name_role");
      $statement->bindParam(':name_role', $name_role, PDO::PARAM_STR);
      $statement->execute();
      $role = $statement->fetch(PDO::FETCH_OBJ);

      if ($role) {
          return json_encode(array('response' => true, 'data' => $role, 'description' => 'Роль успешно найдена'),JSON_UNESCAPED_UNICODE);
          exit;
      }
      else {
          return json_encode(array('response' => false, 'description' => 'Роль c таким названием не найдена'),JSON_UNESCAPED_UNICODE);
          exit;
      }

  }

  // Смена стиля пользвателя
  public function switch_user_style($new_style,$id_user) {
      global $database;

      $upd_activity_user = $database->prepare("UPDATE $this->users SET css_style = :style WHERE id = :id");
      $upd_activity_user->bindParam(':id', $id_user, PDO::PARAM_INT);
      $upd_activity_user->bindParam(':style', $new_style, PDO::PARAM_STR);
      $check_upd_activity_user = $upd_activity_user->execute();
      $count = $upd_activity_user->rowCount();

      if ($count) {
            return json_encode(array('response' => true, 'description' => 'Стиль оформения страницы успешно сменен'),JSON_UNESCAPED_UNICODE);
            exit;
      }
      else {
            return json_encode(array('response' => false, 'description' => 'Ошибка смены стиля'),JSON_UNESCAPED_UNICODE);
            exit;
      }



  }

  // Получение пользователей постраничено с поискоим и без для datatable
  public function get_users_datatable($order_request, $type_order_request, $limit_start, $limit_count, $searh_value = '') {
      global $database;

      $count_users = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->main_users ");
      $count_users->execute();
      $data_count_users = $count_users->fetch(PDO::FETCH_OBJ);
      if('' == $searh_value){
        $data_users = $database->prepare("SELECT * FROM $this->main_users ORDER BY {$order_request} {$type_order_request} LIMIT {$limit_start}, {$limit_count} ");
      } else {
        $data_users_count = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->main_users WHERE id_tboil LIKE '%{$searh_value}%' OR last_name LIKE '%{$searh_value}%' OR name LIKE '%{$searh_value}%' OR second_name LIKE '%{$searh_value}%' OR email LIKE '%{$searh_value}%' OR phone LIKE '%{$searh_value}%' ORDER BY id");
        $data_users_count->execute();
        $data_users_count_result = $data_users_count->fetch(PDO::FETCH_OBJ);
        $data_users = $database->prepare("SELECT * FROM $this->main_users WHERE id_tboil LIKE '%{$searh_value}%' OR last_name LIKE '%{$searh_value}%' OR name LIKE '%{$searh_value}%' OR second_name LIKE '%{$searh_value}%' OR email LIKE '%{$searh_value}%' OR phone LIKE '%{$searh_value}%' ORDER BY {$order_request} {$type_order_request} LIMIT {$limit_start}, {$limit_count} ");
      }

      $data_users->execute();
      $data_users = $data_users->fetchAll(PDO::FETCH_OBJ);

      if('' == $searh_value){
        $recordsFiltered = $data_count_users->COUNT;
      } else {
        $recordsFiltered = $data_users_count_result->COUNT;
      }

      return (object) array('recordsTotal' => $data_count_users->COUNT, 'recordsFiltered' => $recordsFiltered, 'data' => $data_users);
  }

  // Получение компаний постраничено с поискоим и без для datatable
  public function get_company_datatable($order_request, $type_order_request, $limit_start, $limit_count, $searh_value = '') {
      global $database;

      $count_users = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->MAIN_entity ");
      $count_users->execute();
      $data_count_users = $count_users->fetch(PDO::FETCH_OBJ);
      if('' == $searh_value){
        $data_users = $database->prepare("SELECT * FROM $this->MAIN_entity ORDER BY {$order_request} {$type_order_request} LIMIT {$limit_start}, {$limit_count} ");
      } else {
        $data_users_count = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->MAIN_entity WHERE inn LIKE '%{$searh_value}%' OR data_fns LIKE '%{$searh_value}%' ");
        $data_users_count->execute();
        $data_users_count_result = $data_users_count->fetch(PDO::FETCH_OBJ);
        $data_users = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE inn LIKE '%{$searh_value}%' OR data_fns LIKE '%{$searh_value}%'  ");
      }

      $data_users->execute();
      $data_users = $data_users->fetchAll(PDO::FETCH_OBJ);

      if('' == $searh_value){
        $recordsFiltered = $data_count_users->COUNT;
      } else {
        $recordsFiltered = $data_users_count_result->COUNT;
      }

      return (object) array('recordsTotal' => $data_count_users->COUNT, 'recordsFiltered' => $recordsFiltered, 'data' => $data_users);
  }

  // Получение ticket постраничено с поискоим и без для datatable
  public function get_ticket_datatable($order_request, $type_order_request, $limit_start, $limit_count, $searh_value = '') {
      global $database;

      $count_с = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->MAIN_support_ticket ");
      $count_с->execute();
      $data_count_users = $count_с->fetch(PDO::FETCH_OBJ);
      if('' == $searh_value){
        $data_ticket = $database->prepare("SELECT * FROM $this->MAIN_support_ticket ORDER BY {$order_request} {$type_order_request} LIMIT {$limit_start}, {$limit_count} ");
      } else {
        $data_ticket_count = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->MAIN_support_ticket WHERE id LIKE '%{$searh_value}%' OR type_support LIKE '%{$searh_value}%' OR name LIKE '%{$searh_value}%' OR 	date_added LIKE '%{$searh_value}%' OR status LIKE '%{$searh_value}%' ");
        $data_ticket_count->execute();
        $data_ticket_count_result = $data_ticket_count->fetch(PDO::FETCH_OBJ);
        $data_ticket = $database->prepare("SELECT * FROM $this->MAIN_support_ticket WHERE id LIKE '%{$searh_value}%' OR type_support LIKE '%{$searh_value}%' OR name LIKE '%{$searh_value}%' OR 	date_added LIKE '%{$searh_value}%' OR status LIKE '%{$searh_value}%' ");
      }

      $data_ticket->execute();
      $data_ticket = $data_ticket->fetchAll(PDO::FETCH_OBJ);

      if('' == $searh_value){
        $recordsFiltered = $data_count_users->COUNT;
      } else {
        $recordsFiltered = $data_users_count_result->COUNT;
      }

      return (object) array('recordsTotal' => $data_count_users->COUNT, 'recordsFiltered' => $recordsFiltered, 'data' => $data_ticket);
  }

  // Получение IPcompany постраничено с поискоим и без для datatable
  public function get_IPcompany_datatable($order_request, $type_order_request, $limit_start, $limit_count, $searh_value = '') {
      global $database;

      $count_users = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->IPCHAIN_entity ");
      $count_users->execute();
      $data_count_users = $count_users->fetch(PDO::FETCH_OBJ);
      if('' == $searh_value){
        $data_users = $database->prepare("SELECT * FROM $this->IPCHAIN_entity ORDER BY {$order_request} {$type_order_request} LIMIT {$limit_start}, {$limit_count} ");
      } else {
        $data_users_count = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->IPCHAIN_entity WHERE Name LIKE '%{$searh_value}%' OR Inn LIKE '%{$searh_value}%' OR Ogrn LIKE '%{$searh_value}%' ");
        $data_users_count->execute();
        $data_users_count_result = $data_users_count->fetch(PDO::FETCH_OBJ);
        $data_users = $database->prepare("SELECT * FROM $this->IPCHAIN_entity WHERE Name LIKE '%{$searh_value}%' OR Inn LIKE '%{$searh_value}%' OR Ogrn LIKE '%{$searh_value}%'  ");
      }

      $data_users->execute();
      $data_users = $data_users->fetchAll(PDO::FETCH_OBJ);

      if('' == $searh_value){
        $recordsFiltered = $data_count_users->COUNT;
      } else {
        $recordsFiltered = $data_users_count_result->COUNT;
      }

      return (object) array('recordsTotal' => $data_count_users->COUNT, 'recordsFiltered' => $recordsFiltered, 'data' => $data_users);
  }

  // Получение event постраничено с поискоим и без для datatable
  public function get_event_datatable($order_request, $type_order_request, $limit_start, $limit_count, $searh_value = '') {
      global $database;

      $count_users = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->MAIN_events ");
      $count_users->execute();
      $data_count_users = $count_users->fetch(PDO::FETCH_OBJ);
      if('' == $searh_value){
        $data_users = $database->prepare("SELECT * FROM $this->MAIN_events ORDER BY {$order_request} {$type_order_request} LIMIT {$limit_start}, {$limit_count} ");
      } else {
        $data_users_count = $database->prepare("SELECT COUNT(*) AS COUNT FROM $this->MAIN_events WHERE name LIKE '%{$searh_value}%' OR place LIKE '%{$searh_value}%' OR start_datetime_event LIKE '%{$searh_value}%' ");
        $data_users_count->execute();
        $data_users_count_result = $data_users_count->fetch(PDO::FETCH_OBJ);
        $data_users = $database->prepare("SELECT * FROM $this->MAIN_events WHERE name LIKE '%{$searh_value}%' OR place LIKE '%{$searh_value}%' OR start_datetime_event LIKE '%{$searh_value}%'  ");
      }

      $data_users->execute();
      $data_users = $data_users->fetchAll(PDO::FETCH_OBJ);

      if('' == $searh_value){
        $recordsFiltered = $data_count_users->COUNT;
      } else {
        $recordsFiltered = $data_users_count_result->COUNT;
      }

      return (object) array('recordsTotal' => $data_count_users->COUNT, 'recordsFiltered' => $recordsFiltered, 'data' => $data_users);
  }

  // обновление прав роли
  public function update_rules_role($name_role,$json_string) {
        global $database;

        $check_json = $this->isJSON($json_string);

        if ($check_json) {

                $update_rules_role = $database->prepare("UPDATE $this->API_USERS_ROLE SET rules = :jsonstring WHERE name = :name");
                $update_rules_role->bindParam(':name', $name_role, PDO::PARAM_STR);
                $update_rules_role->bindParam(':jsonstring', $json_string, PDO::PARAM_STR);
                $check_upd_activity_user = $update_rules_role->execute();
                $count_rules = $update_rules_role->rowCount();

                if ($count_rules) {
                      return json_encode(array('response' => true, 'description' => 'Обновление прав роли прошло успешно'),JSON_UNESCAPED_UNICODE);
                      exit;
                }
                else {
                      return json_encode(array('response' => true, 'description' => 'Права не были обновлены'),JSON_UNESCAPED_UNICODE);
                      exit;
                }
        }
        else {
                return json_encode(array('response' => false, 'description' => 'Ошибка, попробуйте позже'),JSON_UNESCAPED_UNICODE);
                exit;
        }

  }

  // получение данных по првам роли пользователя
  public function get_user_rules($id_role){
        global $database;

        $statement = $database->prepare("SELECT * FROM $this->API_USERS_ROLE WHERE id = :id");
        $statement->bindParam(':id', $id_role, PDO::PARAM_INT);
        $statement->execute();
        $rules = $statement->fetch(PDO::FETCH_OBJ);

        if ($rules->rules) {
            $data_rules = json_decode($rules->rules);
            return json_encode(array('response' => true, 'rules' => $data_rules, 'description' => 'Права роли найдены'),JSON_UNESCAPED_UNICODE);
            exit;
        }
        else {
            return json_encode(array('response' => false, 'description' => 'Роль с таким id не была найдена'),JSON_UNESCAPED_UNICODE);
            exit;
        }

  }









  /* Функции пердназнаяеные для отчетов из фулдаты  */


  // подсчет юридических лиц зарегистрированных в лпмтех
  public function count_main_entity(){
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_entity");
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_OBJ);

      $count_data = count($data);

      if ($count_data > 0) {
          return $count_data;
          exit;
      } else {
          return 0;
          exit;
      }

  }

  // Получение всех юридических лиц
  public function get_all_main_entity() {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_entity");
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_OBJ);

      if (count($data)) {
          return $data;
          exit;
      } else {
          return false;
          exit;
      }

  }

  // подсчет количества компаний являющихся резидентами сколково
  public function count_main_entity_skolkovo() {
      global $database;

        $check_entity = $this->get_all_main_entity();

        if ($check_entity) {


            $array_entity = array();
            foreach ($check_entity as $key => $value) {
                array_push($array_entity,$value->inn);
            }

            $skolkovo_fond = file_get_contents("https://crmapi.sk.ru/api/Public/GetMembers");
            $test_json = json_decode($skolkovo_fond);
            $massiv_skolkovo = array();
            foreach ($test_json as $key) {
              array_push($massiv_skolkovo,$key->Inn);
            }

            $return_array = array_intersect($array_entity,$massiv_skolkovo);

            return count($return_array);
            exit;

        }
        else {
            return 0;
            exit;
        }
  }

  // Количество участников ФСИ в фулл дата
  public function count_main_entity_fci() {
        global $database;

        $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_StateSupport INNER JOIN $this->IPCHAIN_entity ON $this->IPCHAIN_StateSupport.`ipchain_id_entity` = $this->IPCHAIN_entity.`id` INNER JOIN $this->MAIN_entity ON $this->MAIN_entity.`inn` = $this->IPCHAIN_entity.`inn` GROUP BY $this->IPCHAIN_entity.`inn`");
        $statement->execute();
        $data_users = $statement->fetchAll(PDO::FETCH_OBJ);

        if (!$data_users) {
            return 0;
            exit;
        }

        $array_document_fsi = array(
          'У' => 'Умник',
          'Соц' => 'Социум Цифровые технологии',
          'С1ЦТ' => 'Старт-1 Цифровые технологии',
          'С2ЦТ' => 'Старт-2 Цифровые технологии',
          'С2ЦТ' => 'Старт-3 Цифровые технологии',
          'С1' => 'Старт-1',
          'С2' => 'Старт-2',
          'СЦТ' => 'Старт Цифровые технологии',
          'Комм' => 'Коммерциализация',
          'КЭ' => 'Коммерциализация Экспорт',
          'Разв' => 'Развитие',
          'ЦТ' => 'Цифровые технологии',
          'НТИ' => 'Развитие НТИ',
          'ЦП' => 'Развитие-Цифровые платформы',
          'ДП' => 'Дежурный по планете',
          'Мол' => 'Вовлечение молодежи в инновационную деятельность',
          'С1ЦП' => 'Старт-Цифровые платформы',
          'С1Н' => 'Старт-1',
          'Агро' => 'АГРОНТИ',
          'Kor' => 'Российско-корейский конкурс',
          'ДЦ' => 'Поддержка центров молодежного инновационного творчества'
        );

        $count_fci_entity = 0;
        foreach ($data_users as $key => $value) {
            if ($array_document_fsi[stristr($value->id_Support, '-', true)]) {
                $count_fci_entity++;
            }
            else {
              continue;
            }
        }

        return $count_fci_entity;
        exit;

  }

  // получение количества юридических лиц по отраслям:
  public function get_count_entity_branch() {
      global $database;

      $data_all_entity = $this->get_all_main_entity();
      $ipchain_GetIndustries = json_decode($this->ipchain_GetIndustries())->data;

      $array_brach = array();
      foreach ($data_all_entity as $key => $value) {
          $data_brach = json_decode($value->branch);
          for ($i=0; $i < count($data_brach); $i++) {
              array_push($array_brach,$data_brach[$i]->Name);
          }
      }

      $count_branches = array_count_values($array_brach);

      return $count_branches;

  }

  // получение количства компаний по типам компанией
  public function get_count_entity_type_inf() {
      global $database;

      $data_entity = $this->get_all_main_entity();

      $array_massiv = array();

      foreach ($data_entity as $key => $value) {
          $type_inf = ($value->type_inf == ' ' || !$value->type_inf) ? 'Без типа' : $value->type_inf;
          array_push($array_massiv,$type_inf);
      }

      $count_type_inf = array_count_values($array_massiv);

      return $count_type_inf;
      exit;

  }

  // подсчет юридических лиц осуществляющих экспорт
  public function get_count_entity_export() {
      global $database;

      $data_entity = $this->get_all_main_entity();

      $count_entity = 0;
      foreach ($data_entity as $key => $value) {
          $data_entity2 = json_decode($value->export);
          //var_dump($data_entity2);
          // var_dump($data_entity2->other);
          $pos = strripos($value->export, 'true');
          if (!$pos) {
              if (is_array($data_entity2->other)) {
                $count_entity++;
              }
          }
          else {
              $count_entity++;
          }
      }

      return $count_entity;
      exit;


  }

  // подсчет получения компаний за период
  public function count_main_entity_period(){
    global $database;




  }

  // подсчет юридеских лиц по периодам год/месяц/неделя/день
  public function get_count_entity_groupby_time_reg($period) {
    global $database;

    if ($period == 'year') {
      $statement = $database->prepare("SELECT count(inn) as sum,YEAR(date_register) as yeard, count(inn) as entity_groupby FROM $this->MAIN_entity GROUP BY YEAR(date_register)");
    }
    if ($period == 'month') {
      $statement = $database->prepare("SELECT count(inn) as sum,MONTH(date_register) as monthd,YEAR(date_register) as yeard, count(inn) as entity_groupby FROM $this->MAIN_entity GROUP BY MONTH(date_register),YEAR(date_register)");
    }
    if ($period == 'week') {
      $statement = $database->prepare("SELECT count(inn) as sum, WEEK(date_register) as weekd, YEAR(date_register) as yeard, count(inn) as entity_groupby FROM $this->MAIN_entity GROUP BY WEEK(date_register),YEAR(date_register)");
    }
    if ($period == 'day') {
      $statement = $database->prepare("SELECT count(inn) as sum, DAY(date_register) as dayd, MONTH(date_register) as monthd, YEAR(date_register) as yeard, count(inn) as entity_groupby FROM $this->MAIN_entity GROUP BY DAY(date_register),MONTH(date_register),YEAR(date_register)");
    }
    if ($period == 'data') {
      $statement = $database->prepare("SELECT * FROM $this->MAIN_entity");
    }
    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_OBJ);
    return $data;

  }

  // подсчет юридических лиц имеющих статус сколково по периодам год/месяц/неделя/день
  public function get_count_entity_skolkovo_groupby_time_reg($period) {
      global $database;

      $skolkovo_fond = file_get_contents("https://crmapi.sk.ru/api/Public/GetMembers");
      $test_json = json_decode($skolkovo_fond);
      $massiv_skolkovo = array();
      foreach ($test_json as $key) {
          array_push($massiv_skolkovo,$key->Inn);
      }

      $check_entity = $this->get_all_main_entity();

      if ($check_entity) {

          $array_entity = array();
          foreach ($check_entity as $key => $value) {
              array_push($array_entity,$value->inn);
          }
          $return_array = array_intersect($array_entity,$massiv_skolkovo);
      }
      else {
          return 0;
          exit;
      }

      $strokaSQL = "SELECT";
      // return $return_array;
      if ($period == 'year') {
          $strokaSQL .= ' count(inn) as sum, YEAR(date_register) as yeard, count(inn) as skolkovo_groupby';
      }
      if ($period == 'month') {
          $strokaSQL .= ' count(inn) as sum, MONTH(date_register) as monthd, YEAR(date_register) as yeard, count(inn) as skolkovo_groupby';
      }
      if ($period == 'week') {
          $strokaSQL .= ' count(inn) as sum, WEEK(date_register) as weekd, YEAR(date_register) as yeard, count(inn) as skolkovo_groupby';
      }
      if ($period == 'day') {
          $strokaSQL .= ' count(inn) as sum, DAY(date_register) as dayd, MONTH(date_register) as monthd, YEAR(date_register) as yeard, count(inn) as skolkovo_groupby';
      }
      if ($period == 'data') {
          $strokaSQL .= ' * ';
      }

      $strokaSQL .=  " FROM $this->MAIN_entity WHERE";

      $count = 0;
      foreach ($return_array as $i => $value){
            if ($count == 0 ) {
                $strokaSQL .= " inn = :inn_$i";
                $count++;
            }
            else {
                $strokaSQL .= " OR inn = :inn_$i";
            }
      }

      if ($period == 'year') {
          $strokaSQL .= ' GROUP BY YEAR(date_register)';
      }
      if ($period == 'month') {
          $strokaSQL .= ' GROUP BY MONTH(date_register), YEAR(date_register)';
      }
      if ($period == 'week') {
          $strokaSQL .= ' GROUP BY WEEK(date_register), YEAR(date_register)';
      }
      if ($period == 'day') {
          $strokaSQL .= ' GROUP BY DAY(date_register), MONTH(date_register), YEAR(date_register)';
      }
      if ($period == 'data') {
          $strokaSQL .= '';
      }

      $statement = $database->prepare($strokaSQL);

      foreach ($return_array as $i => $value){
          $statement->bindValue(":inn_$i", $value, PDO::PARAM_STR);
      }
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_OBJ);

      return $data;

  }

  // подсчет компаний имеющих статус поддержки по периодам год/месяц/неделя/день
  public function get_count_entity_fci_groupby_time_reg($period) {
      global $database;


      $statement = $database->prepare("SELECT * FROM $this->IPCHAIN_StateSupport INNER JOIN $this->IPCHAIN_entity ON $this->IPCHAIN_StateSupport.`ipchain_id_entity` = $this->IPCHAIN_entity.`id` INNER JOIN $this->MAIN_entity ON $this->MAIN_entity.`inn` = $this->IPCHAIN_entity.`inn` GROUP BY $this->IPCHAIN_entity.`inn`");
      $statement->execute();
      $data_users = $statement->fetchAll(PDO::FETCH_OBJ);

      if (!$data_users) {
          return 0;
          exit;
      }

      $array_document_fsi = array(
        'У' => 'Умник',
        'Соц' => 'Социум Цифровые технологии',
        'С1ЦТ' => 'Старт-1 Цифровые технологии',
        'С2ЦТ' => 'Старт-2 Цифровые технологии',
        'С2ЦТ' => 'Старт-3 Цифровые технологии',
        'С1' => 'Старт-1',
        'С2' => 'Старт-2',
        'СЦТ' => 'Старт Цифровые технологии',
        'Комм' => 'Коммерциализация',
        'КЭ' => 'Коммерциализация Экспорт',
        'Разв' => 'Развитие',
        'ЦТ' => 'Цифровые технологии',
        'НТИ' => 'Развитие НТИ',
        'ЦП' => 'Развитие-Цифровые платформы',
        'ДП' => 'Дежурный по планете',
        'Мол' => 'Вовлечение молодежи в инновационную деятельность',
        'С1ЦП' => 'Старт-Цифровые платформы',
        'С1Н' => 'Старт-1',
        'Агро' => 'АГРОНТИ',
        'Kor' => 'Российско-корейский конкурс',
        'ДЦ' => 'Поддержка центров молодежного инновационного творчества'
      );

      $count_fci_entity = array();
      foreach ($data_users as $key => $value) {
          if ($array_document_fsi[stristr($value->id_Support, '-', true)]) {
              array_push($count_fci_entity,$value->inn);
          }
          else {
              continue;
          }
      }



      $strokaSQL = "SELECT";
      // return $return_array;
      if ($period == "year") {
          $strokaSQL .= " count(inn) as sum, YEAR(date_register) as yeard, count(inn) as fci_groupby";
      }
      if ($period == "month") {
          $strokaSQL .= " count(inn) as sum, MONTH(date_register) as monthd, YEAR(date_register) as yeard, count(inn) as fci_groupby";
      }
      if ($period == "week") {
          $strokaSQL .= " count(inn) as sum, WEEK(date_register) as weekd, YEAR(date_register) as yeard, count(inn) as fci_groupby";
      }
      if ($period == "day") {
          $strokaSQL .= " count(inn) as sum, DAY(date_register) as dayd, MONTH(date_register) as monthd, YEAR(date_register) as yeard, count(inn) as fci_groupby";
      }
      if ($period == "data") {
          $strokaSQL .= " * ";
      }


      $strokaSQL .=  " FROM $this->MAIN_entity WHERE";

      $count = 0;
      foreach ($count_fci_entity as $i => $value){
            if ($count == 0 ) {
                $strokaSQL .= " inn = :inn_$i";
                $count++;
            }
            else {
                $strokaSQL .= " OR inn = :inn_$i";
            }
      }

      if ($period == 'year') {
          $strokaSQL .= " GROUP BY YEAR(date_register)";
      }
      if ($period == 'month') {
          $strokaSQL .= " GROUP BY MONTH(date_register), YEAR(date_register)";
      }
      if ($period == 'week') {
          $strokaSQL .= " GROUP BY WEEK(date_register), YEAR(date_register)";
      }
      if ($period == 'day') {
          $strokaSQL .= " GROUP BY DAY(date_register), MONTH(date_register), YEAR(date_register)";
      }
      if ($period == 'data') {
          $strokaSQL .= "";
      }

      $statement = $database->prepare($strokaSQL);

      foreach ($count_fci_entity as $i => $value){
          $statement->bindValue(":inn_$i", $value, PDO::PARAM_STR);
      }

      $statement->execute();
      $data_users = $statement->fetchAll(PDO::FETCH_OBJ);





      return $data_users;
      exit;



  }

  // подсчет юридических лиц осуществляющих экспорт по периодам год/месяц/неделя/день
  public function get_count_entity_export_groupby_time_reg($period) {
      global $database;


      $data_entity = $this->get_all_main_entity();

      $new_data_entity = array();
      foreach ($data_entity as $key => $value) {
          $data_entity2 = json_decode($value->export);
          $pos = strripos($value->export, 'true');
          if (!$pos) {
              if (is_array($data_entity2->other)) {
                array_push($new_data_entity,$value->inn);
              }
              else {continue;}
          }
          else {
              array_push($new_data_entity,$value->inn);
          }
      }


      $strokaSQL = "SELECT";
      // return $return_array;
      if ($period == "year") {
          $strokaSQL .= " count(inn) as sum, YEAR(date_register) as yeard, count(inn) as export_groupby";
      }
      if ($period == "month") {
          $strokaSQL .= " count(inn) as sum, MONTH(date_register) as monthd, YEAR(date_register) as yeard, count(inn) as export_groupby";
      }
      if ($period == "week") {
          $strokaSQL .= " count(inn) as sum, WEEK(date_register) as weekd, YEAR(date_register) as yeard, count(inn) as export_groupby";
      }
      if ($period == "day") {
          $strokaSQL .= " count(inn) as sum, DAY(date_register) as dayd, MONTH(date_register) as monthd, YEAR(date_register) as yeard, count(inn) as export_groupby";
      }
      if ($period == "data") {
          $strokaSQL .= " * ";
      }


      $strokaSQL .=  " FROM $this->MAIN_entity WHERE";

      $count = 0;
      foreach ($new_data_entity as $i => $value){
            if ($count == 0 ) {
                $strokaSQL .= " inn = :inn_$i";
                $count++;
            }
            else {
                $strokaSQL .= " OR inn = :inn_$i";
            }
      }

      if ($period == 'year') {
          $strokaSQL .= " GROUP BY YEAR(date_register)";
      }
      if ($period == 'month') {
          $strokaSQL .= " GROUP BY MONTH(date_register), YEAR(date_register)";
      }
      if ($period == 'week') {
          $strokaSQL .= " GROUP BY WEEK(date_register), YEAR(date_register)";
      }
      if ($period == 'day') {
          $strokaSQL .= " GROUP BY DAY(date_register), MONTH(date_register), YEAR(date_register)";
      }
      if ($period == 'data') {
          $strokaSQL .= "";
      }

      $statement = $database->prepare($strokaSQL);

      foreach ($new_data_entity as $i => $value){
          $statement->bindValue(":inn_$i", $value, PDO::PARAM_STR);
      }

      $statement->execute();
      $data_users = $statement->fetchAll(PDO::FETCH_OBJ);





      return $data_users;
      exit;

  }

  // выгрузка компаний по очтетам
  public function get_entity_by_category($category) {
      global $database;


          $stroka_sql = "SELECT * FROM $this->MAIN_entity";
          $statement = $database->prepare($stroka_sql);
          $statement->execute();
          $data_users = $statement->fetchAll(PDO::FETCH_OBJ);

        if ($category == 'УчасСколково') {
            $data_entity_param = $this->get_count_entity_skolkovo_groupby_time_reg('data');
            $massiv_data_entity_param = array();
            foreach ($data_entity_param as $key => $value) {
                  array_push($massiv_data_entity_param,$value->inn);
            }
        }
        if ($category == 'УчасФСИ') {
            $data_entity_param = $this->get_count_entity_fci_groupby_time_reg('data');
            $massiv_data_entity_param = array();
            foreach ($data_entity_param as $key => $value) {
                  array_push($massiv_data_entity_param,$value->inn);
            }
        }
        // if ($category == 'УчасЛПМ') {
        //     $stroka_sql = "";
        // }





          $itog_masiv = array ();
      foreach ($data_users as $key => $value) {

            $temp_array = array();
            $temp_array['inn'] = $value->inn;
            $mass_entity = json_decode($value->data_fns);
            $data_fns = end($mass_entity->items);
            $temp_array['name_entity'] = (strlen($value->inn) == 12) ? 'ИП '.$data_fns->ИП->ФИОПолн : $data_fns->ЮЛ->НаимСокрЮЛ;

            if ($category == 'мсп') {
                    $temp_array['value'] = ($value->msp && $value->msp != ' ') ? $value->msp : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($category == 'регион') {
                    $temp_array['value'] = ($value->region && $value->region != ' ') ? $value->region : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($category == 'район') {
                    $temp_array['value'] = ($value->region && $value->district != ' ') ? $value->district : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($category == 'тип') {
                    $temp_array['value'] = ($value->region && $value->type_inf != ' ') ? $value->type_inf : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($category == 'отрасль') {
                    $massiv_branch = json_decode($value->branch);
                    $temp_string = '';
                    for ($i=0; $i < count($massiv_branch); $i++) {
                        $temp_string .= $massiv_branch[$i]->Name.', ';
                    }
                    $temp_string = substr($temp_string, 0, -2);
                    $temp_array['value'] = ($temp_string) ? $temp_string : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($category == 'УчасСколково') {
                    $temp_array['value'] = (in_array($value->inn,$massiv_data_entity_param)) ? 'Да' : 'Нет';
                    array_push($itog_masiv,$temp_array);
            }
            if ($category == 'УчасФСИ') {
                    $temp_array['value'] = (in_array($value->inn,$massiv_data_entity_param)) ? 'Да' : 'Нет';
                    array_push($itog_masiv,$temp_array);
            }
            // if ($category == 'УчасЛПМ') {
            //
            // }
            if ($category == 'экспорт') {
                    $massiv_export = json_decode($value->export);
                    $temp_string = '';
                    if ($massiv_export->SNG == true) {$temp_string .= 'СНГ, ';}
                    if ($massiv_export->ES == true) {$temp_string .= 'Евросоюз, ';}
                    if ($massiv_export->all_world  == true) {$temp_string .= 'Весь мир, ';}

                    if (is_array($massiv_export->other)) {
                            $temp_string .= 'Другое: ';
                            for ($i=0; $i < count($massiv_export->other); $i++) {
                                $temp_string .= $massiv_export->other[$i].', ';
                            }
                    }

                    $temp_string = substr($temp_string, 0, -2);
                    $temp_array['value'] = ($temp_string) ? $temp_string : 'Не экспортирует';
                    array_push($itog_masiv,$temp_array);
            }

      }

      return $itog_masiv;

  }

  // получение существующих параметров по юридическим лицам
  public function get_current_parameters($parameter) {
    global $database;

      $data_entity = $this->get_all_main_entity();
      $new_array = array();

      foreach ($data_entity as $key => $value) {

            if ($parameter == 'msp') {
                if ($value->msp && $value->msp != ' ') {
                    array_push($new_array,$value->msp);
                }
            }

            if ($parameter == 'region') {
                if ($value->region && $value->region != ' ') {
                    array_push($new_array,$value->region);
                }
            }

            if ($parameter == 'staff') {
                if ($value->staff && $value->staff != ' ') {
                    array_push($new_array,$value->staff);
                }
            }

            if ($parameter == 'district') {
                if ($value->district && $value->district != ' ') {
                    array_push($new_array,$value->district);
                }
            }

            if ($parameter == 'type_inf') {
                if ($value->type_inf && $value->type_inf != ' ') {
                    array_push($new_array,$value->type_inf);
                }
            }

            if ($parameter == 'export') {
                if ($value->export && $value->export != ' ') {
                    $massiv_export = json_decode($value->export);
                    if ($massiv_export->SNG == true) {array_push($new_array,'СНГ');}
                    if ($massiv_export->ES == true) {array_push($new_array,'Евросоюз');}
                    if ($massiv_export->all_world  == true) {array_push($new_array,'Весь мир');}
                    if (is_array($massiv_export->other)) {
                            $temp_string .= 'Другое: ';
                            for ($i=0; $i < count($massiv_export->other); $i++) {
                                array_push($new_array,'Другое: '.$massiv_export->other[$i]);
                            }
                    }
                }
            }

            if ($parameter == 'branch') {
                if ($value->branch && $value->branch != ' ') {
                    $massiv_branch = json_decode($value->branch);
                    for ($i=0; $i < count($massiv_branch); $i++) {
                        array_push($new_array,$massiv_branch[$i]->Name);
                    }
                }
            }

            if ($parameter == 'technology') {
                if ($value->technology && $value->technology != ' ') {
                    $massiv_technology = json_decode($value->technology);
                    for ($i=0; $i < count($massiv_technology); $i++) {
                        array_push($new_array,$massiv_technology[$i]->Name);
                    }
                }
            }
      }

      $result = array_values(array_unique($new_array));


      return $result;
      exit;

  }

  // получение компаий по определенному критерию
  public function get_entity_search_by_parameter($parameter,$parameter_value) {
      global $database;

      $statement = $database->prepare("SELECT * FROM $this->MAIN_entity WHERE {$parameter} LIKE concat('%',:parameter_value,'%')");
      $statement->bindParam(':parameter_value', $parameter_value, PDO::PARAM_STR);
      $statement->execute();
      $data_user = $statement->fetchAll(PDO::FETCH_OBJ);

      $itog_masiv = array();

      foreach ($data_user as $key => $value) {
            $temp_array = array();
            $temp_array['inn'] = $value->inn;
            $mass_entity = json_decode($value->data_fns);
            $data_fns = end($mass_entity->items);
            $temp_array['name_entity'] = (strlen($value->inn) == 12) ? 'ИП '.$data_fns->ИП->ФИОПолн : $data_fns->ЮЛ->НаимСокрЮЛ;

            if ($parameter == 'msp') {
                    $temp_array['value'] = ($value->msp && $value->msp != ' ') ? $value->msp : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($parameter == 'region') {
                    $temp_array['value'] = ($value->region && $value->region != ' ') ? $value->region : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($parameter == 'district') {
                    $temp_array['value'] = ($value->district && $value->district != ' ') ? $value->district : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($parameter == 'type_inf') {
                    $temp_array['value'] = ($value->type_inf && $value->type_inf != ' ') ? $value->type_inf : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($parameter == 'branch') {
                    $massiv_branch = json_decode($value->branch);
                    $temp_string = '';
                    for ($i=0; $i < count($massiv_branch); $i++) {
                        $temp_string .= $massiv_branch[$i]->Name.', ';
                    }
                    $temp_string = substr($temp_string, 0, -2);
                    $temp_array['value'] = ($temp_string) ? $temp_string : 'Не указано';
                    array_push($itog_masiv,$temp_array);
            }
            if ($parameter == 'export') {
                    $massiv_export = json_decode($value->export);
                    $temp_string = '';
                    if ($massiv_export->SNG == true) {$temp_string .= 'СНГ, ';}
                    if ($massiv_export->ES == true) {$temp_string .= 'Евросоюз, ';}
                    if ($massiv_export->all_world  == true) {$temp_string .= 'Весь мир, ';}

                    if (is_array($massiv_export->other)) {
                            $temp_string .= 'Другое: ';
                            for ($i=0; $i < count($massiv_export->other); $i++) {
                                $temp_string .= $massiv_export->other[$i].', ';
                            }
                    }

                    $temp_string = substr($temp_string, 0, -2);
                    $temp_array['value'] = ($temp_string) ? $temp_string : 'Не экспортирует';
                    array_push($itog_masiv,$temp_array);
            }
      }


      return $itog_masiv;
      exit;

  }

  // выгрузка пользователей у которых есть приявязанные компании
  public function get_users_entity_data() {
    global $database;

      $statement = $database->prepare("SELECT * FROM $this->main_users INNER JOIN $this->MAIN_entity ON $this->main_users.`id_entity` = $this->MAIN_entity.`id` WHERE $this->main_users.`id_entity` > 0");
      $statement->execute();
      $data_user = $statement->fetchAll(PDO::FETCH_OBJ);

      $itog_masiv = array();

      foreach ($data_user as $key => $value) {
          $temp_array = array();
          $temp_array['name'] = ($value->name) ? $value->name : '-';
          $temp_array['last_name'] = ($value->last_name) ? $value->last_name : '-';
          $temp_array['second_name'] = ($value->second_name) ? $value->second_name : '-';
          $temp_array['email'] = ($value->email) ? $value->email : '-';
          $temp_array['phone'] = ($value->phone) ? $value->phone : '-';
          $temp_array['position'] = ($value->position) ? $value->position : '-';
          $mass_entity = json_decode($value->data_fns);
          $data_fns = end($mass_entity->items);
          $temp_array['name_entity'] = (strlen($value->inn) == 12) ? 'ИП '.$data_fns->ИП->ФИОПолн : $data_fns->ЮЛ->НаимСокрЮЛ;
          array_push($itog_masiv,$temp_array);

      }

      return $itog_masiv;
      exit;

  }

}

?>
