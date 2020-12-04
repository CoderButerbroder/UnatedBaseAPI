<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/smtp/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/smtp/SMTP.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/smtp/Exception.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/general/DATAroot.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/dadata/DadataClient.php');

class Settings {

  private $gen_settings = 'API_SETTINGS';
  private $users = 'API_USERS';
  private $tokens = 'API_TOKENS';
  private $api_referer = 'API_REFERER';
  private $history = 'API_HISTORY';
  private $user_referer = 'TIME_user_referer';
  private $main_users = 'MAIN_users';
  private $main_users_social = 'MAIN_users_social';
  private $MAIN_entity = 'MAIN_entity';
  private $MAIN_entity_tech_requests = 'MAIN_entity_tech_requests';
  private $MAIN_entity_tech_requests_solutions = 'MAIN_entity_tech_requests_solutions';
  private $MAIN_entity_tech_services = 'MAIN_entity_tech_services';
  private $MAIN_entity_tech_services_comments = 'MAIN_entity_tech_services_comments';
  private $MAIN_entity_tech_services_rating = 'MAIN_entity_tech_services_rating';
  private $MAIN_entity_tech_services_view = 'MAIN_entity_tech_services_view';
  private $MAIN_users_accounts = 'MAIN_users_accounts';
  private $errors_migrate = 'errors_migrate';

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
           return json_encode(array('response' => true, 'data' => $data),JSON_UNESCAPED_UNICODE);
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
        $statement->bindParam(':inn', $inn, PDO::PARAM_INT);
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

