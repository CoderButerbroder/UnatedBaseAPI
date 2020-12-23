<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Детали юр.лица - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php


$data_company_str = $settings->get_data_entity_inn(trim($_GET["inn"]));

$data_company = json_decode($data_company_str);


if (!$data_company->response) {
  //надо подумать что делать в таких случаях >_<
  echo "<h1>error</h1>";
  exit();
}

function get_data_usr_entity($id_entity) {
  global $database;

  $check_user_data = $database->prepare("SELECT * FROM `MAIN_users` WHERE id_entity = :id_entity");
  $check_user_data->bindParam(':id_entity', $id_entity, PDO::PARAM_INT);
  $check_user_data->execute();
  $user = $check_user_data->fetch(PDO::FETCH_OBJ);

  if($user){
    return json_encode(array('response' => true, 'data' => $user, 'description' => 'Данные пользователя отправлены'),JSON_UNESCAPED_UNICODE);
  } else {
    return json_encode(array('response' => false, 'data' => $user, 'description' => 'Данных нет'),JSON_UNESCAPED_UNICODE);
  }
}

$data_user = json_decode(get_data_usr_entity($data_company->data->id));

$data_fns_arr = json_decode($data_company->data->data_fns);
$data_fns = array_pop($data_fns_arr->items);

$flag_company_ip_ul = (isset($data_fns->ЮЛ)) ? true : false;


$false_data = 'Данные отсутствуют';
/* для технологий.. >_> */
$arr_BM = [];
$arr_NFP = [];
$arr_NTI = [];
$arr_TECH = [];
$arr_CST = [];

$user_tech_field_arr = json_decode($data_company->data->technology);

foreach ($user_tech_field_arr as $key_field_arr  => $value_field_arr) {
    $pieces_code = explode("-", $value_field_arr->Code);
    if($pieces_code[0] == 'BM') {
      array_push($arr_BM, $value_field_arr);
    }
    if($pieces_code[0] == 'NFP') {
      array_push($arr_NFP, $value_field_arr);
    }
    if($pieces_code[0] == 'NTI') {
      array_push($arr_NTI, $value_field_arr);
    }
    if($pieces_code[0] == 'TECH') {
      array_push($arr_TECH, $value_field_arr);
    }
    if($pieces_code[0] == 'CST') {
      array_push($arr_CST, $value_field_arr);
    }
}


if(count($arr_BM) == 0) { $arr_BM[0] = (object) array('Name' => $false_data); }
if(count($arr_NFP) == 0) { $arr_NFP[0] = (object) array('Name' => $false_data); }
if(count($arr_NTI) == 0) { $arr_NTI[0] = (object) array('Name' => $false_data); }
if(count($arr_TECH) == 0) { $arr_TECH[0] = (object) array('Name' => $false_data); }
if(count($arr_CST) == 0) { $arr_CST[0] = (object) array('Name' => $false_data); }


//для статуса сколково
$skolkovo_fond = file_get_contents("https://crmapi.sk.ru/api/Public/GetMembers");
$test_json = json_decode($skolkovo_fond);
$status_skolkovo = false;
foreach ($test_json as $key) {
      if ($key->Inn == $data_company->data->inn) {
          $status_skolkovo = true;
          break;
      }
}

//для подсчета доходов с печени
$out_stateSupport = $settings->IPCHAIN_entity_inner_join($data_company->data->inn);

?>


