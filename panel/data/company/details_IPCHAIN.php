<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Детали юр.лица - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php
$count_list_fns = 0;
$data_local_ipchain = $settings->ipchain_get_data_entity('inn', $_GET["inn"]);
$arr_data_local_ipchain = json_decode($data_local_ipchain);

if (!$arr_data_local_ipchain->response) {
  //надо подумать что делать в таких случаях >_<
  echo "<h1>error</h1>";
  exit();
}

// echo $data_local_ipchain;

//патенты
$data_ipchain_IpObjects = json_decode($settings->get_EBD_IPCHAIN_IpObjects(true, $arr_data_local_ipchain->data->id));
//statesupport - поддержка
$data_ipchain_StateSupport = json_decode($settings->get_EBD_IPCHAIN_StateSupport(true, $arr_data_local_ipchain->data->id));
//project - проекты
$data_ipchain_Project = json_decode($settings->get_EBD_IPCHAIN_Project(true, $arr_data_local_ipchain->data->id));

$data_type_support = $settings->get_state_support_types_ipchain();

?>



<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="#">Юр. лица IPchain</a></li>
    <li class="breadcrumb-item active" aria-current="page">Детали юр. лица IPchain <?php echo $arr_data_local_ipchain->data->Name; ?></li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="container-fluid" style="border-radius: 3px;">
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
                      <p>ИНН:</p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <?php echo $arr_data_local_ipchain->data->Inn; ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-3">
                      <p>ОГРН:</p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <?php echo $arr_data_local_ipchain->data->Ogrn; ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-3">
                      <p>Код ОКВЭД:</p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <?php echo $arr_data_local_ipchain->data->Okved; ?>
                      </p>
                    </div>
                  </div>
                  <?php if(isset($arr_data_local_ipchain->data->Website) && trim($arr_data_local_ipchain->data->Website) != ''){ ?>
                    <div class="row pl-1 pt-1">
                      <div class="col-sm-3">
                        <p>Сайт:</p>
                      </div>
                      <div class="col-sm-9">
                        <a href="<? echo (trim($arr_data_local_ipchain->data->Website) != '' && $arr_data_local_ipchain->data->Website != NULL) ? $arr_data_local_ipchain->data->Website : $false_data ?>">
                          <? echo (trim($arr_data_local_ipchain->data->Website) != '' && $arr_data_local_ipchain->data->Website != NULL) ? $arr_data_local_ipchain->data->Website : $false_data ?></a>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-3">
                      <p>Адрес:</p>
                    </div>
                    <div class="col-sm-9">
                      <?php echo $arr_data_local_ipchain->data->LawAddress; ?>
                    </div>
                  </div>
                  <?php if(isset($arr_data_local_ipchain->data->Website) && trim($arr_data_local_ipchain->data->Website) != ''){ ?>
                    <div class="row pl-1 pt-1">
                      <div class="col-sm-3">
                        <p>Отрасль:</p>
                      </div>
                      <div class="col-sm-9">
                        <div id="technology_branch"></div>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if(isset($arr_data_local_ipchain->data->Website) && trim($arr_data_local_ipchain->data->Website) != ''){ ?>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-3">
                      <p>Технологии:</p>
                    </div>
                    <div class="col-sm-9">
                      <div id="technology_TECH"></div>
                    </div>
                  </div>
                  <?php } ?>
                </div>

                <?php // блок ключевые показатели ФНС ?>
                <div class="col-lg-6 p-4">
                  <?php // Заголовок и кнопка редактирвоания  ?>
                  <div class="row p-1 ">
                    <div class="col-sm-9">
                      <h5>Ключевые показатели</h5>
                    </div>
                    <div class="col-sm-3 text-right" style="padding-top: .05rem">
                      <h5><span class="badge" style="background: #727cf5; color: #fff">ФНС</span></h5>
                    </div>
                  </div>
                  <?php // Основная информация о компании  ?>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Выручка за год:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                          if ($data_fns->ЮЛ->ОткрСведения->СумДоход) {echo $data_fns->ЮЛ->ОткрСведения->СумДоход.' руб.';}
                          else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Количество сотрудников:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                         if ($data_fns->ЮЛ->ОткрСведения->КолРаб) {echo $data_fns->ЮЛ->ОткрСведения->КолРаб;}
                         else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Специальные налоговые режимы:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                        if ($data_fns->ЮЛ->ОткрСведения->СведСНР) {echo $data_fns->ЮЛ->ОткрСведения->СведСНР;}
                        else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-3">
                    <div class="col-md-12">
                      <h5>Налоги или сборы (последний год)</h5>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Сумма уплаченных налогов или сборов:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                        if ($data_fns->ЮЛ->ОткрСведения->Налоги) {
                          $nalog = 0;
                          for ($i=0; $i < count($data_fns->ЮЛ->ОткрСведения->Налоги); $i++) {
                              $nalog = $nalog + $data_fns->ЮЛ->ОткрСведения->Налоги[$i]->СумУплНал;
                          }
                          echo $nalog.' руб.';
                        }
                        else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Общая сумма недоимки по налогу, пени и штрафу:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                        if ($data_fns->ЮЛ->ОткрСведения->Налоги) {
                          $peni = 0;
                          for ($i=0; $i < count($data_fns->ЮЛ->ОткрСведения->Налоги); $i++) {
                              $peni = $peni + $data_fns->ЮЛ->ОткрСведения->Налоги[$i]->ОбщСумНедоим;
                          }
                          echo $peni.' руб.';
                        }
                        else {echo $false_data;} */
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
                            <h5>Контактное лицо <?php //echo ($data_user->response) ? "" : "Не зарегистрированно в системе ЕБД" ;?></h5>
                          </div>
                          <div class="col-sm-3 text-right" style="padding-top: .05rem">

                          </div>
                      </div>
                      <?php // Основная информация о пользователе  ?>
                      <div class="row pl-1 pt-3">
                          <div class="col-sm-auto">
                            <img style="width: 180px; height: auto;" src="/assets/images/male-user-profile-picture.png" />
                          </div>
                          <div class="col-sm-auto"><?php /*
                              <p><? echo ($data_user->data->name) ? $data_user->data->last_name." ".$data_user->data->name." ".$data_user->data->second_name : 'ФИО не указано'; ?></p>
                              <p><? echo ($data_user->data->position) ? $data_user->data->position : 'Должность не указан'; ?></p>
                              <p><? echo ($data_user->data->phone) ? $data_user->data->phone : 'Телефон не указан'; ?></p>
                              <p><? echo ($data_user->data->email) ? $data_user->data->email : 'Email не указан'; ?></p>
                              <p>Tboil ID: <a href="https://tboil.spb.ru/"><? echo ($data_user->data->id_tboil) ? $data_user->data->id_tboil : 'Не зарегистрирован'; ?></a></p>
                              <?php if($data_user->data->id_tboil) { ?><p><button href="javascript:void(0)" class="btn btn btn-outline-info" onclick="window.open('<?php echo 'https://'.$_SERVER["SERVER_NAME"];?>/panel/data/users/details?tboil=<?php echo $data_user->data->id_tboil; ?>')">Посмотреть профиль</button></p> <?php } ?>
                              */ ?>
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
                                <p>Рынки НТИ:</p>
                              </div>
                              <div class="col-sm-auto">
                                  <div id="technology_NTI"></div>
                              </div>
                          </div>
                          <div class="row pl-1 pt-1">
                              <div class="col-sm-auto">
                                <p>Национальные и федеральные проекты:</p>
                              </div>
                              <div class="col-sm-auto">
                                  <div id="technology_NFP"></div>
                              </div>
                          </div>
                          <div class="row pl-1 pt-1">
                              <div class="col-sm-auto">
                                <p>Цифровые сквозные технологии:</p>
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
                          <? //echo ($status_skolkovo) ? 'Резидент' : 'Не резидент'; ?>
                        </p>
                      </div>
                    </div>
                    <div class="row pl-1">
                      <div class="col-sm-6">
                        <p><b>Сумма полученных грантов:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <?php /*
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
                            echo $scolkovo_grant_sum.' руб.'; */
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
                        <?php /*
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
                          echo $fsi_grant; */
                        ?>
                      </div>
                    </div>
                    <div class="row pl-1">
                      <div class="col-sm-6">
                        <p><b>Общая сумма средств грантов:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <?php /*
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
                          echo $fsi_grant_sum.' руб.'; */
                          ?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

          </div>
        </div>
    </div>
</div>

<div id="accordion2" class="accordion" role="tablist">
  <div class="card">
    <div class="card-header" role="tab" id="heading_card_ip_data">
      <h6 class="mb-0">
        <a data-toggle="collapse" href="#card_ip_data" aria-expanded="false" aria-controls="card_ip_data">
          Данные Юр. лица из IPChain
        </a>
      </h6>
    </div>
    <div id="card_ip_data" class="collapse" role="tabpanel" data-parent="#accordion2">
      <div class="card-body">
        <div class="row">

          <?php if($data_ipchain_IpObjects->response == false && $data_ipchain_Project->response == false && $data_ipchain_StateSupport->response == false){
                    echo '<div class="col-md-12"><div class="alert alert-primary" role="alert">
                            Сведения о Патентах, Проектах и Мерах поддержки отсутствуют
                          </div></div>';
                  }
          ?>


          <?php if($data_ipchain_IpObjects->response) { ?>
            <div class="col-md-12">
              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
              <div class="row pl-1 ">
                <div class="col-sm-5 "><text class="list_fns" list="<?php echo $count_list_fns;?>"><b>Сведения о Патентах:</b></text></div>
                <div class="col-sm-7"></div>
              </div>
            </div>
            <div class="row" id="list_li_fns_<?php echo $count_list_fns; $count_list_fns++ ?>" style="display:none; width: 100%;">
              <div class="col-md-12 mt-3 pl-3 pr-3">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Наименование</th>
                          <th scope="col">Тип Патента</th>
                          <th scope="col">Страна регистрации</th>
                          <th scope="col">Дата регистрации</th>
                          <th scope="col">Номер РИДа</th>
                          <th scope="col">Ссылка на патент</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($data_ipchain_IpObjects->data as $key => $value)  {?>
                          <tr>
                           <td scope="row"><?php echo ($key+1); ?></td>
                           <td class="text-wrap"><?php echo $value->Name; ?></td>
                           <td class=""><?php echo $value->Type; ?></td>
                           <td class=""><?php echo $value->Country; ?></td>
                           <td><?php echo date('d.m.Y', strtotime($value->RegistrationDate)); ?></td>
                           <td class="text-wrap"><?php echo $value->Number_Objects; ?></td>
                           <td class="text-wrap"><button type="button" href="javascript:void(0)" onclick="window.open('<?php echo $value->Url; ?>')" class="btn btn-link">URl</button></td>
                         </tr>
                        <?php } ?>
                      </tbody>
                    </table>
              </div>
            </div>
          <?php } ?>
          <?php if($data_ipchain_Project->response) { ?>
            <div class="col-md-12">
              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
              <div class="row pl-1">
                <div class="col-sm-5 "><text class="list_fns" list="<?php echo $count_list_fns;?>"><b>Сведения о Проектах:</b></text></div>
                <div class="col-sm-7"></div>
              </div>
            </div>
            <div class="row" id="list_li_fns_<?php echo $count_list_fns; $count_list_fns++ ?>" style="display:none; width: 100%;">
              <div class="col-md-12 mt-3 pl-3 pr-3">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Наименование</th>
                          <th scope="col">Описаие</th>
                          <th scope="col">Дата Старт</th>
                          <th scope="col">Дата Конец</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($data_ipchain_Project->data as $key => $value)  {?>
                          <tr>
                           <td scope="row"><?php echo ($key+1); ?></td>
                           <td class="text-wrap"><?php echo $value->Name; ?></td>
                           <td class="overflow-auto text-wrap"><?php echo $value->Description; ?></td>
                           <td><?php echo $settings->date_time_rus($value->StartDate); ?></td>
                           <td><?php echo $settings->date_time_rus($value->EndDate); ?></td>
                         </tr>
                        <?php } ?>
                      </tbody>
                    </table>
              </div>
            </div>
          <?php } ?>
          <?php if($data_ipchain_StateSupport->response) { ?>
            <div class="col-md-12">
              <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
              <div class="row pl-1 ">
                <div class="col-sm-5 "><text class="list_fns" list="<?php echo $count_list_fns;?>"><b>Сведения о Мерах поддержки:</b></text></div>
                <div class="col-sm-7"></div>
              </div>
            </div>
            <div class="row" id="list_li_fns_<?php echo $count_list_fns; $count_list_fns++ ?>" style="display:none; width: 100%;">
              <div class="col-md-12 mt-3 pl-3 pr-3">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Номер Документа</th>
                          <th scope="col">Направления поддержки</th>
                          <th scope="col">Дата Поддержки</th>
                          <th scope="col">Сумма</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($data_ipchain_StateSupport->data as $key => $value)  {?>
                          <tr>
                           <td scope="row"><?php echo ($key+1); ?></td>
                           <td><?php echo $value->id_Support; ?></td>
                           <td><?php $temp_str_type_support = $value->typeId; echo $data_type_support->$temp_str_type_support; ?></td>
                           <td><?php echo $settings->date_time_rus($value->date_support); ?></td>
                           <td><?php echo $value->Sum; ?> ₽</td>
                         </tr>
                        <?php } ?>
                      </tbody>
                    </table>
              </div>
            </div>
          <?php }  ?>
        </div>
        <hr style="border: none; color: #727cf5; background-color: #727cf5; height: 1px; ">
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
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
  });

</script>



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