      $validFields = array('msp','site','region','staff','district','street','house','type_inf','additionally','export','branch');

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
                      'branch' => PDO::PARAM_STR
                    );

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
  public function register_entity($id_user_tboil,$inn,$msp,$site,$region,$staff,$district,$street,$house,$type_inf,$additionally,$export,$branch){
      global $database;

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
                                          'branch' => $branch);

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

            $request = $database->prepare("INSERT INTO $this->MAIN_entity (inn,data_fns,data_dadata,msp,site,region,staff,district,street,house,type_inf,additionally,export,branch,hash,date_pickup)
                                                  VALUES (:inn,:data_fns,:data_dadata,:msp,:site,:region,:staff,:district,:street,:house,:type_inf,:additionally,:export,:branch,:hash,:date_pickup)");
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
            $request->bindParam(':hash', $hash, PDO::PARAM_STR);
            $request->bindParam(':date_pickup', $date_pickup, PDO::PARAM_STR);
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
            return json_encode(array('response' => false, 'description' => 'Ошибка Обновления токена tboil', 'token' => $admin_token->data->token),JSON_UNESCAPED_UNICODE);
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
        $new_erorr->bindParam(':id_tboil', $id_tboil, PDO::PARAM_STR);
        $new_erorr->bindParam(':type', $type, PDO::PARAM_STR);
        $new_erorr->bindParam(':date_record', $today, PDO::PARAM_STR);
        $check_new_erorr = $new_erorr->execute();
        $check_id = $database->lastInsertId();

        $this->telega_send($this->get_global_settings('telega_chat_error'), $type.' '.$id_tboil);
    }



  /* НЕНУЖНЫЕ ФУНКЦИИ КОТОРЫЕ БЫЛИ УДАЛЕНЫ  */



  // // Запись переходов реферов
  // public function record_user_referer($session_id,$ip_user,$refer) {
  //     global $database;
  //
  //         $today = date("Y-m-d H:i:s");
  //
  //         $resource = parse_url($refer, PHP_URL_HOST);
  //         $resource2 = $this->get_global_settings('hosting_name');
  //         if ($resource2 != $resource) {
  //
  //           $add_user_referer = $database->prepare("UPDATE $this->user_referer SET ip = :ip, referer = :referer, date_record = :date_record WHERE session_id = :session_id");
  //           $add_user_referer->bindParam(':ip', $ip_user, PDO::PARAM_STR);
  //           $add_user_referer->bindParam(':referer', $refer, PDO::PARAM_STR);
  //           $add_user_referer->bindParam(':date_record', $today, PDO::PARAM_STR);
  //           $add_user_referer->bindParam(':session_id', $session_id, PDO::PARAM_STR);
  //           $temp = $add_user_referer->execute();
  //           $count = $add_user_referer->rowCount();
  //
  //           if ($count) {
  //                 return json_encode(array('response' => true, 'description' => 'Рефер пользователя успешно обновлен'),JSON_UNESCAPED_UNICODE);
  //           } else {
  //
  //               $add_user_referer = $database->prepare("INSERT INTO $this->user_referer (session_id,ip,referer,date_record) VALUES (:session_id,:ip,:referer,:date_record)");
  //               $add_user_referer->bindParam(':session_id', $session_id, PDO::PARAM_STR);
  //               $add_user_referer->bindParam(':ip', $ip_user, PDO::PARAM_STR);
  //               $add_user_referer->bindParam(':referer', $refer, PDO::PARAM_STR);
  //               $add_user_referer->bindParam(':date_record', $today, PDO::PARAM_STR);
  //               $check_referer = $add_user_referer->execute();
  //               if ($check_referer) {
  //                 return json_encode(array('response' => true, 'description' => 'Рефер пользователя успешно добавлен'),JSON_UNESCAPED_UNICODE);
  //               } else {
  //                 return json_encode(array('response' => false, 'description' => 'Рефер пользователя не был добавлен, попробуйте позже'),JSON_UNESCAPED_UNICODE);
  //               }
  //
  //           }
  //
  //         } else {
  //            return json_encode(array('response' => false, 'description' => 'Этот же хост'),JSON_UNESCAPED_UNICODE);
  //         }
  //
  //  }
  //
  // // Получение данных пользователя
  // public function get_cur_user($hash_or_id) {
  //     global $database;
  //
  //     $check_activiti = $this->update_activity($hash_or_id);
  //
  //     if (is_numeric($hash_or_id)) {
  //         $check_user_data = $database->prepare("SELECT * FROM $this->main_users WHERE id = :id");
  //         $check_user_data->bindParam(':id', $hash_or_id, PDO::PARAM_INT);
  //     } else {
  //         $check_user_data = $database->prepare("SELECT * FROM $this->main_users WHERE hash = :hash");
  //         $check_user_data->bindParam(':hash', $hash_or_id, PDO::PARAM_STR);
  //     }
  //     $check_user_data->execute();
  //     $user = $check_user_data->fetch(PDO::FETCH_OBJ);
  //
  //     if ($user) {
  //          return json_encode(array('response' => true, 'data' => $user),JSON_UNESCAPED_UNICODE);
  //     }
  //     else {
  //          return json_encode(array('response' => false, 'description' => 'Нет данных по пользователю с данным ключем'),JSON_UNESCAPED_UNICODE);
  //     }
  //
  // }
  //
  // // Авторизация пользователя
  // public function auth_user($login,$password,$session_id,$ip,$type) {
  //  global $database;
  //
  //      if ($type == 'phone') {
  //           $login = preg_replace('![^0-9]+!', '', $login);
  //           $login = trim($login);
  //           if (mb_strlen($login) <= 9 || mb_strlen($login) > 11) {
  //                 return json_encode(array('response' => false, 'description' => 'Неверный формат номера'),JSON_UNESCAPED_UNICODE);
  //           }
  //           if (mb_strlen($login) == 11) {
  //               if ($login[0] != '7') {
  //                 $login = substr($login, 1);
  //                 $login = '7'.$login;
  //               }
  //           }
  //           if (mb_strlen($login) == 10) {
  //               $login = '7'.$login;
  //           }
  //      }
  //
  //      $check_email = $database->prepare("SELECT * FROM $this->main_users WHERE email = :email OR phone = :email");
  //      $check_email->bindParam(':email', $login, PDO::PARAM_STR);
  //      $check_email->execute();
  //      $user_email = $check_email->fetch(PDO::FETCH_OBJ);
  //
  //      if (password_verify($password, $user_email->password)) {
  //             $_SESSION["key_user"] = $user_email->hash;
  //
  //             $session_refer = $database->prepare("SELECT * FROM $this->user_referer WHERE session_id = :session_id OR ip = :ip ORDER BY date_record DESC LIMIT 1");
  //             $session_refer->bindParam(':network', $session_id, PDO::PARAM_STR);
  //             $session_refer->bindParam(':network_id', $ip, PDO::PARAM_STR);
  //             $session_refer->execute();
  //             $user_session_refer = $session_refer->fetch(PDO::FETCH_OBJ);
  //
  //             if ($user_session_refer) {
  //             $check_refer = $this->get_data_referer($user_session_refer->referer);
  //                 if (json_decode($check_refer)->response) {
  //                     return json_encode(array('response' => true, 'description' => 'Пользователь успешно авторизован требуется редиректна сайт', 'redirect' => true, 'referer' => json_decode($check_refer)->data->auth_referer),JSON_UNESCAPED_UNICODE);
  //                 } else {
  //                     return json_encode(array('response' => true, 'description' => 'Пользователь успешно авторизован без редиректа. '.json_decode($check_refer)->description),JSON_UNESCAPED_UNICODE);
  //                 }
  //             } else {
  //                 return json_encode(array('response' => true, 'description' => 'Пользователь успешно авторизован'),JSON_UNESCAPED_UNICODE);
  //             }
  //      } else {
  //             return json_encode(array('response' => false, 'description' => 'Ошибка, данные не верны'),JSON_UNESCAPED_UNICODE);
  //      }
  //
  // }
  //
  // // Авторизация пользотвателя через социальную сеть
  // public function auth_user_social($data,$session_id,$ip) {
  //     global $database;
  //
  //     $data_user = json_decode($data, true);
  //
  //     $check_email = $database->prepare("SELECT * FROM $this->main_users WHERE email = :email");
  //     $check_email->bindParam(':email', $data_user['email'], PDO::PARAM_STR);
  //     $check_email->execute();
  //     $user = $check_email->fetch(PDO::FETCH_OBJ);
  //
  //     if ($user) {
  //           $check_social = $database->prepare("SELECT * FROM $this->main_users_social WHERE id_user = :id AND network = :network AND network_id = :network_id");
  //           $check_social->bindParam(':id', $user->id, PDO::PARAM_INT);
  //           $check_social->bindParam(':network', $data_user['network'], PDO::PARAM_STR);
  //           $check_social->bindParam(':network_id', $data_user['uid'], PDO::PARAM_INT);
  //           $check_social->execute();
  //           $user_check_social = $check_social->fetch(PDO::FETCH_OBJ);
  //
  //           if ($user_check_social) {
  //                 $_SESSION["key_user"] = $user->hash;
  //                 return json_encode(array('response' => true, 'description' => 'Пользотватель авторизован'),JSON_UNESCAPED_UNICODE);
  //           } else {
  //                 return json_encode(array('response' => false, 'description' => 'Данная социальная сеть не привязана к аккаунту'),JSON_UNESCAPED_UNICODE);
  //           }
  //     } else {
  //           // Создание аккаунта если данный аккаунт не был создан
  //           // проверяем не был ли аккаунт привязан ранее к другим записям
  //           $check_social = $database->prepare("SELECT * FROM $this->main_users_social WHERE network = :network AND network_id = :network_id");
  //           $check_social->bindParam(':network', $data_user['network'], PDO::PARAM_STR);
  //           $check_social->bindParam(':network_id', $data_user['uid'], PDO::PARAM_INT);
  //           $check_social->execute();
  //           $user_check_social = $check_social->fetch(PDO::FETCH_OBJ);
  //
  //           if (!$user_check_social) {
  //
  //               $password = password_hash($data_user['email'].$data_user['uid'], PASSWORD_DEFAULT);
  //               $default = '';
  //               $default_int = 0;
  //               $hash = md5($data_user['email'].$data_user['uid'].$data_user['first_name'].$data_user['last_name'].$password);
  //               $DOB = '0000-00-00';
  //
  //               $session_refer = $database->prepare("SELECT * FROM $this->user_referer WHERE session_id = :session_id OR ip = :ip ORDER BY date_record DESC LIMIT 1");
  //               $session_refer->bindParam(':session_id', $session_id, PDO::PARAM_STR);
  //               $session_refer->bindParam(':ip', $ip, PDO::PARAM_STR);
  //               $session_refer->execute();
  //               $user_session_refer = $session_refer->fetch(PDO::FETCH_OBJ);
  //
  //               if ($user_session_refer) {
  //                   $first_referer = parse_url($user_session_refer->referer, PHP_URL_HOST);
  //               } else {
  //                   $first_referer = '';
  //               }
  //
  //               $today = date("Y-m-d H:i:s");
  //
  //               $recocery_link = md5($hash);
  //
  //               $status = 'active';
  //               $role = 'user';
  //               $data_adres = json_decode($this->iplocate($this->get_ip()));
  //               $adres = $data_adres->location->unrestricted_value;
  //               if (!$adres) {
  //                   $adres = '';
  //               }
  //
  //               $new_uruser = $database->prepare("INSERT INTO $this->main_users (email,password,phone,name,last_name,second_name,DOB,photo,adres,inn,passport_id,id_entity,position,hash,first_referer,reg_date,last_activity,recovery_link,status,role) VALUES (:email,:password,:phone,:name,:last_name,:second_name,:DOB,:photo,:adres,:inn,:passport_id,:id_entity,:position,:hash,:first_referer,:reg_date,:last_activity,:recovery_link,:status,:role)");
  //               $new_uruser->bindParam(':email', $data_user['email'], PDO::PARAM_STR);
  //               $new_uruser->bindParam(':password', $password, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':phone', $default, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':name', $data_user['first_name'], PDO::PARAM_STR);
  //               $new_uruser->bindParam(':last_name', $data_user['last_name'], PDO::PARAM_STR);
  //               $new_uruser->bindParam(':second_name', $default, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':DOB', $DOB, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':photo', $default, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':adres', $default, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':inn', $default_int, PDO::PARAM_INT);
  //               $new_uruser->bindParam(':passport_id', $default_int, PDO::PARAM_INT);
  //               $new_uruser->bindParam(':id_entity', $default_int, PDO::PARAM_INT);
  //               $new_uruser->bindParam(':position', $default, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':hash', $hash, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':first_referer', $first_referer, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':reg_date', $today, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':last_activity', $today, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':recovery_link', $recocery_link, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':status', $status, PDO::PARAM_STR);
  //               $new_uruser->bindParam(':role', $role, PDO::PARAM_STR);
  //               $new_uruser->execute();
  //               $count = $new_uruser->rowCount();
  //               $id_new_user = $database->lastInsertId();
  //
  //               if ($count) {
  //
  //                     if ($data_user['access_token']) {$token = $data_user['access_token'];} else {$token = '';}
  //
  //                           $new_uruser = $database->prepare("INSERT INTO $this->main_users_social (id_user,network,network_id,profile,email,first_name,last_name,token,date_binding) VALUES (:id_user,:network,:network_id,:profile,:email,:first_name,:last_name,:token,:date_binding)");
  //                           $new_uruser->bindParam(':id_user', $id_new_user, PDO::PARAM_INT);
  //                           $new_uruser->bindParam(':network', $data_user['network'], PDO::PARAM_STR);
  //                           $new_uruser->bindParam(':network_id', $data_user['uid'], PDO::PARAM_INT);
  //                           $new_uruser->bindParam(':profile', $data_user['profile'], PDO::PARAM_STR);
  //                           $new_uruser->bindParam(':email', $data_user['email'], PDO::PARAM_STR);
  //                           $new_uruser->bindParam(':first_name', $data_user['first_name'], PDO::PARAM_STR);
  //                           $new_uruser->bindParam(':last_name', $data_user['last_name'], PDO::PARAM_STR);
  //                           $new_uruser->bindParam(':token', $token, PDO::PARAM_STR);
  //                           $new_uruser->bindParam(':date_binding', $today, PDO::PARAM_STR);
  //                           $new_uruser->execute();
  //                           $count = $new_uruser->rowCount();
  //
  //                     if ($count) {
  //                           $_SESSION["key_user"] = $hash;
  //                           return json_encode(array('response' => true, 'description' => 'Пользотватель успешно создан и авторизован'),JSON_UNESCAPED_UNICODE);
  //                     } else {
  //                           $check_social = $database->prepare("DELETE FROM $this->main_users WHERE id = :id");
  //                           $check_social->bindParam(':id', $id_new_user, PDO::PARAM_INT);
  //                           $check_social->execute();
  //                           return json_encode(array('response' => false, 'description' => 'Ошибка 1 создания пользователя через социальную сеть '.$data_user->network.', попробуйте чуть позже'),JSON_UNESCAPED_UNICODE);
  //                     }
  //               } else {
  //                     return json_encode(array('response' => false, 'description' => 'Ошибка 2 создания пользователя через социальную сеть '.$data_user->network.', попробуйте чуть позже'),JSON_UNESCAPED_UNICODE);
  //               }
  //           } else {
  //                 return json_encode(array('response' => false, 'description' => 'Данный аккунт социальной сети уже привязан к другой учетной записи'),JSON_UNESCAPED_UNICODE);
  //           }
  //     }
  //
  // }
  //
  // // Воссстановление достпа пользователя
  // public function recovery_user($email) {
  //     global $database;
  //
  //             if ((strripos($email, '@')) && strripos($email, '.')) {
  //
  //             }
  //             else {
  //                   return json_encode(array('response' => false, 'description' => 'Данный email не валидный'),JSON_UNESCAPED_UNICODE);
  //             }
  //
  //             $statement = $database->prepare("SELECT * FROM $this->main_users WHERE email = :email");
  //             $statement->bindParam(':email', $email, PDO::PARAM_STR);
  //             $statement->execute();
  //             $user = $statement->fetch(PDO::FETCH_OBJ);
  //
  //             if (!$user) {
  //                   return json_encode(array('response' => false, 'description' => 'Пользователь с данным email не найден'),JSON_UNESCAPED_UNICODE);
  //             }
  //
  //             $content =  'Здравствуйте, '.$user->name.' '.$user->second_name.'<br>';
  //             $content .= 'Ваша ссылка для восстановления доступа на сайте e-spb.ru<br>';
  //             $content .= '<a href="https://'.$_SERVER['SERVER_NAME'].'/?link='.$user->recovery_link.'">https://'.$_SERVER['SERVER_NAME'].'/?link='.$user->recovery_link.'</a>';
  //             $content .= '<br></br> Если Вы не делали запрос на восстановления доступа, просто проигнориуйте данное письмо.';
  //
  //             $tema = 'Восстановление пароля LPM connect';
  //
  //             $check_mail = $this->send_email_user($email,$tema,$content);
  //
  //             return $check_mail;
  //
  // }
  //
  // // Задание нового пароля пользователя после восстановления доступа
  // public function new_pass_user($recovery_link,$password) {
  //     global $database;
  //
  //         $statement = $database->prepare("SELECT * FROM $this->main_users WHERE recovery_link = :recovery_link");
  //         $statement->bindParam(':recovery_link', $recovery_link, PDO::PARAM_STR);
  //         $statement->execute();
  //         $user = $statement->fetch(PDO::FETCH_OBJ);
  //
  //         if (!$user) {
  //               return json_encode(array('response' => false, 'description' => 'Ссылка для восстановления пароля недействительна'),JSON_UNESCAPED_UNICODE);
  //         }
  //
  //         $hash_password = password_hash($password);
  //         $today = date("Y-m-d H:i:s");
  //         $hash_new_link = md5($hash_password.$password.$today.$recovery_link);
  //
  //         $new_password_user = $database->prepare("UPDATE $this->main_users SET password = :hash_password, recovery_link = :new_recovery_link WHERE recovery_link = :recovery_link");
  //         $new_password_user->bindParam(':hash_password', $hash_password, PDO::PARAM_STR);
  //         $new_password_user->bindParam(':new_recovery_link', $hash_new_link, PDO::PARAM_STR);
  //         $new_password_user->bindParam(':recovery_link', $recovery_link, PDO::PARAM_STR);
  //         $check_new_password_user = $new_password_user->execute();
  //         $count = $new_password_user->rowCount();
  //
  //         if ($count) {
  //               return json_encode(array('response' => true, 'description' => 'Новый пароль успешно задан'),JSON_UNESCAPED_UNICODE);
  //         }
  //         else {
  //               return json_encode(array('response' => false, 'description' => 'Ошибка задания нового пароля'),JSON_UNESCAPED_UNICODE);
  //         }
  //
  // }
  //
  // // Функция разофторизации пользотвателя
  // public function logout() {
  //
  //     $_SESSION = array();
  //     if (ini_get("session.use_cookies")) {
  //       $params = session_get_cookie_params();
  //       setcookie(session_name(), '', time() - 42000,
  //           $params["path"], $params["domain"],
  //           $params["secure"], $params["httponly"]
  //       );
  //     }
  //     session_destroy();
  //     return true;
  // }
  //
  // // проверка логина и телефона
  // public function check_login_valid($login) {
  //
  //   if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
  //       return json_encode(array('response' => true, 'description' => 'Пользотватель использовал email', 'type' => 'email'),JSON_UNESCAPED_UNICODE);
  //   }
  //   else {
  //
  //       $phoneNumber = preg_replace('![^0-9]+!', '', $login); // удалим пробелы, и прочие не нужные знаки
  //
  //     	if(is_numeric($phoneNumber))
  //     	{
  //     		if(strlen($phoneNumber) < 10) {
  //     			  return json_encode(array('response' => false, 'description' => 'Слишком короткий телефон'),JSON_UNESCAPED_UNICODE);
  //     		} else {
  //             return json_encode(array('response' => true, 'description' => 'Пользотватель использовал телефон', 'type' => 'phone'),JSON_UNESCAPED_UNICODE);
  //     		}
  //       } else {
  //     		    return json_encode(array('response' => false, 'description' => 'Не верный формат телефона, присутсвуют посторонние символы'),JSON_UNESCAPED_UNICODE);
  //     	}
  //
  //   }
  //
  //
  // }
  //
  // // Обновление активности аккаунта
  // public function update_activity($hash_user_or_id) {
  //     global $database;
  //
  //     $today = date("Y-m-d H:i:s");
  //
  //     if (is_numeric($hash_or_id)) {
  //       $upd_activity_user = $database->prepare("UPDATE $this->main_users SET last_activity = :last_activity WHERE id = :id");
  //       $upd_activity_user->bindParam(':id', $hash_user_or_id, PDO::PARAM_INT);
  //     } else {
  //       $upd_activity_user = $database->prepare("UPDATE $this->main_users SET last_activity = :last_activity WHERE hash = :hash");
  //       $upd_activity_user->bindParam(':hash', $hash_user_or_id, PDO::PARAM_STR);
  //     }
  //     $upd_activity_user->bindParam(':last_activity', $today, PDO::PARAM_STR);
  //     $check_upd_activity_user = $upd_activity_user->execute();
  //     $count = $upd_activity_user->rowCount();
  //
  //     if ($count) {
  //         return json_encode(array('response' => true, 'description' => 'Активность пользователя обновлена'),JSON_UNESCAPED_UNICODE);
  //     } else {
  //         return json_encode(array('response' => false, 'description' => 'Ошибка обновления активности пользователя'),JSON_UNESCAPED_UNICODE);
  //     }
  //
  // }
  //
  // // отправка смс СНЯТЬ ТЕСТОВЫЙ РЕЖИМ ПРИ СОЗДАНИИ ИНТЕРФЕЙСА ДЛЯ АДМИНОВ
  // public function sistem_sms($phone,$text) {
  //   global $database;
  //
  //   $code_sms = $this->get_global_settings('api_smsru_key');
  //   $vowels = array("(", ")", "+", "_", "-", " ");
  //   $phone = str_replace($vowels, "", $phone);
  //   $body = file_get_contents("https://sms.ru/sms/send?api_id=".$code_sms."&to=".$phone."&msg=".urlencode(iconv("windows-1251","utf-8",$text))."&json=1");
  //   $json = json_decode($body);
  //
  //   if ($json) {
  //       if ($json->status == "OK") {
  //           return json_encode(array('response' => true, 'description' => 'СМС сообщении успешно отпралено', 'data' => $json),JSON_UNESCAPED_UNICODE);
  //       } else {
  //           return json_encode(array('response' => false, 'description' => 'Ошибка отправки смс, пожалуйста попробуйте чуть позже', 'data' => $json),JSON_UNESCAPED_UNICODE);
  //       }
  //   } else {
  //           return json_encode(array('response' => false, 'description' => 'Системная ошибка отправки СМС сообщения', 'data' => $json),JSON_UNESCAPED_UNICODE);
  //
  //   }
  //
  // }
  //
  // // функция регистрации пользователя
  // public function base_register_user($email,$password,$phone,$name,$last_name,$session_id,$ip) {
  //   global $database;
  //
  //       $check_email = $database->prepare("SELECT * FROM $this->main_users WHERE email = :email OR phone = :phone");
  //       $check_email->bindParam(':email', $email, PDO::PARAM_STR);
  //       $check_email->bindParam(':phone', $phone, PDO::PARAM_STR);
  //       $check_email->execute();
  //       $user = $check_email->fetch(PDO::FETCH_OBJ);
  //
  //       if ($user) {
  //             if ($user->email == $email) {
  //                   return json_encode(array('response' => false, 'description' => 'Пользователь с данным email уже зарегистрирован'),JSON_UNESCAPED_UNICODE);
  //             } else {
  //                   return json_encode(array('response' => false, 'description' => 'Данный телефон привязан к другому аккаунту'),JSON_UNESCAPED_UNICODE);
  //             }
  //       }
  //
  //
  //       $password = password_hash($password, PASSWORD_DEFAULT);
  //       $default = '';
  //       $default_int = 0;
  //       $hash = md5($email.$name.$last_name.$password);
  //       $DOB = '0000-00-00';
  //
  //       $session_refer = $database->prepare("SELECT * FROM $this->user_referer WHERE session_id = :session_id OR ip = :ip ORDER BY date_record DESC LIMIT 1");
  //       $session_refer->bindParam(':session_id', $session_id, PDO::PARAM_STR);
  //       $session_refer->bindParam(':ip', $ip, PDO::PARAM_STR);
  //       $session_refer->execute();
  //       $user_session_refer = $session_refer->fetch(PDO::FETCH_OBJ);
  //
  //       if ($user_session_refer) {
  //         $first_referer = parse_url($user_session_refer->referer, PHP_URL_HOST);
  //       } else {
  //         $first_referer = '';
  //       }
  //
  //       $today = date("Y-m-d H:i:s");
  //       $recocery_link = md5($hash);
  //       $status = 'not active';
  //       $role = 'user';
  //       $data_adres = json_decode($this->iplocate($this->get_ip()));
  //       $adres = $data_adres->location->unrestricted_value;
  //       if (!$adres) {
  //           $adres = '';
  //       }
  //
  //       $new_uruser = $database->prepare("INSERT INTO $this->main_users (email,password,phone,name,last_name,second_name,DOB,photo,adres,inn,passport_id,id_entity,position,hash,first_referer,reg_date,last_activity,recovery_link,status,role) VALUES (:email,:password,:phone,:name,:last_name,:second_name,:DOB,:photo,:adres,:inn,:passport_id,:id_entity,:position,:hash,:first_referer,:reg_date,:last_activity,:recovery_link,:status,:role)");
  //       $new_uruser->bindParam(':email', $email, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':password', $password, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':phone', $default, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':name', $name, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':last_name', $last_name, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':second_name', $default, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':DOB', $DOB, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':photo', $default, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':adres', $adres, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':inn', $default_int, PDO::PARAM_INT);
  //       $new_uruser->bindParam(':passport_id', $default_int, PDO::PARAM_INT);
  //       $new_uruser->bindParam(':id_entity', $default_int, PDO::PARAM_INT);
  //       $new_uruser->bindParam(':position', $default, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':hash', $hash, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':first_referer', $first_referer, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':reg_date', $today, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':last_activity', $today, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':recovery_link', $recocery_link, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':status', $status, PDO::PARAM_STR);
  //       $new_uruser->bindParam(':role', $role, PDO::PARAM_STR);
  //       $new_uruser->execute();
  //       $count = $new_uruser->rowCount();
  //       $id_new_user = $database->lastInsertId();
  //
  //       if ($count) {
  //             $_SESSION["key_user"] = $hash;
  //             return json_encode(array('response' => true, 'description' => 'Пользователь успешно зарегистрирован'),JSON_UNESCAPED_UNICODE);
  //       } else {
  //             return json_encode(array('response' => false, 'description' => 'Пользователь не зарегистрирован, попробуйте позже'),JSON_UNESCAPED_UNICODE);
  //       }
  //
  // }
  //
  // // функция отсылки письма об активации аккаунта
  // public function send_email_activation($hash) {
  //     global $database;
  //
  //
  //     $statement = $database->prepare("SELECT * FROM $this->main_users WHERE hash = :hash");
  //     $statement->bindParam(':hash', $hash, PDO::PARAM_STR);
  //     $statement->execute();
  //     $user = $statement->fetch(PDO::FETCH_OBJ);
  //
  //     if (!$user) {
  //         return json_encode(array('response' => false, 'description' => 'Пользователь не найден'),JSON_UNESCAPED_UNICODE);
  //     }
  //
  //     $content =  'Здравствуйте, '.$user->name.' '.$user->second_name.'<br>';
  //     $content .= 'Активация аккаунта на сайте LPM-connect<br>';
  //     $content .= '<a href="https://'.$_SERVER['SERVER_NAME'].'/general/actions/activate_account?link='.$user->hash.'">https://'.$_SERVER['SERVER_NAME'].'/general/actions/activate_account?link='.$user->hash.'</a>';
  //     $content .= '<br></br> После активации аккаунта Вы сможете пользоваться всеми доступными Вам функциями';
  //
  //     $tema = 'Активация аккаунта на сайте';
  //
  //     $check_mail = $this->send_email_user($user->email,$tema,$content);
  //
  //     if (json_decode($check_mail)->response) {
  //         return json_encode(array('response' => true, 'description' => 'Письмо для активации аккаунта успешно выслано на email '.$user->email),JSON_UNESCAPED_UNICODE);
  //     } else {
  //         return $check_mail;
  //     }
  //
  // }
  //
  // // функция активации аккаунта
  // public function email_activation($hash) {
  //     global $database;
  //
  //     $old_status = 'not active';
  //
  //     $statement = $database->prepare("SELECT * FROM $this->main_users WHERE hash = :hash AND status = :status");
  //     $statement->bindParam(':hash', $hash, PDO::PARAM_STR);
  //     $statement->bindParam(':status', $old_status, PDO::PARAM_STR);
  //     $statement->execute();
  //     $user = $statement->fetch(PDO::FETCH_OBJ);
  //
  //     if (!$user) {
  //         return json_encode(array('response' => false, 'description' => 'Пользователь не зарегистрирован'),JSON_UNESCAPED_UNICODE);
  //         exit();
  //     }
  //
  //     $status = 'active';
  //
  //     $activate_new_user = $database->prepare("UPDATE $this->main_users SET status = :status WHERE id = :id_user");
  //     $activate_new_user->bindParam(':status', $status, PDO::PARAM_STR);
  //     $activate_new_user->bindParam(':id_user', $user->id, PDO::PARAM_INT);
  //     $check_activate_new_user = $activate_new_user->execute();
  //     $count = $activate_new_user->rowCount();
  //
  //     if ($count) {
  //           return json_encode(array('response' => true, 'description' => 'Аккаунт пользователя активирован'),JSON_UNESCAPED_UNICODE);
  //     }
  //     else {
  //           return json_encode(array('response' => false, 'description' => 'Не удалось активировать аккаунт, попробуйте позже'),JSON_UNESCAPED_UNICODE);
  //     }
  //
  // }
  //
  // // Функция прохождения капчи
  // public function validate_recaptcha($captcha) {
  //
  //     $secretKey = $this->get_global_settings('google_recaptacha_secret');
  //     $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretKey).'&response='.urlencode($captcha);
  //     $response = file_get_contents($url);
  //     $responseKeys = json_decode($response,true);
  //     if($responseKeys["success"]) {
  //           return json_encode(array('response' => true, 'description' => 'Капча успешно пройдена'),JSON_UNESCAPED_UNICODE);
  //     } else {
  //           return json_encode(array('response' => false, 'description' => 'Ошибка, капча не была пройдена'),JSON_UNESCAPED_UNICODE);
  //     }
  //
  // }




}

?>
