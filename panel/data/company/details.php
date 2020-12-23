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
                    //echo json_encode($data_fns, JSON_UNESCAPED_UNICODE);
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
    ?>

    <div class="row">
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>ИНН:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->ИНН; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>КПП:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->КПП; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>ОГРН:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->ОГРН; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>НаимСокрЮЛ:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->НаимСокрЮЛ; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>Номер контактного телефона:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->НомТел; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>Адрес электронной почты:</b></p></div>
          <div class="col-sm-9"><?php $temp_obj_fns_str = 'E-mail'; echo $data_fns->ЮЛ->$temp_obj_fns_str; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>НаимПолнЮЛ:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->НаимПолнЮЛ; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>ДатаРег:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->ДатаРег; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>ОКОПФ:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->ОКОПФ; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>Статус:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->Статус; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>СтатусДата:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->СтатусДата; ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>СпОбрЮЛ:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->СпОбрЮЛ; ?></div>
        </div>
      </div>
      <?php if(isset($data_fns->ЮЛ->ДатаПрекр)) echo '
      <div class="col-md-12">
        <div class="row pl-1 pt-3">
          <div class="col-sm-3"><p><b>ДатаПрекр:</b></p></div>
          <div class="col-sm-9"><?php echo $data_fns->ЮЛ->ДатаПрекр; ?></div>
        </div>
      </div>'; ?>
      <!-- НО -->
        <div class="col-md-12">
          <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
          <div class="row pl-1 pt-3">
            <div class="col-sm-3 "><text class="list_fns" list="1"><b>Сведения о налоговых органах:</b></text></div>
            <div class="col-sm-9"></div>
          </div>
        </div>
        <div class="row" id="list_li_fns_1" style="display:none">
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Код и наименование регистрирующего НО:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->НО->Рег; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Дата внесения записи о регистрации:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->НО->РегДата; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Код и наименование НО Учет.:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->НО->Учет; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Дата постановки на учет в НО:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->НО->УчетДата; ?></div>
            </div>
          </div>
        </div>
      <!-- ПФ -->
        <div class="col-md-12">
          <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
          <div class="row pl-1 pt-3">
            <div class="col-sm-3" ><text class="list_fns" list="2"><b>Сведения Пенсионного фонда РФ:</b></text></div>
            <div class="col-sm-9"></div>
          </div>
        </div>
        <div class="row" id="list_li_fns_2" style="display:none">
          <div class="col-md-12 ">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Регистрационный номер:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ПФ->РегНомПФ; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Дата рег. ЮЛ в кач. страхователя:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ПФ->ДатаРегПФ; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Код и наименование терр. Орг. ПФ РФ:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ПФ->КодПФ; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Дата постановки на учет в НО:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ПФ->УчетДата; ?></div>
            </div>
          </div>
        </div>
      <!-- ФСС -->
        <div class="col-md-12">
          <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
          <div class="row pl-1 pt-3">
            <div class="col-sm-3"><text class="list_fns" list="3"><b>Сведения о рег. ЮЛ в качестве страхователя:</b><text></div>
            <div class="col-sm-9">&nbsp;</div>
          </div>
        </div>
        <div class="row" id="list_li_fns_3" style="display:none">
          <div class="col-md-12 " >
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Регистрационный номер:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ФСС->РегНомФСС; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Дата рег. ЮЛ в кач. страхователя:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ФСС->ДатаРегФСС; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Код и наименование терр. Орг. Фонда соц. страхования РФ:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ФСС->КодФСС; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Дата постановки на учет в НО:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ФСС->УчетДата; ?></div>
            </div>
          </div>
        </div>
      <!-- Капитал -->
        <div class="col-md-12" >
          <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
          <div class="row pl-1 pt-3">
            <div class="col-sm-3"><text class="list_fns" list="4"><b>Капитал:</b></text></div>
            <div class="col-sm-9">&nbsp;</div>
          </div>
        </div>
        <div class="row" id="list_li_fns_4" style="display:none">
        <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Вид капитала.:</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Капитал->ВидКап; ?></div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Размер капитала в рублях:</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Капитал->СумКап; ?></div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Код и наименование терр. Орг. Фонда соц. страхования РФ:</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Капитал->КодФСС; ?></div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Дата внесения информации о капитале:</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Капитал->Дата; ?></div>
          </div>
        </div>
      </div>
      <!-- Адрес -->
        <div class="col-md-12">
          <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
          <div class="row pl-1 pt-3">
            <div class="col-sm-3"><p><b>Сведения об адресе в РФ, внесенные в ЕГРЮЛ:</b></p></div>
            <div class="col-sm-9"></div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Код субъекта Российской Федерации:</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Адрес->КодРегион; ?></div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Индекс:</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Адрес->Индекс; ?></div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Полный адрес (Регион, Район, Город, Населенный пункт, Улица, Дом, Корпус, Квартира):</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Адрес->АдресПолн; ?></div>
          </div>
        </div>
       <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Дата внесения информации в ЕГРЮЛ об адресе:</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Адрес->Дата; ?></div>
          </div>
        </div>
        <?php
          if(isset($data_fns->ЮЛ->Адрес->ПризнНедАдресЮЛ)) { ?>
            <!-- Недостоверный адрес -->
              <div class="col-md-12">
                <div class="row pl-1 pt-3">
                  <div class="col-sm-3"><p><b>Сведения о недостоверности адреса:</b></p></div>
                  <div class="col-sm-9">
                    <div class="row">
                      <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                      <div class="col-md-12">
                        <div class="row pl-1 pt-3">
                          <div class="offset-1 col-sm-3"><p><b>Признак недостоверности адреса:</b></p></div>
                          <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Адрес->ПризнНедАдресЮЛ->Код; ?></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="row pl-1 pt-3">
                          <div class="offset-1 col-sm-3"><p><b>Результат:</b></p></div>
                          <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Адрес->ПризнНедАдресЮЛ->Текст; ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        <?php } ?>
        <?php
          if(isset($data_fns->ЮЛ->Адрес->РешИзмАдрес)) { ?>
            <!-- Недостоверный адрес -->
              <div class="col-md-12">
                <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                <div class="row pl-1 pt-3">
                  <div class="col-sm-3"><p><b>Сведения о принятии ЮЛ решения об изм. Адреса:</b></p></div>
                  <div class="col-sm-9">
                    <div class="row">
                      <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                      <div class="col-md-12">
                        <div class="row pl-1 pt-3">
                          <div class="offset-1 col-sm-3"><p><b>Полный адрес нового места нахождения:</b></p></div>
                          <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Адрес->РешИзмАдрес->НовыйАдрес; ?></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="row pl-1 pt-3">
                          <div class="offset-1 col-sm-3"><p><b>Дата решения об изменении адреса:</b></p></div>
                          <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Адрес->РешИзмАдрес->ДатаРеш; ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        <?php } ?>

        <!-- Руководитель -->
          <div class="col-md-12">
            <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
            <div class="row pl-1 pt-3">
              <div class="col-sm-3"><p><b>Сведения о руководителе организации:</b></p></div>
              <div class="col-sm-9"></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Вид должности по справочнику СКФЛЮЛ:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Руководитель->ВидДолжн; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>Наименование должности:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Руководитель->Должн; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>ФИО должностного лица:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Руководитель->ФИОПолн; ?></div>
            </div>
          </div>
         <div class="col-md-12">
            <div class="row pl-1 pt-3">
              <div class="offset-1 col-sm-3"><p><b>ИНН должностного лица:</b></p></div>
              <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Руководитель->ИННФЛ; ?></div>
            </div>
        </div>
        <div class="col-md-12">
             <div class="row pl-1 pt-3">
               <div class="offset-1 col-sm-3"><p><b>ОГРН ИП - управляющего ЮЛ:</b></p></div>
               <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Руководитель->ОГРНИП; ?></div>
             </div>
        </div>
        <?php
          if(isset($data_fns->ЮЛ->Руководитель->ПризнНедДанДолжнФЛ)) { ?>
            <!-- Недостоверный адрес -->
              <div class="col-md-12">
                <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                <div class="row pl-1 pt-3">
                  <div class="col-sm-3"><p><b>Сведения о недостоверности данных о лице, имеющем доверенность ЮЛ:</b></p></div>
                  <div class="col-sm-9">
                    <div class="row">
                      <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                      <div class="col-md-12">
                        <div class="row pl-1 pt-3">
                          <div class="offset-1 col-sm-3"><p><b>Дата начала дисквалификации лица:</b></p></div>
                          <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Руководитель->ПризнНедДанДолжнФЛ->ДатаНачДискв; ?></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="row pl-1 pt-3">
                          <div class="offset-1 col-sm-3"><p><b>Дата окончания дисквалификации лица:</b></p></div>
                          <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Руководитель->ПризнНедДанДолжнФЛ->ДатаОкончДискв; ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        <?php } ?>
        <div class="col-md-12">
          <div class="row pl-1 pt-3">
            <div class="offset-1 col-sm-3"><p><b>Дата внесения в ЕГРЮЛ сведений о рук.:</b></p></div>
            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->Руководитель->Дата; ?></div>
          </div>
        </div>
        <!-- Учредители -->
          <?php if(!empty($data_fns->ЮЛ->Учредители)){
            echo '<div class="col-md-12">
            <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
              <div class="row pl-1 pt-3">
                <div class="col-sm-5"><p><b>Сведения об учредителях (участниках) юридического лица:</b></p></div>
                <div class="col-sm-7"></div>
              </div>
            </div>';

            echo '<div class="col-md-12">
              <div class="row pl-1 pt-3">';
            foreach ($data_fns->ЮЛ->Учредители as $key_uch => $value_uch) {
              if (isset($value_uch->УчрЮЛ)) { ?>
                <div class="offset-1 col-sm-3"><p><b>Наименование ЮЛ сокращенное:</b></p></div>
                <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрЮЛ->НаимСокрЮЛ; ?>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>ОГРН ЮЛ:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->УчрЮЛ->ОГРН; ?></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>ИНН ЮЛ:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->УчрЮЛ->ИНН; ?></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>Статус ЮЛ:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->УчрЮЛ->Статус; ?></div>
                  </div>
                </div>
              <?php }
              if (isset($value_uch->УчрИН)) { ?>
                <div class="offset-1 col-sm-3"><p><b>Сведения об учредителе (участнике) - иностранном юридическом лице:</b></p></div>
                <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрИН->НаимПолнЮЛ; ?>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>ОКСМ:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->УчрИН->ОКСМ; ?></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>Регистрационный номер:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->УчрИН->РегНомер; ?></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>Дата регистрации:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->УчрИН->ДатаРег; ?></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>Адрес (место нахождения) в стране происхождения:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->УчрИН->АдресПолн; ?></div>
                  </div>
                </div>
              <?php }
              if (isset($value_uch->УчрФЛ)) { ?>
                <div class="offset-1 col-sm-3"><p><b>Сведения об учредителе (участнике) - физическом лице:</b></p></div>
                <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрФЛ->ФИОПолн; ?>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>ИНН учредителя:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->УчрФЛ->ИННФЛ; ?></div>
                  </div>
                </div>
              <?php }
              if (isset($value_uch->УчрРФСубМО)) { ?>
                <div class="offset-1 col-sm-3"><p><b>Сведения об учредителе (участнике) - Российской Федерации, субъекте Российской Федерации, муниципальном образовании:</b></p></div>
                <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрРФСубМО; ?>
              <?php }
              if (isset($value_uch->СвОргОсущПр)) { ?>
                <div class="offset-1 col-sm-3"><p><b>Сведения об органе государственной власти, органе местного самоуправления или о юридическом лице, осуществляющем права учредителя (участника:</b></p></div>
                <div class="offset-4 col-sm-8"><?php echo $value_uch->СвОргОсущПр->УчрЮЛ->НаимСокрЮЛ; ?>
                  <div class="col-md-12">
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-4"><p><b>ОГРН ЮЛ:</b></p></div>
                      <div class="col-sm-8"><?php echo $value_uch->СвОргОсущПр->УчрЮЛ->ОГРН; ?></div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-4"><p><b>ИНН ЮЛ:</b></p></div>
                      <div class="col-sm-8"><?php echo $value_uch->СвОргОсущПр->УчрЮЛ->ИНН; ?></div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-4"><p><b>Статус ЮЛ:</b></p></div>
                      <div class="col-sm-8"><?php echo $value_uch->СвОргОсущПр->УчрЮЛ->Статус; ?></div>
                    </div>
                  </div>
            <?php }
              if (isset($value_uch->УчрПИФ)) { ?>
                <div class="offset-1 col-sm-3"><p><b>Сведения о паевом инвестиционном фонде, в состав имущества которого включена доля в уставном капитале:</b></p></div>
                <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрПИФ->НаимСокрЮЛ; ?>
                  <div class="col-md-12">
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-4"><p><b>ОГРН ЮЛ:</b></p></div>
                      <div class="col-sm-8"><?php echo $value_uch->УчрПИФ->ОГРН; ?></div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-4"><p><b>ИНН ЮЛ:</b></p></div>
                      <div class="col-sm-8"><?php echo $value_uch->УчрПИФ->ИНН; ?></div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-4"><p><b>Статус ЮЛ:</b></p></div>
                      <div class="col-sm-8"><?php echo $value_uch->УчрПИФ->Статус; ?></div>
                    </div>
                  </div>
            <?php } ?>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>Номинальная стоимость доли в рублях:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->СуммаУК; ?></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>Размер доли (в процентах):</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->Процент; ?></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-4"><p><b>Дата внесения в ЕГРЮЛ:</b></p></div>
                    <div class="col-sm-8"><?php echo $value_uch->Дата; ?></div>
                  </div>
                </div>
              </div>
           <?php }
             if(isset($value_uch->ПризнНедДанДолжнФЛ)) { ?>
               <!-- Недостоверный адрес -->
                 <div class="col-md-12">
                   <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                   <div class="row pl-1 pt-3">
                     <div class="col-sm-4"><p><b>	Сведения о недостоверности данных об учредителе (участнике):</b></p></div>
                     <div class="col-sm-8">
                       <div class="row">
                         <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                         <div class="col-md-12">
                           <div class="row pl-1 pt-3">
                             <div class="offset-1 col-sm-3"><p><b>Признак недостоверности:</b></p></div>
                             <div class="col-sm-8"><?php echo $value_uch->ПризнНедДанУчр->Код; ?></div>
                           </div>
                         </div>
                         <div class="col-md-12">
                           <div class="row pl-1 pt-3">
                             <div class="offset-1 col-sm-3"><p><b>Результат:</b></p></div>
                             <div class="col-sm-8"><?php echo $value_uch->ПризнНедДанУчр->Текст; ?></div>
                           </div>
                         </div>
                       </div>
                     </div>
                   </div>
                 </div>
           <?php }
           if(isset($value_uch->Залогодержатели)) { ?>
             <!-- Недостоверный адрес -->
               <div class="col-md-12">
                 <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                 <div class="row pl-1 pt-3">
                   <div class="col-sm-4"><p><b>	Сведения о залогодержателе доли:</b></p></div>
                   <div class="col-sm-8">
                     <div class="row">
                       <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>ОГРН:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ОГРН; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>ИНН:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ИНН; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Наименование ЮЛ сокращенное (только для ЮЛ):</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->НаимСокрЮЛ; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Статус ЮЛ:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->Статус; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Фамилия Имя Отчество залогодержателя (ФЛ):</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ФИОПолн; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>ИНН залогодержателя (ФЛ):</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ИННФЛ; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Наименование организации залогодержателя (Иностранное лицо):</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->НаимПолнЮЛ; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>ОКСМ:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ОКСМ; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>ИННФЛ:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ИННФЛ; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Регистрационный номер:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->РегНомер; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Дата регистрации:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ДатаРег; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Адрес (место нахождения) в стране происхождения:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->АдресПолн; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Принимает значение: ЗАЛОГ | ИНОЕ ОБРЕМЕНЕНИЕ:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ВидОбременения; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Срок обременения или порядок определения срока:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->СрокОбременения; ?></div>
                         </div>
                       </div>
                       <div class="col-md-12">
                         <div class="row pl-1 pt-3">
                           <div class="offset-1 col-sm-3"><p><b>Дата внесения в ЕГРЮЛ:</b></p></div>
                           <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->Дата; ?></div>
                         </div>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             <?php }
            }
            echo '</div>
                </div>
              </div>';
            ?>

            <!-- Предшественники -->
            <?php if(isset($data_fns->ЮЛ->Предшественники)) { ?>
              <div class="col-md-12">
                <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                <div class="row pl-1 pt-3">
                  <div class="col-sm-3"><p><b>Сведения о правопредшественниках:</b></p></div>
                  <div class="col-sm-9"></div>
                </div>
              </div>
              <?php if (!empty($data_fns->ЮЛ->Предшественники)) {
                foreach($data_fns->ЮЛ->Предшественники as $key_pred => $value_pred){ ?>
                  <div class="col-md-12">
                      <div class="row pl-1 pt-3">
                        <div class="offset-1 col-sm-11"><div class="mt-3"><b><?php echo $value_pred->НаимСокрЮЛ; ?></b></div>
                          <div class="col-md-12">
                            <div class="row pl-1 pt-3">
                              <div class="col-sm-4"><p><b>ИНН:</b></p></div>
                              <div class="col-sm-8"><?php echo $value_pred->ИНН; ?>></div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row pl-1 pt-3">
                              <div class="col-sm-4"><p><b>ОГРН:</b></p></div>
                              <div class="col-sm-8"><?php echo $value_pred->ОГРН; ?>></div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row pl-1 pt-3">
                              <div class="col-sm-4"><p><b>Статус:</b></p></div>
                              <div class="col-sm-8"><?php echo $value_pred->Статус; ?>></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
              }} } ?>

            <!-- Предшественники -->
            <?php if(isset($data_fns->ЮЛ->Преемники)) { ?>
              <div class="col-md-12">
                <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                <div class="row pl-1 pt-3">
                  <div class="col-sm-3"><p><b>Сведения о правопреемниках:</b></p></div>
                  <div class="col-sm-9"></div>
                </div>
              </div>
              <?php if (!empty($data_fns->ЮЛ->Преемники)) {
                foreach($data_fns->ЮЛ->Преемники as $key_pred => $value_pred){ ?>
                    <div class="offset-1 col-sm-11"><div class="mt-3"><b><?php echo $value_pred->НаимСокрЮЛ; ?></b></div>
                      <div class="row pl-1 pt-3">
                          <div class="col-md-12">
                            <div class="row pl-1 pt-3">
                              <div class="col-sm-4"><p><b>ИНН:</b></p></div>
                              <div class="col-sm-8"><?php echo $value_pred->ИНН; ?>></div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row pl-1 pt-3">
                              <div class="col-sm-4"><p><b>ОГРН:</b></p></div>
                              <div class="col-sm-8"><?php echo $value_pred->ОГРН; ?>></div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row pl-1 pt-3">
                              <div class="col-sm-4"><p><b>Статус:</b></p></div>
                              <div class="col-sm-8"><?php echo $value_pred->Статус; ?>></div>
                            </div>
                          </div>
                        </div>
                      </div>
                <?php
              }} } ?>

              <!-- УправлОрг -->
              <?php if(isset($data_fns->ЮЛ->УправлОрг)) { ?>
                <div class="col-md-12">
                  <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-3"><p><b>Сведения о доверительном управляющем:</b></p></div>
                    <div class="col-sm-9"></div>
                  </div>
                </div>
                <?php if (!empty($data_fns->ЮЛ->УправлОрг)) {
                  foreach($data_fns->ЮЛ->УправлОрг as $key_pred => $value_pred){ ?>
                      <div class="offset-1 col-sm-11">
                        <div class="mt-3"><b><?php echo $value_pred->НаимСокрЮЛ; ?></b></div>
                        <div class="row pl-1 pt-3">
                            <div class="col-md-12">
                              <div class="row pl-1 pt-3">
                                <div class="col-sm-4"><p><b>ИНН:</b></p></div>
                                <div class="col-sm-8"><?php echo $value_pred->ИНН; ?></div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="row pl-1 pt-3">
                                <div class="col-sm-4"><p><b>ОГРН:</b></p></div>
                                <div class="col-sm-8"><?php echo $value_pred->ОГРН; ?></div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="row pl-1 pt-3">
                                <div class="col-sm-4"><p><b>Статус:</b></p></div>
                                <div class="col-sm-8"><?php echo $value_pred->Статус; ?></div>
                              </div>
                            </div>
                          </div>
                        </div>
                  <?php
                }} } ?>

                <!-- ДержРеестрАО -->
                <?php if(isset($data_fns->ЮЛ->ДержРеестрАО)) { ?>
                  <div class="col-md-12">
                    <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-3"><p><b>Сведения о держателе реестра акционеров акционерного общества:</b></p></div>
                      <div class="col-sm-9"></div>
                    </div>
                  </div>
                  <?php if (!empty($data_fns->ЮЛ->ДержРеестрАО)) {
                    foreach($data_fns->ЮЛ->ДержРеестрАО as $key_pred => $value_pred){ ?>
                        <div class="offset-1 col-sm-11">
                          <div class="mt-3"><b><?php echo $value_pred->НаимСокрЮЛ; ?></b><div>
                          <div class="row pl-1 pt-3">
                              <div class="col-md-12">
                                <div class="row pl-1 pt-3">
                                  <div class="col-sm-4"><p><b>ИНН:</b></p></div>
                                  <div class="col-sm-8"><?php echo $value_pred->ИНН; ?></div>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="row pl-1 pt-3">
                                  <div class="col-sm-4"><p><b>ОГРН:</b></p></div>
                                  <div class="col-sm-8"><?php echo $value_pred->ОГРН; ?></div>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="row pl-1 pt-3">
                                  <div class="col-sm-4"><p><b>Статус:</b></p></div>
                                  <div class="col-sm-8"><?php echo $value_pred->Статус; ?></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php
                  }} } ?>

                  <!-- УправлОрг -->
                  <?php
                    $temp_obj_value_Участники_реорганизации = 'Участники в реорганизации';
                    if (isset($data_fns->ЮЛ->$temp_obj_value_Участники_реорганизации)) { ?>
                    <div class="col-md-12">
                      <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                      <div class="row pl-1 pt-3">
                        <div class="col-sm-3"><p><b>Сведения об участниках в реорганизации:</b></p></div>
                        <div class="col-sm-9"></div>
                      </div>
                    </div>
                    <?php
                      if (!empty($data_fns->ЮЛ->$temp_obj_value_Участники_реорганизации)) {
                      foreach($data_fns->ЮЛ->$temp_obj_value_Участники_реорганизации as $key_pred => $value_pred){ ?>
                          <div class="offset-1 col-sm-11">
                            <div class="mt-3"><b><?php echo $value_pred->НаимСокрЮЛ; ?></b></div>
                            <div class="row pl-1 pt-3">
                                <div class="col-md-12">
                                  <div class="row pl-1 pt-3">
                                    <div class="col-sm-4"><p><b>ИНН:</b></p></div>
                                    <div class="col-sm-8"><?php echo $value_pred->ИНН; ?></div>
                                  </div>
                                </div>
                                <div class="col-md-12">
                                  <div class="row pl-1 pt-3">
                                    <div class="col-sm-4"><p><b>ОГРН:</b></p></div>
                                    <div class="col-sm-8"><?php echo $value_pred->ОГРН; ?></div>
                                  </div>
                                </div>
                                <div class="col-md-12">
                                  <div class="row pl-1 pt-3">
                                    <div class="col-sm-4"><p><b>Статус:</b></p></div>
                                    <div class="col-sm-8"><?php echo $value_pred->Статус; ?></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                      <?php
                      }}
                    } ?>

                    <!-- ОснВидДеят  -->
                    <?php if (isset($data_fns->ЮЛ->ОснВидДеят)) { ?>
                      <div class="col-md-12">
                        <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                        <div class="row pl-1 pt-3">
                          <div class="col-sm-3"><p><b>Сведения об основном виде деятельности:</b></p></div>
                          <div class="col-sm-9">
                            <span class="badge mr-2" style="background: #727cf5; color: #fff; word-wrap: break-word" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="<?php echo $data_fns->ЮЛ->ОснВидДеят->Текст; ?>" ><?php echo $data_fns->ЮЛ->ОснВидДеят->Код; ?></span>
                          </div>
                        </div>
                      </div>
                    <?php } ?>

                      <!-- ДопВидДеят  -->
                    <?php if (isset($data_fns->ЮЛ->ДопВидДеят)) { ?>
                      <div class="col-md-12">
                        <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                        <div class="row pl-1 pt-3">
                          <div class="col-sm-3"><p><b>Сведения о дополнительных видах деятельности:</b></p></div>
                          <div class="col-sm-9">
                            <?php
                              foreach($data_fns->ЮЛ->ДопВидДеят as $key_work => $value_work) {
                                echo '<span class="badge mr-2" style="background: #727cf5; color: #fff; word-wrap: break-word" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="'.$value_work->Текст.' '.$value_work->Дата.'" >'.$value_work->Код.'</span>';
                              }
                            ?>
                          </div>
                        </div>
                      </div>
                    <?php } ?>

                    <!-- СПВЗ  -->
                    <?php if (isset($data_fns->ЮЛ->СПВЗ)) { ?>
                      <div class="col-md-12">
                        <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                        <div class="row pl-1 pt-3">
                          <div class="col-sm-3"><p><b>Сведения о причинах внесения записей в реестр ЕГРЮЛ:</b></p></div>
                          <div class="col-sm-9">
                            <?php
                              foreach($data_fns->ЮЛ->СПВЗ as $key_work => $value_work) {
                                echo '<span class="badge mr-2" style="background: #727cf5; color: #fff; word-wrap: break-word" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto" data-content="'.$value_work->Текст.'" >'.$value_work->Дата.'</span>';
                              }
                            ?>
                          </div>
                        </div>
                      </div>
                    <?php } ?>

                    <!-- Филиалы -->
                    <?php if (isset($data_fns->ЮЛ->Филиалы)) { ?>
                      <div class="col-md-12">
                        <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                        <div class="row pl-1 pt-3">
                          <div class="col-sm-3"><p><b>Сведения о филиалах и представительствах компании:</b></p></div>
                          <div class="col-sm-9"></div>
                        </div>
                      </div>
                      <?php if (!empty($data_fns->ЮЛ->Филиалы)) {
                        foreach($data_fns->ЮЛ->Филиалы as $key_pred => $value_pred){ ?>
                            <div class="offset-1 col-sm-11">
                              <div class="mt-3"><b><?php echo $value_pred->Тип; ?></b></div>
                              <div class="row pl-1 pt-3">
                                <div class="col-md-12">
                                  <div class="row pl-1 pt-3">
                                    <div class="col-sm-4"><p><b>Наименование:</b></p></div>
                                    <div class="col-sm-8"><?php echo $value_pred->Наименование; ?></div>
                                  </div>
                                </div>
                                <div class="col-md-12">
                                  <div class="row pl-1 pt-3">
                                    <div class="col-sm-4"><p><b>Адрес:</b></p></div>
                                    <div class="col-sm-8"><?php echo $value_pred->Адрес; ?></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        <?php
                      }} } ?>

                      <!-- Открытые Сведения -->
                      <?php if (isset($data_fns->ЮЛ->ОткрСведения)) { ?>
                        <div class="col-md-12">
                          <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                          <div class="row pl-1 pt-3">
                            <div class="col-sm-3"><p><b>Открытые Сведения:</b></p></div>
                            <div class="col-sm-9"></div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="row pl-1 pt-3">
                            <div class="offset-1 col-sm-3"><p><b>Среднесписочное количество работников юридического лица:</b></p></div>
                            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ОткрСведения->КолРаб; ?></div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="row pl-1 pt-3">
                            <div class="offset-1 col-sm-3"><p><b>Список специальных налоговых режимов,:</b></p></div>
                            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ОткрСведения->СведСНР; ?></div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="row pl-1 pt-3">
                            <div class="offset-1 col-sm-3"><p><b>Да - при участии организации в консолидированной группе налогоплательщиков, иначе - Нет:</b></p></div>
                            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ОткрСведения->ПризнУчКГН; ?></div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="row pl-1 pt-3">
                            <div class="offset-1 col-sm-3"><p><b>Сумма доходов по данным ФНС:</b></p></div>
                            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ОткрСведения->СумДоход; ?></div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="row pl-1 pt-3">
                            <div class="offset-1 col-sm-3"><p><b>Сумма расходов по данным ФНС:</b></p></div>
                            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ОткрСведения->СумРасход; ?></div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="row pl-1 pt-3">
                            <div class="offset-1 col-sm-3"><p><b>Актуальность:</b></p></div>
                            <div class="col-sm-8"><?php echo $data_fns->ЮЛ->ОткрСведения->Дата; ?></div>
                          </div>
                        </div>
                        <!-- налоги -->
                        <?php if (isset($data_fns->ЮЛ->ОткрСведения->Налоги)) { ?>
                        <div class="col-md-12">
                          <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                          <div class="row pl-1 pt-3">
                            <div class="col-sm-3"><p><b>Информация об уплаченных компанией налогах и сборах за год:</b></p></div>
                            <div class="col-sm-9"></div>
                          </div>
                        </div>
                        <?php
                          foreach($data_fns->ЮЛ->ОткрСведения->Налоги as $key_pred => $value_pred){ ?>
                              <div class="offset-1 col-sm-11">
                                <div class="mt-3"><b><?php echo $value_pred->Тип; ?></b></div>
                                <div class="row pl-1 pt-3">
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Наименование налога или сбора:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->НаимНалог; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Сумма уплаченного налога или сбора:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->СумУплНал; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Сумма недоимки по налогу:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->СумНедНалог; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Сумма задолженности по пеням:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->СумПени; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Сумма штрафа из-за недоимки по налогу:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->СумШтраф; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Общая сумма недоимки по налогу, пени и штрафу:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->ОбщСумНедоим; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Сведения об отраслевых показателях:</b></p></div>
                                        <div class="col-sm-8">
                                          <div class="row">
                                            <div class="col-md-12">
                                              <div class="row pl-1 pt-3">
                                                <div class="col-sm-4"><p><b>Сведения о среднеотраслевой налоговой нагрузке:</b></p></div>
                                                <div class="col-sm-8"><?php echo $value_pred->ОтраслевыеПок->НалогНагрузка; ?></div>
                                              </div>
                                            </div>
                                            <div class="col-md-12">
                                              <div class="row pl-1 pt-3">
                                                <div class="col-sm-4"><p><b>Сведения о среднеотраслевой рентабельности:</b></p></div>
                                                <div class="col-sm-8"><?php echo $value_pred->ОтраслевыеПок->Рентабельность; ?></div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                          <?php
                        //конец блока с налогами
                        }}
                      //конец блока с ОткрСведения
                      }
                      ?>

                      <!-- Лицензии -->
                      <?php if (isset($data_fns->ЮЛ->Лицензии)) { ?>
                        <div class="col-md-12">
                          <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                          <div class="row pl-1 pt-3">
                            <div class="col-sm-3"><p><b>Cведения о лицензиях, выданных ЮЛ:</b></p></div>
                            <div class="col-sm-9"></div>
                          </div>
                        </div>
                        <?php if (!empty($data_fns->ЮЛ->Лицензии)) {
                          foreach($data_fns->ЮЛ->Лицензии as $key_pred => $value_pred){ ?>
                              <div class="offset-1 col-sm-11">
                                <div class="mt-3"><b><?php echo $value_pred->Тип; ?></b><div>
                                <div class="row pl-1 pt-3">
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Серия и номер лицензии:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->НомерЛиц; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Наименование лицензируемого вида деятельности:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->ВидДеятельности; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Дата начала действия лицензии:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->ДатаНачала; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Дата окончания действия лицензии:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->ДатаОконч; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Сведения об адресах осуществления лицензируемого вида деятельности:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_pred->МестоДейств; ?></div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php
                        }} } ?>

                        <!-- Участия -->
                        <?php if (isset($data_fns->ЮЛ->Участия)) { ?>
                          <div class="col-md-12">
                            <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                            <div class="row pl-1 pt-3">
                              <div class="col-sm-3"><p><b>Сведения об организациях, в капитале которых участвует компания:</b></p></div>
                              <div class="col-sm-9"></div>
                            </div>
                          </div>
                          <?php if (!empty($data_fns->ЮЛ->Участия)) {
                            foreach($data_fns->ЮЛ->Участия as $key_pred => $value_pred){ ?>
                                <div class="offset-1 col-sm-11">
                                  <div class="mt-3"><b><?php echo $value_pred->НаимСокрЮЛ; ?></b><div>
                                  <div class="row pl-1 pt-3">
                                      <div class="col-md-12">
                                        <div class="row pl-1 pt-3">
                                          <div class="col-sm-4"><p><b>ИНН ЮЛ:</b></p></div>
                                          <div class="col-sm-8"><?php echo $value_pred->ИНН; ?></div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="row pl-1 pt-3">
                                          <div class="col-sm-4"><p><b>ОГРН ЮЛ:</b></p></div>
                                          <div class="col-sm-8"><?php echo $value_pred->ОГРН; ?></div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="row pl-1 pt-3">
                                          <div class="col-sm-4"><p><b>Статус ЮЛ:</b></p></div>
                                          <div class="col-sm-8"><?php echo $value_pred->Статус; ?></div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="row pl-1 pt-3">
                                          <div class="col-sm-4"><p><b>Размер доли (в процентах):</b></p></div>
                                          <div class="col-sm-8"><?php echo $value_pred->Процент; ?></div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="row pl-1 pt-3">
                                          <div class="col-sm-4"><p><b>Номинальная стоимость доли в рублях:</b></p></div>
                                          <div class="col-sm-8"><?php echo $value_pred->СуммаУК; ?></div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php
                          }} } ?>

                          <!-- История -->
                          <?php if(isset($data_fns->ЮЛ->История)) {
                          $twmp_str_history = 'Период актуальности данных';
                          ?>
                          <div class="col-md-12">
                            <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                            <div class="row pl-1 pt-3">
                              <div class="col-sm-3"><p><b>Исторические сведения о компании:</b></p></div>
                              <div class="col-sm-9"></div>
                            </div>
                          </div>

                          <!-- Капитал -->
                          <?php if (isset($data_fns->ЮЛ->История->Капитал)) { ?>
                            <div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                              <div class="row pl-1 pt-3">
                                <div class="offset-1 col-sm-11"><b>Капитал:</b>
                                  <?php
                                    foreach($data_fns->ЮЛ->История->Капитал as $key => $value){
                                      echo '<div class="row mt-3">';
                                      echo '<div class="col-3">'.$key.'</div>';
                                      echo '<div class="col-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></div>';
                                      echo '<div class="col-8">'.$value->СумКап.'₽</div>';
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                              </div>
                            </div>
                          <?php } ?>

                          <!-- НаимЮЛПолн -->
                          <?php if (isset($data_fns->ЮЛ->История->НаимЮЛПолн)) { ?>
                            <div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                              <div class="row pl-1 pt-3">
                                <div class="offset-1 col-sm-11"><b>Прошлые полные названия организации:</b>
                                  <?php
                                    foreach($data_fns->ЮЛ->История->НаимЮЛПолн as $key => $value){
                                      echo '<div class="row mt-3">';
                                      echo '<div class="col-3">'.$key.'</div>';
                                      echo '<div class="col-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></div>';
                                      echo '<div class="col-8">'.$value.'</div>';
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                              </div>
                            </div>
                          <?php } ?>

                          <!-- НаимЮЛСокр -->
                          <?php if (isset($data_fns->ЮЛ->История->НаимЮЛСокр)) { ?>
                            <div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                              <div class="row pl-1 pt-3">
                                <div class="offset-1 col-sm-11"><b>Прошлые сокращенные названия организации:</b>
                                  <?php
                                    foreach($data_fns->ЮЛ->История->НаимЮЛСокр as $key => $value){
                                      echo '<div class="row mt-3">';
                                      echo '<div class="col-3">'.$key.'</div>';
                                      echo '<div class="col-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></div>';
                                      echo '<div class="col-8">'.$value.'</div>';
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                              </div>
                            </div>
                          <?php } ?>

                          <!-- Статус -->
                          <?php if (isset($data_fns->ЮЛ->История->Статус)) { ?>
                            <div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                              <div class="row pl-1 pt-3">
                                <div class="offset-1 col-sm-11"><b>Прошлые статусы организации:</b>
                                  <?php
                                    foreach($data_fns->ЮЛ->История->Статус as $key => $value){
                                      echo '<div class="row mt-3">';
                                      echo '<div class="col-3">'.$key.'</div>';
                                      echo '<div class="col-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></div>';
                                      echo '<div class="col-8">'.$value.'</div>';
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                              </div>
                            </div>
                          <?php } ?>

                          <!-- Адрес -->
                          <?php if (isset($data_fns->ЮЛ->История->Адрес)) { ?>
                            <div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                              <div class="row pl-1 pt-3">
                                <div class="offset-1 col-sm-11"><b>Исторические сведения о прошлых адресах компании:</b>
                                  <?php
                                    foreach($data_fns->ЮЛ->История->Адрес as $key => $value){
                                      echo '<div class="row mt-3">';
                                      echo '<div class="col-3">'.$key.'</div>';
                                      echo '<div class="col-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></div>';
                                      echo '<div class="col-8">'.$value->АдресПолн.'</div>';
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                              </div>
                            </div>
                          <?php } ?>

                          <!-- Руководитель -->
                          <?php if (isset($data_fns->ЮЛ->История->Руководитель)) { ?>
                            <div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                              <div class="row pl-1 pt-3">
                                <div class="offset-1 col-sm-11"><b>Исторические сведения о бывших руководителях:</b>
                                  <?php
                                    foreach($data_fns->ЮЛ->История->Руководитель as $key => $value){
                                      //echo '<div class="row">';
                                      echo '<div class="row mt-3"><div class="col-sm-12">'.$value->ФИОПолн.'</div>';
                                      echo '<div class="offset-2 col-sm-2">Период</div><div class="col-sm-8">'.$key.'</div>';
                                      echo '<div class="offset-2 col-sm-2">ИНН</div><div class="col-sm-8">'.$value->ИННФЛ.'</div>';
                                      if (isset($value->ДатаОкончДискв)) {
                                        echo '<div class="offset-2 col-sm-2">Недостоверность руководителя</div>';
                                        echo '<div class="col-sm-8">';
                                          echo '<div class="row">';
                                            echo '<div class="offset-2 col-sm-2">Код</div><div class="col-sm-8">'.$value->ДатаОкончДискв->Код.'</div>';
                                            echo '<div class="offset-2 col-sm-2">Результат</div><div class="col-sm-8">'.$value->ДатаОкончДискв->Текст.'</div>';
                                          echo '</div>';
                                        echo '</div>';
                                      }
                                      if(isset($value->ДатаОкончДискв)) echo '<div class="offset-2 col-sm-2">Дате оконч. дисквалификации</div><div class="col-sm-8">'.$value->ДатаОкончДискв.'</div>';
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                              </div>
                            </div>
                          <?php }  ?>

                          <!-- НомТел -->
                          <?php if (isset($data_fns->ЮЛ->История->НомТел)) { ?>
                            <div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                              <div class="row pl-1 pt-3">
                                <div class="offset-1 col-sm-11"><b>Прошлые номера контактного телефона:</b>
                                  <?php
                                    foreach($data_fns->ЮЛ->История->НомТел as $key => $value){
                                      //echo '<div class="row">';
                                      echo '<div class="row mt-3">';
                                      echo '<div class="col-3">'.$key.'</div>';
                                      echo '<div class="col-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></div>';
                                      echo '<div class="col-8">'.$value.'</div>';
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                              </div>
                            </div>
                          <?php } ?>

                          <!-- НомТел -->
                          <?php
                            $str_obj_email = 'E-mail';
                            if (isset($data_fns->ЮЛ->История->$str_obj_email)) { ?>
                            <div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                              <div class="row pl-1 pt-3">
                                <div class="offset-1 col-sm-11"><b>Прошлые адреса электронной почты:</b>
                                  <?php
                                    foreach($data_fns->ЮЛ->История->$str_obj_email as $key => $value){
                                      //echo '<div class="row">';
                                      echo '<div class="row mt-3">';
                                      echo '<div class="col-3">'.$key.'</div>';
                                      echo '<div class="col-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></div>';
                                      echo '<div class="col-8">'.$value.'</div>';
                                      echo '</div>';
                                    }
                                  ?>
                                </div>
                              </div>
                            </div>
                          <?php } ?>

                          <!-- Учредители История -->
                            <?php if(!empty($data_fns->ЮЛ->История->Учредители)){
                              echo '<div class="col-md-12">
                              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                                <div class="row pl-1 pt-3">
                                  <div class="col-sm-5"><p><b>Сведения об учредителях (участниках) юридического лица:</b></p></div>
                                  <div class="col-sm-7"></div>
                                </div>
                              </div>';

                              echo '<div class="col-md-12">
                                <div class="row pl-1 pt-3">';
                              foreach ($data_fns->ЮЛ->История->Учредители as $key_uch => $value_uch) {
                                if (isset($value_uch->УчрЮЛ)) { ?>
                                  <div class="offset-1 col-sm-3"><p><b>Наименование ЮЛ сокращенное:</b></p></div>
                                  <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрЮЛ->НаимСокрЮЛ; ?>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>ОГРН ЮЛ:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->УчрЮЛ->ОГРН; ?></div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>ИНН ЮЛ:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->УчрЮЛ->ИНН; ?></div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>Статус ЮЛ:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->УчрЮЛ->Статус; ?></div>
                                    </div>
                                  </div>
                                <?php }
                                if (isset($value_uch->УчрИН)) { ?>
                                  <div class="offset-1 col-sm-3"><p><b>Сведения об учредителе (участнике) - иностранном юридическом лице:</b></p></div>
                                  <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрИН->НаимПолнЮЛ; ?>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>ОКСМ:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->УчрИН->ОКСМ; ?></div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>Регистрационный номер:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->УчрИН->РегНомер; ?></div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>Дата регистрации:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->УчрИН->ДатаРег; ?></div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>Адрес (место нахождения) в стране происхождения:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->УчрИН->АдресПолн; ?></div>
                                    </div>
                                  </div>
                                <?php }
                                if (isset($value_uch->УчрФЛ)) { ?>
                                  <div class="offset-1 col-sm-3"><p><b>Сведения об учредителе (участнике) - физическом лице:</b></p></div>
                                  <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрФЛ->ФИОПолн; ?>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>ИНН учредителя:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->УчрФЛ->ИННФЛ; ?></div>
                                    </div>
                                  </div>
                                <?php }
                                if (isset($value_uch->УчрРФСубМО)) { ?>
                                  <div class="offset-1 col-sm-3"><p><b>Сведения об учредителе (участнике) - Российской Федерации, субъекте Российской Федерации, муниципальном образовании:</b></p></div>
                                  <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрРФСубМО; ?>
                                <?php }
                                if (isset($value_uch->СвОргОсущПр)) { ?>
                                  <div class="offset-1 col-sm-3"><p><b>Сведения об органе государственной власти, органе местного самоуправления или о юридическом лице, осуществляющем права учредителя (участника:</b></p></div>
                                  <div class="offset-4 col-sm-8"><?php echo $value_uch->СвОргОсущПр->УчрЮЛ->НаимСокрЮЛ; ?>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>ОГРН ЮЛ:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_uch->СвОргОсущПр->УчрЮЛ->ОГРН; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>ИНН ЮЛ:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_uch->СвОргОсущПр->УчрЮЛ->ИНН; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Статус ЮЛ:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_uch->СвОргОсущПр->УчрЮЛ->Статус; ?></div>
                                      </div>
                                    </div>
                              <?php }
                                if (isset($value_uch->УчрПИФ)) { ?>
                                  <div class="offset-1 col-sm-3"><p><b>Сведения о паевом инвестиционном фонде, в состав имущества которого включена доля в уставном капитале:</b></p></div>
                                  <div class="offset-4 col-sm-8"><?php echo $value_uch->УчрПИФ->НаимСокрЮЛ; ?>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>ОГРН ЮЛ:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_uch->УчрПИФ->ОГРН; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>ИНН ЮЛ:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_uch->УчрПИФ->ИНН; ?></div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="row pl-1 pt-3">
                                        <div class="col-sm-4"><p><b>Статус ЮЛ:</b></p></div>
                                        <div class="col-sm-8"><?php echo $value_uch->УчрПИФ->Статус; ?></div>
                                      </div>
                                    </div>
                              <?php } ?>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>Номинальная стоимость доли в рублях:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->СуммаУК; ?></div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>Размер доли (в процентах):</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->Процент; ?></div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row pl-1 pt-3">
                                      <div class="col-sm-4"><p><b>Дата внесения в ЕГРЮЛ:</b></p></div>
                                      <div class="col-sm-8"><?php echo $value_uch->Дата; ?></div>
                                    </div>
                                  </div>
                                </div>
                             <?php }
                               if(isset($value_uch->ПризнНедДанДолжнФЛ)) { ?>
                                 <!-- Недостоверный адрес -->
                                   <div class="col-md-12">
                                     <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                                     <div class="row pl-1 pt-3">
                                       <div class="col-sm-4"><p><b>	Сведения о недостоверности данных об учредителе (участнике):</b></p></div>
                                       <div class="col-sm-8">
                                         <div class="row">
                                           <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                                           <div class="col-md-12">
                                             <div class="row pl-1 pt-3">
                                               <div class="offset-1 col-sm-3"><p><b>Признак недостоверности:</b></p></div>
                                               <div class="col-sm-8"><?php echo $value_uch->ПризнНедДанУчр->Код; ?></div>
                                             </div>
                                           </div>
                                           <div class="col-md-12">
                                             <div class="row pl-1 pt-3">
                                               <div class="offset-1 col-sm-3"><p><b>Результат:</b></p></div>
                                               <div class="col-sm-8"><?php echo $value_uch->ПризнНедДанУчр->Текст; ?></div>
                                             </div>
                                           </div>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                             <?php }
                             if(isset($value_uch->Залогодержатели)) { ?>
                               <!-- Недостоверный адрес -->
                                 <div class="col-md-12">
                                   <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                                   <div class="row pl-1 pt-3">
                                     <div class="col-sm-4"><p><b>	Сведения о залогодержателе доли:</b></p></div>
                                     <div class="col-sm-8">
                                       <div class="row">
                                         <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>ОГРН:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ОГРН; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>ИНН:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ИНН; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Наименование ЮЛ сокращенное (только для ЮЛ):</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->НаимСокрЮЛ; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Статус ЮЛ:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->Статус; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Фамилия Имя Отчество залогодержателя (ФЛ):</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ФИОПолн; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>ИНН залогодержателя (ФЛ):</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ИННФЛ; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Наименование организации залогодержателя (Иностранное лицо):</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->НаимПолнЮЛ; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>ОКСМ:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ОКСМ; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>ИННФЛ:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ИННФЛ; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Регистрационный номер:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->РегНомер; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Дата регистрации:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ДатаРег; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Адрес (место нахождения) в стране происхождения:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->АдресПолн; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Принимает значение: ЗАЛОГ | ИНОЕ ОБРЕМЕНЕНИЕ:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->ВидОбременения; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Срок обременения или порядок определения срока:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->СрокОбременения; ?></div>
                                           </div>
                                         </div>
                                         <div class="col-md-12">
                                           <div class="row pl-1 pt-3">
                                             <div class="offset-1 col-sm-3"><p><b>Дата внесения в ЕГРЮЛ:</b></p></div>
                                             <div class="col-sm-8"><?php echo $value_uch->Залогодержатели->Дата; ?></div>
                                           </div>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                                 </div>
                    <?php    } } } ?>

    </div>

    <?php }
                  ?>

                  <!-- <div  id="list_fns_head">

                  </div> -->


                </div>
              </div>
            </div>


          </div>
        </div>
    </div>
</div>

<script>

  $(document).ready(function() {

    console.log($('.list_fns'));
    var icon_min = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>';
    var icon_plus = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>';

    var arr_list = $('.list_fns');
    for (var i = 0; i<arr_list.length; i++) {
      var temp_str = $(arr_list[i]).html();
      $(arr_list[i]).attr('state', 'false');
      $(arr_list[i]).html(icon_plus+temp_str);
    }

    $('.list_fns').on('click', function(){
      var element = event.path[1];
      var list_num = $(element).attr('list');
      var el_li = $('#list_li_fns_'+list_num);
      var el_b = $(element).children()[1].outerHTML;
      if( $(element).is(':visible') ) {
        if( $(el_li).is(':visible') ) {
           $(el_li).slideUp();
           $(element).html(icon_plus+el_b);
        } else {
          $(el_li).slideDown();
          $(element).html(icon_min+el_b);
        }
      }
    });

    // var str_fns_base = '<?php echo addslashes(json_encode($data_fns->ЮЛ, JSON_UNESCAPED_UNICODE)); ?>';
    // var arr_fns_base = JSON.parse(str_fns_base);
    //
    // function read_fns(arr_fns) {
    //   //arr_element =  document .createElement( 'div' );
    //   var arr_element = [];
    //   for (var prop in arr_fns) {
    //     //console.log(create_div_obj(prop, arr_fns[prop]).outerHTML);
    //     arr_element.push(create_div_obj(prop, arr_fns[prop]));
    //
    //     //console.log("obj." + prop + " = " + arr_fns_base[prop]);
    //   }
    //   return arr_element;
    // }


    // function create_div_obj(key, value) {
    //     var div_col_12 = document .createElement( 'div' );
    //     $(div_col_12).attr('class', 'col-md-12');
    //     var div_col_row = document .createElement( 'div' );
    //     $(div_col_row).attr('class', 'row pl-1 pt-3');
    //     var div_col_row_sm_3 = document .createElement( 'div' );
    //     $(div_col_row_sm_3).attr('class', 'col-sm-3');
    //     var div_col_row_sm_9 = document .createElement( 'div' );
    //     $(div_col_row_sm_9).attr('class', 'col-sm-9');
    //
    //     if(typeof value == 'object') {
    //       $(div_col_row_sm_3).html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    //       $(div_col_row_sm_9).html(read_fns(value));
    //     }
    //     if(typeof value == 'string') {
    //       $(div_col_row_sm_9).html(value);
    //     }
    //
    //     $(div_col_row_sm_3).html('<p>'+$(div_col_row_sm_3).html()+' '+'<b>'+key+'</p></b>');
    //     $(div_col_row).html(div_col_row_sm_3.outerHTML+div_col_row_sm_9.outerHTML);
    //     $(div_col_12).html(div_col_row.outerHTML);
    //     return div_col_12;
    // };

    // <div class="col-md-12">
    //   <div class="row pl-1 pt-3">
    //     <div class="col-sm-3"><p><b>ИНН:</b></p></div>
    //     <div class="col-sm-9"><?php echo $data_fns->ЮЛ->ИНН; ?></div>
    //   </div>
    // </div>

    // console.log(arr_fns_base);
    //
    // result_fns_arr =  read_fns(arr_fns_base);
    // result_fns_arr.forEach(element => {
    //   //$(element).attr('class', 'row');
    //   $('#list_fns_head').append(element).html();
    // })
    // console.log(result_fns_arr);
    //$(result_fns).attr('class', 'row');

    //$('#list_fns_head').append(result_fns).html();


    // $('#list_fns_head').on('click', function(){
    //   var element = event.path[0];
    //   console.log($(element).children().length > 0);
    //   //console.log();
    // });



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
