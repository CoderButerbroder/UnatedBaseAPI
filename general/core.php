<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/general/DATAroot.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/general/plugins/dadata/DadataClient.php');


class ClassName {



}


class Settings {

  private $gen_settings = 'API_SETTINGS';
  private $users = 'API_USERS';
  private $tokens = 'API_TOKENS';
  private $api_referer = 'API_REFERER';
  private $history = 'API_HISTORY';
  private $user_referer = 'TIME_user_referer';

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
          return json_encode(array('response' => false, 'description' => 'Вы не имеете прав на данный ресурс'),JSON_UNESCAPED_UNICODE);
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
          return json_decode($data_token)->description;
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

                if (json_decode($data_exp)->response) {
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

   return false;
 }

 // Забор данных из ФНС
 public function fns_base($inn,$json = false) {
   global $database;

       $valid_inn = $this->is_valid_inn($inn);

       if (!$valid_inn) {
           return json_encode(array('response' => false, 'description' => 'ИНН не прошел проверку на корректность'),JSON_UNESCAPED_UNICODE);
       }

         $data_fnc = file_get_contents("https://api-fns.ru/api/egr?req=".$inn."&key=".$this->get_global_settings('api_fns_key'));
         $fnc = json_decode($data_fnc);

         $chek_inn = $fnc->items[0]->ЮЛ->ИНН;
         $chek_inn2 = $fnc->items[0]->ИП->ИННФЛ;

         if ($chek_inn == '') {
               if ($chek_inn2 == '') {
                   return json_encode(array('response' => false, 'description' => 'ИНН не найден в базе ФНС'),JSON_UNESCAPED_UNICODE);
               }
               else {

                 $add_fns_database = $database->prepare("INSERT INTO $this->fns_database (inn,info) VALUES (:inn,:info)");
                 $add_fns_database->bindParam(':inn', $inn, PDO::PARAM_STR);
                 $add_fns_database->bindParam(':info', $data_fnc, PDO::PARAM_STR);
                 $check_add = $add_fns_database->execute();
                 if (!$check_add) {
                       return json_encode(array('response' => false, 'description' => 'Внутреняя ошибка записи данных из ФНС, попробуйте позже'),JSON_UNESCAPED_UNICODE);
                 }
                 else {
                       return json_encode(array('response' => true, 'data' => $fnc),JSON_UNESCAPED_UNICODE);
                 }
               }
      }
 }

 // Загрузка данных из ФНС
 public function get_fns_base($inn,$json = false) {
   global $database,$unated_database,$UNATED_BASE_PREFIX__;

   $valid_inn = $this->is_valid_inn($inn);

   if (!$valid_inn) {
       return '615';
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

 // Запись переходов реферов
 public function record_user_referer($session_id,$ip_user,$refer) {
    global $database;


                     $add_fns_database = $database->prepare("INSERT INTO $this->user_referer () VALUES ()");
                     $add_fns_database->bindParam(':inn', $inn, PDO::PARAM_STR);
                     $add_fns_database->bindParam(':info', $data_fnc, PDO::PARAM_STR);
                     $check_add = $add_fns_database->execute();


 }

}

class DaData extends Settings {


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



}



?>