<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="#">Юр. лица</a></li>
    <li class="breadcrumb-item active" aria-current="page">Детали юр. лица <?php echo ($flag_company_ip_ul) ? $data_fns->ЮЛ->НаимСокрЮЛ : $data_fns->ИП->ФИОПолн ; ?></li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="container-fluid" style="background: #ffffff; border-radius: 3px;">
            <?php // 1 строка с основной информацией и показателей ФНС ?>
            <div class="row border-bottom">
                <?php // блок основной информации ?>
                <div class="col-lg-6 border-right p-4">
                  <?php // Заголовок и кнопка редактирвоания  ?>
                  <div class="row p-1">
                    <div class="col-sm-9">
                      <h5>Основная информация</h5>
                    </div>
                    <div class="col-sm-3 text-right" style="padding-top: .05rem">

                    </div>
                  </div>
                  <?php // Основная информация о компании  ?>
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-3">
                      <p><b>ИНН:</b></p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <? echo $data_company->data->inn ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-3">
                      <p><b>ОГРН:</b></p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <? echo ($flag_company_ip_ul) ? $data_fns->ЮЛ->ОГРН : $data_fns->ИП->ОГРНИП ; ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-3">
                      <p><b>КПП:</b></p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <? echo ($flag_company_ip_ul) ? $data_fns->ЮЛ->КПП : $false_data ; ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-3">
                      <p><b>Код ОКВЭД:</b></p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <? echo ($flag_company_ip_ul) ? $data_fns->ЮЛ->ОснВидДеят->Код : $data_fns->ИП->ОснВидДеят->Код ; ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-3">
                      <p><b>Сайт:</b></p>
                    </div>
                    <div class="col-sm-9">
                      <a style="color: #000" href="<? echo (trim($data_company->data->site) != '' && $data_company->data->site != NULL) ? $data_company->data->site : $false_data ?>">
                        <? echo (trim($data_company->data->site) != '' && $data_company->data->site != NULL) ? $data_company->data->site : $false_data ?></a>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-3">
                      <p><b>Адрес:</b></p>
                    </div>
                    <div class="col-sm-9">
                      <?php
                        if(trim($data_company->data->additionally) != '' && $data_company->data->additionally != NULL) {
                          $arr_json_adr = json_decode($data_company->data->additionally);
                          if(isset($arr_json_adr->post_kod)) { echo $arr_json_adr->post_kod.', '; } echo $arr_json_adr->adr.', '.$arr_json_adr->house;
                        } else {
                          echo ($flag_company_ip_ul) ? $data_fns->ЮЛ->Адрес->Индекс." ".$data_fns->ЮЛ->Адрес->АдресПолн : $data_fns->ИП->Адрес->Индекс." ".$data_fns->ИП->Адрес->АдресПолн ;
                        }
                       ?>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-3">
                      <p><b>Отрасль:</b></p>
                    </div>
                    <div class="col-sm-9">
                      <div id="technology_branch"></div>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-3">
                      <p><b>Технологии:</b></p>
                    </div>
                    <div class="col-sm-9">
                      <div id="technology_TECH"></div>
                    </div>
                  </div>
                </div>

                <?php // блок ключевые показатели ФНС ?>
                <div class="col-lg-6 p-4">
                  <?php // Заголовок и кнопка редактирвоания  ?>
                  <div class="row p-1 ">
                    <div class="col-sm-9">
                      <h5>Ключевые показатели</h5>
                    </div>
                    <div class="col-sm-3 text-right" style="padding-top: .05rem">
                      <h5><span class="badge pr-3 pl-3" style="background: #2079cf; color: #fff">ФНС</span></h5>
                    </div>
                  </div>
                  <?php // Основная информация о компании  ?>
                  <div class="row pl-1">
                    <div class="col-sm-8">
                      <p><b>Выручка за год:</b></p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <?
                          if ($data_fns->ЮЛ->ОткрСведения->СумДоход) {echo $data_fns->ЮЛ->ОткрСведения->СумДоход.' руб.';}
                          else {echo $false_data;}
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-8">
                      <p><b>Количество сотрудников:</b></p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <?
                         if ($data_fns->ЮЛ->ОткрСведения->КолРаб) {echo $data_fns->ЮЛ->ОткрСведения->КолРаб;}
                         else {echo $false_data;}
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-8">
                      <p><b>Специальные налоговые режимы:</b></p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <?
                        if ($data_fns->ЮЛ->ОткрСведения->СведСНР) {echo $data_fns->ЮЛ->ОткрСведения->СведСНР;}
                        else {echo $false_data;}
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-md-12">
                      <h5>Налоги или сборы (последний год)</h5>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p><b>Сумма уплаченных налогов или сборов:</b></p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <?
                        if ($data_fns->ЮЛ->ОткрСведения->Налоги) {
                          $nalog = 0;
                          for ($i=0; $i < count($data_fns->ЮЛ->ОткрСведения->Налоги); $i++) {
                              $nalog = $nalog + $data_fns->ЮЛ->ОткрСведения->Налоги[$i]->СумУплНал;
                          }
                          echo $nalog.' руб.';
                        }
                        else {echo $false_data;}
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1">
                    <div class="col-sm-8">
                      <p><b>Общая сумма недоимки по налогу, пени и штрафу:</b></p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <?
                        if ($data_fns->ЮЛ->ОткрСведения->Налоги) {
                          $peni = 0;
                          for ($i=0; $i < count($data_fns->ЮЛ->ОткрСведения->Налоги); $i++) {
                              $peni = $peni + $data_fns->ЮЛ->ОткрСведения->Налоги[$i]->ОбщСумНедоим;
                          }
                          echo $peni.' руб.';
                        }
                        else {echo $false_data;}
                        ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <?php /* конец блока с общ информацией */ ?>

              <?php // 2 строка с пользователем и нти ?>
              <div class="row border-bottom">
                  <?php // блок основной информации ?>
                  <div class="col-lg-6 border-right p-4">
                      <?php // Заголовок и кнопка редактирвоания  ?>
                      <div class="row p-1">
                          <div class="col-sm-9">
                            <h5>Контактное лицо <?php echo ($data_user->response) ? "" : "Не зарегистрированно в системе ЕБД" ;?></h5>
                          </div>
                          <div class="col-sm-3 text-right" style="padding-top: .05rem">

                          </div>
                      </div>
                      <?php // Основная информация о пользователе  ?>
                      <div class="row pl-1 pt-3">
                          <div class="col-sm-auto">
                            <img style="width: 180px; height: auto;" src="/assets/images/male-user-profile-picture.png" />
                          </div>
                          <div class="col-sm-auto">
                              <p><? echo ($data_user->data->name) ? $data_user->data->last_name." ".$data_user->data->name." ".$data_user->data->second_name : $false_data; ?></p>
                              <p><? echo ($data_user->data->position) ? $data_user->data->position : $false_data; ?></p>
                              <p><? echo ($data_user->data->phone) ? $data_user->data->phone : $false_data; ?></p>
                              <p><? echo ($data_user->data->email) ? $data_user->data->email : $false_data; ?></p>
                              <p>Tboil ID: <a href="https://tboil.spb.ru/" style="color: #000;"><? echo ($data_user->data->id_tboil) ? $data_user->data->id_tboil : $false_data; ?></a></p>
                          </div>
                      </div>
                  </div>

                  <?php // блок ключевые показатели ФНС ?>
                  <div class="col-lg-6 p-4">
                          <?php // Заголовок и кнопка редактирвоания  ?>
                          <div class="row p-1">
                              <div class="col-sm-9">
                                <h5>Национальная технологическая инициатива</h5>
                              </div>
                              <div class="col-sm-3 text-right" style="padding-top: .05rem">

                              </div>
                          </div>
                          <?php // Основная информация о компании  ?>
                          <div class="row pl-1 pt-3">
                              <div class="col-sm-auto ">
                                <p><b>Рынки НТИ:</b></p>
                              </div>
                              <div class="col-sm-auto">
                                  <div id="technology_NTI"></div>
                              </div>
                          </div>
                          <div class="row pl-1">
                              <div class="col-sm-auto">
                                <p><b>Национальные и федеральные проекты:</b></p>
                              </div>
                              <div class="col-sm-auto">
                                  <div id="technology_NFP"></div>
                              </div>
                          </div>
                          <div class="row pl-1">
                              <div class="col-sm-auto">
                                <p><b>Цифровые сквозные технологии:</b></p>
                              </div>
                              <div class="col-sm-auto">
                                  <div id="technology_CST"></div>
                              </div>
                          </div>
                  </div>
                </div>

                <?php // 3 строка с информацией по сколково ?>
                <div class="row border-bottom">
                  <?php // блок основной информации ?>
                  <div class="col-lg-6 border-right p-4">
                    <?php // Заголовок и кнопка редактирвоания  ?>
                    <div class="row p-1">
                      <div class="col-sm-12">
                        <h5>Фонд Сколково</h5>
                      </div>
                    </div>
                    <?php // Основная информация о компании  ?>
                    <div class="row pl-1">
                      <div class="col-sm-6">
                        <p><b>Резидент Сколково:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <? echo ($status_skolkovo) ? 'Резидент' : 'Не резидент'; ?>
                        </p>
                      </div>
                    </div>
                    <div class="row pl-1">
                      <div class="col-sm-6">
                        <p><b>Сумма полученных грантов:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <?php
                            // 200000001 = соклково
                            // 200000002 = фси
                            // пустые = ФСИ
                            $data_grant = json_decode($out_stateSupport)->data;
                            $scolkovo_grant_sum = 0;
                            for ($i=0; $i < count($data_grant); $i++) {
                                if ($data_grant[$i]->typeId == '200000001') {
                                  $scolkovo_grant_sum = $scolkovo_grant_sum + $data_grant[$i]->Sum;
                                }
                            }
                            echo $scolkovo_grant_sum.' руб.';
                            ?>
                        </p>
                      </div>
                    </div>
                  </div>

                  <?php // блок ключевые показатели ФНС ?>
                  <div class="col-lg-6 p-4">
                    <?php // Заголовок и кнопка редактирвоания  ?>
                    <div class="row p-1 ">
                      <div class="col-sm-12">
                        <h5>Фонд содействия инновациям</h5>
                      </div>
                    </div>
                    <?php // Основная информация о компании  ?>
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-6">
                        <p><b>Программы:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <?php
                          // 200000001 = соклково
                          // 200000002 = фси
                          // пустые = ФСИ
                          // var_dump(json_decode($out_stateSupport)->data);
                          $data_grant = json_decode($out_stateSupport)->data;
                          //
                          $fsi_grant= '';
                          for ($i=0; $i < count($data_grant); $i++) {
                              if ($array_document_fsi[stristr($data_grant[$i]->id_Support, '-', true)]) {
                                $fsi_grant .= '<p class="badge mr-2 h5" style="background: #2079cf; color: #fff; word-wrap: break-word">'.$array_document_fsi[stristr($data_grant[$i]->id_Support, '-', true)].'</p>';
                                // $fsi_grant_sum = $fsi_grant_sum + intval($data_grant[$i]->Sum);
                              }
                          }
                          echo $fsi_grant;
                        ?>
                      </div>
                    </div>
                    <div class="row pl-1">
                      <div class="col-sm-6">
                        <p><b>Общая сумма средств грантов:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <?php
                          // // 200000001 = соклково
                          // // 200000002 = фси
                          // // пустые = ФСИ
                          // // var_dump(json_decode($out_stateSupport)->data);
                          $data_grant = json_decode($out_stateSupport)->data;
                          $fsi_grant_sum = 0;
                          for ($i=0; $i < count($data_grant); $i++) {
                              if ($data_grant[$i]->typeId == '200000002' || trim($data_grant[$i]->typeId) == '') {
                                $fsi_grant_sum = $fsi_grant_sum + intval($data_grant[$i]->Sum);
                              }
                          }
                          echo $fsi_grant_sum.' руб.';
                          ?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            <div class="card">
              <div class="card-header" role="tab" id="heading_card_fns_data">
                <h6 class="mb-0">
                  <a class="" data-toggle="collapse" href="#collapse_card_fns_data" role="button" aria-expanded="false" aria-controls="collapse_card_fns_data">
                    Данные Компании Из ФНС
                  </a>
                </h6>
              </div>
              <div id="collapse_card_fns_data" class="collapse" >
                <div class="card-body">
                  <div class="row">

                  <?php
                    $html_icon_popover_start = '<td><svg xmlns="http://www.w3.org/2000/svg" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="';
                    $html_icon_popover_end = '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg></td>';
                    //var_dump($data_fns);
                    echo json_encode($data_fns, JSON_UNESCAPED_UNICODE);
                    if (isset($data_fns->ИП)) {
                    //заполняем таблицу для ИП
                    //var_dump($data_fns->ИП);

    $arr_fns_descr_IP = array(
      'ФИОПолн' => (object) array( 'alias' => 'ФИО', 'descr' => 'Фамилия Имя Отчество индивидуального предпринимателя'),
      'ИННФЛ' => (object) array( 'alias' => 'ИНН', 'descr' => 'ИНН физического лица'),
      'ОГРНИП' => (object) array( 'alias' => 'ОГРН', 'descr' => 'ОГРН ИП'),
      'ДатаОГРН' => (object) array( 'alias' => 'Дата присвоения', 'descr' => 'Дата внесения соответствующей записи в ЕГР' ),
      'ДатаРег' => (object) array( 'alias' => 'Дата Регистрации', 'descr' => 'Дата регистрации ИП в формате YYYY-MM-DD'),
      'ВидИП' => (object) array( 'alias' => 'Вид ИП', 'descr' => 'Индивидуальный предприниматель или глава крестьянского фермерского хозяйства'),
      'Пол' => (object) array( 'alias' => 'Пол', 'descr' => 'Мужской или Женский'),
      'ВидГражд' => (object) array( 'alias' => 'Вид Гражданства', 'descr' => 'Гражданин РФ или Иностранный гражданин'),
      'Статус' => (object) array( 'alias' => 'Статус', 'descr' => 'Статус ИП. Например, «Действующее», Прекратило деятельность и др.'),
      'СтатусДата' => (object) array( 'alias' => 'Актуальность Статуса', 'descr' => 'Дата актуальности статуса в формате YYYY-MM-DD'),
      'СпОбрЮЛ' => (object) array( 'alias' => 'Способ обр. юр. лица', 'descr' => 'Способ образования юридического лица'),
      'ДатаПрекр' => (object) array( 'alias' => 'Дата прекращения деятельности ИП', 'descr' => 'Дата прекращения деятельности ЮЛ (ИП) (если деятельность прекращена) в формате YYYY-MM-DD'),
      'НО' => (object) array( 'alias' => 'Налоговые Органы', 'descr' => 'Сведения о налоговых органах'),
      'Рег' => (object) array( 'alias' => 'Регистратор', 'descr' => 'Код и наименование регистрирующего (налогового) органа), внесшем запись о юридическом лице'),
      'РегДата' => (object) array( 'alias' => 'Дата Регистрации', 'descr' => 'Дата внесения записи о регистрации'),
      'Учет' => (object) array( 'alias' => 'Налог. Орган', 'descr' => 'Код и наименование налогового органа), в котором юридическое лицо состоит (для ЮЛ, прекративших деятельность - состояло) на учете'),
      'УчетДата' => (object) array( 'alias' => 'Дата постановки', 'descr' => 'Дата постановки на учет в налоговом органе'),
      'ПФ' => (object) array( 'alias' => 'Пенсионный фонд', 'descr' => 'Сведения о регистрации юридического лица в качестве страхователя в территориальном органе Пенсионного фонда Российской Федерации'),
      'РегНомПФ' => (object) array( 'alias' => 'Регистрационный номер', 'descr' => 'Регистрационный номер в территориальном органе Пенсионного фонда Российской Федерации'),
      'ДатаРегПФ' => (object) array( 'alias' => 'Дата регистрации', 'descr' => 'Дата регистрации юридического лица в качестве страхователя'),
      'КодПФ' => (object) array( 'alias' => 'Код ПФ', 'descr' => 'Код и наименование территориального органа Пенсионного фонда Российской Федерации'),
      'ФСС' => (object) array( 'alias' => 'ФСС', 'descr' => 'Сведения о регистрации юридического лица в качестве страхователя в территориальном органе Пенсионного фонда Российской Федерации'),
      'РегНомФСС' => (object) array( 'alias' => 'Регистрациионный номер', 'descr' => 'Регистрационный номер в исполнительном органе Фонда социального страхования Российской Федерации'),
      'ДатаРегФСС' => (object) array( 'alias' => 'Дата регистрации', 'descr' => 'Дата регистрации юридического лица в качестве страхователя'),
      'КодФСС' => (object) array( 'alias' => 'Исполнительный Орган', 'descr' => 'Код и наименование исполнительного органа Фонда социального страхования Российской Федерации'),
      'Адрес' => (object) array( 'alias' => 'Адрес', 'descr' => 'Сведения об адресе в РФ), внесенные в ЕГРЮЛ'),
      'КодРегион' => (object) array( 'alias' => 'Код субъекта РФ', 'descr' => 'Код субъекта Российской Федерации'),
      'Индекс' => (object) array( 'alias' => 'Индекс', 'descr' => 'Индекс'),
      'АдресПолн' => (object) array( 'alias' => 'Адрес Полный', 'descr' => 'Полный адрес (Регион,Район,Город,Населенный пункт,Улица,Дом,Корпус,Квартира)'),
      'E-mail' => (object) array( 'alias' => 'E-mail', 'descr' => 'Адрес электронной почты'),
      'ОснВидДеят' => (object) array( 'alias' => 'Вид деятельности', 'descr' => 'Сведения об основном виде деятельности'),
      'Код' => (object) array( 'alias' => 'Код', 'descr' => 'Код по Общероссийскому классификатору видов экономической деятельности'),
      'ДопВидДеят' => (object) array( 'alias' => 'Сведения о доп. вид. деятельности', 'descr' => 'Сведения о дополнительных видах деятельности'),
      'Текст' => (object) array( 'alias' => 'Наименование вида деятельности', 'descr' => 'Наименование вида деятельности по Общероссийскому классификатору видов экономической деятельности'),
      'СПВЗ' => (object) array( 'alias' => 'Причина внесения', 'descr' => 'Сведения о причинах внесения записей в реестр ЕГРИП'),
      'Дата' => (object) array( 'alias' => 'Дата внесения', 'descr' => 'Дата внесения записи в ЕГРЮЛ'),
      'Лицензии' => (object) array( 'alias' => 'Лицензии', 'descr' => 'Сведения о лицензиях,выданных ИП'),
      'НомерЛиц' => (object) array( 'alias' => 'Серия и номер', 'descr' => 'Серия и номер лицензии'),
      'ВидДеятельности' => (object) array( 'alias' => 'Наименовние регистратора', 'descr' => 'Наименование лицензируемого вида деятельности, на который выдана лицензия'),
      'ДатаНачала' => (object) array( 'alias' => 'Дата начала действия лицензии', 'descr' => 'Дата начала действия лицензии'),
      'ДатаОконч' => (object) array( 'alias' => 'Дата окончания действия лицензии', 'descr' => 'Дата окончания действия лицензии'),
      'МестоДейств' => (object) array( 'alias' => 'Сведения об адресах', 'descr' => 'Сведения об адресах осуществления лицензируемого вида деятельности (если несколько, то адреса разделяются знаком вертикальной черты |)'),
      'История' => (object) array( 'alias' => 'История', 'descr' => 'Исторические сведения о компании'),
      'Период актуальности данных' => (object) array('alias' => 'Актуальность', 'descr' => 'В формате YYYY-MM-DD ~ YYYY-MM-DD (начальная и конечная даты, разделенные знаком ~ «тильда»'));

    /*
        честно говоря мне оч стыдно за это глиномесие..
    */

    function paint_table_ip($arr)
    {
      global $html_icon_popover_start,$html_icon_popover_end,$arr_fns_descr_IP;
      foreach ($arr as $key => $value) {

          if ($key == 'Адрес') {
            //var_dump($value);

            if(!isset($value->КодРегион)) {
              echo '<div class="col-md-12">
                      <div class="row pl-1 pt-3">
                        <div class="col-sm-3"><p><b>Адрес:</b></p></div>
                        <div class="col-sm-9">';
              foreach ($value as $key_adres => $value_adres) {
                echo '<span class="badge mr-2" style="background: #727cf5; color: #fff; word-wrap: break-word" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="'.$value_adres->АдресПолн.'" >'.$key_adres.'</span>';
              }
              echo '</div></div></div>';
              continue;
            }
          }

          echo '<div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="col-sm-3">
              <p><b>'.$arr_fns_descr_IP[$key]->alias.':</b></p>
            </div>
            <div class="col-sm-9">
              ';

            if (is_object($value)) {
              paint_table_ip($value);
            }
            if (is_array($value)) {
              foreach ($value as $key_value => $value_value) {
                if($key == 'ДопВидДеят') echo '<span class="badge mr-2" style="background: #727cf5; color: #fff; word-wrap: break-word" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="'.$value_value->Текст.' '.date('d.m.Y', strtotime($value_value->Дата)).'" >'.$value_value->Код.'</span>';
                if($key == 'СПВЗ') echo '<span class="badge mr-2" style="background: #727cf5; color: #fff; word-wrap: break-word" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="'.$value_value->Текст.'" >'.date('d.m.Y', strtotime($value_value->Дата)).'</span>';
                if($key == 'Лицензии') echo '<span class="badge mr-2" style="background: #727cf5; color: #fff; word-wrap: break-word" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="'.$value_value->ВидДеятельности.' '.$value->ДатаНачала.' '.$value->ДатаОконч.' '.$value->МестоДейств.'" >'.$value->НомерЛиц.'</span>';
                if($key == 'Статус') {
                  $temp_name_str = 'Период актуальности данных';
                  echo '<span class="badge mr-2" style="background: #727cf5; color: #fff; word-wrap: break-word" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="'.$arr_fns_descr_IP[$value->$temp_name_str]->descr.'" >'.$value->$temp_name_str.'</span>';
                }
              }
            }
            if (is_string($value)) {
              echo $value;
            }
          echo '</div></div></div>';
      }
    }
    paint_table_ip($data_fns->ИП);
  } else {


  }
                  ?>

                </div>
              </div>
            </div>


          </div>
        </div>
    </div>
</div>

<script>

  $(document).ready(function() {

  });

  function fill_technolagy(str, name){
    //console.log(str);
    if(str.trim() != '' && str.trim() != 'false') {
      arr_data_tech = JSON.parse(str);
      for (var prop in arr_data_tech) {
          if(arr_data_tech[prop]["Name"] != 'Данные отсутствуют') {
            $('#technology_'+name).html($('#technology_'+name).html()+'<text class="badge mr-2 h5" style="background: #727cf5; color: #fff; word-wrap: break-word">'+arr_data_tech[prop]["Name"]+'</text>');
          } else {
            $('#technology_'+name).html($('#technology_'+name).html()+'<text>'+arr_data_tech[prop]["Name"]+'</text>');
          }
      }
    }
  };

  fill_technolagy('<?php echo json_encode($arr_BM, JSON_UNESCAPED_UNICODE); ?>', 'BD');
  fill_technolagy('<?php echo json_encode($arr_NFP, JSON_UNESCAPED_UNICODE); ?>', 'NFP');
  fill_technolagy('<?php echo json_encode($arr_NTI, JSON_UNESCAPED_UNICODE); ?>', 'NTI');
  fill_technolagy('<?php echo json_encode($arr_TECH, JSON_UNESCAPED_UNICODE); ?>', 'TECH');
  fill_technolagy('<?php echo json_encode($arr_CST, JSON_UNESCAPED_UNICODE); ?>', 'CST');
  fill_technolagy('<?php echo $data_company->data->branch; ?>', 'branch');



</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
